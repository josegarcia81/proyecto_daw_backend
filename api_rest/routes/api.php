<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\TransaccionController;
use App\Http\Controllers\ValoracionController;
use App\Http\Controllers\MensajeController;

/**
 * API Routes
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 * 
 */

///////////////////////////////////////////////////////////////////////////
// Rutas públicas de autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas que requieren autenticación
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rutas de usuario que ahora podrían requerir autenticación
    Route::get('/users', [UserController::class, 'getAllUsers']);
    Route::get('/users/{id}', [UserController::class, 'getUser']);
    Route::post('/users', [UserController::class, 'createUser']);
    Route::put('/users/{user}', [UserController::class, 'updateUser']);
    Route::delete('/users/{user}', [UserController::class, 'deleteUser']);

    // Ruta para obtener el usuario esta autenticado / tiene la sesion vigente
    // Se envia su token nada mas para verificar, devuelve el usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// Ruta para obtener la documentación de la API
Route::get('/api-docs', function () {
    return view('api-docs');
});

// Rutas sin autentificacion
Route::get('/users', [UserController::class, 'getAllUsers']);
// Ruta para obtener si el usuario esta autenticado / tiene la sesion vigente
// Se envia su token nada mas para verificar, devuelve el usuario autenticado
Route::get('/user', function (Request $request) {
    return $request->user();
});

////////////////////////////////////////////////////////////////////////
// Rutas a CommonController
// Ruta para obtener provincias
Route::get('getProvincias', [CommonController::class, 'getProvincias']);
// Ruta para obtener poblaciones
Route::get('getPoblaciones', [CommonController::class, 'getPoblaciones']);
// Ruta para obtener tablas
Route::get('getTables', [CommonController::class, 'getAllTables']);
// Ruta para obtener categorias
Route::get('getCategorias', [CommonController::class, 'getCategorias']);
// Ruta para obtener roles
Route::get('getRoles', [CommonController::class, 'getRoles']);

////////////////////////////////////////////////////////////////////////////
// Rutas CRUD de Servicios (sin autenticación)
Route::get('servicios', [ServicioController::class, 'getAllServicios']);
Route::get('servicios/{id}', [ServicioController::class, 'getServicios']);
Route::post('servicio', [ServicioController::class, 'createServicio']);
Route::put('servicio/{servicio}', [ServicioController::class, 'updateServicio']);
Route::delete('servicio/{servicio}', [ServicioController::class, 'deleteServicio']);

////////////////////////////////////////////////////////////////////////////
// Rutas CRUD de Transacciones (sin autenticación)
Route::get('transacciones', [TransaccionController::class, 'getAllTransacciones']);
Route::get('transacciones/{usuario_id}', [TransaccionController::class, 'getTransacciones']);
Route::post('transaccion', [TransaccionController::class, 'createTransaccion']);
Route::put('transaccion/{transaccion}', [TransaccionController::class, 'updateTransaccion']);
Route::delete('transaccion/{transaccion}', [TransaccionController::class, 'deleteTransaccion']);

////////////////////////////////////////////////////////////////////////////
// Rutas CRUD de Valoraciones (sin autenticación)
Route::get('valoraciones', [ValoracionController::class, 'getAllValoraciones']);
Route::get('valoraciones/{usuario_id}', [ValoracionController::class, 'getValoraciones']);
Route::post('valoracion', [ValoracionController::class, 'createValoracion']);
Route::put('valoracion/{valoracion}', [ValoracionController::class, 'updateValoracion']);
Route::delete('valoracion/{valoracion}', [ValoracionController::class, 'deleteValoracion']);

////////////////////////////////////////////////////////////////////////////
// Rutas CRUD de Mensajes (sin autenticación)
Route::get('mensajes', [MensajeController::class, 'getAllMensajes']);
Route::get('mensajes/{usuario_id}', [MensajeController::class, 'getMensajes']);
Route::post('mensaje', [MensajeController::class, 'createMensaje']);
Route::put('mensaje/{mensaje}', [MensajeController::class, 'updateMensaje']);
Route::delete('mensaje/{mensaje}', [MensajeController::class, 'deleteMensaje']);
