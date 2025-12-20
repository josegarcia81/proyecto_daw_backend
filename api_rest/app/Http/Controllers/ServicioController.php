<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Exception;

class ServicioController extends Controller
{
    /**
     * @OA\Get(
     *     path="/servicios",
     *     summary="Obtener todos los servicios",
     *     tags={"Servicios"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de servicios obtenida correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="time", type="string", format="date-time"),
     *             @OA\Property(property="message", type="string", example="Todos los servicios obtenidos correctamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=19),
     *                     @OA\Property(property="usuario_id", type="integer", example=1),
     *                     @OA\Property(property="categoria_id", type="integer", example=1),
     *                     @OA\Property(property="tipo", type="string", example="oferta"),
     *                     @OA\Property(property="titulo", type="string", example="Clases de matemáticas"),
     *                     @OA\Property(property="descripcion", type="string", example="Ofrezco clases particulares de matemáticas"),
     *                     @OA\Property(property="provincia", type="integer", example=3),
     *                     @OA\Property(property="ciudad", type="integer", example=3),
     *                     @OA\Property(property="horas_estimadas", type="integer", example=2),
     *                     @OA\Property(property="estado", type="string", example="activo")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Error del servidor")
     * )
     */
    public function getAllServicios(){
        try{
            // Obtener todos los servicios con sus relaciones (usuario, categoría, provincia, ciudad)
            $servicios = Servicio::with(['usuario', 'categoria', 'provinciaRelacion', 'ciudadRelacion'])->get();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                "time" => now()->toIso8601String(),
                "message" => "Todos los servicios obtenidos correctamente",
                'data' => $servicios
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
     *     path="/servicios/{user_id}",
     *     summary="Obtener todos los servicios de un usuario",
     *     tags={"Servicios"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="ID del usuario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Servicios del usuario obtenidos correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="time", type="string", format="date-time"),
     *             @OA\Property(property="message", type="string", example="Servicios del usuario obtenidos correctamente"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="code", type="integer", example=406),
     *             @OA\Property(property="message", type="string", example="Usuario no encontrado"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Error del servidor")
     * )
     */
    public function getServicios($user_id){
        try{
            // Verificar que el usuario existe
            $usuario = User::find($user_id);
            
            if (!$usuario) {
                return response()->json([
                    "status" => "error",
                    "code" => 406,
                    "time" => now()->toIso8601String(),
                    "message" => "Usuario no encontrado",
                    "data" => null
                ], 404);
            }
            
            // Obtener todos los servicios del usuario con sus relaciones
            $servicios = Servicio::with(['usuario', 'categoria', 'provinciaRelacion', 'ciudadRelacion'])
                ->where('usuario_id', $user_id)
                ->get();
            
            return response()->json([
                "status" => "success",
                "code" => 200,
                "time" => now()->toIso8601String(),
                "message" => "Servicios del usuario obtenidos correctamente",
                "data" => $servicios
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
     * @OA\Get(
     *     path="/servicio/{servicio_id}",
     *     summary="Obtener un servicio por su ID",
     *     tags={"Servicios"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="servicio_id",
     *         in="path",
     *         description="ID del servicio",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Servicio obtenido correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="time", type="string", format="date-time"),
     *             @OA\Property(property="message", type="string", example="Servicio obtenido correctamente"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Servicio no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Servicio no encontrado"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Error del servidor")
     * )
     */
    public function getServicio($service_id){
        try{
            // Verificar que el servicio existe
            $servicio = Servicio::with(['usuario', 'categoria', 'provinciaRelacion', 'ciudadRelacion'])->find($service_id);
            
            if (!$servicio) {
                return response()->json([
                    "status" => "error",
                    "code" => 404,
                    "time" => now()->toIso8601String(),
                    "message" => "Servicio no encontrado",
                    "data" => null
                ], 404);
            }
            
            return response()->json([
                "status" => "success",
                "code" => 200,
                "time" => now()->toIso8601String(),
                "message" => "Servicio obtenido correctamente",
                "data" => $servicio
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
     *     path="/servicios",
     *     summary="Crear un nuevo servicio",
     *     tags={"Servicios"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"usuario_id","categoria_id","tipo","titulo","descripcion","provincia_id","ciudad_id","horas_estimadas"},
     *             @OA\Property(property="usuario_id", type="integer", example=1),
     *             @OA\Property(property="categoria_id", type="integer", example=1),
     *             @OA\Property(property="tipo", type="string", example="oferta", description="oferta o demanda"),
     *             @OA\Property(property="titulo", type="string", example="Clases de matemáticas"),
     *             @OA\Property(property="descripcion", type="string", example="Ofrezco clases particulares de matemáticas para ESO y Bachillerato"),
     *             @OA\Property(property="provincia_id", type="integer", example=3),
     *             @OA\Property(property="ciudad_id", type="integer", example=3),
     *             @OA\Property(property="horas_estimadas", type="integer", example=2),
     *             @OA\Property(property="estado", type="string", example="activo", description="activo, en_proceso, finalizado o cancelado")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Servicio creado correctamente"),
     *     @OA\Response(response=422, description="Error de validación"),
     *     @OA\Response(response=500, description="Error del servidor")
     * )
     */
    public function createServicio(Request $request)
    {
        try {
            $validated = $request->validate([
                'usuario_id' => 'required|integer|exists:usuarios,id',
                'categoria_id' => 'required|integer|exists:categorias,id',
                'tipo' => 'required|string|in:oferta,demanda',
                'titulo' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'provincia_id' => 'required|integer|exists:provincias,id',
                'ciudad_id' => 'required|integer|exists:ciudades,id',
                'horas_estimadas' => 'required|integer|min:1',
                'estado' => 'nullable|string|in:activo,en_proceso,finalizado,cancelado',
            ]);

            $servicio = Servicio::create([
                'usuario_id' => $validated['usuario_id'],
                'categoria_id' => $validated['categoria_id'],
                'tipo' => $validated['tipo'],
                'titulo' => $validated['titulo'],
                'descripcion' => $validated['descripcion'],
                'provincia_id' => $validated['provincia_id'],
                'ciudad_id' => $validated['ciudad_id'],
                'horas_estimadas' => $validated['horas_estimadas'],
                'estado' => $validated['estado'] ?? 'activo',
            ]);

            // Cargar relaciones para devolverlas en la respuesta
            $servicio->load(['usuario', 'categoria', 'provinciaRelacion', 'ciudadRelacion']);

            return response()->json([
                'status' => 'success',
                'code' => 201,
                'time' => now()->toIso8601String(),
                'message' => 'Servicio creado correctamente',
                'data' => $servicio
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
     *     path="/servicios/{servicio}",
     *     summary="Actualizar un servicio existente",
     *     tags={"Servicios"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="servicio",
     *         in="path",
     *         description="ID del servicio",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="usuario_id", type="integer", example=1),
     *             @OA\Property(property="categoria_id", type="integer", example=1),
     *             @OA\Property(property="tipo", type="string", example="oferta"),
     *             @OA\Property(property="titulo", type="string", example="Clases de matemáticas avanzadas"),
     *             @OA\Property(property="descripcion", type="string", example="Clases particulares actualizadas"),
     *             @OA\Property(property="provincia_id", type="integer", example=3),
     *             @OA\Property(property="ciudad_id", type="integer", example=3),
     *             @OA\Property(property="horas_estimadas", type="integer", example=3),
     *             @OA\Property(property="estado", type="string", example="activo", description="activo, en_proceso, finalizado o cancelado")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Servicio actualizado correctamente"),
     *     @OA\Response(response=404, description="Servicio no encontrado"),
     *     @OA\Response(response=422, description="Error de validación"),
     *     @OA\Response(response=500, description="Error del servidor")
     * )
     */
    public function updateServicio(Request $request, Servicio $servicio)
    {
        try {
            $validated = $request->validate([
                'usuario_id' => 'sometimes|integer|exists:usuarios,id',
                'categoria_id' => 'sometimes|integer|exists:categorias,id',
                'tipo' => 'sometimes|string|in:oferta,demanda',
                'titulo' => 'sometimes|string|max:255',
                'descripcion' => 'sometimes|string',
                'provincia_id' => 'sometimes|integer|exists:provincias,id',
                'ciudad_id' => 'sometimes|integer|exists:ciudades,id',
                'horas_estimadas' => 'sometimes|integer|min:1',
                'estado' => 'sometimes|string|in:activo,en_proceso,finalizado,cancelado',
            ]);

            if (isset($validated['usuario_id'])) {
                $servicio->usuario_id = $validated['usuario_id'];
            }
            if (isset($validated['categoria_id'])) {
                $servicio->categoria_id = $validated['categoria_id'];
            }
            if (isset($validated['tipo'])) {
                $servicio->tipo = $validated['tipo'];
            }
            if (isset($validated['titulo'])) {
                $servicio->titulo = $validated['titulo'];
            }
            if (isset($validated['descripcion'])) {
                $servicio->descripcion = $validated['descripcion'];
            }
            if (isset($validated['provincia_id'])) {
                $servicio->provincia_id = $validated['provincia_id'];
            }
            if (isset($validated['ciudad_id'])) {
                $servicio->ciudad_id = $validated['ciudad_id'];
            }
            if (isset($validated['horas_estimadas'])) {
                $servicio->horas_estimadas = $validated['horas_estimadas'];
            }
            if (isset($validated['estado'])) {
                $servicio->estado = $validated['estado'];
            }

            $servicio->save();

            // Recargar relaciones para devolverlas en la respuesta
            $servicio->load(['usuario', 'categoria', 'provinciaRelacion', 'ciudadRelacion']);

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'time' => now()->toIso8601String(),
                'message' => 'Servicio actualizado correctamente',
                'data' => $servicio
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
     *     path="/servicios/{servicio}",
     *     summary="Eliminar un servicio",
     *     tags={"Servicios"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="servicio",
     *         in="path",
     *         description="ID del servicio",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Servicio eliminado correctamente"),
     *     @OA\Response(response=404, description="Servicio no encontrado"),
     *     @OA\Response(response=500, description="Error del servidor")
     * )
     */
    public function deleteServicio(Servicio $servicio){
        try {
            $servicio->delete();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'time' => now()->toIso8601String(),
                'message' => 'Servicio eliminado correctamente',
                'data' => null
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'time' => now()->toIso8601String(),
                'message' => 'Ocurrió un error al eliminar el servicio',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
