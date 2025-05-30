<?php
namespace App\Http\Controllers;

use App\Models\Reservacion;
use App\Models\Suscripcion;
use App\Models\Vehiculo;
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

        // Obtener la suscripción activa
        $suscripcion = Suscripcion::with('plan')->findOrFail($request->id_suscripcion);

        // Obtener el vehículo que se desea reservar
        $vehiculo = Vehiculo::findOrFail($request->id_vehiculo);

        // Validar que la categoría del vehículo coincida con la categoría del plan de la suscripción
        if ($suscripcion->plan->id_categoria !== $vehiculo->id_categoria) {
            return response()->json([
                'error' => 'No puedes reservar un vehículo de una categoría diferente a la de tu plan.'
            ], 403);
        }

        // Validar que el vehículo no esté reservado en el rango de fechas
        $reservaExistente = Reservacion::where('id_vehiculo', $request->id_vehiculo)
            ->where(function ($query) use ($request) {
                $query->whereBetween('fecha_desde', [$request->fecha_desde, $request->fecha_hasta])
                      ->orWhereBetween('fecha_hasta', [$request->fecha_desde, $request->fecha_hasta]);
            })
            ->exists();

        if ($reservaExistente) {
            return response()->json([
                'error' => 'El vehículo ya está reservado en el rango de fechas seleccionado.'
            ], 409);
        }

        // Crear la reservación
        $reservacion = Reservacion::create($request->all());

        return response()->json($reservacion, 201);
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

        // Validar que la categoría del vehículo coincida con el plan de la suscripción
        $suscripcion = Suscripcion::with('plan')->findOrFail($request->id_suscripcion);
        $vehiculo = Vehiculo::findOrFail($request->id_vehiculo);

        if ($suscripcion->plan->id_categoria !== $vehiculo->id_categoria) {
            return response()->json([
                'error' => 'No puedes reservar un vehículo de una categoría diferente a la de tu plan.'
            ], 403);
        }

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

    public function reservacionesPorCliente($id_usuario)
    {
        // Obtener todas las reservaciones realizadas por el cliente
        $reservaciones = Reservacion::whereHas('suscripcion', function ($query) use ($id_usuario) {
            $query->where('id_usuario', $id_usuario);
        })->with(['vehiculo', 'suscripcion.plan', 'suscripcion.estado'])->get();

        if ($reservaciones->isEmpty()) {
            return response()->json(['message' => 'No se encontraron reservaciones para este cliente'], 404);
        }

        return response()->json($reservaciones);
    }
}