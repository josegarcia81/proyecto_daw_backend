<?php

namespace App\Http\Controllers;

use App\Models\Transaccion;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Exception;

class TransaccionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/transacciones",
     *     summary="Obtener todas las transacciones",
     *     tags={"Transacciones"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de transacciones obtenida correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="time", type="string", format="date-time"),
     *             @OA\Property(property="message", type="string", example="Todas las transacciones obtenidas correctamente"),
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
    public function getAllTransacciones(){
        try{
            // Obtener todas las transacciones con sus relaciones
            $transacciones = Transaccion::with(['servicio', 'usuarioSolicitante', 'usuarioOfertante'])->get();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                "time" => now()->toIso8601String(),
                "message" => "Todas las transacciones obtenidas correctamente",
                'data' => $transacciones
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
     *     path="/transacciones/{usuario_id}",
     *     summary="Obtener transacciones de un usuario (como solicitante u ofertante)",
     *     tags={"Transacciones"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="usuario_id",
     *         in="path",
     *         description="ID del usuario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transacciones del usuario obtenidas correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="time", type="string", format="date-time"),
     *             @OA\Property(property="message", type="string", example="Transacciones del usuario obtenidas correctamente"),
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
    public function getTransacciones($usuario_id){
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
            
            // Obtener transacciones donde el usuario es solicitante u ofertante
            $transacciones = Transaccion::with(['servicio', 'usuarioSolicitante', 'usuarioOfertante'])
                ->where('usuario_solicitante_id', $usuario_id)
                ->orWhere('usuario_ofertante_id', $usuario_id)
                ->get();
            
            return response()->json([
                "status" => "success",
                "code" => 200,
                "time" => now()->toIso8601String(),
                "message" => "Transacciones del usuario obtenidas correctamente",
                "data" => $transacciones
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
     *     path="/transaccion",
     *     summary="Crear una nueva transacción",
     *     tags={"Transacciones"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"servicio_id","usuario_solicitante_id","usuario_ofertante_id","horas"},
     *             @OA\Property(property="servicio_id", type="integer", example=1),
     *             @OA\Property(property="usuario_solicitante_id", type="integer", example=1),
     *             @OA\Property(property="usuario_ofertante_id", type="integer", example=2),
     *             @OA\Property(property="horas", type="integer", example=2),
     *             @OA\Property(property="estado", type="string", example="pendiente", description="pendiente, confirmado, cancelado"),
     *             @OA\Property(property="fecha_confirmacion", type="string", format="date-time", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Transacción creada correctamente"),
     *     @OA\Response(response=422, description="Error de validación"),
     *     @OA\Response(response=500, description="Error del servidor")
     * )
     */
    public function createTransaccion(Request $request)
    {
        try {
            $validated = $request->validate([
                'servicio_id' => 'required|integer|exists:servicios,id',
                'usuario_solicitante_id' => 'required|integer|exists:usuarios,id',
                'usuario_ofertante_id' => 'required|integer|exists:usuarios,id',
                'horas' => 'required|integer|min:1',
                'estado' => 'nullable|string|in:pendiente,confirmado,cancelado',
                'fecha_confirmacion' => 'nullable|date',
            ]);

            $transaccion = Transaccion::create([
                'servicio_id' => $validated['servicio_id'],
                'usuario_solicitante_id' => $validated['usuario_solicitante_id'],
                'usuario_ofertante_id' => $validated['usuario_ofertante_id'],
                'horas' => $validated['horas'],
                'estado' => $validated['estado'] ?? 'pendiente',
                'fecha_confirmacion' => $validated['fecha_confirmacion'] ?? null,
            ]);

            // Cargar relaciones
            $transaccion->load(['servicio', 'usuarioSolicitante', 'usuarioOfertante']);

            return response()->json([
                'status' => 'success',
                'code' => 201,
                'time' => now()->toIso8601String(),
                'message' => 'Transacción creada correctamente',
                'data' => $transaccion
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
     *     path="/transaccion/{transaccion}",
     *     summary="Actualizar una transacción existente",
     *     tags={"Transacciones"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="transaccion",
     *         in="path",
     *         description="ID de la transacción",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="servicio_id", type="integer", example=1),
     *             @OA\Property(property="usuario_solicitante_id", type="integer", example=1),
     *             @OA\Property(property="usuario_ofertante_id", type="integer", example=2),
     *             @OA\Property(property="horas", type="integer", example=3),
     *             @OA\Property(property="estado", type="string", example="confirmado"),
     *             @OA\Property(property="fecha_confirmacion", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Transacción actualizada correctamente"),
     *     @OA\Response(response=404, description="Transacción no encontrada"),
     *     @OA\Response(response=422, description="Error de validación"),
     *     @OA\Response(response=500, description="Error del servidor")
     * )
     */
    public function updateTransaccion(Request $request, Transaccion $transaccion)
    {
        try {
            $validated = $request->validate([
                'servicio_id' => 'sometimes|integer|exists:servicios,id',
                'usuario_solicitante_id' => 'sometimes|integer|exists:usuarios,id',
                'usuario_ofertante_id' => 'sometimes|integer|exists:usuarios,id',
                'horas' => 'sometimes|integer|min:1',
                'estado' => 'sometimes|string|in:pendiente,confirmado,cancelado',
                'fecha_confirmacion' => 'nullable|date',
            ]);

            $transaccion->update($validated);

            // Recargar relaciones
            $transaccion->load(['servicio', 'usuarioSolicitante', 'usuarioOfertante']);

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'time' => now()->toIso8601String(),
                'message' => 'Transacción actualizada correctamente',
                'data' => $transaccion
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
     *     path="/transaccion/{transaccion}",
     *     summary="Eliminar una transacción",
     *     tags={"Transacciones"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="transaccion",
     *         in="path",
     *         description="ID de la transacción",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Transacción eliminada correctamente"),
     *     @OA\Response(response=404, description="Transacción no encontrada"),
     *     @OA\Response(response=500, description="Error del servidor")
     * )
     */
    public function deleteTransaccion(Transaccion $transaccion){
        try {
            $transaccion->delete();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'time' => now()->toIso8601String(),
                'message' => 'Transacción eliminada correctamente',
                'data' => null
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'time' => now()->toIso8601String(),
                'message' => 'Ocurrió un error al eliminar la transacción',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
