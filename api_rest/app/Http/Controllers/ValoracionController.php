<?php

namespace App\Http\Controllers;

use App\Models\Valoracion;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Exception;

class ValoracionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/valoraciones",
     *     summary="Obtener todas las valoraciones",
     *     tags={"Valoraciones"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de valoraciones obtenida correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="time", type="string", format="date-time"),
     *             @OA\Property(property="message", type="string", example="Todas las valoraciones obtenidas correctamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(type="object")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Error del servidor")
     * )
     */
    public function getAllValoraciones(){
        try{
            // Obtener todas las valoraciones con sus relaciones
            $valoraciones = Valoracion::with(['transaccion', 'valorador', 'valorado'])->get();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                "time" => now()->toIso8601String(),
                "message" => "Todas las valoraciones obtenidas correctamente",
                'data' => $valoraciones
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'time' => now()->toIso8601String(),
                'message' => 'Ocurrió un error con la base de datos',
                'error' => $e->getMessage()
            ], 500);   
        }
    }

    /**
     * @OA\Get(
     *     path="/valoraciones/{usuario_id}",
     *     summary="Obtener valoraciones recibidas por un usuario",
     *     tags={"Valoraciones"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="usuario_id",
     *         in="path",
     *         description="ID del usuario valorado",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Valoraciones del usuario obtenidas correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="time", type="string", format="date-time"),
     *             @OA\Property(property="message", type="string", example="Valoraciones del usuario obtenidas correctamente"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="code", type="integer", example=406),
     *             @OA\Property(property="message", type="string", example="Usuario no encontrado")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Error del servidor")
     * )
     */
    public function getValoraciones($usuario_id){
        try{
            // Verificar que el usuario existe
            $usuario = User::find($usuario_id);
            
            if (!$usuario) {
                return response()->json([
                    "status" => "error",
                    "code" => 406,
                    "time" => now()->toIso8601String(),
                    "message" => "Usuario no encontrado",
                    "data" => null
                ], 404);
            }
            
            // Obtener valoraciones donde el usuario es el valorado
            $valoraciones = Valoracion::with(['transaccion', 'valorador', 'valorado'])
                ->where('valorado_id', $usuario_id)
                ->get();
            
            return response()->json([
                "status" => "success",
                "code" => 200,
                "time" => now()->toIso8601String(),
                "message" => "Valoraciones del usuario obtenidas correctamente",
                "data" => $valoraciones
            ], 200);
        }catch(Exception $e){
            return response()->json([
                "status" => "error",
                "code" => 500,
                "time" => now()->toIso8601String(),
                "message" => "Ocurrió un error con la base de datos",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/valoracion",
     *     summary="Crear una nueva valoración",
     *     tags={"Valoraciones"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"transaccion_id","valorador_id","valorado_id","puntuacion"},
     *             @OA\Property(property="transaccion_id", type="integer", example=1),
     *             @OA\Property(property="valorador_id", type="integer", example=1),
     *             @OA\Property(property="valorado_id", type="integer", example=2),
     *             @OA\Property(property="puntuacion", type="integer", example=5, description="Puntuación del 1 al 5"),
     *             @OA\Property(property="comentario", type="string", example="Excelente servicio, muy recomendado", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Valoración creada correctamente"),
     *     @OA\Response(response=422, description="Error de validación"),
     *     @OA\Response(response=500, description="Error del servidor")
     * )
     */
    public function createValoracion(Request $request)
    {
        try {
            $validated = $request->validate([
                'transaccion_id' => 'required|integer|exists:transacciones,id',
                'valorador_id' => 'required|integer|exists:usuarios,id',
                'valorado_id' => 'required|integer|exists:usuarios,id',
                'puntuacion' => 'required|integer|min:1|max:5',
                'comentario' => 'nullable|string',
            ]);

            $valoracion = Valoracion::create([
                'transaccion_id' => $validated['transaccion_id'],
                'valorador_id' => $validated['valorador_id'],
                'valorado_id' => $validated['valorado_id'],
                'puntuacion' => $validated['puntuacion'],
                'comentario' => $validated['comentario'] ?? null,
            ]);

            // Cargar relaciones
            $valoracion->load(['transaccion', 'valorador', 'valorado']);

            return response()->json([
                'status' => 'success',
                'code' => 201,
                'time' => now()->toIso8601String(),
                'message' => 'Valoración creada correctamente',
                'data' => $valoracion
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 422,
                'time' => now()->toIso8601String(),
                'message' => 'Error de validación',
                'error' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'time' => now()->toIso8601String(),
                'message' => 'Ocurrió un error con la base de datos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/valoracion/{valoracion}",
     *     summary="Actualizar una valoración existente",
     *     tags={"Valoraciones"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="valoracion",
     *         in="path",
     *         description="ID de la valoración",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="transaccion_id", type="integer", example=1),
     *             @OA\Property(property="valorador_id", type="integer", example=1),
     *             @OA\Property(property="valorado_id", type="integer", example=2),
     *             @OA\Property(property="puntuacion", type="integer", example=4),
     *             @OA\Property(property="comentario", type="string", example="Buen servicio")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Valoración actualizada correctamente"),
     *     @OA\Response(response=404, description="Valoración no encontrada"),
     *     @OA\Response(response=422, description="Error de validación"),
     *     @OA\Response(response=500, description="Error del servidor")
     * )
     */
    public function updateValoracion(Request $request, Valoracion $valoracion)
    {
        try {
            $validated = $request->validate([
                'transaccion_id' => 'sometimes|integer|exists:transacciones,id',
                'valorador_id' => 'sometimes|integer|exists:usuarios,id',
                'valorado_id' => 'sometimes|integer|exists:usuarios,id',
                'puntuacion' => 'sometimes|integer|min:1|max:5',
                'comentario' => 'nullable|string',
            ]);

            $valoracion->update($validated);

            // Recargar relaciones
            $valoracion->load(['transaccion', 'valorador', 'valorado']);

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'time' => now()->toIso8601String(),
                'message' => 'Valoración actualizada correctamente',
                'data' => $valoracion
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 422,
                'time' => now()->toIso8601String(),
                'message' => 'Error de validación',
                'error' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'time' => now()->toIso8601String(),
                'message' => 'Ocurrió un error con la base de datos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/valoracion/{valoracion}",
     *     summary="Eliminar una valoración",
     *     tags={"Valoraciones"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="valoracion",
     *         in="path",
     *         description="ID de la valoración",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Valoración eliminada correctamente"),
     *     @OA\Response(response=404, description="Valoración no encontrada"),
     *     @OA\Response(response=500, description="Error del servidor")
     * )
     */
    public function deleteValoracion(Valoracion $valoracion){
        try {
            $valoracion->delete();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'time' => now()->toIso8601String(),
                'message' => 'Valoración eliminada correctamente',
                'data' => null
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'time' => now()->toIso8601String(),
                'message' => 'Ocurrió un error al eliminar la valoración',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
