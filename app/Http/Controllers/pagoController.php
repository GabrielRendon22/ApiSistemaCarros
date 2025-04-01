<?php
namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index()
    {
        return Pago::with('suscripcion')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_suscripcion' => 'required|exists:Suscripcion,suscripcion_id'
        ]);
        return Pago::create($request->all());
    }

    public function show($id)
    {
        return Pago::with('suscripcion')->findOrFail($id);
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
}