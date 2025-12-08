<?php

namespace App\Http\Controllers;

use App\Models\Horse;
use App\Models\Stable;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HorseController extends Controller
{
    
    public function index(){

       /*  $horses = Horse::with(['stable', 'students'])->latest()->get(); */
       $horses = Horse::with(['user', 'stable'])->latest()->get();
        return view('horses.index', compact('horses'));
    }

    
    public function create(){

        $users = User::all();
        $stables = Stable::all();
        $students = Student::all();
        return view('horses.create', compact('users', 'stables', 'students'));
    }

    
    public function store(Request $request){

        $validated = $request->validate([

            'user_id' => 'required|exists:users,id',
            'stable_id' => 'required|exists:stables,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'breed' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'sex' => 'required|in:male,female',
            'height' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'patology' => 'nullable|string',
            'vaccination' => 'nullable|date',
            'last_exam' => 'nullable|date',
            'observation' => 'nullable|string',
            'condition' => 'required|in:FINE,SICK,INJURED','HORSESHOE','MOUNT','DOCTOR',
           
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('horses', 'public');
        }

        $horse = Horse::create($validated);


        return redirect()->route('horses.index')->with('success', 'Registr exitosamente');
    }

    
    public function edit(Horse $horse){

        $users = User::all();
        $stables = Stable::all();
        return view('horses.edit', compact('horse', 'users', 'stables'));
    }

   
    public function update(Request $request, Horse $horse){

        $validated = $request->validate([

            
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'breed' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'sex' => 'required|in:male,female',
            'height' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'pathology' => 'nullable|string',
            'vaccination' => 'nullable|date',
            'last_exam' => 'nullable|date',
            'observation' => 'nullable|string',
            'condition' => 'required|in:FINE,SICK,INJURED','HORSESHOE','MOUNT','DOCTOR',
            'stable_id' => 'required|exists:stables,id',
            'user_id' => 'required|exists:users,id',
           
        ]);

        if ($request->hasFile('photo')) {
            // Eliminar foto anterior si existe
            if ($horse->photo) {
                Storage::disk('public')->delete($horse->photo);
            }
            $validated['photo'] = $request->file('photo')->store('horses', 'public');
        }

        $horse->update($validated);


        return redirect()->route('horses.index')->with('success', 'Caballo actualizado exitosamente');
    }

    
    public function destroy(Horse $horse){
        
        try {
            // Eliminar foto si existe
            if ($horse->photo) {
                Storage::disk('public')->delete($horse->photo);
            }

            // Eliminar relaciones many-to-many si existen
            if (method_exists($horse, 'students')) {
                $horse->students()->detach();
            }

            // Eliminar el caballo
            $horse->delete();

            // Respuesta para AJAX
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Caballo eliminado exitosamente'
                ]);
            }

            // Redirección normal para peticiones no AJAX
            return redirect()->route('horses.index');

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el caballo: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error al eliminar el caballo: ' . $e->getMessage());
        }
    }
}