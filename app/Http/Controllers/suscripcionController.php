<?php
namespace App\Http\Controllers;

use App\Models\Suscripcion;
use Illuminate\Http\Request;

class SuscripcionController extends Controller
{
    public function index()
    {
        return Suscripcion::with(['usuarios', 'planes', 'estados'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
            'fecha_pago' => 'nullable|date',
            'id' => 'required|exists:usuarios,id',
            'id' => 'required|exists:planes,id',
            'id' => 'required|exists:estados,id'
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