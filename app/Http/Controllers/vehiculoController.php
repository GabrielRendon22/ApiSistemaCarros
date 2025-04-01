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
            'marca' => 'required|string|min:2|max:50',
            'modelo' => 'required|string|min:2|max:50',
            'anio' => 'required|integer|digits:4|min:1900|max:' . date('Y'),
            'placa' => 'required|string|min:6|max:10|unique:vehiculos,placa',
            'id_estado' => 'required|integer|exists:estados,id_estado',
            'id_categoria' => 'required|integer|exists:categorias,id_categoria',
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
    public function vehiculosSinReserva()
    {
        $vehiculosReservados = Reservacion::pluck('id_vehiculo'); // Obtener todos los vehículos con reservas
        $vehiculosSinReserva = Vehiculo::whereNotIn('id_vehiculo', $vehiculosReservados)->get();

        return response()->json($vehiculosSinReserva);
    }

    /**
     * Listar vehículos CON reservas.
     */
    public function vehiculosConReserva()
    {
        $vehiculosReservados = Vehiculo::whereHas('reservaciones')->with('reservaciones')->get();

        return response()->json($vehiculosReservados);
    }
}