<?php
namespace App\Http\Controllers;

use App\Models\categoria;
use Illuminate\Http\Request;

class categoriaController extends Controller
{
    public function index()
    {
        return categoria::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_categoria' => 'required|unique:categorias',
            'descripcion' => 'nullable'
        ]);
        return categoria::create($request->all());
    }

    public function show($id)
    {
        return categoria::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $categoria = categoria::findOrFail($id);
        $categoria->update($request->all());
        return $categoria;
    }

    public function destroy($id)
    {
        $categoria = categoria::findOrFail($id);
        $categoria->delete();
        return response()->json(['message' => 'Categoría eliminada']);
    }
}