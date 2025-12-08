<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Registro de usuario
     */
    public function register(Request $request){

        $validator = Validator::make($request->all(), [

            'rol_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([

            'rol_id'=> $request->rol_id,
            'name' => $request->name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Login de usuario
     */
    public function login(Request $request){

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credenciales inválidas'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo crear el token'], 500);
        }

        return response()->json([
            'token' => $token,
            'user' => Auth::user(),
        ]);
    }

    /**
     * Logout off
     */
    public function logout(){

        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Sesión cerrada exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo cerrar la sesión'], 500);
        }
    }

    /**
     * Obtener datos de usuario autenticado
     */
    public function me(){

        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }
    }

    /**
     * Refrescar token
     */
    public function refresh(){

        try {
            $newToken = JWTAuth::parseToken()->refresh();
            return response()->json(['token' => $newToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo refrescar el token'], 401);
        }
    }
}
