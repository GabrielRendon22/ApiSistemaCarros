<?php
namespace App\Http\Controllers;

use App\Models\Suscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SuscripcionController extends Controller
{
    public function index()
    {
        Log::info('Accediendo al listado de suscripciones');
        return Suscripcion::with('plan')->get();
    }

    public function store(Request $request)
    {
        Log::info('Solicitud de creación recibida', [
            'ip' => $request->ip(),
            'datos' => $request->all(),
            'fecha_actual' => now()->toDateString()
        ]);

        $validatedData = $request->validate([
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
            'fecha_pago' => 'nullable|date|after_or_equal:fecha_inicio',
            'id_usuario' => 'required|integer|exists:usuarios,id_usuario',
            'id_plan' => 'required|integer|exists:planes,id_plan',
            'id_estado' => 'required|integer|exists:estados,id_estado',
        ]);

        Log::debug('Datos después de validación', $validatedData);

        try {
            DB::beginTransaction();
            
            Log::info('Intentando crear registro en BD');
            $suscripcion = Suscripcion::create($validatedData);
            
            Log::info('Registro creado en BD', ['id' => $suscripcion->id_suscripcion]);
            DB::commit();
            
            return response()->json([
                'success' => true,
                'data' => $suscripcion
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Fallo al crear suscripción', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Error al guardar',
                'detalle' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function show($id)
    {
        Log::info('Buscando suscripción', ['id' => $id]);
        return Suscripcion::with(['plan', 'usuario', 'estado'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        Log::info('Solicitud de actualización', [
            'id' => $id,
            'datos' => $request->all()
        ]);

        $suscripcion = Suscripcion::findOrFail($id);
        $suscripcion->update($request->all());
        
        Log::info('Suscripción actualizada', [
            'id' => $id,
            'nuevos_datos' => $suscripcion->getChanges()
        ]);
        
        return $suscripcion;
    }

    public function destroy($id)
    {
        Log::warning('Eliminando suscripción', ['id' => $id]);
        
        $suscripcion = Suscripcion::findOrFail($id);
        $suscripcion->delete();
        
        Log::info('Suscripción eliminada', ['id' => $id]);
        
        return response()->json(['message' => 'Suscripción eliminada']);
    }

    public function suscripcionActivaPorCliente($id_usuario)
    {
        Log::info('Buscando suscripción activa para el cliente', ['id_usuario' => $id_usuario]);

        $suscripcionActiva = Suscripcion::where('id_usuario', $id_usuario)
            ->where('id_estado', 1) // Suponiendo que el estado "1" representa una suscripción activa
            ->whereDate('fecha_inicio', '<=', now())
            ->whereDate('fecha_fin', '>=', now())
            ->with(['plan', 'estado'])
            ->first();

        if (!$suscripcionActiva) {
            Log::info('No se encontró una suscripción activa para el cliente', ['id_usuario' => $id_usuario]);
            return response()->json(['message' => 'No se encontró una suscripción activa para este cliente'], 404);
        }

        Log::info('Suscripción activa encontrada', ['id_usuario' => $id_usuario, 'id_suscripcion' => $suscripcionActiva->id_suscripcion]);

        return response()->json($suscripcionActiva);
    }

    public function suscripcionesPorCliente($id_usuario)
    {
        // 1) Buscas las suscripciones con sus relaciones:
        $suscripciones = Suscripcion::where('id_usuario', $id_usuario)
            ->with(['plan.categoria','estado'])
            ->get();
    
        // 2) Si no hay suscripciones, devuelves 404:
        if ($suscripciones->isEmpty()) {
            return response()->json(['message' => 'No se encontraron suscripciones para este cliente'], 404);
        }
    
        // 3) Mapeas cada suscripción para construir tu propia estructura:
        $suscripcionesTransformadas = $suscripciones->map(function($suscripcion) {
            return [
                'id_suscripcion' => $suscripcion->id_suscripcion,
                'fecha_inicio'   => $suscripcion->fecha_inicio,
                'fecha_fin'      => $suscripcion->fecha_fin,
                'plan'           => [
                    'id'        => $suscripcion->plan->id_plan,
                    'nombre'    => $suscripcion->plan->nombre_plan,
                    'categoria' => [
                        'id'     => $suscripcion->plan->categoria->id_categoria,
                        'nombre' => $suscripcion->plan->categoria->nombre_categoria,
                    ],
                ],
                // Aqui puedes "aplanar" o ajustar como quieras mostrar el estado
                'estado' => [
                    'id'     => $suscripcion->estado->id_estado,
                    'nombre' => $suscripcion->estado->descripcion,
                ],
            ];
        });
    
        // 4) Devuelves el arreglo transformado como JSON:
        return response()->json($suscripcionesTransformadas);
    }
    
}