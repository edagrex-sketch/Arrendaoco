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
    public function index()
    {
        $usuarios = Usuario::with('roles')->get();
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
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'array'
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'estatus' => $request->status ?? 'activo',
        ]);

        if ($request->has('roles')) {
            $usuario->roles()->sync($request->roles);
        }

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
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email,'.$id,
            'roles' => 'array'
        ]);

        $usuario->update([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'estatus' => $request->estatus,
        ]);

        if ($request->filled('password')) {
            $usuario->update(['password' => Hash::make($request->password)]);
        }

        if ($request->has('roles')) {
            $usuario->roles()->sync($request->roles);
        }

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario eliminado exitosamente');
    }

    public function reporte()
    {
        $usuarios = Usuario::with('roles')->get();
        $pdf = Pdf::loadView('admin.usuarios.reporte', compact('usuarios'));
        return $pdf->download('reporte_usuarios.pdf');
    }
}
