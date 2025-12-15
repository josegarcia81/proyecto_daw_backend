<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Provincia;
use App\Models\Poblacion;
use App\Models\Categoria;
use App\Models\Rol;
use App\Models\Servicio;
use App\Models\Transaccion;
use App\Models\Valoracion;
use App\Models\Mensaje;

class CommonController extends Controller
{


    /**
     * @OA\Get(
     *     path="/getProvincias",
     *     summary="Obtener todas las provincias",
     *     tags={"Common"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Provincias obtenidas correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="time", type="string", format="date-time"),
     *             @OA\Property(property="message", type="string", example="Provincias obtenidas correctamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nombre", type="string", example="Madrid")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Ocurrió un error al obtener las provincias")
     *         )
     *     )
     * )
     */
    public function getProvincias()
    {
        try {
            $provincias = Provincia::all();
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'time' => now()->toIso8601String(),
                'message' => 'Provincias obtenidas correctamente',
                'data' => $provincias
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'time' => now()->toIso8601String(),
                'message' => 'Ocurrió un error al obtener las provincias',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/getPoblaciones",
     *     summary="Obtener todas las poblaciones",
     *     tags={"Common"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="provincia_id",
     *         in="query",
     *         description="ID de la provincia para filtrar poblaciones",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Poblaciones obtenidas correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="time", type="string", format="date-time"),
     *             @OA\Property(property="message", type="string", example="Poblaciones obtenidas correctamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nombre", type="string", example="Alcalá de Henares"),
     *                     @OA\Property(property="provincia_id", type="integer", example=1)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Ocurrió un error al obtener las poblaciones")
     *         )
     *     )
     * )
     */
    public function getPoblaciones(Request $request)
    {
        try {
            // Verificar si se solicita filtrar por provincia_id
            if ($request->has('provincia_id')) {
                // Obtener poblaciones de una provincia específica, incluyendo datos de la provincia
                $poblaciones = Poblacion::with('provincia')
                    ->where('provincia_id', $request->provincia_id)
                    ->get();
            } else {
                // Obtener todas las poblaciones, incluyendo datos de la provincia
                $poblaciones = Poblacion::with('provincia')->get();
            }

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'time' => now()->toIso8601String(),
                'message' => 'Poblaciones obtenidas correctamente',
                'data' => $poblaciones
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'time' => now()->toIso8601String(),
                'message' => 'Ocurrió un error al obtener las poblaciones',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    // @OA\  // Comentado para que no aparezca en la documentacion publica / Esto va delante del @Get
    /**
     * @OA\Get(
     *     path="/getTables",
     *     summary="Obtener todas las tablas de la base de datos",
     *     tags={"Common"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Tablas obtenidas correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="time", type="string", format="date-time"),
     *             @OA\Property(property="message", type="string", example="Tablas obtenidas correctamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(type="string", example="usuarios")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Ocurrió un error al obtener las tablas")
     *         )
     *     )
     * )
     */
    public function getAllTables()
    {
        try {
            $tables = \DB::select('SHOW TABLES');
            $database = env('DB_DATABASE');
            $tableNames = array_map(function ($table) use ($database) {
                return $table->{"Tables_in_" . $database};
            }, $tables);

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'time' => now()->toIso8601String(),
                'message' => 'Tablas obtenidas correctamente',
                'data' => $tableNames
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'time' => now()->toIso8601String(),
                'message' => 'Ocurrió un error al obtener las tablas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/getCategorias",
     *     summary="Obtener todas las categorías",
     *     tags={"Common"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Categorías obtenidas correctamente"),
     *     @OA\Response(response=500, description="Error del servidor")
     * )
     */
    public function getCategorias()
    {
        try {
            $categorias = Categoria::all();
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'time' => now()->toIso8601String(),
                'message' => 'Categorías obtenidas correctamente',
                'data' => $categorias
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'time' => now()->toIso8601String(),
                'message' => 'Ocurrió un error al obtener las categorías',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/getRoles",
     *     summary="Obtener todos los roles",
     *     tags={"Common"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Roles obtenidos correctamente"),
     *     @OA\Response(response=500, description="Error del servidor")
     * )
     */
    public function getRoles()
    {
        try {
            $roles = Rol::all();
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'time' => now()->toIso8601String(),
                'message' => 'Roles obtenidos correctamente',
                'data' => $roles
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'time' => now()->toIso8601String(),
                'message' => 'Ocurrió un error al obtener los roles',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
