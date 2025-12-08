<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use App\Models\Stable;
use App\Models\Horse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StableController extends Controller
{
    
    public function index(){

        $conditions = [

            'use'    => 'Ocupado',
            'mant'   => 'Mantenimiento',
            'clear'  => 'Limpio',
            'repair' => 'Reparación',
        ];

       $sectors = Sector::all();

        $stables = Stable::with('sector')->latest()->get();
        return view('stables.index', compact('stables', 'conditions', 'sectors'));
        
    }

    
    // StableController.php

    public function create(){

        $conditions = [

            'use'    => 'Ocupado',
            'mant'   => 'Mantenimiento',
            'clear'  => 'Limpio',
            'repair' => 'Reparación',
        ];

        return view('stables.index', compact('conditions'));
    }

    
    public function store(Request $request){

        $stable = Stable::create($request->only( 'name', 'sector_id', 'capacity', 'type','location', 'condition'));

        return redirect()->route('stables.index')->with([

            'success' => 'Ubicacion creada correctamente.',
            'category_id' => $stable->id
        ]);
    }

    
    public function show(string $id){


       
    }

    
    public function edit(string $id){

        $conditions = [
            'use'    => 'Occupying',
            'mant'   => 'Maintenance',
            'clear'  => 'Clean',
            'repair' => 'Repair',
        ];
    
        $types = [
            'STABLE' => 'STABLE',
            'PADDOCK' => 'PADDOCK',
            'SAND' => 'SAND',
        ];
    
        // ✅ Obtener sectores desde la base de datos
        $sectors = Sector::where('status', true)
            ->pluck('name', 'id')
            ->toArray();
        
        $stable = Stable::findOrFail($id);
        
        return response()->json([
            
            'id' => $stable->id,
            'name' => $stable->name,
            'sector_id' => $stable->sector_id,
            'type' => $stable->type,
            'condition' => $stable->condition,
            'status' => $stable->status,
            'sectors' => $sectors,        // ✅ Sectores desde BD
            'types' => $types,            
            'conditions' => $conditions   
        ]);
    }
    
    public function update(Request $request, string $id){

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sector' => 'required|exists:sectors,id',     
            'type' => 'required|in:STABLE,PADDOCK,SAND',  
            'condition' => 'required|in:use,mant,clear,repair',
            'status' => 'required|boolean'
        ]);
    
        $stable = Stable::findOrFail($id);
    
        $stable->update([
            'name' => $validated['name'],
            'sector_id' => $validated['sector'],  
            'type' => $validated['type'],         
            'condition' => $validated['condition'],
            'status' => $validated['status']
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Establo actualizado correctamente'
        ]);
    }

    
    public function destroy(string $id){

        $stable = Stable::findOrFail($id);

        // Eliminar el Establo
        $stable->delete();
    
        return response()->json(['message' => 'Establo eliminado correctamente'], 200);
        
    }




    //View Horses to Sector
    public function horsesBySector($sectorId){
        try {
            $sector = Sector::with(['stables' => function($query) {
                $query->where('status', true);
            }])->findOrFail($sectorId);

            $horses = Horse::with(['currentStable', 'specialConditions' => function($query) {
                $query->where('status', 'ACTIVE');
            }])
            ->whereHas('currentStable', function($query) use ($sectorId) {
                $query->where('sector_id', $sectorId)->where('status', true);
            })
            ->where('status', true)
            ->get();

            return view('sectors.horses', compact('sector', 'horses'));
            
        } catch (\Exception $e) {
            return redirect()->route('sectors.index')
                ->with('error', 'Sector no encontrado');
        }
    }

    /**
     * Get horses by sector for DataTables
     */
    /* public function getHorsesBySectorData(Request $request, $sectorId){

        $horses = Horse::with([
                'currentStable', 
                'currentStable.sector',
                'specialConditions' => function($query) {
                    $query->where('status', 'ACTIVE')->latest();
                },
                'specialConditions.assignedTo'
            ])
            ->whereHas('currentStable', function($query) use ($sectorId) {
                $query->where('sector_id', $sectorId)->where('status', true);
            })
            ->where('status', true)
            ->select('horses.*');

        return DataTables::of($horses)
            ->addColumn('current_condition', function($horse) {
                if ($horse->specialConditions->count() > 0) {
                    $condition = $horse->specialConditions->first();
                    return $condition->title . ' (' . $condition->type . ')';
                }
                return 'LIBRE';
            })
            ->addColumn('medical_history', function($horse) {
                return $horse->medicalConditions->count() . ' registros médicos';
            })
            // ... otras columnas ...
            ->make(true);
    } */

    /**
     * Get horse details for modal
     */
    public function getHorseDetails($horseId){

        $horse = Horse::with([
            'currentStable.sector', 
            'specialConditions' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'specialConditions.assignedTo',
            'movements' => function($query) {
                $query->orderBy('created_at', 'desc')->take(5);
            },
            'movements.fromStable',
            'movements.toStable',
            'movements.movedBy'
        ])->findOrFail($horseId);

        return response()->json([
            'success' => true,
            'horse' => $horse
        ]);
    }

    
}
