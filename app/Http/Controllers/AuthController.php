<?php

namespace App\Http\Controllers;

Use App\Models\Usuario;
Use App\Models\Contrasena;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * Registro de usuario
     */
    public function register(Request $request)
    {
        // Validar los datos de entrada, incluyendo DUI y teléfono
        $request->validate([
            'nombres' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'password' => 'required|string|min:6',
            'dui' => 'required|string|size:9|unique:usuarios,dui',  // Validación para DUI
            'telefono' => 'required|string|unique:usuarios,telefono', // Validación para teléfono
            'id_rol' => 'required|exists:rols,id_rol',
        ]);

        // Crear el usuario en la tabla usuarios
        $user = Usuario::create([
            'nombres' => $request->nombres,
            'email' => $request->email,
            'dui' => $request->dui,  // Guardar el DUI
            'telefono' => $request->telefono,  // Guardar el teléfono
            'id_rol' => $request->id_rol,
        ]);
    
        // Crear la entrada de la contraseña en la tabla contrasenas
        $contrasena = new Contrasena([
            'id_usuario' => $user->id_usuario,
            'contrasena' => bcrypt($request->password),  // Cifrar la contraseña
        ]);

        // Guardar la contraseña
        $contrasena->save();
    
        // Crear el token JWT para el usuario
        $token = JWTAuth::fromUser($user);
    
        // Retornar el token en la respuesta
        return response()->json(['token' => $token], 201);
    }

    /**
     * Login de usuario
     */
    public function login(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
    
        // Buscar al usuario en la tabla usuarios
        $credentials = $request->only('email', 'password');
        $user = Usuario::where('email', $credentials['email'])->first();
    
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 401);
        }
    
        // Obtener la contraseña del usuario desde la tabla contrasenas
        $contrasena = Contrasena::where('id_usuario', $user->id_usuario)->first();
    
        if (!$contrasena) {
            return response()->json(['error' => 'Contraseña no encontrada en la base de datos'], 401);
        }
    
        // Verificar si la contraseña es válida
        if (!\Hash::check($credentials['password'], $contrasena->contrasena)) {
            return response()->json(['error' => 'Contraseña incorrecta'], 401);
        }
    
        // Si todo está bien, generar el token
        try {
            // Usamos JWTAuth::fromUser() para obtener el token correctamente
            $token = JWTAuth::fromUser($user);
            return response()->json(compact('token'));
        } catch (JWTException $e) {
            return response()->json(['error' => 'Error al generar el token'], 500);
        }
    }
}
