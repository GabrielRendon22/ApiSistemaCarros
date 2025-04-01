<?php
namespace App\Http\Controllers;

use App\Models\Suscripcion;
use Illuminate\Http\Request;

class SuscripcionController extends Controller
{
    public function index()
    {
        return Suscripcion::with(['cliente', 'plan', 'estado'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
            'fecha_pago' => 'nullable|date',
            'id_cliente' => 'required|exists:Usuario,usuario_id',
            'id_plan' => 'required|exists:Plan,id_plan',
            'id_estado' => 'required|exists:Estado,estado_id'
        ]);
        return Suscripcion::create($request->all());
    }

    public function show($id)
    {
        return Suscripcion::with(['cliente', 'plan', 'estado'])->findOrFail($id);
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