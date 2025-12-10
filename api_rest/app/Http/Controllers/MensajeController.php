<?php

namespace App\Http\Controllers;

use App\Models\Mensaje;
use App\Models\User;
use App\Models\Servicio;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class MensajeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/mensajes",
     *     summary="Obtener todos los mensajes",
     *     tags={"Mensajes"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de mensajes obtenida correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="time", type="string", format="date-time"),
     *             @OA\Property(property="message", type="string", example="Todos los mensajes obtenidos correctamente"),
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
    public function getAllMensajes(){
        try{
            // Obtener todos los mensajes con sus relaciones
            $mensajes = Mensaje::with(['emisor', 'receptor', 'servicio'])->get();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                "time" => now()->toIso8601String(),
                "message" => "Todos los mensajes obtenidos correctamente",
                'data' => $mensajes
            ], 200);
        }catch(\Exception $e){
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
     *     path="/mensajes/{usuario_id}",
     *     summary="Obtener mensajes de un usuario (como emisor o receptor)",
     *     tags={"Mensajes"},
     *     @OA\Parameter(
     *         name="usuario_id",
     *         in="path",
     *         description="ID del usuario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mensajes del usuario obtenidos correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="time", type="string", format="date-time"),
     *             @OA\Property(property="message", type="string", example="Mensajes del usuario obtenidos correctamente"),
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
    public function getMensajes($usuario_id){
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
            
            // Obtener mensajes donde el usuario es emisor o receptor
            $mensajes = Mensaje::with(['emisor', 'receptor', 'servicio'])
                ->where('emisor_id', $usuario_id)
                ->orWhere('receptor_id', $usuario_id)
                ->get();
            
            return response()->json([
                "status" => "success",
                "code" => 200,
                "time" => now()->toIso8601String(),
                "message" => "Mensajes del usuario obtenidos correctamente",
                "data" => $mensajes
            ], 200);
        }catch(\Exception $e){
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
     *     path="/mensaje",
     *     summary="Crear un nuevo mensaje",
     *     tags={"Mensajes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"emisor_id","receptor_id","servicio_id","mensaje"},
     *             @OA\Property(property="emisor_id", type="integer", example=1),
     *             @OA\Property(property="receptor_id", type="integer", example=2),
     *             @OA\Property(property="servicio_id", type="integer", example=1),
     *             @OA\Property(property="mensaje", type="string", example="Hola, estoy interesado en tu servicio"),
     *             @OA\Property(property="leido", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Mensaje creado correctamente"),
     *     @OA\Response(response=422, description="Error de validación"),
     *     @OA\Response(response=500, description="Error del servidor")
     * )
     */
    public function createMensaje(Request $request)
    {
        try {
            $validated = $request->validate([
                'emisor_id' => 'required|integer|exists:usuarios,id',
                'receptor_id' => 'required|integer|exists:usuarios,id',
                'servicio_id' => 'required|integer|exists:servicios,id',
                'mensaje' => 'required|string',
                'leido' => 'nullable|boolean',
            ]);

            $mensaje = Mensaje::create([
                'emisor_id' => $validated['emisor_id'],
                'receptor_id' => $validated['receptor_id'],
                'servicio_id' => $validated['servicio_id'],
                'mensaje' => $validated['mensaje'],
                'leido' => $validated['leido'] ?? false,
            ]);

            // Cargar relaciones
            $mensaje->load(['emisor', 'receptor', 'servicio']);

            return response()->json([
                'status' => 'success',
                'code' => 201,
                'time' => now()->toIso8601String(),
                'message' => 'Mensaje creado correctamente',
                'data' => $mensaje
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 422,
                'time' => now()->toIso8601String(),
                'message' => 'Error de validación',
                'error' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
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
     *     path="/mensaje/{mensaje}",
     *     summary="Actualizar un mensaje existente",
     *     tags={"Mensajes"},
     *     @OA\Parameter(
     *         name="mensaje",
     *         in="path",
     *         description="ID del mensaje",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="emisor_id", type="integer", example=1),
     *             @OA\Property(property="receptor_id", type="integer", example=2),
     *             @OA\Property(property="servicio_id", type="integer", example=1),
     *             @OA\Property(property="mensaje", type="string", example="Mensaje actualizado"),
     *             @OA\Property(property="leido", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Mensaje actualizado correctamente"),
     *     @OA\Response(response=404, description="Mensaje no encontrado"),
     *     @OA\Response(response=422, description="Error de validación"),
     *     @OA\Response(response=500, description="Error del servidor")
     * )
     */
    public function updateMensaje(Request $request, Mensaje $mensaje)
    {
        try {
            $validated = $request->validate([
                'emisor_id' => 'sometimes|integer|exists:usuarios,id',
                'receptor_id' => 'sometimes|integer|exists:usuarios,id',
                'servicio_id' => 'sometimes|integer|exists:servicios,id',
                'mensaje' => 'sometimes|string',
                'leido' => 'sometimes|boolean',
            ]);

            $mensaje->update($validated);

            // Recargar relaciones
            $mensaje->load(['emisor', 'receptor', 'servicio']);

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'time' => now()->toIso8601String(),
                'message' => 'Mensaje actualizado correctamente',
                'data' => $mensaje
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 422,
                'time' => now()->toIso8601String(),
                'message' => 'Error de validación',
                'error' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
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
     *     path="/mensaje/{mensaje}",
     *     summary="Eliminar un mensaje",
     *     tags={"Mensajes"},
     *     @OA\Parameter(
     *         name="mensaje",
     *         in="path",
     *         description="ID del mensaje",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Mensaje eliminado correctamente"),
     *     @OA\Response(response=404, description="Mensaje no encontrado"),
     *     @OA\Response(response=500, description="Error del servidor")
     * )
     */
    public function deleteMensaje(Mensaje $mensaje){
        try {
            $mensaje->delete();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'time' => now()->toIso8601String(),
                'message' => 'Mensaje eliminado correctamente',
                'data' => null
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'time' => now()->toIso8601String(),
                'message' => 'Ocurrió un error al eliminar el mensaje',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
