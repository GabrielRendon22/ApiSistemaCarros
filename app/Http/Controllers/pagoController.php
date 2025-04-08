<?php
namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index()
    {
        // Incluir la información de la suscripción y del plan
        return Pago::with(['suscripcion.plan'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_suscripcion' => 'required|integer|exists:suscripciones,id_suscripcion',
            'fecha_registro' => 'nullable|date',
            'monto' => 'required|numeric|min:0',
        ]);
        
        return Pago::create($request->all());
    }

    public function show($id)
    {
        // Incluir la información de la suscripción y del plan
        return Pago::with(['suscripcion.plan'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $pago = Pago::findOrFail($id);
        $pago->update($request->all());
        return $pago;
    }

    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);
        $pago->delete();
        return response()->json(['message' => 'Pago eliminado']);
    }

    public function pagosPorCliente($id_usuario)
    {
        // Obtener todos los pagos realizados por el cliente
        $pagos = Pago::whereHas('suscripcion', function ($query) use ($id_usuario) {
            $query->where('id_usuario', $id_usuario);
        })->with(['suscripcion.plan'])->get();

        if ($pagos->isEmpty()) {
            return response()->json(['message' => 'No se encontraron pagos para este cliente'], 404);
        }

        return response()->json($pagos);
    }
}