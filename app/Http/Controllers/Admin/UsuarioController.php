<?php

namespace App\Http\Controllers\Admin;

use App\Models\Usuario;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Barryvdh\DomPDF\Facade\Pdf;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Usuario::with('roles');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        $usuarios = $query->paginate(10)->withQueryString();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // Sanitizar nombre: eliminar espacios extra
        $request->merge([
            'nombre' => preg_replace('/\s+/', ' ', trim($request->nombre ?? '')),
            'email' => strtolower(trim($request->email ?? '')),
        ]);

        $request->validate([
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:usuarios,email',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:64',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(3),
            ],
            'estatus' => 'required|in:activo,inactivo',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ], [
            'nombre.required' => 'El nombre completo es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios (sin números ni caracteres especiales).',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido (ej: usuario@dominio.com).',
            'email.unique' => 'Este correo ya está registrado en la plataforma. Usa otro o edita el usuario existente.',
            'email.regex' => 'El formato del correo electrónico no es válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.max' => 'La contraseña no puede exceder los 64 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden. Verifica ambos campos.',
            'password.mixed' => 'La contraseña debe contener al menos una mayúscula y una minúscula.',
            'password.numbers' => 'La contraseña debe incluir al menos un número.',
            'password.symbols' => 'La contraseña debe incluir al menos un carácter especial (!@#$%^&*).',
            'password.uncompromised' => 'Esta contraseña ha sido filtrada en brechas de seguridad. Elige una contraseña más segura.',
            'estatus.required' => 'El estatus es obligatorio.',
            'estatus.in' => 'El estatus debe ser "activo" o "inactivo".',
            'roles.required' => 'Debes asignar al menos un rol al usuario.',
            'roles.min' => 'Debes asignar al menos un rol al usuario.',
            'roles.*.exists' => 'Uno de los roles seleccionados no es válido.',
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'estatus' => $request->estatus ?? 'activo',
        ]);

        // Asignar roles
        if ($request->has('roles')) {
            $usuario->roles()->sync($request->roles);
        }

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario "' . $usuario->nombre . '" creado exitosamente con ' . count($request->roles) . ' rol(es) asignado(s).');
    }

    public function edit($id)
    {
        $usuario = Usuario::with('roles')->findOrFail($id);

        // Verificar si tiene inmuebles o rentas
        $tieneInmuebles = $usuario->inmuebles()->count() > 0;
        $esInquilino = $usuario->contratosComoInquilino()->whereIn('estatus', ['activo', 'vigente'])->count() > 0;
        $puedeDesactivar = !($tieneInmuebles || $esInquilino);

        $roles = Role::all();
        return view('admin.usuarios.edit', compact('usuario', 'roles', 'puedeDesactivar'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::with('roles')->findOrFail($id);

        // Verificar si tiene inmuebles o rentas
        $tieneInmuebles = $usuario->inmuebles()->count() > 0;
        $esInquilino = $usuario->contratosComoInquilino()->whereIn('estatus', ['activo', 'vigente'])->count() > 0;

        if (($tieneInmuebles || $esInquilino) && $request->estatus === 'inactivo' && $usuario->estatus === 'activo') {
            return redirect()->back()->with('error', 'No se puede desactivar el usuario "' . $usuario->nombre . '" porque tiene propiedades publicadas o contratos vigentes.');
        }

        // Reglas base (nombre y email ya no se editan)
        $rules = [
            'estatus' => 'required|in:activo,inactivo',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ];

        // Si se proporciona contraseña, validarla con las mismas reglas de seguridad
        if ($request->filled('password')) {
            $rules['password'] = [
                'string',
                'min:8',
                'max:64',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ];
        }

        $messages = [
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.max' => 'La contraseña no puede exceder los 64 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.mixed' => 'La contraseña debe contener mayúsculas y minúsculas.',
            'password.numbers' => 'La contraseña debe incluir al menos un número.',
            'password.symbols' => 'La contraseña debe incluir un carácter especial.',
            'estatus.in' => 'El estatus seleccionado no es válido.',
            'roles.required' => 'Debes asignar al menos un rol.',
            'roles.min' => 'Debes asignar al menos un rol.',
            'roles.*.exists' => 'Uno de los roles seleccionados no es válido.',
        ];

        $request->validate($rules, $messages);

        // Protección: no desactivarse a sí mismo
        if ($id == auth()->id() && $request->estatus === 'inactivo') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No puedes desactivar tu propia cuenta de administrador. Pide a otro administrador que lo haga.');
        }

        // Protección: no quitarse el rol admin a sí mismo
        if ($id == auth()->id()) {
            $rolAdmin = Role::where('nombre', 'admin')->first();
            if ($rolAdmin && !in_array($rolAdmin->id, $request->roles ?? [])) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'No puedes quitarte el rol de administrador a ti mismo.');
            }
        }

        // Detectar cambios realizados
        $cambios = [];
        if ($usuario->estatus !== $request->estatus)
            $cambios[] = 'estatus';
        if ($request->filled('password'))
            $cambios[] = 'contraseña';

        $rolesActuales = $usuario->roles->pluck('id')->sort()->values()->toArray();
        $rolesNuevos = collect($request->roles)->map(fn($r) => (int) $r)->sort()->values()->toArray();
        if ($rolesActuales !== $rolesNuevos)
            $cambios[] = 'roles';

        $usuario->fill([
            'estatus' => $request->estatus,
        ]);

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        // Actualizar roles
        if ($request->has('roles')) {
            $usuario->roles()->sync($request->roles);
        }

        if (empty($cambios)) {
            return redirect()->route('admin.usuarios.index')->with('success', 'No se detectaron cambios en el usuario "' . $usuario->nombre . '".');
        }

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario "' . $usuario->nombre . '" actualizado. Campos modificados: ' . implode(', ', $cambios) . '.');
    }

    public function destroy($id)
    {
        $usuario = Usuario::with('roles')->findOrFail($id);

        // 1. Evitar que el admin se desactive a sí mismo
        if ($id == auth()->id()) {
            return redirect()->back()->with('error', 'No puedes desactivar tu propia cuenta de administrador.');
        }

        $nuevoEstatus = $usuario->estatus === 'activo' ? 'inactivo' : 'activo';

        // 2. Verificar si tiene inmuebles o rentas al intentar desactivar
        if ($nuevoEstatus === 'inactivo') {
            $tieneInmuebles = $usuario->inmuebles()->count() > 0;
            $esInquilino = $usuario->contratosComoInquilino()->whereIn('estatus', ['activo', 'vigente'])->count() > 0;

            if ($tieneInmuebles || $esInquilino) {
                return redirect()->back()->with('error', 'No se puede desactivar el usuario "' . $usuario->nombre . '" porque tiene propiedades publicadas o contratos vigentes.');
            }
        }

        $nuevoEstatus = $usuario->estatus === 'activo' ? 'inactivo' : 'activo';
        $usuario->estatus = $nuevoEstatus;
        $usuario->save();

        $texto = $nuevoEstatus === 'activo' ? 'activado' : 'desactivado';
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario "' . $usuario->nombre . '" ' . $texto . ' correctamente.');
    }

    public function reporte()
    {
        $usuarios = Usuario::with('roles')->get();
        $pdf = Pdf::loadView('admin.usuarios.reporte', compact('usuarios'));
        return $pdf->download('reporte_usuarios.pdf');
    }
}
