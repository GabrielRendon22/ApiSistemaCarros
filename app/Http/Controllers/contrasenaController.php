<?php
namespace App\Http\Controllers;

use App\Models\Contrasena;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ContrasenaController extends Controller
{
    public function index()
    {
        return Contrasena::with('usuario')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'contrasena' => 'required|min:8',
            'id_usuario' => 'required|exists:usuarios,id'
        ]);

        $data = $request->all();
        $data['contrasena'] = Hash::make($request->contrasena); // Encriptar la contraseña
        return Contrasena::create($data);
    }

    public function show($id)
    {
        return Contrasena::with('usuario')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $contrasena = Contrasena::findOrFail($id);
        $data = $request->all();
        if ($request->has('contrasena')) {
            $data['contrasena'] = Hash::make($request->contrasena);
        }
        $contrasena->update($data);
        return $contrasena;
    }

    public function destroy($id)
    {
        $contrasena = Contrasena::findOrFail($id);
        $contrasena->delete();
        return response()->json(['message' => 'Contraseña eliminada']);
    }
}