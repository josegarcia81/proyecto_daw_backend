<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Proyecto DAW Backend API",
 *     version="1.0.0",
 *     description="API REST del Proyecto DAW. Esta API gestiona la lógica de negocio para la plataforma de intercambio de tiempo.
 *     
 *     Módulos incluidos:
 *     - **Autenticación**: Registro, login, logout y gestión de perfil.
 *     - **Usuarios**: Gestión completa de usuarios (CRUD).
 *     - **Servicios**: Gestión de ofertas y demandas de servicios.
 *     - **Transacciones**: Control de intercambios de tiempo entre usuarios.
 *     - **Valoraciones**: Sistema de reputación y feedback.
 *     - **Mensajes**: Sistema de mensajería interna.
 *     - **Common**: Recursos compartidos (provincias, poblaciones, etc.).",
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
 *     description="Autenticación mediante token Laravel Sanctum. Ingrese el token directamente."
 * )
 */
abstract class Controller
{
    //
}
