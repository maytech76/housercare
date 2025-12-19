<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GuestController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\File;


Route::prefix('auth')->group(function () {
    
    Route::post('/register', [AuthController::class, 'register']); /* http://events.test/api/auth/register */
    Route::post('/login', [AuthController::class, 'login']); /* http://events.test/api/auth/login */
    
    Route::middleware('jwt.auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });
});



/* Visualizar publicamente las Productos */
Route::get('/storage/guest_photos/{filename}', function ($filename) {
    $path = storage_path('app/public/guest_photos/' . $filename);
    
    if (!File ::exists($path)) {
        abort(404);
    }
    
    return response()->file($path);
})->name('storage.guest_photos');


Route::get('guests', [GuestController::class, 'index']);


// routes/api.php
Route::get('/events/{eventId}/guests', [GuestController::class, 'getGuestsByEvent']);
                                                                 

// Obtener todos los invitados de un evento
Route::get('/guests/{event_id}', [GuestController::class, 'getGuestsByEvent']);

Route::delete('/guest/{guest_id}', [GuestController::class, 'destroy']);


//Captura datos del invitado y Solicita una actualizacion al registro pasando a staus checkin
Route::post('/guests/by-code/{code_in}/checkin', [GuestController::class, 'processCheckInByCode']);

//Captura datos del invitado y Solicita una actualizacion al registro pasando a staus checkOuts 
Route::post('/guests/by-code/{code_in}/checkout', [GuestController::class, 'processCheckOutByCode']);

// Obtener detalles de un invitado por su código QR
Route::get('/guest/{code_in}', [GuestController::class, 'getGuestByCode']);

//
Route::get('/guests/find-by-code/{code_in}', [GuestController::class, 'findGuestByCode']);



