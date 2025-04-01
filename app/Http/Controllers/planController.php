<?php
namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        return Plan::with('categoria')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_plan' => 'required',
            'precio_mensual' => 'required|numeric|min:0',
            'limite_km' => 'nullable|numeric',
            'id_categoria' => 'required|exists:categorias,id_categoria'
        ]);
        return Plan::create($request->all());
    }

    public function show($id)
    {
        return Plan::with('categoria')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);
        $plan->update($request->all());
        return $plan;
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();
        return response()->json(['message' => 'Plan eliminado']);
    }
}