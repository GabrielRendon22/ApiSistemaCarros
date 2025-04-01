<?php
namespace App\Http\Controllers;

use App\Models\Reservacion;
use Illuminate\Http\Request;

class ReservacionController extends Controller
{
    public function index()
    {
        return Reservacion::with(['suscripcion', 'vehiculo'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha_desde' => 'required|date',
            'fecha_hasta' => 'required|date|after:fecha_desde',
            'id_suscripcion' => 'required|exists:Suscripcion,suscripcion_id',
            'id_vehiculo' => 'required|exists:Vehiculo,vehiculo_id'
        ]);
        return Reservacion::create($request->all());
    }

    public function show($id)
    {
        return Reservacion::with(['suscripcion', 'vehiculo'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $reservacion = Reservacion::findOrFail($id);
        $reservacion->update($request->all());
        return $reservacion;
    }

    public function destroy($id)
    {
        $reservacion = Reservacion::findOrFail($id);
        $reservacion->delete();
        return response()->json(['message' => 'Reservación eliminada']);

    }
        
}
