<?php

namespace App\Http\Controllers;

use App\Models\HorseSpecialCondition;
use App\Models\Horse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HorseSpecialConditionController extends Controller
{
    /**
     * Display conditions with filtering and pagination
     * 
     * Lógica de negocio: Lista condiciones especiales con capacidad
     * de filtrado por tipo, estado, caballo o fechas
     */
    public function index(Request $request){
        try {
            $query = HorseSpecialCondition::with(['horse', 'assignedTo']);

            // Filtros múltiples
            if ($request->has('horse_id') && $request->horse_id) {
                $query->where('horse_id', $request->horse_id);
            }

            if ($request->has('type') && $request->type) {
                $query->where('type', $request->type);
            }

            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('start_date', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('start_date', '<=', $request->date_to);
            }

            // Solo condiciones activas por defecto
            if (!$request->has('status')) {
                $query->where('status', 'ACTIVE');
            }

            $conditions = $query->orderBy('start_date', 'desc')->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $conditions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar las condiciones: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created special condition
     * 
     * Lógica de negocio: Crea una nueva condición especial para un caballo
     * con validación de fechas y conflictos de condiciones
     */
    public function store(Request $request){
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'horse_id' => 'required|exists:horses,id',
                'type' => 'required|in:TRAINING,MEDICAL,NUTRITION,BEHAVIORAL,RECREATION',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after:start_date',
                'status' => 'required|in:ACTIVE,COMPLETED,CANCELLED'
            ]);

            $horse = Horse::findOrFail($validated['horse_id']);

            // Verificar conflictos de condiciones activas del mismo tipo
            if ($validated['status'] === 'ACTIVE') {
                $conflictingCondition = HorseSpecialCondition::where('horse_id', $horse->id)
                    ->where('type', $validated['type'])
                    ->where('status', 'ACTIVE')
                    ->where(function($query) use ($validated) {
                        $query->whereNull('end_date')
                            ->orWhere('end_date', '>=', $validated['start_date']);
                    })
                    ->first();

                if ($conflictingCondition) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ya existe una condición activa de este tipo para el caballo'
                    ], 422);
                }
            }

            $condition = HorseSpecialCondition::create([
                'horse_id' => $horse->id,
                'type' => $validated['type'],
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'] ?? null,
                'status' => $validated['status'],
                'assigned_to' => Auth::id()
            ]);

            DB::commit();

            $condition->load(['horse', 'assignedTo']);

            return response()->json([
                'success' => true,
                'message' => 'Condición especial registrada exitosamente',
                'data' => $condition
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la condición: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified special condition
     * 
     * Lógica de negocio: Actualiza una condición existente
     * con validación de integridad de datos
     */
    public function update(Request $request, $id){
        DB::beginTransaction();

        try {
            $condition = HorseSpecialCondition::findOrFail($id);

            $validated = $request->validate([
                'type' => 'sometimes|in:TRAINING,MEDICAL,NUTRITION,BEHAVIORAL,RECREATION',
                'title' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'sometimes|date',
                'end_date' => 'nullable|date|after:start_date',
                'status' => 'sometimes|in:ACTIVE,COMPLETED,CANCELLED'
            ]);

            $condition->update($validated);

            DB::commit();

            $condition->load(['horse', 'assignedTo']);

            return response()->json([
                'success' => true,
                'message' => 'Condición actualizada exitosamente',
                'data' => $condition
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la condición: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active conditions for a specific horse
     * 
     * Lógica de negocio: Obtiene todas las condiciones activas
     * de un caballo para mostrar en su ficha técnica
     */
    public function getHorseConditions($horseId){
        try {
            $horse = Horse::findOrFail($horseId);

            $conditions = HorseSpecialCondition::with(['assignedTo'])
                ->where('horse_id', $horseId)
                ->where('status', 'ACTIVE')
                ->where('start_date', '<=', now())
                ->where(function($query) {
                    $query->whereNull('end_date')
                        ->orWhere('end_date', '>=', now());
                })
                ->orderBy('start_date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'horse' => $horse,
                'active_conditions' => $conditions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar las condiciones: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete a special condition
     * 
     * Lógica de negocio: Marca una condición como completada
     * automáticamente cuando finaliza su período
     */
    public function completeCondition($id){
        DB::beginTransaction();

        try {
            $condition = HorseSpecialCondition::findOrFail($id);

            if ($condition->status === 'COMPLETED') {
                return response()->json([
                    'success' => false,
                    'message' => 'La condición ya está completada'
                ], 422);
            }

            $condition->update([
                'status' => 'COMPLETED',
                'end_date' => $condition->end_date ?? now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Condición marcada como completada',
                'data' => $condition
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al completar la condición: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get condition statistics
     * 
     * Lógica de negocio: Genera estadísticas de condiciones
     * por tipo y estado para análisis gerencial
     */
    public function statistics(Request $request){

        try {
            $dateFrom = $request->date_from ?? now()->subMonth();
            $dateTo = $request->date_to ?? now();

            $stats = HorseSpecialCondition::select(
                DB::raw('COUNT(*) as total_conditions'),
                DB::raw('type, COUNT(type) as type_count'),
                DB::raw('status, COUNT(status) as status_count')
            )
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('type', 'status')
            ->get();

            $activeByType = HorseSpecialCondition::select(
                DB::raw('type'),
                DB::raw('COUNT(*) as active_count')
            )
            ->where('status', 'ACTIVE')
            ->where('start_date', '<=', now())
            ->where(function($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->groupBy('type')
            ->get();

            return response()->json([
                'success' => true,
                'period' => ['from' => $dateFrom, 'to' => $dateTo],
                'statistics' => $stats,
                'active_by_type' => $activeByType
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar estadísticas: ' . $e->getMessage()
            ], 500);
        }
    }
}