<?php

namespace App\Http\Controllers;

use App\Models\SupplyDetail;
use App\Models\Supply;
use App\Models\Horse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SupplyDetailController extends Controller
{
    /**
     * Mostrar formulario para crear nuevo detalle
     */
    public function create($supplyId)
    {
        $supply = Supply::findOrFail($supplyId);
        $horses = Horse::where('status', 1)->get();
        $products = Product::with('consumptionUnit')->where('status', 1)->get();
        
        return view('supply-details.create', compact('supply', 'horses', 'products'));
    }

    /**
     * Almacenar nuevo detalle de suministro
     */
    public function store(Request $request, $supplyId)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'horse_id' => 'required|exists:horses,id',
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|numeric|min:0.01',
                'shift' => 'required|in:AM,PM',
                'limit_date' => 'required|date'
            ]);

            $supply = Supply::findOrFail($supplyId);

            // Verificar que el suministro no esté ejecutado
            if ($supply->status === 'EXECUTED') {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pueden agregar detalles a un suministro ya ejecutado'
                ], 422);
            }

            $supplyDetail = SupplyDetail::create([
                'supply_id' => $supply->id,
                'horse_id' => $validated['horse_id'],
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'shift' => $validated['shift'],
                'limit_date' => $validated['limit_date']
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Detalle de suministro agregado correctamente',
                'data' => $supplyDetail->load(['horse', 'product.consumptionUnit'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar detalle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($supplyId, $detailId)
    {
        $supplyDetail = SupplyDetail::with(['supply', 'horse', 'product'])->findOrFail($detailId);
        $horses = Horse::where('status', 1)->get();
        $products = Product::with('consumptionUnit')->where('status', 1)->get();
        
        return view('supply-details.edit', compact('supplyDetail', 'horses', 'products'));
    }

    /**
     * Actualizar detalle de suministro
     */
    public function update(Request $request, $supplyId, $detailId)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'horse_id' => 'required|exists:horses,id',
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|numeric|min:0.01',
                'shift' => 'required|in:AM,PM',
                'limit_date' => 'required|date'
            ]);

            $supplyDetail = SupplyDetail::with('supply')->findOrFail($detailId);

            // Verificar que el suministro no esté ejecutado
            if ($supplyDetail->supply->status === 'EXECUTED') {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pueden editar detalles de un suministro ya ejecutado'
                ], 422);
            }

            $supplyDetail->update([
                'horse_id' => $validated['horse_id'],
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'shift' => $validated['shift'],
                'limit_date' => $validated['limit_date']
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Detalle de suministro actualizado correctamente',
                'data' => $supplyDetail->load(['horse', 'product.consumptionUnit'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar detalle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar detalle de suministro
     */
    public function destroy($supplyId, $detailId)
    {
        DB::beginTransaction();

        try {
            $supplyDetail = SupplyDetail::with('supply')->findOrFail($detailId);

            // Verificar que el suministro no esté ejecutado
            if ($supplyDetail->supply->status === 'EXECUTED') {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pueden eliminar detalles de un suministro ya ejecutado'
                ], 422);
            }

            $supplyDetail->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Detalle de suministro eliminado correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar detalle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener detalles por suministro (API)
     */
    public function getBySupply($supplyId)
    {
        $supplyDetails = SupplyDetail::with(['horse', 'product.consumptionUnit'])
            ->where('supply_id', $supplyId)
            ->orderBy('shift')
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $supplyDetails
        ]);
    }

    /**
     * Obtener detalles por caballo y fecha (API)
     */
    public function getByHorseAndDate(Request $request, $horseId)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'shift' => 'nullable|in:AM,PM'
        ]);

        $query = SupplyDetail::with(['supply', 'product.consumptionUnit'])
            ->where('horse_id', $horseId)
            ->where('limit_date', '>=', $validated['date']);

        if ($request->has('shift')) {
            $query->where('shift', $validated['shift']);
        }

        $supplyDetails = $query->orderBy('shift')
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $supplyDetails
        ]);
    }
}