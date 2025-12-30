<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Cloudinary\Cloudinary;
use Exception;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Obtener todos los usuarios",
     *     tags={"Usuarios"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuarios obtenida correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="time", type="string", format="date-time"),
     *             @OA\Property(property="message", type="string", example="Todos los usuarios obtenidos correctamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nombre", type="string", example="pedro"),
     *                     @OA\Property(property="apellido", type="string", example="garcia"),
     *                     @OA\Property(property="email", type="string", example="pedro.garcia@example.com"),
     *                     @OA\Property(property="provincia_id", type="integer", example=1),
     *                     @OA\Property(property="ciudad_id", type="integer", example=1),
     *                     @OA\Property(property="descripcion", type="string", example="Descripción del usuario"),
     *                     @OA\Property(property="horas_saldo", type="integer", example=5),
     *                     @OA\Property(property="valoracion", type="integer", example=0),
     *                     @OA\Property(property="rol_id", type="integer", example=1),
     *                     @OA\Property(property="ruta_img", type="string", example="https://res.cloudinary.com/demo/image/upload/sample.jpg", nullable=true)
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
     *             @OA\Property(property="message", type="string", example="Ocurrió un error con la base de datos")
     *         )
     *     )
     * )
     */
    public function getAllUsers(){
        try{
            $users = User::select('id', 
                                'nombre', 
                                'apellido', 
                                'email', 
                                'provincia_id', 
                                'ciudad_id', 
                                'descripcion', 
                                'horas_saldo', 
                                'valoracion', 
                                'rol_id',
                                'ruta_img',
                                //'direccion'
                                )->get();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                "time" => now()->toIso8601String(), // Fecha en formato ISO-8601
                "message" => "Todos los usuarios obtenidos correctamente",
                'data' => $users
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => 'Ocurrió un error con la base de datos',
                'error' => $e->getMessage()
            ], 500);   
        }
    }

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     summary="Obtener un usuario específico",
     *     tags={"Usuarios"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del usuario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="time", type="string", format="date-time"),
     *             @OA\Property(property="message", type="string", example="Usuario encontrado"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Pedro"),
     *                 @OA\Property(property="apellido", type="string", example="Garcia"),
     *                 @OA\Property(property="email", type="string", example="pedro.garcia@example.com"),
     *                 @OA\Property(property="provincia_id", type="integer", example=1),
     *                 @OA\Property(property="ciudad_id", type="integer", example=1),
     *                 @OA\Property(property="descripcion", type="string", example="Descripción del usuario"),
     *                 @OA\Property(property="horas_saldo", type="integer", example=5),
     *                 @OA\Property(property="valoracion", type="integer", example=0),
     *                 @OA\Property(property="rol_id", type="integer", example=1),
     *                 @OA\Property(property="ruta_img", type="string", example="https://res.cloudinary.com/demo/image/upload/sample.jpg", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Usuario no Encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
    public function getUser($id){
        try{
            $usuario = User::find($id);
            if ($usuario) {
                return response()->json([
                    "status" => "success",
                    "code" => 200,
                    "time" => now()->toIso8601String(),
                    "message" => "Usuario encontrado",
                    "data" => $usuario
                ], 200);
            } else {
                return response()->json([
                    "status" => "error",
                    "code" => 404,
                    "time" => now()->toIso8601String(),
                    "message" => "Usuario no Encontrado",
                    "data" => null
                ], 404);
            }
        }catch(Exception $e){
            return response()->json([
                "status" => "error",
                "code" => 500,
                "message" => "Ocurrió un error con la base de datos",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     summary="Crear un nuevo usuario",
     *     tags={"Usuarios"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"nombre","apellido","email","password","rol_id"},
     *                 @OA\Property(property="nombre", type="string", example="Pedro"),
     *                 @OA\Property(property="apellido", type="string", example="Garcia"),
     *                 @OA\Property(property="email", type="string", example="pedro.garcia@example.com"),
     *                 @OA\Property(property="password", type="string", format="password", example="secreto123"),
     *                 @OA\Property(property="provincia_id", type="integer", example=1),
     *                 @OA\Property(property="ciudad_id", type="integer", example=1),
     *                 @OA\Property(property="descripcion", type="string", example="Descripción del usuario"),
     *                 @OA\Property(property="horas_saldo", type="integer", example=5),
     *                 @OA\Property(property="valoracion", type="integer", example=0),
     *                 @OA\Property(property="rol_id", type="integer", example=2, description="Solo valores 2 o 3"),
     *                 @OA\Property(property="img", type="string", format="binary", description="Imagen de perfil"),
     *                 @OA\Property(property="direccion", type="string", example="Dirección del usuario"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario creado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="code", type="integer", example=201),
     *             @OA\Property(property="time", type="string", format="date-time"),
     *             @OA\Property(property="message", type="string", example="Usuario creado correctamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Pedro"),
     *                 @OA\Property(property="apellido", type="string", example="Garcia"),
     *                 @OA\Property(property="email", type="string", example="pedro.garcia@example.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="code", type="integer", example=422),
     *             @OA\Property(property="message", type="string", example="Error de validación")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
    public function createUser(Request $request)
    {
        try {
            // Validar los datos del request
            $validated = $request->validate([
                'nombre' => 'required|string|max:100',
                'apellido' => 'required|string|max:100',
                'email' => 'required|string|email|max:150|unique:usuarios,email',
                'password' => 'required|string|min:6',
                'provincia_id' => 'nullable|integer|exists:provincias,id',
                'ciudad_id' => 'nullable|integer|exists:ciudades,id',
                'descripcion' => 'nullable|string',
                'horas_saldo' => 'nullable|integer',
                'valoracion' => 'nullable|numeric',
                'rol_id' => 'required|integer|in:2,3', // Solo roles 2 o 3
                'img' => 'nullable|image|max:2048',
                'direccion' => 'nullable|string',
            ]);

            // Subir imagen a Cloudinary y obtener url de almacenaje
            $url = null;
            if ($request->hasFile('img')) {
                $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
                $result = $cloudinary->uploadApi()->upload($request->file('img')->getRealPath());
                $url = $result['secure_url'];
            }

            // Crear el nuevo usuario
            $user = User::create([
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'provincia_id' => $validated['provincia_id'] ?? null,
                'ciudad_id' => $validated['ciudad_id'] ?? null,
                'descripcion' => $validated['descripcion'] ?? null,
                'horas_saldo' => $validated['horas_saldo'] ?? 5,
                'valoracion' => $validated['valoracion'] ?? 0.0,
                'rol_id' => $validated['rol_id'],
                'ruta_img' => $url, // Guardar la URL de la imagen en Cloudinary
                //'direccion' => $validated['direccion'] ?? null,
            ]);

            return response()->json([
                'status' => 'success',
                'code' => 201,
                'time' => now()->toIso8601String(),
                'message' => 'Usuario creado correctamente',
                'data' => $user
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
     *     path="/users/{user}",
     *     summary="Actualizar un usuario existente",
     *     tags={"Usuarios"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="ID del usuario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="nombre", type="string", example="Pedro"),
     *                 @OA\Property(property="apellido", type="string", example="Garcia"),
     *                 @OA\Property(property="email", type="string", example="pedro.garcia@example.com"),
     *                 @OA\Property(property="password", type="string", format="password", example="nuevo_secreto123"),
     *                 @OA\Property(property="provincia_id", type="integer", example=1),
     *                 @OA\Property(property="ciudad_id", type="integer", example=1),
     *                 @OA\Property(property="descripcion", type="string", example="Descripción actualizada"),
     *                 @OA\Property(property="horas_saldo", type="integer", example=10),
     *                 @OA\Property(property="valoracion", type="integer", example=0),
     *                 @OA\Property(property="rol_id", type="integer", example=2, description="Solo valores 2 o 3"),
     *                 @OA\Property(property="img", type="string", format="binary", description="Nueva imagen de perfil"),
     *                 @OA\Property(property="_method", type="string", example="PUT", description="Necesario para simular PUT en form-data"),
     *                 @OA\Property(property="direccion", type="string", example="Dirección actualizada"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario actualizado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="time", type="string", format="date-time"),
     *             @OA\Property(property="message", type="string", example="Usuario actualizado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\User]")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
    public function updateUser(Request $request, User $user)
    {
        try {
            // Validar los datos del request
            $validated = $request->validate([
                'nombre' => 'sometimes|string|max:100',
                'apellido' => 'sometimes|string|max:100',
                'email' => 'sometimes|string|email|max:150|unique:usuarios,email,' . $user->id,
                'password' => 'sometimes|string|min:6',
                'provincia_id' => 'sometimes|nullable|integer|exists:provincia,id',
                'ciudad_id' => 'sometimes|nullable|integer|exists:ciudad,id',
                'descripcion' => 'sometimes|nullable|string',
                'horas_saldo' => 'sometimes|nullable|integer',
                'valoracion' => 'sometimes|nullable|numeric',
                'rol_id' => 'sometimes|integer|in:2,3', // Solo roles 2 o 3
                'img' => 'sometimes|nullable|image|max:2048',
                //'direccion' => 'sometimes|nullable|string',
            ]);

            // Actualizar los datos del usuario
            if (isset($validated['nombre'])) {
                $user->nombre = $validated['nombre'];
            }
            if (isset($validated['apellido'])) {
                $user->apellido = $validated['apellido'];
            }
            if (isset($validated['email'])) {
                $user->email = $validated['email'];
            }
            if (isset($validated['password'])) {
                $user->password = bcrypt($validated['password']);
            }
            if (isset($validated['provincia_id'])) {
                $user->provincia_id = $validated['provincia_id'];
            }
            if (isset($validated['ciudad_id'])) {
                $user->ciudad_id = $validated['ciudad_id'];
            }
            if (isset($validated['descripcion'])) {
                $user->descripcion = $validated['descripcion'];
            }
            if (isset($validated['horas_saldo'])) {
                $user->horas_saldo = $validated['horas_saldo'];
            }
            if (isset($validated['valoracion'])) {
                $user->valoracion = $validated['valoracion'];
            }
            if (isset($validated['rol_id'])) {
                $user->rol_id = $validated['rol_id'];
            }
            // Si hay una nueva imagen, subirla a Cloudinary y actualizar la URL
            if ($request->hasFile('img')) {
                $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
                $result = $cloudinary->uploadApi()->upload($request->file('img')->getRealPath());
                $user->ruta_img = $result['secure_url'];
            }
            
            // if (isset($validated['direccion'])) {
            //     $user->direccion = $validated['direccion'];
            // }
            // Guardar los cambios
            $user->save();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'time' => now()->toIso8601String(),
                'message' => 'Usuario actualizado correctamente',
                'data' => $user
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
     *     path="/users/{user}",
     *     summary="Eliminar un usuario",
     *     tags={"Usuarios"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="ID del usuario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario eliminado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="time", type="string", format="date-time"),
     *             @OA\Property(property="message", type="string", example="Usuario eliminado correctamente"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\User]")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
    public function deleteUser(User $user){
        // Se recibe un id pero lo trata como modelo User  
        // Al recibir un usuario como parámetro, 
        // ya hace la busqueda automaticamente y si no lo encuentra devuelve 404 
        // se utiliza la inyección de dependencias
        try {
            $user->delete();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'time' => now()->toIso8601String(),
                'message' => 'Usuario eliminado correctamente',
                'data' => null
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'time' => now()->toIso8601String(),
                'message' => 'Ocurrió un error al eliminar el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/users/{user}/change-password",
     *     summary="Cambiar contraseña del usuario",
     *     tags={"Usuarios"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"password"},
     *             @OA\Property(property="password", type="string", format="password", example="nueva_contraseña123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Contraseña actualizada"),
     *     @OA\Response(response=400, description="La contraseña es la misma"),
     *     @OA\Response(response=500, description="Error del servidor")
     * )
     */
    public function changePassword(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'password' => 'required|string|min:6', // Validamos la entrada
            ]);

            $nuevaPassword = $validated['password'];

            // Comprobar usando la contraseña PLANA contra el HASH guardado
            if (Hash::check($nuevaPassword, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'time' => now()->toIso8601String(),
                    'message' => 'La nueva contraseña no puede ser igual a la actual'
                ], 400);
            }

            // Si es diferente, la encriptamos y guardamos
            $user->password = bcrypt($nuevaPassword);
            $user->save();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'time' => now()->toIso8601String(),
                'message' => 'Contraseña cambiada correctamente',
                'data' => $user
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'time' => now()->toIso8601String(),
                'message' => 'Ocurrió un error al cambiar la contraseña',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
