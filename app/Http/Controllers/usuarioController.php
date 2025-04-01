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
            'nombres' => 'required',
            'email' => 'required|email|unique:usuarios',
            'id_rol' => 'required|exists:Rols,id'
        ]);
        return Usuario::create($request->all());
    }

    public function show($id)
    {
        return Usuario::with('rols')->findOrFail($id);
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