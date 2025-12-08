<?php

namespace App\Http\Controllers;

use App\Models\Horse;
use App\Models\Supply;
use App\Models\Store;
use App\Models\Stock;
use App\Models\SupplyDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HorseCardController extends Controller
{
    /**
     * Método principal - Lista de horseCards paginadas
     */
    public function index(Request $request){
        $query = Supply::with(['store', 'assignedBy', 'executedBy', 'horse']);

        $supplies = $query->orderBy('supply_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $stores = Store::where('is_active', true)->get();
        $horses = Horse::where('status', 1)->get(); 
        
        return view('horsecard.index', compact('supplies', 'stores', 'horses'));
    }

    // ==========================================================================
    // MÉTODOS PARA MODAL DE DETALLES
    // ==========================================================================

    /**
     * Ejecutar turno de suministro - CON DESCUENTO DE INVENTARIO
     */
    public function executeShift(Request $request, Supply $supply){
        
        DB::beginTransaction();
        
        try {
            $shift = $request->input('shift');
            $userId = auth()->id();
            $now = now();
            
            Log::info("🎯 Ejecutando turno {$shift} para supply ID: {$supply->id} por usuario: {$userId}");

            // Validar turno
            if (!in_array($shift, ['AM', 'PM'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Turno no válido'
                ], 400);
            }

            // Verificar si el turno ya está ejecutado
            $isShiftExecuted = $supply->supplyDetails()
                ->where('shift', $shift)
                ->where('is_executed', true)
                ->exists();

            if ($isShiftExecuted) {
                return response()->json([
                    'success' => false,
                    'message' => "El turno {$shift} ya ha sido ejecutado"
                ]);
            }

            // Obtener los detalles del turno a ejecutar
            $shiftDetails = $supply->supplyDetails()
                ->where('shift', $shift)
                ->where('is_executed', false)
                ->get();

            if ($shiftDetails->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron productos para ejecutar en este turno'
                ]);
            }

            // 🔥 IMPLEMENTACIÓN DEL DESCUENTO DE INVENTARIO
            $this->processInventoryDeduction($shiftDetails, $supply->store_id);

            // Ejecutar el turno específico
            $updated = $supply->supplyDetails()
                ->where('shift', $shift)
                ->update([
                    'is_executed' => true,
                    'executed_at' => $now,
                    'executed_by' => $userId
                ]);

            if ($updated > 0) {
                Log::info("✅ Turno {$shift} ejecutado. Detalles actualizados: {$updated}");

                // Verificar si todos los turnos están ejecutados
                $allShiftsExecuted = $this->checkAllShiftsExecuted($supply);

                if ($allShiftsExecuted) {
                    // Marcar el supply completo como ejecutado
                    $supply->update([
                        'status' => 'EXECUTED',
                        'executed_at' => $now,
                        'executed_by' => $userId
                    ]);
                    Log::info("🎉 Supply COMPLETAMENTE ejecutado ID: {$supply->id}");
                }

                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => "✅ Turno {$shift} ejecutado correctamente",
                    'supply_status' => $allShiftsExecuted ? 'EXECUTED' : 'ASSIGNED',
                    'redirect_url' => route('horsecard.index')
                ]);

            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudieron actualizar los detalles del turno'
                ]);
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error ejecutando turno: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al ejecutar el turno: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🔥 NUEVO MÉTODO: Procesar descuento de inventario
     */
    private function processInventoryDeduction($shiftDetails, $storeId){

        foreach ($shiftDetails as $detail) {
            // Buscar el stock del producto en el almacén específico
            $stock = Stock::where('product_id', $detail->product_id)
                        ->where('store_id', $storeId)
                        ->first();

            if (!$stock) {
                Log::warning("⚠️ No se encontró stock para producto ID: {$detail->product_id} en almacén ID: {$storeId}");
                throw new \Exception("Stock insuficiente o no encontrado para el producto: " . ($detail->product->name ?? 'N/A'));
            }

            // Verificar stock suficiente
            if ($stock->consumption_quantity < $detail->quantity) {
                Log::error("❌ Stock insuficiente. Producto: {$detail->product_id}, Stock actual: {$stock->consumption_quantity}, Requerido: {$detail->quantity}");
                throw new \Exception("Stock insuficiente para el producto: " . ($detail->product->name ?? 'N/A'));
            }

            // Descontar del consumo_quantity
            $stock->consumption_quantity -= $detail->quantity;
            $stock->save();

            Log::info("📦 Stock actualizado - Producto: {$detail->product_id}, Cantidad descontada: {$detail->quantity}, Stock restante: {$stock->consumption_quantity}");
        }
    }

    /**
     * Verificar si todos los turnos están ejecutados
     */
    private function checkAllShiftsExecuted(Supply $supply): bool{

        $totalDetails = $supply->supplyDetails()->count();
        $executedDetails = $supply->supplyDetails()->where('is_executed', true)->count();
        
        return $totalDetails > 0 && $totalDetails === $executedDetails;
    }

    /**
     * Método supplyDetails muestra detalles del suministro AM-PM 
     * Modal de Ejecucion de Suministro User Manager
     */
    public function supplyDetails($id){
        try {
            Log::info("📦 Cargando detalles del suministro ID: {$id}");
            
            // Cargar suministro con relaciones
            $supply = Supply::with([
                'store',
                'horse',
                'assignedBy',
                'executedBy',
                'supplyDetails.product.consumptionUnit',
                'supplyDetails.executedBy'
            ])->findOrFail($id);

            Log::info("📋 Estado del supply: {$supply->status}");

            // Preparar variables para la vista
            $amProducts = $supply->supplyDetails->where('shift', 'AM');
            $pmProducts = $supply->supplyDetails->where('shift', 'PM');
            
            $amExecuted = $amProducts->where('is_executed', true)->count() > 0;
            $pmExecuted = $pmProducts->where('is_executed', true)->count() > 0;

            $amExecutionInfo = $amProducts->where('is_executed', true)->first();
            $pmExecutionInfo = $pmProducts->where('is_executed', true)->first();

            Log::info("📊 Estadísticas - AM Ejecutado: " . ($amExecuted ? 'Sí' : 'No') . 
                     ", PM Ejecutado: " . ($pmExecuted ? 'Sí' : 'No') . 
                     ", Supply Status: {$supply->status}");

            // Renderizar vista
            $html = view('horsecard.partials.supply-details', 
                compact('supply', 'amProducts', 'pmProducts', 'amExecuted', 'pmExecuted', 'amExecutionInfo', 'pmExecutionInfo')
            )->render();

            Log::info("✅ Vista renderizada exitosamente para supply ID: {$id}");

            return response()->json([
                'success' => true,
                'supply' => $supply,
                'html' => $html
            ]);
            
        } catch (\Exception $e) {
            Log::error("❌ Error en supplyDetails: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error cargando los detalles del suministro: ' . $e->getMessage()
            ], 500);
        }
    }
}