<?php

namespace App\Http\Controllers;

use App\Models\HorseMovement;
use App\Models\Horse;
use App\Models\Stable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HorseMovementController extends Controller
{
    /**
     * Display a listing of horse movements with filters
     */
    public function index(Request $request){
        try {
            $query = HorseMovement::with(['horse', 'fromStable', 'toStable', 'movedBy']);

            // Aplicar filtros
            if ($request->has('horse_id') && $request->horse_id) {
                $query->where('horse_id', $request->horse_id);
            }

            if ($request->has('from_stable_id') && $request->from_stable_id) {
                $query->where('from_stable_id', $request->from_stable_id);
            }

            if ($request->has('to_stable_id') && $request->to_stable_id) {
                $query->where('to_stable_id', $request->to_stable_id);
            }

            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $movements = $query->orderBy('created_at', 'desc')->paginate(20);
            
            // Obtener datos para filtros
            $horses = Horse::all();
            $stables = Stable::all();

            // Renderizar vista HTML
            return view('horse-movements.index', compact('movements', 'horses', 'stables'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al cargar los movimientos: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created horse movement
     */
    public function store(Request $request){

        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'horse_id' => 'required|exists:horses,id',
                'to_stable_id' => 'required|exists:stables,id',
                'reason' => 'required|in:ROTATION,TRAINING,DOCTOR,COMPETENCE,RETUR,CLEANING,OTHER',
                'notes' => 'nullable|string|max:500'
            ]);

            $horse = Horse::findOrFail($validated['horse_id']);
            $toStable = Stable::findOrFail($validated['to_stable_id']);

            // Verificar que el establo destino tenga capacidad
            if ($toStable->type === 'STABLE' && $toStable->horses()->count() >= $toStable->capacity) {
                return response()->json([
                    'success' => false,
                    'message' => 'The Destiny has no available capacity'
                ], 422);
            }

            // Crear el registro de movimiento
            $movement = HorseMovement::create([

                'horse_id' => $horse->id,
                'from_stable_id' => $horse->stable_id,
                'to_stable_id' => $validated['to_stable_id'],
                'reason' => $validated['reason'],
                'notes' => $validated['notes'] ?? null,
                'moved_by' => Auth::id()
            ]);

            // Actualizar la ubicación actual del caballo
            $horse->stable_id = $validated['to_stable_id'];
            $horse->save();

            DB::commit();

            // Cargar relaciones para la respuesta
            $movement->load(['horse', 'fromStable', 'toStable', 'movedBy']);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Movement registered successfully',
                    'data' => $movement
                ], 201);
            }

            return redirect()->route('horse-movements')->with('success', 'Movement registered successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al registrar el movimiento: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error al registrar el movimiento: ' . $e->getMessage());
        }
    }

    /**
     * Display movements for a specific horse
     */
    public function showHorseMovements($horseId, Request $request)
    {
        try {
            $horse = Horse::findOrFail($horseId);

            $movements = HorseMovement::with(['fromStable', 'toStable', 'movedBy'])
                ->where('horse_id', $horseId)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'horse' => $horse,
                    'movements' => $movements
                ]);
            }

            return view('horse-movements.history', compact('horse', 'movements'));

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cargar el historial: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error al cargar el historial: ' . $e->getMessage());
        }
    }

    /**
     * Get movement statistics
     */
    public function statistics(Request $request)
    {
        try {
            $dateFrom = $request->date_from ?? now()->subMonth();
            $dateTo = $request->date_to ?? now();

            $stats = HorseMovement::select(
                DB::raw('COUNT(*) as total_movements'),
                DB::raw('COUNT(DISTINCT horse_id) as unique_horses'),
                DB::raw('reason, COUNT(reason) as reason_count')
            )
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('reason')
            ->get();

            $dailyMovements = HorseMovement::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as movements_count')
            )
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'period' => ['from' => $dateFrom, 'to' => $dateTo],
                    'statistics' => $stats,
                    'daily_trend' => $dailyMovements
                ]);
            }

            return view('horse-movements.statistics', compact('stats', 'dailyMovements', 'dateFrom', 'dateTo'));

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al generar estadísticas: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error al generar estadísticas: ' . $e->getMessage());
        }
    }
}