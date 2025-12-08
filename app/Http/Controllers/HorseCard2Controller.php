<?php

namespace App\Http\Controllers;

use App\Models\Assigment;
use App\Models\AssigmentDetail;
use App\Models\Horse;
use App\Models\Service;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache; // 🔥 FALTABA ESTE IMPORT
use Carbon\Carbon; // 🔥 FALTABA ESTE IMPORT

class HorseCard2Controller extends Controller
{
    /**
     * Método principal - Lista de horseCards paginadas
     */
    public function index(Request $request)
    {
        $query = Assigment::with([
            'service', 
            'assignedBy', 
            'executedBy', 
            'horse',
            'assignmentDetails.service',
            'assignmentDetails.executedBy'
        ]);

        $assignments = $query->orderBy('assigned_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $horses = Horse::where('status', 1)->get();
        $service = Service::where('status', true)->get(); 
        
        return view('horsecard2.index', compact('assignments', 'service','horses'));
    }

    /**
     * Obtener detalles de una asignación para el modal
     */
    public function assignedDetails($id)
    {
        try {
            $assignment = Assigment::with([
                'horse',
                'service',
                'assignedBy',
                'executedBy',
                'assignmentDetails.service',
                'assignmentDetails.executedBy'
            ])->findOrFail($id);

            $html = view('horsecard2.partials.assigned-details', compact('assignment'))->render();

            return response()->json([
                'success' => true,
                'assigned' => $assignment,
                'html' => $html
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar detalles de asignación: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los detalles de la asignación: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ejecutar servicio
     */
    public function executeService(Request $request, $assignmentId)
    {
        try {
            DB::beginTransaction();

            // Validar los datos de entrada
            $validated = $request->validate([
                'detail_id' => 'required|exists:assignment_details,id',
                'shift' => 'required|in:AM,PM'
            ]);

            // Obtener el detalle específico a ejecutar
            $assignmentDetail = AssigmentDetail::where('id', $validated['detail_id'])
                ->where('assigned_id', $assignmentId)
                ->where('shift', $validated['shift'])
                ->firstOrFail();

            // Verificar que el servicio no esté ya ejecutado
            if ($assignmentDetail->is_executed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este servicio ya ha sido ejecutado anteriormente.'
                ], 400);
            }

            // Obtener el usuario autenticado
            $user = Auth::user();
            $currentDateTime = Carbon::now();

            // 🔥 ACTUALIZAR EL DETALLE DEL SERVICIO
            $assignmentDetail->update([
                'is_executed' => 1,
                'executed_at' => $currentDateTime,
                'executed_by' => $user->id
            ]);

            // 🔥 VERIFICAR SI TODOS LOS DETALLES ESTÁN EJECUTADOS
            $assignment = Assigment::with('assignmentDetails')->findOrFail($assignmentId);
            
            // Contar detalles ejecutados vs totales
            $totalDetails = $assignment->assignmentDetails->count();
            $executedDetails = $assignment->assignmentDetails->where('is_executed', 1)->count();
            
            $allExecuted = ($totalDetails > 0) && ($executedDetails === $totalDetails);

            Log::info("Verificación de ejecución completa - Assignment: {$assignmentId}, Total: {$totalDetails}, Ejecutados: {$executedDetails}, TodosEjecutados: " . ($allExecuted ? 'Sí' : 'No'));

            // Si todos los servicios están ejecutados, actualizar la asignación principal
            if ($allExecuted) {
                $assignment->update([
                    'status' => 'EXECUTED',
                    'executed_at' => $currentDateTime,
                    'executed_by' => $user->id
                ]);
                
                Log::info("Assignment {$assignmentId} marcado como EXECUTED - Todos los servicios completados");
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Servicio ejecutado correctamente.',
                'data' => [
                    'detail_id' => $assignmentDetail->id,
                    'executed_at' => $currentDateTime->format('d/m/Y H:i'),
                    'executed_by' => $user->name,
                    'all_executed' => $allExecuted,
                    'executed_count' => $executedDetails,
                    'total_count' => $totalDetails
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Datos de entrada inválidos.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el detalle del servicio especificado.'
            ], 404);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al ejecutar servicio: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al ejecutar el servicio: ' . $e->getMessage()
            ], 500);
        }
    }

     /**
     * 🔥 MÉTODO MEJORADO: Resetear asignaciones expiradas y eliminar detalles vencidos
     * 
     * Este método realiza dos acciones principales:
     * 1. Resetea asignaciones EXECUTED de días anteriores a ASSIGNED
     * 2. Elimina detalles de asignaciones con fecha límite expirada
     */
    public function resetExpiredAssignments(){

        $cacheKey = 'assignment_status_check_last_run';
        
        try {
            DB::beginTransaction();

            $today = Carbon::today();
            $now = Carbon::now();
            
            Log::info("🔄 Iniciando reset de asignaciones expiradas - Fecha: {$today->format('Y-m-d')}");

            //  1. OBTENER ASIGNACIONES EXPIRADAS (assigned_date < hoy Y status = EXECUTED)
            $expiredAssignmentIds = Assigment::where('assigned_date', '<', $today)
                ->where('status', 'EXECUTED')
                ->pluck('id');

            $totalAssignments = $expiredAssignmentIds->count();

            //  2. ELIMINACIÓN DE DETALLES CON FECHA LÍMITE EXPIRADA (SIEMPRE se ejecuta)
            $detailsDeleted = AssigmentDetail::whereNotNull('limit_date')
                ->where('limit_date', '<', $today) // 🔥 Usar $today en lugar de now() para consistencia
                ->delete();

            Log::info("🚮 Detalles expirados eliminados: {$detailsDeleted}");

            //  3. RESETEO DE ASIGNACIONES EXPIRADAS (solo si existen)
            if ($totalAssignments > 0) {
                
                // 3.1 Resetear assignment_details relacionados
                $detailsUpdated = AssigmentDetail::whereIn('assigned_id', $expiredAssignmentIds)
                    ->where('is_executed', 1)
                    ->update([
                        'is_executed' => 0,
                        'executed_by' => NULL,
                        'executed_at' => NULL,
                        'updated_at' => $now
                    ]);

                // 3.2 Resetear assignments principales
                $assignmentsUpdated = Assigment::whereIn('id', $expiredAssignmentIds)
                    ->update([
                        'status' => 'ASSIGNED',
                        'executed_by' => NULL,
                        'executed_at' => NULL,
                        'updated_at' => $now
                    ]);

                // 3.3 Actualizar cache
                Cache::put($cacheKey, $now, now()->addHours(8));

                DB::commit();

                Log::info("✅ Reset completado - Assignments: {$assignmentsUpdated}, Details: {$detailsUpdated}, Deleted: {$detailsDeleted}");

                return response()->json([
                    'success' => true,
                    'message' => "Reset manual completado exitosamente",
                    'data' => [
                        'assignments_updated' => $assignmentsUpdated,
                        'details_updated' => $detailsUpdated,
                        'details_deleted' => $detailsDeleted,
                        'total_expired' => $totalAssignments
                    ]
                ]);

            } else {
                DB::commit();

                Log::info("ℹ️  No se encontraron asignaciones expiradas para resetear. Detalles eliminados: {$detailsDeleted}");

                return response()->json([
                    'success' => true,
                    'message' => "No se encontraron asignaciones expiradas para resetear, pero se eliminaron {$detailsDeleted} detalles con fecha límite expirada",
                    'data' => [
                        'assignments_updated' => 0,
                        'details_updated' => 0,
                        'details_deleted' => $detailsDeleted,
                        'total_expired' => 0
                    ]
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("❌ Error en reset manual: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => "Error en reset manual: " . $e->getMessage()
            ], 500);
        }
    }
}