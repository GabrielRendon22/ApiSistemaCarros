<?php
namespace App\Http\Controllers;

use App\Models\Suscripcion;
use Illuminate\Http\Request;

class SuscripcionController extends Controller
{
    public function index()
    {
        return Suscripcion::all();
    }

    public function store(Request $request)
{
    $request->validate([
        'fecha_inicio' => [
            'required',
            'date',
            'after_or_equal:today' // Asegura que la fecha de inicio no sea en el pasado
        ],
        'fecha_fin' => [
            'nullable',
            'date',
            'after:fecha_inicio' // La fecha de fin debe ser posterior a la de inicio
        ],
        'fecha_pago' => [
            'nullable',
            'date',
            'after_or_equal:fecha_inicio' // La fecha de pago no debe ser antes de la fecha de inicio
        ],
        'id_cliente' => [
            'required',
            'integer',
            'exists:usuarios,id_usuario' // Verifica que el cliente exista en la tabla usuarios
        ],
        'id_plan' => [
            'required',
            'integer',
            'exists:planes,id_plan' // Verifica que el plan exista en la tabla planes
        ],
        'id_estado' => [
            'required',
            'integer',
            'exists:estados,id_estado' // Verifica que el estado exista en la tabla estados
        ],
    ], [
        // Mensajes personalizados de error
        'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
        'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
        'fecha_inicio.after_or_equal' => 'La fecha de inicio no puede estar en el pasado.',

        'fecha_fin.date' => 'La fecha de fin debe ser una fecha válida.',
        'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',

        'fecha_pago.date' => 'La fecha de pago debe ser una fecha válida.',
        'fecha_pago.after_or_equal' => 'La fecha de pago no puede ser antes de la fecha de inicio.',

        'id_cliente.required' => 'El ID del cliente es obligatorio.',
        'id_cliente.integer' => 'El ID del cliente debe ser un número entero.',
        'id_cliente.exists' => 'El cliente seleccionado no existe.',

        'id_plan.required' => 'El ID del plan es obligatorio.',
        'id_plan.integer' => 'El ID del plan debe ser un número entero.',
        'id_plan.exists' => 'El plan seleccionado no existe.',

        'id_estado.required' => 'El ID del estado es obligatorio.',
        'id_estado.integer' => 'El ID del estado debe ser un número entero.',
        'id_estado.exists' => 'El estado seleccionado no existe.',
    ]);

    try {
        $suscripcion = Suscripcion::create($request->all());
        return response()->json([
            'message' => 'Suscripción creada exitosamente.',
            'suscripcion' => $suscripcion
        ], 201); // Código 201 (Created)
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'No se pudo crear la suscripción.',
            'detalle' => $e->getMessage()
        ], 500);
    }
}


    public function show($id)
    {
        return Suscripcion::with(['usuario', 'plan', 'estado'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $suscripcion = Suscripcion::findOrFail($id);
        $suscripcion->update($request->all());
        return $suscripcion;
    }

    public function destroy($id)
    {
        $suscripcion = Suscripcion::findOrFail($id);
        $suscripcion->delete();
        return response()->json(['message' => 'Suscripción eliminada']);
    }
}