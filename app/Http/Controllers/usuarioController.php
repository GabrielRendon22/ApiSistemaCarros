<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        return Usuario::with('rol')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'telefono' => 'nullable|string|regex:/^\d{8,15}$/',
            'dui' => 'nullable|string|size:9|unique:usuarios,dui',
            'id_rol' => 'required|integer|exists:rols,id_rol',
        ]);

       

        return Usuario::create($request->all());
    }

    public function show($id)
    {
        return Usuario::with('rol')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update($request->all());
        return $usuario;
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();
        return response()->json(['message' => 'Usuario eliminado']);
    }
}
