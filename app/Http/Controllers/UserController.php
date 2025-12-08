<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

/* Librerias PhpMailer */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class UserController extends Controller{

    public function index(){

      $users = User::with('role')->get();
      $roles = Role::all();
      return view('users.index', compact('users', 'roles'));

    }

    public function create(){

        $roles = Role::all();
        return view('users.create', compact('roles'));
    }


    public function store(Request $request){

        /*  dd($request->toArray()); */

        $request->validate([

            'rol_id' => 'nullable',
            'name' => 'required',
            'last_name' => 'nullable',
            'phone' => 'nullable',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        $photoPath = null;

        if ($request->hasFile('profile_photo_path')){
            $photoPath = $request->file('profile_photo_path')->store('users','public');

        }

         $user = User::create([

            'rol_id' => $request->rol_id,
            'name' => $request->name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'pass_basic' => $request->password, // sin encriptar para el correo
            'profile_photo_path' => $photoPath,

         ]);

          return redirect()->route('users')->with('success', 'User created successfully');
    }


    public function edit($id){

        $user = User::findOrFail($id);
        $roles = Role::all();

        return response()->json([
            'user' => $user,
            'roles' => $roles,
        ]);

    }

    public function update(Request $request, $id){

            $user = User::findOrFail($id);

            $request->validate([

                'rol_id' => 'required',
                'name' => 'required',
                'last_name' => 'required',
                'phone' => 'required',
                'email' => 'required',
            ]);

            // Preparar los datos comunes
            $data = [
                'rol_id' => $request->rol_id,
                'name' => $request->name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'email' => $request->email,
            ];

            // Verificar si se proporcionó una nueva contraseña
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
                $data['pass_basic'] = $request->password;

                // Cargar relación para verificar el nombre del rol
                $user->load('role');

                if ($user->role && $user->role->name === 'staff') {

                    $this->sendEmail($user);
                }
            }

            // Actualizar el usuario
            $user->update($data);

            return redirect()->route('users')->with('success', 'User updated successfully');
    }



    public function destroy($id){

        $user = User::findOrFail($id);

        // Eliminar el usuario
        $user->delete();
    
        return response()->json(['message' => 'Usuario eliminado correctamente'], 200);

    }

    // Método para enviar el correo de confirmación de la reserva
    public function sendEmail($user){

        $user = User::find($user->id);


        $mail = new PHPMailer(true);

        /* Validamos con un try catch */
        try{
            $mail->isSMTP();
            $mail->Host = 'smtp.hostinger.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'eventos@maydev.tech';
            $mail->Password = '@dmiN1321**';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('eventos@maydev.tech','Usuario Actualizado');
            $mail->addAddress($user->email);

            $mail->CharSet = 'UTF-8';

            $mail->Subject = 'Usuario activado exitosamente...!'; /* Asunto */

            $html = View::make('emails.update',[ /* Parametros a enviar a la vista emails.reserva */

                'userName' => $user->name .' '. $user->last_name,
                'password' => $user->pass_basic,
                'userEmail' => $user->email,
                'url'=> 'https://events.maydev.tech/login'
                
               

            ])->render();

            $mail->isHTML(true); /* Indicamos que acepte html */
            $mail->Body = $html; /* Indicamos que el cuerpo del correo es el html generado en la vista emails.reserva */

            $mail->send(); /* Enviamos el correo */

            return back()->with('success', 'Correo enviado correctamente.');

        } catch(Exception $e){
            Log ::error('Error al enviar el correo: '. $mail->ErrorInfo);
            return back()->with('error','Error al enviar el correo :' . $mail->ErrorInfo);
        }
    }


}

    

