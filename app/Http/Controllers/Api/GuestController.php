<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guest;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;



class GuestController extends Controller
{

    /* Listar Categorias */
    public function index(){

        // Obtener solo los eventos con status = activo
        $events = Event::where('status', 'activo')->get();

        return response()->json($events);
    }

    // visualizar listado de guest x event
    public function getGuestsByEvent(Request $request){
        
        // Validar si se requiere un event_id específico
        $eventId = $request->query('event_id');

        $query = Event::with(['guests' => function($query) {
            $query->select('id', 'event_id', 'photo', 'name', 'email', 'is_attended', 'code_in', 'qr_code_path')
                ->orderBy('created_at', 'desc');
        }]);

        // Filtrar por evento si se especifica
        if ($eventId) {
            $query->where('id', $eventId);
        }

        // Paginar resultados (10 por página)
        $events = $query->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $events
        ]);
    }
    
        // app/Http/Controllers/GuestController.php
    public function getGuestByCode($code_in){

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");

            try {
                $guest = Guest::where('code_in', $code_in)->first();

                if (!$guest) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invitado no encontrado.'
                    ], 404);
                }

                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'id' => $guest->id,
                        'name' => $guest->name,
                        'email' => $guest->email,
                        'event_id' => $guest->event_id,
                        'is_attended' => $guest->is_attended,
                        'status' => $guest->status,
                        'qr_code_url' => $guest->qr_code_path ? asset("storage/$guest->qr_code_path") : null,
                        'photo_url' => $guest->photo ? asset("storage/$guest->photo") : null
                    ]
                ]);

            } catch (\Exception $e) {
                // Log del error para debugging
                Log ::error("Error en getGuestByCode: " . $e->getMessage());
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error interno del servidor.'
                ], 500);
            }
    }

    /**
     * Busca un invitado por su código QR (code_in) y verifica su estado de asistencia.
     * @route GET /api/guests/find-by-code/{code_in}
     */
    public function findGuestByCode($code_in){
        
        try {
            // Busca el invitado y filtra por is_attended = 1
            $guest = Guest::where('code_in', $code_in)
                        ->where('is_attended', 1)
                        ->first();

            if (!$guest) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invitado no encontrado o no ha confirmado asistencia.',
                    'is_valid' => false
                ], 404);
            }

            // Datos clave para el check-in
            return response()->json([
                'status' => 'success',
                'is_valid' => true,
                'guest' => [
                    'id' => $guest->id,
                    'full_name' => $guest->name . ' ' . $guest->last_name,
                    'event_id' => $guest->event_id,
                    'photo_url' => $guest->photo ? asset("storage/$guest->photo") : null,
                    'checked_in_at' => $guest->updated_at->format('d/m/Y H:i'),
                    'qr_code_url' => asset("storage/$guest->qr_code_path")
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Error en findGuestByCode: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error interno al validar el código.'
            ], 500);
        }
    }

    //Verifica si el status no es  checkin, actualizando el status a checkin
    public function processCheckInByCode($code_in){

        try {
            $guest = Guest::where('code_in', $code_in)->firstOrFail();
        
            if ($guest->status === 'checkin') {
                return response()->json([
                    'success' => false,
                    'message' => 'El invitado ya tenía check-in registrado',
                    'checkin_time' => now('H:i:s')
                ], 200);
            }
        
            $checkinTime = now()->subHours(4);
        

            
            $guest->update([

                'status' => 'checkin',
                'checkin_time' => $checkinTime,
                'is_attended' => true,
                
                
            ]);
        
            // Recargar el modelo para obtener los cambios
            $guest->refresh();
        
            return response()->json([
                'success' => true,
                'guest' => $guest->only('id', 'name', 'last_name', 'event_id', 'checkin_time'),
                'checkin_time' => now(),
                'message' => 'Check-in registrado correctamente'
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("Código de invitado no encontrado: {$code_in}");
            return response()->json([
                'success' => false,
                'message' => 'Código de invitado no encontrado'
            ], 404);
            
        } catch (\Exception $e) {
            Log::error("Error en check-in: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el check-in'
            ], 500);
        }
    }

    

    //Se ejecuta el Checkout del invitado
    public function processCheckOutByCode($code_in){
        

        $guest = Guest::where('code_in', $code_in)->firstOrFail();

        $checkoutTime = now()->subHours(4);


        if ($guest->status === 'checkIn') {
            return response()->json([

                $guest->update(['status' => 'CheckOut', 'checkout_time' => $checkoutTime ]),
                
               
            ], 200);
        }

       
    
        return response()->json([
           
                'success' => true,
                'message' => 'Gracias por su Visita',
                'guest' => $guest->only('id', 'name', 'last_name', 'event_id', 'checkout_time'),
                'checkout_time' => now(),
                'message' => 'Check-out registrado correctamente'
            
        ]);



    }



          
}




