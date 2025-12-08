<?php

namespace App\Http\Controllers;

use App\Models\Assigment;
use App\Models\AssigmentDetail;
use App\Models\Service;
use App\Models\Horse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){

        $assignments = Assigment::with(['horse', 'service', 'assignedBy', 'executedBy', 'assignmentdetails'])
            ->latest()
            ->orderBy('assigned_number', 'desc')
            ->get();

        return view('assignments.index', compact('assignments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(){

        $horses = Horse::where('status', 1)
                        ->where('add_service', false)
                        ->get(); 
        $services = Service::all();
        $currentUser = auth()->user(); // Obtener usuario logeado
    
        return view('assignments.create', compact('horses', 'services', 'currentUser'));

    }

     /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){

        try {
            DB::beginTransaction();
    
            // DEBUG: Ver qué llega
            Log::info('=== STORE REQUEST DATA ===');
            Log::info('Horse ID: ' . $request->horse_id);
            Log::info('Assignment Date: ' . $request->assigned_date);
            Log::info('Services count: ' . count($request->services ?? []));
            Log::info('Services data: ', $request->services ?? []);
            Log::info('User ID: ' . auth()->id());
    
            $validated = $request->validate([
                'horse_id' => 'required|exists:horses,id',
                'assigned_date' => 'required|date',
                'notes' => 'nullable|string|max:500',
                'services' => 'required|array|min:1',
                'services.*.service_id' => 'required|exists:services,id',
                'services.*.shift' => 'required|in:AM,PM,BOTH',
                'services.*.limit_date' => 'nullable|date',
            ]);
    
            // Generar número de asignación
            $assignmentNumber = Assigment::generateSupplyNumber();
            
            // Obtener usuario logeado
            $assignedBy = auth()->id();
    
            // OBTENER EL PRIMER SERVICIO DEL ARRAY
            $firstService = reset($validated['services']);
            $firstServiceId = $firstService['service_id'];
    
            Log::info('First service ID: ' . $firstServiceId);
    
            // ACTUALIZAR EL CABALLO - marcar add_service como true
            $horse = Horse::findOrFail($validated['horse_id']);
            $horse->update([
                'add_service' => true
            ]);
    
            Log::info('Horse updated - add_service set to true for horse ID: ' . $horse->id);
    
            // Crear el Assignment (cabecera)
            $assignment = Assigment::create([
                'assigned_number' => $assignmentNumber,
                'horse_id' => $validated['horse_id'],
                'service_id' => $firstServiceId,
                'assigned_by' => $assignedBy,
                'executed_by' => null,
                'status' => 'ASSIGNED',
                'assigned_date' => $validated['assigned_date'],
                'notes' => $validated['notes'] ?? null,
                'executed_at' => null,
            ]);
    
            Log::info('Assignment created with ID: ' . $assignment->id);
    
            // Crear los AssignmentDetails (detalles)
            foreach ($validated['services'] as $serviceData) {
                AssigmentDetail::create([
                    'assigned_id' => $assignment->id,
                    'service_id' => $serviceData['service_id'],
                    'limit_date' => !empty($serviceData['limit_date']) ? $serviceData['limit_date'] : null,
                    'shift' => $serviceData['shift'],
                    'is_executed' => false,
                    'executed_at' => null,
                    'executed_by' => null,
                    'assigned_date' => $validated['assigned_date'],
                ]);
                
                Log::info('AssignmentDetail created for service: ' . $serviceData['service_id'] . ' with shift: ' . $serviceData['shift']);
            }
    
            DB::commit();
    
            Log::info('Assignment successfully created: ' . $assignmentNumber);
    
            return redirect()->route('assignments.index')
                ->with('success', 'Asignación creada exitosamente.');
    
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error creating assignment: ' . $e->getMessage());
            Log::error('Exception trace: ' . $e->getTraceAsString());
    
            return redirect()->back()
                ->with('error', 'Error al crear la asignación: ' . $e->getMessage())
                ->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Assigment $assignment){

        $assignment->load(['horse', 'service', 'assignedBy', 'executedBy', 'assignmentdetails']);
        
        return view('assignments.show', compact('assignment'));
    }

    
    // Método para visualizar los detalles del suministro en modal detalles
    /* public function assignedDetails($id){
        try {
            $assigned = Assigment::with([
                'horse',
                'service',
                'assignedBy',
                'executedBy',
                'assignmentDetails.service',
                'assignmentDetails.executedBy' // Agregar esta relación
            ])->findOrFail($id);
    
            // Verificar diferentes posibles ubicaciones de la vista
            $possibleViewPaths = [
                'assignments.partials.assigned-details',
                'assignments.partials.assignment-details', 
                'admin.assignments.partials.assigned-details'
            ];
    
            $viewPath = null;
            foreach ($possibleViewPaths as $path) {
                if (view()->exists($path)) {
                    $viewPath = $path;
                    break;
                }
            }
    
            if (!$viewPath) {
                Log::error("Ninguna vista de detalles encontrada. Buscadas: " . implode(', ', $possibleViewPaths));
                return response()->json([
                    'success' => false,
                    'message' => 'Vista no encontrada. Rutas buscadas: ' . implode(', ', $possibleViewPaths)
                ], 404);
            }
    
            Log::info("Cargando vista: {$viewPath} para assignment ID: {$id}");
    
            $html = view($viewPath, compact('assigned'))->render();
    
            return response()->json([
                'success' => true,
                'assigned' => $assigned,
                'html' => $html
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en assignmentDetails: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error cargando los detalles de la asignación: ' . $e->getMessage()
            ], 500);
        }
    } */


    // ELIMINAR el método assignedDetails y mantener solo este:
    public function details(Assigment $assignment){
        
        try {
            // Cargar relaciones completas - CORREGIDO
            $assignment->load([
                'horse',
                'service', 
                'assignedBy',
                'executedBy',
                'assignmentDetails.service',
                'assignmentDetails.executedBy' // AGREGAR esta relación para los detalles
            ]);
            
            Log::info('Cargando detalles para assignment ID: ' . $assignment->id);
            Log::info('Total assignmentDetails: ' . $assignment->assignmentDetails->count());
            Log::info('AM services: ' . $assignment->assignmentDetails->where('shift', 'AM')->count());
            Log::info('PM services: ' . $assignment->assignmentDetails->where('shift', 'PM')->count());
            
            // Verificar si la vista existe
            $viewPath = 'assignments.partials.assigned-details';
            if (!view()->exists($viewPath)) {
                Log::error("La vista {$viewPath} NO existe");
                return response()->json([
                    'success' => false,
                    'message' => 'Vista no encontrada'
                ], 404);
            }
            
            $html = view($viewPath, compact('assignment'))->render();
            
            Log::info('Vista cargada exitosamente');
            
            return response()->json([
                'success' => true,
                'assigned' => $assignment, // CORREGIDO: Cambiar 'supply' por 'assigned'
                'html' => $html
            ]);
        } catch (\Exception $e) {
            Log::error('Error en details method: ' . $e->getMessage());
            Log::error('Exception trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los detalles: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    // En AssignmentController.php
    public function edit($id){

        try {
            $assignment = Assigment::with([
                'horse',
                'assignmentDetails.service' // Cargar detalles con servicio
            ])->findOrFail($id);

            // Obtener lista de servicios para el select
            $services = Service::all();

            return response()->json([
                'success' => true,
                'assignment' => $assignment,
                'services' => $services
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading assignment for edit: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error cargando los datos de la asignación: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
   // En AssignmentController.php
   public function update(Request $request, $id){

       try {
           DB::beginTransaction();
   
           Log::info("=== UPDATE ASSIGNMENT REQUEST ===");
           Log::info("All request data: ", $request->all());
   
           $assignment = Assigment::findOrFail($id);
   
           // CORRECCIÓN: Validación más flexible para debugging
           $validated = $request->validate([
               'notes' => 'nullable|string|max:500',
               'new_services' => 'sometimes|array',
               'deleted_services' => 'sometimes|array',
           ]);
   
           Log::info("Validated data: ", $validated);
   
           // Actualizar notas si existen
           if (isset($validated['notes'])) {
               $assignment->update([
                   'notes' => $validated['notes']
               ]);
               Log::info("Notes updated");
           }
   
           // Eliminar servicios marcados para eliminación
           if (isset($validated['deleted_services']) && count($validated['deleted_services']) > 0) {
               AssigmentDetail::whereIn('id', $validated['deleted_services'])->delete();
               Log::info("Deleted services: " . implode(', ', $validated['deleted_services']));
           }
   
           // Agregar nuevos servicios - CON VALIDACIÓN MANUAL
           if (isset($validated['new_services']) && count($validated['new_services']) > 0) {
               Log::info("Processing new services: ", $validated['new_services']);
               
               foreach ($validated['new_services'] as $index => $serviceData) {
                   // Validación manual de cada servicio
                   if (empty($serviceData['service_id']) || empty($serviceData['shift'])) {
                       Log::error("Invalid service data at index {$index}: " . json_encode($serviceData));
                       continue;
                   }
   
                   $limitDate = !empty($serviceData['limit_date']) ? $serviceData['limit_date'] : null;
                   
                   AssigmentDetail::create([
                       'assigned_id' => $assignment->id,
                       'service_id' => $serviceData['service_id'],
                       'shift' => $serviceData['shift'],
                       'limit_date' => $limitDate,
                       'is_executed' => false,
                       'executed_at' => null,
                       'executed_by' => null,
                       'assigned_date' => $assignment->assigned_date,
                       'assigned_by' => auth()->id(), // ← AGREGAR ESTE CAMPO
                   ]);
                   
                   Log::info("Created new service: Service ID {$serviceData['service_id']}, Shift {$serviceData['shift']}, Limit: " . ($limitDate ?? 'NULL'));
               }
           }
   
           DB::commit();
   
           Log::info("✅ Assignment updated successfully: " . $id);
   
           return response()->json([
               'success' => true,
               'message' => 'Asignación actualizada correctamente',
               'assignment_id' => $id
           ]);
   
       } catch (\Exception $e) {
           DB::rollBack();
           Log::error('❌ Error updating assignment: ' . $e->getMessage());
           Log::error('Stack trace: ' . $e->getTraceAsString());
           
           return response()->json([
               'success' => false,
               'message' => 'Error al actualizar la asignación: ' . $e->getMessage(),
               'error_details' => $e->getMessage()
           ], 500);
       }
   }

     /**----------------------------------------
      * Remove the assignment specified to table.
      ------------------------------------------*/
    public function destroy(Assigment $assignment){
          try {
              DB::beginTransaction();

              // Guardar el horse_id antes de eliminar la asignación
               $horseId = $assignment->horse_id;
      
              $assignment->delete();

              // Actualizar el campo add_service a 0 en la tabla horses
                DB::table('horses')
                ->where('id', $horseId)
                ->update(['add_service' => 0]);
      
              DB::commit();
      
              // Si es una petición AJAX, responder con JSON
              if (request()->ajax() || request()->wantsJson()) {
                  return response()->json([
                      'success' => true,
                      'message' => 'Asignación eliminada exitosamente.'
                  ]);
              }
      
              // Si no es AJAX, redirigir normalmente
              return redirect()->route('assignments.index')
                  ->with('success', 'Asignación eliminada exitosamente.');
      
          } catch (\Exception $e) {
              DB::rollBack();
              
              // Si es una petición AJAX, responder con error JSON
              if (request()->ajax() || request()->wantsJson()) {
                  return response()->json([
                      'success' => false,
                      'message' => 'Error al eliminar la asignación: ' . $e->getMessage()
                  ], 500);
              }
      
              return redirect()->back()
                  ->with('error', 'Error al eliminar la asignación: ' . $e->getMessage());
          }
    }


    /**
     * Remove a specific service from an assignment
     */
    public function destroyDetail(AssigmentDetail $assignmentDetail)
    {
        try {
            DB::beginTransaction();

            // Verificar si el servicio ya fue ejecutado
            if ($assignmentDetail->is_executed) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar un servicio que ya ha sido ejecutado'
                ], 422);
            }

            // Guardar información para el mensaje de éxito
            $serviceName = $assignmentDetail->service ? $assignmentDetail->service->name : 'Servicio';
            $assignedId = $assignmentDetail->assigned_id; // CORREGIDO: assigned_id en lugar de assignment_id

            // Eliminar el detalle
            $assignmentDetail->delete();

            // Verificar si la asignación queda sin servicios - CORREGIDO
            $remainingServices = AssigmentDetail::where('assigned_id', $assignedId)->count(); // CORREGIDO: assigned_id
            
            if ($remainingServices === 0) {
                // Si no quedan servicios, eliminar la asignación completa
                Assigment::find($assignedId)->delete(); // CORREGIDO: assignedId
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Servicio eliminado. La asignación fue eliminada por no tener más servicios.',
                    'redirect' => route('assignments.index')
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Servicio eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el servicio: ' . $e->getMessage()
            ], 500);
        }
    }



    /**
     * Marcar asignación como ejecutada
     */
    public function markAsExecuted(Assigment $assignment){
        try {
            DB::beginTransaction();

            $assignment->update([
                'status' => 'EXECUTED',
                'executed_at' => now(),
                'executed_by' => auth()->id() // Asumiendo que el usuario autenticado ejecuta
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Asignación marcada como ejecutada.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al marcar como ejecutada: ' . $e->getMessage());
        }
    }

    /**
     * Obtener asignaciones del día
     */
    public function today(){

        $assignments = Assigment::today()
            ->with(['horse', 'service', 'assignmentBy'])
            ->get();

        return view('assignments.today', compact('assignments'));
    }

    /**
     * Obtener asignaciones por fecha
     */
    public function byDate(Request $request){

        $date = $request->input('date', today()->format('Y-m-d'));
        
        $assignments = Assigment::byDate($date)
            ->with(['horse', 'service', 'assignmentBy'])
            ->get();

        return view('assignments.by-date', compact('assignments', 'date'));
    }

    /**
     * Obtener asignaciones asignadas
     */
    public function assignment(){

        $assignments = Assigment::assignment()
            ->with(['horse', 'service', 'assignmentBy'])
            ->get();

        return view('assignments.assignment', compact('assignments'));
    }

    /**
     * Obtener asignaciones ejecutadas
     */
    public function executed(){

        $assignments = Assigment::executed()
            ->with(['horse', 'service', 'executedBy'])
            ->get();

        return view('assignments.executed', compact('assignments'));
    }

    public function assignedDetails(){


    }

}