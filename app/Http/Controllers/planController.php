<?php
namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        return Plan::with('categorias')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_plan' => 'required|string|max:500',
            'descripcion' => 'nullable|string', // Agregado: Campo opcional de tipo texto
            'precio_mensual' => 'required|numeric|min:0',
            'limite_km' => 'nullable|numeric',
            'id' => 'required|exists:categorias,categoria_id' // Corregido: Nombre de columna correcto
        ]);
        
        return Plan::create($request->all());
    }

    public function show($id)
    {
        return Plan::with('categoria')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_plan' => 'sometimes|required|string|max:500',
            'descripcion' => 'nullable|string', // Agregado: Validación en update
            'precio_mensual' => 'sometimes|required|numeric|min:0',
            'limite_km' => 'nullable|numeric',
            'id' => 'sometimes|required|exists:categorias,id' // Corregido
        ]);

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