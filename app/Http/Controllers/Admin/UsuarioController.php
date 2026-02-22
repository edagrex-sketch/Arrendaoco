<?php

namespace App\Http\Controllers\Admin;

use App\Models\Usuario;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Usuario::with('roles');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
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
        $request->validate([
            'nombre' => 'required|string|max:255|min:3',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Formato de correo inválido.',
            'email.unique' => 'Este correo ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.'
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'estatus' => $request->status ?? 'activo',
        ]);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario creado exitosamente');
    }

    public function edit($id)
    {
        $usuario = Usuario::with('roles')->findOrFail($id);
        $roles = Role::all();
        return view('admin.usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255|min:3',
            'email' => 'required|string|email|max:255|unique:usuarios,email,' . $id,
            'password' => 'nullable|string|min:8',
            'estatus' => 'required|in:activo,inactivo',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Formato de correo inválido.',
            'email.unique' => 'Este correo ya está registrado por otro usuario.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'estatus.in' => 'El estatus seleccionado no es válido.'
        ]);

        $usuario->fill([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'estatus' => $request->estatus,
        ]);

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado con éxito y validaciones completadas.');
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);

        // 1. Evitar que el admin se elimine a sí mismo
        if ($id == auth()->id()) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta de administrador.');
        }

        // 2. Verificar si tiene contratos activos (como dueño o inquilino)
        $tieneContratosActivos = $usuario->contratosComoPropietario()->where('estatus', 'activo')->exists() || 
                                 $usuario->contratosComoInquilino()->where('estatus', 'activo')->exists();

        if ($tieneContratosActivos) {
            return redirect()->back()->with('error', 'No se puede eliminar al usuario: tiene contratos vigentes. Finalice los contratos primero.');
        }

        // 3. Verificar si tiene inmuebles publicados
        if ($usuario->inmuebles()->exists()) {
            return redirect()->back()->with('error', 'El usuario tiene propiedades registradas. Elimine o transfiera las propiedades antes de borrar al usuario.');
        }

        $usuario->delete();
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }

    public function reporte()
    {
        $usuarios = Usuario::with('roles')->get();
        $pdf = Pdf::loadView('admin.usuarios.reporte', compact('usuarios'));
        return $pdf->download('reporte_usuarios.pdf');
    }
}
