<?php

namespace App\Http\Controllers;

use App\Models\Horse;
use App\Models\Product;
use App\Models\Supply;
use App\Models\SupplyDetail;
use App\Models\Stock;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SupplyController extends Controller
{
    public function index(Request $request){

        $query = Supply::with(['store', 'assignedBy', 'executedBy', 'horse']);

        $supplies = $query->orderBy('supply_date', 'desc')
        ->orderBy('created_at', 'desc')
        ->paginate($request->get('per_page', )); // Dinámico

         $stores = Store::where('is_active', true)->get();
         $horses = Horse::where('status', 1)->get(); 
        
        return view('supplies.index', compact('supplies', 'stores', 'horses'));
    }

    public function create(){

        $horses = Horse::where('status', 1)
                        ->where('is_supplied', false)
                        ->get(); 

        $products = Product::with('consumptionUnit')->where('status', 1)->get();

        $stores = Store::where('is_active', true)->get();
        
        return view('supplies.create', compact('horses', 'products', 'stores'));
    }

    public function store(Request $request){

        DB::beginTransaction();
    
        try {
            $validated = $request->validate([
    
                'store_id' => 'required|exists:stores,id',
                'horse_id' => 'required|exists:horses,id',
                'supply_date' => 'required|date',
                'limit_date' => 'nullable|date',
                'notes' => 'nullable|string',
    
                'am_supplies' => 'sometimes|array',
                'am_supplies.*.product_id' => 'required_with:am_supplies|exists:products,id',
                'am_supplies.*.quantity' => 'required_with:am_supplies|numeric|min:0.01',
                
                'pm_supplies' => 'sometimes|array',
                'pm_supplies.*.product_id' => 'required_with:pm_supplies|exists:products,id',
                'pm_supplies.*.quantity' => 'required_with:pm_supplies|numeric|min:0.01',
                
            ]);
    
            // ✅ ACTUALIZAR: el valor del campo is_supplied == 1 (SUministrando)
            $horse = Horse::findOrFail($validated['horse_id']);
            $horse->update([
                'is_supplied' => true
            ]);
    
            // Crear el suministro principal
            $supply = Supply::create([
                'horse_id' => $validated['horse_id'],
                'store_id' => $validated['store_id'],
                'supply_number' => Supply::generateSupplyNumber(),
                'assigned_by' => Auth::id(),
                'supply_date' => $validated['supply_date'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'ASSIGNED'
            ]);
    
            // Procesar suministros AM - YA VIENEN COMO ARRAY, NO JSON
            if ($request->has('am_supplies') && is_array($request->am_supplies)) {
                foreach ($request->am_supplies as $supplyData) {
                    SupplyDetail::create([
                        'supply_id' => $supply->id,
                        'unit_name' => $supplyData['unit_name'],
                        'product_id' => $supplyData['product_id'],
                        'quantity' => $supplyData['quantity'],
                        'limit_date' => $supplyData['limit_date'] ?? null,
                        'shift' => 'AM'
                    ]);
                }
            }
    
            // Procesar suministros PM - YA VIENEN COMO ARRAY, NO JSON
            if ($request->has('pm_supplies') && is_array($request->pm_supplies)) {
                foreach ($request->pm_supplies as $supplyData) {
                    SupplyDetail::create([
                        'supply_id' => $supply->id,
                        'unit_name' => $supplyData['unit_name'],
                        'product_id' => $supplyData['product_id'],
                        'quantity' => $supplyData['quantity'],
                        'limit_date' => $supplyData['limit_date'] ?? null,
                        'shift' => 'PM'
                    ]);
                }
            }
    
            DB::commit();
    
            return redirect()->route('supplies.index')
                ->with('success', 'Suministros asignados correctamente. Número: ' . $supply->supply_number);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al asignar suministros: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function editData($id){
        try {
            $supply = Supply::with(['supplyDetails.product'])->findOrFail($id);
            
            $horses = Horse::where('status', 1)->get(['id', 'name']);
            $stores = Store::where('is_active', true)->get(['id', 'name']);
            $products = Product::with('consumptionUnit')->where('status', 1)->get();

            return response()->json([
                'success' => true,
                'supply' => [
                    'id' => $supply->id,
                    'supply_number' => $supply->supply_number,
                    'horse_id' => $supply->horse_id,
                    'store_id' => $supply->store_id,
                    'supply_date' => $supply->supply_date->format('Y-m-d'),
                    'limit_date' => $supply->limit_date ? $supply->limit_date->format('Y-m-d') : null,
                    'notes' => $supply->notes,
                    'supply_details' => $supply->supplyDetails->map(function($detail) {
                        return [
                            'id' => $detail->id,
                            'product_id' => $detail->product_id,
                            'product' => [
                                'name' => $detail->product->name
                            ],
                            'quantity' => $detail->quantity,
                            'unit_name' => $detail->unit_name,
                            'limit_date' => $detail->limit_date,
                            'shift' => $detail->shift
                        ];
                    })
                ],
                'horses' => $horses,
                'stores' => $stores,
                'products' => $products->map(function($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'unit' => $product->consumptionUnit->name ?? 'N/A'
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error cargando datos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id){
        DB::beginTransaction();

        try {
            // ✅ VALIDACIÓN COMPLETA para AJAX
            $validated = $request->validate([
                'horse_id' => 'required|exists:horses,id',
                'store_id' => 'required|exists:stores,id',
                'supply_date' => 'required|date',
                'limit_date' => 'nullable|date|after_or_equal:supply_date',
                'notes' => 'nullable|string',
                'am_supplies' => 'sometimes|array',
                'am_supplies.*.product_id' => 'required_with:am_supplies|exists:products,id',
                'am_supplies.*.quantity' => 'required_with:am_supplies|numeric|min:0.01',
                'pm_supplies' => 'sometimes|array',
                'pm_supplies.*.product_id' => 'required_with:pm_supplies|exists:products,id',
                'pm_supplies.*.quantity' => 'required_with:pm_supplies|numeric|min:0.01',
            ]);

            $supply = Supply::findOrFail($id);

            // Si el suministro ya fue ejecutado, no permitir edición
            if ($supply->status === 'EXECUTED') {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede editar un suministro ya ejecutado'
                ], 422);
            }

            // ✅ ACTUALIZAR DATOS BÁSICOS
            $supply->update([
                'horse_id' => $validated['horse_id'],
                'store_id' => $validated['store_id'],
                'supply_date' => $validated['supply_date'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // ✅ ELIMINAR DETALLES EXISTENTES Y CREAR NUEVOS
            $supply->supplyDetails()->delete();

            // ✅ PROCESAR SUMINISTROS AM
            if ($request->has('am_supplies') && is_array($request->am_supplies)) {
                foreach ($request->am_supplies as $supplyData) {
                    SupplyDetail::create([
                        'supply_id' => $supply->id,
                        'product_id' => $supplyData['product_id'],
                        'quantity' => $supplyData['quantity'],
                        'unit_name' => $supplyData['unit_name'],
                        'limit_date' => $supplyData['limit_date'] ?? $validated['limit_date'] ?? null,
                        'shift' => 'AM'
                    ]);
                }
            }

            // ✅ PROCESAR SUMINISTROS PM
            if ($request->has('pm_supplies') && is_array($request->pm_supplies)) {
                foreach ($request->pm_supplies as $supplyData) {
                    SupplyDetail::create([
                        'supply_id' => $supply->id,
                        'product_id' => $supplyData['product_id'],
                        'quantity' => $supplyData['quantity'],
                        'unit_name' => $supplyData['unit_name'],
                        'limit_date' => $supplyData['limit_date'] ?? $validated['limit_date'] ?? null,
                        'shift' => 'PM'
                    ]);
                }
            }

            DB::commit();

            // ✅ RETORNAR JSON PARA AJAX
            return response()->json([
                'success' => true,
                'message' => 'Suministro actualizado correctamente',
                'supply_number' => $supply->supply_number
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // ✅ RETORNAR ERROR EN JSON
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el suministro: ' . $e->getMessage()
            ], 500);
        }
    }


    public function destroy($id){

        DB::beginTransaction();

        try {
            $supply = Supply::with('supplyDetails')->findOrFail($id);

            // No permitir eliminar suministros ejecutados
            if ($supply->status === 'EXECUTED') {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar un suministro ya ejecutado'
                ], 422);
            }

            // ✅ ACTUALIZAR: Restablecer el campo is_supplied del caballo a false ==0
            $horse = Horse::find($supply->horse_id);
            if ($horse) {
                $horse->update([
                    'is_supplied' => false
                ]);
            }

            // Eliminar detalles primero
            $supply->supplyDetails()->delete();
            $supply->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Suministro eliminado correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el suministro: ' . $e->getMessage()
            ], 500);
        }
    }


    /* Ejecuta el suministro restando la cantidad asiganda en el campo
    consumtion_quantity de la tabla stocks */ 
    public function executeSupply($supplyId){

        DB::beginTransaction();

        try {
            $supply = Supply::with(['supplyDetails.product', 'store'])->findOrFail($supplyId);

            // Verificar stock para todos los productos
            foreach ($supply->supplyDetails as $detail) {
                $stock = Stock::where('product_id', $detail->product_id)
                            ->where('store_id', $supply->store_id)
                            ->first();
                
                if (!$stock || $stock->consumption_quantity < $detail->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stock insuficiente para el producto: ' . $detail->product->name
                    ], 422);
                }
            }

            // Actualizar stock y marcar como ejecutado
            foreach ($supply->supplyDetails as $detail) {
                $stock = Stock::where('product_id', $detail->product_id)
                            ->where('store_id', $supply->store_id)
                            ->first();
                
                $stock->consumption_quantity -= $detail->quantity;
                $stock->save();
            }

            // Marcar suministro como ejecutado
            $supply->update([
                'status' => 'EXECUTED',
                'executed_by' => Auth::id(),
                'executed_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Suministro ejecutado correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al ejecutar suministro: ' . $e->getMessage()
            ], 500);
        }
    }

    public function searchProducts(Request $request){

        $search = $request->get('search');

        $products = Product::with(['consumptionUnit', 'stocks'])
            ->where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('codebar', 'LIKE', "%{$search}%");
            })
            ->where('status', 1)
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    public function resetDailySupplies(){

        $activeHorses = Horse::where('status', 1)->pluck('id');

        // Resetear suministros de caballos activos
        Supply::whereHas('supplyDetails', function($query) use ($activeHorses) {
            $query->whereIn('horse_id', $activeHorses);
        })
        ->where('supply_date', today())
        ->where('status', 'EXECUTED')
        ->update([
            'status' => 'ASSIGNED',
            'executed_by' => null,
            'executed_at' => null,
        ]);

        // Eliminar detalles expirados
        SupplyDetail::where('limit_date', '<', now())->delete();

        Log::info('Suministros reseteados y detalles expirados eliminados para el día: ' . today()->format('Y-m-d'));
    }

   
    /**
     *  RESET MANUAL: Resetear supplies ejecutados desde el botón
     */
    public function resetSupplies(Request $request){
        
        DB::beginTransaction();
        
        try {
            Log::info('🎯 INICIANDO RESET MANUAL DESDE BOTÓN - ' . now()->toDateTimeString());
            
            $activeHorses = Horse::where('status', 1)->pluck('id');
            
            Log::info("🐎 Caballos activos encontrados: " . $activeHorses->count());

            // 1. RESETEO DE SUPPLIES (días anteriores)
            $suppliesUpdated = Supply::whereIn('horse_id', $activeHorses)
                ->where('supply_date', '<', now()->toDateString())
                ->where('status', 'EXECUTED')
                ->update([
                    'status' => 'ASSIGNED',
                    'executed_by' => null,
                    'executed_at' => null,
                ]);

            Log::info("📦 Supplies reseteados: {$suppliesUpdated}");

            // 2. RESETEO DE SUPPLY_DETAILS (misma fecha que supplies)
            $detailsReset = SupplyDetail::whereHas('supply', function($query) use ($activeHorses) {
                $query->whereIn('horse_id', $activeHorses)
                      ->where('supply_date', '<', now()->toDateString());
            })
            ->where('is_executed', true)
            ->update([
                'is_executed' => false,
                'executed_by' => null,
                'executed_at' => null,
            ]);

            Log::info("📋 Supply details reseteados: {$detailsReset}");

            // 3. ELIMINACIÓN DE DETALLES EXPIRADOS
            $detailsDeleted = SupplyDetail::whereNotNull('limit_date')
                ->where('limit_date', '<', now())
                ->delete();

            Log::info("🚮 Detalles expirados eliminados: {$detailsDeleted}");

            DB::commit();

            // 🔥 RESPUESTA DE ÉXITO CON DATOS PARA SWEETALERT2
            return response()->json([
                'success' => true,
                'message' => 'El proceso de reset se completó exitosamente',
                'supplies_updated' => $suppliesUpdated,
                'details_reset' => $detailsReset,
                'details_deleted' => $detailsDeleted,
                'timestamp' => now()->format('d/m/Y H:i:s')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('❌ ERROR EN RESET MANUAL: ' . $e->getMessage());
            Log::error('📍 Archivo: ' . $e->getFile() . ' - Línea: ' . $e->getLine());

            // 🔥 RESPUESTA DE ERROR
            return response()->json([
                'success' => false,
                'message' => 'Error al resetear los supplies: ' . $e->getMessage()
            ], 500);
        }
    }


    public function getSuppliesByDate(Request $request){

        $supplies = Supply::with(['store', 'assignedBy', 'executedBy', 'supplyDetails.horse', 'supplyDetails.product'])
            ->where('supply_date', $request->date)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $supplies
        ]);
    }


    public function show($id){

        $supply = Supply::with([
            'store', 
            'assignedBy', 
            'executedBy', 
            'supplyDetails.horse', 
            'supplyDetails.product.consumptionUnit'
        ])->findOrFail($id);

        return view('supplies.show', compact('supply'));
    }

   
    // Método para visualizar los detalles del suministro en modal detalles
    public function supplyDetails($id){

        try {
            $supply = Supply::with([
                'store',
                'horse',
                'assignedBy',
                'executedBy',
                'supplyDetails.product.consumptionUnit'
            ])->findOrFail($id);

            $html = view('supplies.partials.supply-details', compact('supply'))->render();

            return response()->json([
                'success' => true,
                'supply' => $supply,
                'html' => $html
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error cargando los detalles del suministro: ' . $e->getMessage()
            ], 500);
        }
    }


    public function details(Supply $supply){

        try {
            // Cargar todas las relaciones necesarias
            $supply->load([
                'store', 
                'assignedBy', 
                'executedBy', 
                'horse',
                'supplyDetails.product.consumptionUnit'
            ]);
            
            // LOG para verificar qué vista se está cargando
            Log::info('Cargando vista para supply: ' . $supply->id);
            Log::info('Vista path: horsecard.partials.supply-details');
            
            // Verificar si la vista existe
            if (!view()->exists('horsecard.partials.supply-details')) {
                Log::error('La vista horsecard.partials.supply-details NO existe');
                return response()->json([
                    'success' => false,
                    'message' => 'Vista no encontrada'
                ], 404);
            }
            
            $html = view('horsecard.partials.supply-details', compact('supply'))->render();
            
            Log::info('Vista cargada exitosamente');
            
            return response()->json([
                'success' => true,
                'supply' => $supply,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            Log::error('Error en details method: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los detalles: ' . $e->getMessage()
            ], 500);
        }
    }

    

    // Metodo para Ejecutar Suministro user manager = EXCECUTED
    public function executeShift(Request $request, Supply $supply){
        try {
            $shift = $request->input('shift');
            
            // Validar turno
            if (!in_array($shift, ['AM', 'PM'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Turno no válido'
                ], 400);
            }
            
            // Actualizar los detalles del suministro para el turno específico
            $updated = $supply->supplyDetails()
                ->where('shift', $shift)
                ->update([
                    'is_executed' => true,
                    'executed_at' => now(),
                    'executed_by' => auth()->id() // O el ID del usuario que ejecuta
                ]);
                
            if ($updated > 0) {
                // Verificar si todos los turnos están ejecutados para marcar el supply completo
                $totalDetails = $supply->supplyDetails()->count();
                $executedDetails = $supply->supplyDetails()->where('is_executed', true)->count();
                
                if ($totalDetails === $executedDetails) {
                    $supply->update([
                        'is_executed' => true,
                        'executed_at' => now()
                    ]);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Turno marcado como ejecutado correctamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron productos para este turno'
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Error ejecutando turno: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al ejecutar el turno: ' . $e->getMessage()
            ], 500);
        }
    }

    
}