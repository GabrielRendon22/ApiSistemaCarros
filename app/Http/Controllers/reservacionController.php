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
        'fecha_desde' => 'required|date|after_or_equal:today',
        'fecha_hasta' => 'required|date|after:fecha_desde',
        'id_suscripcion' => 'required|integer|exists:suscripciones,id_suscripcion',
        'id_vehiculo' => 'required|integer|exists:vehiculos,id_vehiculo',
    ]);

    // Validar que el vehículo no esté reservado en ese rango de fechas
    $reservaExistente = Reservacion::where('id_vehiculo', $request->id_vehiculo)
        ->where(function ($query) use ($request) {
            $query->whereBetween('fecha_desde', [$request->fecha_desde, $request->fecha_hasta])
                  ->orWhereBetween('fecha_hasta', [$request->fecha_desde, $request->fecha_hasta])
                  ->orWhere(function ($query) use ($request) {
                      $query->where('fecha_desde', '<=', $request->fecha_desde)
                            ->where('fecha_hasta', '>=', $request->fecha_hasta);
                  });
        })
        ->exists();

    if ($reservaExistente) {
        return response()->json([
            'error' => 'El vehículo ya está reservado en ese período de tiempo.'
        ], 422);
    }

    // Crear la reserva
    $reservacion = Reservacion::create($request->all());

    return response()->json([
        'message' => 'Reservación creada exitosamente.',
        'data' => $reservacion
    ], 201);
}

    public function show($id)
    {
        return Reservacion::with(['suscripcion', 'vehiculo'])->findOrFail($id);
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'fecha_desde' => 'required|date|after_or_equal:today',
        'fecha_hasta' => 'required|date|after:fecha_desde',
        'id_suscripcion' => 'required|integer|exists:suscripciones,id_suscripcion',
        'id_vehiculo' => 'required|integer|exists:vehiculos,id_vehiculo',
    ]);

    $reservacion = Reservacion::findOrFail($id);

    // Validar que el vehículo no esté reservado en el nuevo rango de fechas
    $reservaExistente = Reservacion::where('id_vehiculo', $request->id_vehiculo)
        ->where('id_reservacion', '!=', $id) // Excluir la reserva actual
        ->where(function ($query) use ($request) {
            $query->whereBetween('fecha_desde', [$request->fecha_desde, $request->fecha_hasta])
                  ->orWhereBetween('fecha_hasta', [$request->fecha_desde, $request->fecha_hasta])
                  ->orWhere(function ($query) use ($request) {
                      $query->where('fecha_desde', '<=', $request->fecha_desde)
                            ->where('fecha_hasta', '>=', $request->fecha_hasta);
                  });
        })
        ->exists();

    if ($reservaExistente) {
        return response()->json([
            'error' => 'El vehículo ya está reservado en ese período de tiempo.'
        ], 422);
    }

    // Actualizar la reserva
    $reservacion->update($request->all());

    return response()->json([
        'message' => 'Reservación actualizada exitosamente.',
        'data' => $reservacion
    ]);
}


    public function destroy($id)
    {
        $reservacion = Reservacion::findOrFail($id);
        $reservacion->delete();
        return response()->json(['message' => 'Reservación eliminada']);
    }
}