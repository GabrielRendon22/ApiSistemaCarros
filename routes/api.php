<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\categoriaController;
use App\Http\Controllers\ContrasenaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ReservacionController;
use App\Http\Controllers\SuscripcionController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', function () {
    return view('welcome');
});

// Rutas para CategoriaController
Route::get('categorias', [categoriaController::class, 'index']);
Route::post('categorias', [categoriaController::class, 'store']);
Route::get('categorias/{id}', [categoriaController::class, 'show']);
Route::put('categorias/{id}', [CategoriaController::class, 'update']);
Route::delete('categorias/{id}', [CategoriaController::class, 'destroy']);

// Rutas para ContrasenaController
Route::get('contrasenas', [ContrasenaController::class, 'index']);
Route::post('contrasenas', [ContrasenaController::class, 'store']);
Route::get('contrasenas/{id}', [ContrasenaController::class, 'show']);
Route::put('contrasenas/{id}', [ContrasenaController::class, 'update']);
Route::delete('contrasenas/{id}', [ContrasenaController::class, 'destroy']);

// Rutas para UsuarioController
Route::get('usuarios', [UsuarioController::class, 'index']);
Route::post('usuarios', [UsuarioController::class, 'store']);
Route::get('usuarios/{id}', [UsuarioController::class, 'show']);
Route::put('usuarios/{id}', [UsuarioController::class, 'update']);
Route::delete('usuarios/{id}', [UsuarioController::class, 'destroy']);
// Rutas para EstadoController
Route::get('estados', [EstadoController::class, 'index']);
Route::post('estados', [EstadoController::class, 'store']);
Route::get('estados/{id}', [EstadoController::class, 'show']);
Route::put('estados/{id}', [EstadoController::class, 'update']);
Route::delete('estados/{id}', [EstadoController::class, 'destroy']);

// Rutas para RolController
Route::get('rols', [RolController::class, 'index']);
Route::post('rols', [RolController::class, 'store']);
Route::get('rols/{id}', [RolController::class, 'show']);
Route::put('rols/{id}', [RolController::class, 'update']);
Route::delete('rols/{id}', [RolController::class, 'destroy']);

// Rutas para PlanController
Route::get('planes', [PlanController::class, 'index']);
Route::post('planes', [PlanController::class, 'store']);
Route::get('planes/{id}', [PlanController::class, 'show']);
Route::put('planes/{id}', [PlanController::class, 'update']);
Route::delete('planes/{id}', [PlanController::class, 'destroy']);

// Rutas para PagoController
Route::get('pagos', [PagoController::class, 'index']);
Route::post('pagos', [PagoController::class, 'store']);
Route::get('pagos/{id}', [PagoController::class, 'show']);
Route::put('pagos/{id}', [PagoController::class, 'update']);
Route::delete('pagos/{id}', [PagoController::class, 'destroy']);
Route::get('pagos/cliente/{id_usuario}', [PagoController::class, 'pagosPorCliente']);

// Rutas para ReservacionController
Route::get('reservaciones', [ReservacionController::class, 'index']);
Route::post('reservaciones', [ReservacionController::class, 'store']);
Route::get('reservaciones/{id}', [ReservacionController::class, 'show']);
Route::put('reservaciones/{id}', [ReservacionController::class, 'update']);
Route::delete('reservaciones/{id}', [ReservacionController::class, 'destroy']);
Route::get('reservaciones/cliente/{id_usuario}', [ReservacionController::class, 'reservacionesPorCliente']);

// Rutas para SuscripcionController
Route::get('suscripciones', [SuscripcionController::class, 'index']);
Route::post('suscripciones', [SuscripcionController::class, 'store']);
Route::get('suscripciones/{id}', [SuscripcionController::class, 'show']);
Route::put('suscripciones/{id}', [SuscripcionController::class, 'update']);
Route::delete('suscripciones/{id}', [SuscripcionController::class, 'destroy']);
Route::get('suscripciones/activa/{id_usuario}', [SuscripcionController::class, 'suscripcionActivaPorCliente']);

// Rutas para VehiculoController
Route::get('vehiculos', [VehiculoController::class, 'index']);
Route::post('vehiculos', [VehiculoController::class, 'store']);
Route::get('vehiculos/{id}', [VehiculoController::class, 'show']);
Route::put('vehiculos/{id}', [VehiculoController::class, 'update']);
Route::delete('vehiculos/{id}', [VehiculoController::class, 'destroy']);
Route::get('vehiculos/sin-reserva', [VehiculoController::class, 'vehiculosSinReserva']);
Route::get('vehiculos/con-reserva', [VehiculoController::class, 'vehiculosConReserva']);
Route::get('vehiculos/categoria/{id_categoria}', [VehiculoController::class, 'vehiculosPorCategoria']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
