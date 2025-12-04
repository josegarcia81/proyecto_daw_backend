<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Proyecto DAW Backend API",
 *     version="1.0.0",
 *     description="Documentación de la API REST del Proyecto DAW",
 *     @OA\Contact(
 *         email="soporte@proyectodaw.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Servidor de desarrollo local"
 * )
 * 
 * @OA\Server(
 *     url="https://proyecto-daw-backend.onrender.com/api",
 *     description="Servidor de producción"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Autenticación mediante token Laravel Sanctum"
 * )
 */
abstract class Controller
{
    //
}
