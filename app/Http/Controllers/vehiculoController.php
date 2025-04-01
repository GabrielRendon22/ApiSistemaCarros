<?php
namespace App\Http\Controllers;

use App\Models\Vehiculo;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    public function index()
    {
        return Vehiculo::with(['estado', 'categoria'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'marca' => 'required',
            'modelo' => 'required',
            'anio' => 'required|digits:4',
            'placa' => 'required|unique:Vehiculo',
            'id_estado' => 'required|exists:Estado,estado_id',
            'id_categoria' => 'required|exists:Categoria,categoria_id'
        ]);
        return Vehiculo::create($request->all());
    }

    public function show($id)
    {
        return Vehiculo::with(['estado', 'categoria'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->update($request->all());
        return $vehiculo;
    }

    public function destroy($id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->delete();
        return response()->json(['message' => 'Vehículo eliminado']);
    }
}