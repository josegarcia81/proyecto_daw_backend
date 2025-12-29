<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Cloudinary\Cloudinary;

class AuthController extends Controller{
    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Registrar nuevo usuario",
     *     description="Registra un nuevo usuario en el sistema y devuelve un token de autenticación",
     *     operationId="register",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del nuevo usuario",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"nombre","apellido","email","password","provincia_id","ciudad_id"},
     *                 @OA\Property(property="nombre", type="string", maxLength=100, example="Pedro"),
     *                 @OA\Property(property="apellido", type="string", maxLength=100, example="Garcia"),
     *                 @OA\Property(property="email", type="string", format="email", maxLength=150, example="pedro.garcia@example.com"),
     *                 @OA\Property(property="password", type="string", format="password", minLength=6, example="password123"),
     *                 @OA\Property(property="rol_id", type="integer", example=3, description="Siempre se asigna rol 3 (no es necesario enviarlo)"),
     *                 @OA\Property(property="provincia_id", type="integer", example=1),
     *                 @OA\Property(property="ciudad_id", type="integer", example=1),
     *                 @OA\Property(property="direccion", type="string", maxLength=255, nullable=true, example="Calle Falsa 123", description="Campo de prueba - no se guarda en BD"),
     *                 @OA\Property(property="descripcion", type="string", maxLength=255, nullable=true, example="Usuario de prueba"),
     *                 @OA\Property(property="horas_saldo", type="integer", nullable=true, example=5),
     *                 @OA\Property(property="valoracion", type="integer", nullable=true, example=0),
     *                 @OA\Property(property="img", type="string", format="binary", description="Imagen de perfil")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario registrado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="¡Usuario registrado exitosamente!"),
     *             @OA\Property(property="access_token", type="string", example="1|abcdef123456..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Pedro"),
     *                 @OA\Property(property="apellido", type="string", example="Garcia"),
     *                 @OA\Property(property="email", type="string", example="pedro.garcia@example.com"),
     *                 @OA\Property(property="rol_id", type="integer", example=1),
     *                 @OA\Property(property="provincia_id", type="integer", example=1),
     *                 @OA\Property(property="ciudad_id", type="integer", example=1),
     *                 @OA\Property(property="descripcion", type="string", example="Usuario de prueba"),
     *                 @OA\Property(property="horas_saldo", type="integer", example=5),
     *                 @OA\Property(property="valoracion", type="integer", example=0),
     *                 @OA\Property(property="ruta_img", type="string", example="https://res.cloudinary.com/demo/image/upload/sample.jpg", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="array", @OA\Items(type="string", example="The email has already been taken."))
     *         )
     *     )
     * )
     */
    public function register(Request $request): JsonResponse
    {
        // Validación de los datos de entrada
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|string|email|max:150|unique:usuarios,email',
            'password' => 'required|string|min:6', // Requiere campo 'password_confirmation' si se aniade => |confirmed,( eliminado, se confirma en frontend).
            'rol_id' => 'nullable|integer|exists:roles,id', // Opcional, se fuerza a 3
            'provincia_id' => 'required|integer|exists:provincias,id',
            'ciudad_id' => 'required|integer|exists:ciudades,id',
            'descripcion' => 'nullable|string|max:255',
            'horas_saldo' => 'nullable|integer',
            'valoracion' => 'nullable|integer',
            'direccion' => 'nullable|string|max:255', // Acepta pero no guarda en BD (testing)
            'img' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Subir imagen a Cloudinary y obtener url de almacenaje
        $url = null;
        if ($request->hasFile('img')) {
            $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
            $result = $cloudinary->uploadApi()->upload($request->file('img')->getRealPath());
            $url = $result['secure_url'];
        }

        // Creación del usuario
        $user = User::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol_id' => 3, // Siempre rol 3 para registros públicos
            'provincia_id' => $request->provincia_id,
            'ciudad_id' => $request->ciudad_id,
            'descripcion' => $request->descripcion,
            'horas_saldo' => $request->horas_saldo ?? 5, // Valor por defecto si no se envía
            'valoracion' => $request->valoracion ?? 0,
            'ruta_img' => $url,
            //'direccion' => $request->direccion,
        ]);

        // Cargar relaciones (provincia, ciudad, rol)
        $user->load(['provincia', 'ciudad', 'rol']);

        // Creación del token para el nuevo usuario
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => '¡Usuario registrado exitosamente!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Iniciar sesión",
     *     description="Autentica un usuario y devuelve un token de acceso",
     *     operationId="login",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Credenciales del usuario",
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="pedro.garcia@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="¡Hola Juan!"),
     *             @OA\Property(property="access_token", type="string", example="1|abcdef123456..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Pedro"),
     *                 @OA\Property(property="apellido", type="string", example="Garcia"),
     *                 @OA\Property(property="email", type="string", example="pedro.garcia@example.com"),
     *                 @OA\Property(property="rol_id", type="integer", example=1),
     *                 @OA\Property(property="provincia_id", type="integer", example=1),
     *                 @OA\Property(property="ciudad_id", type="integer", example=1),
     *                 @OA\Property(property="descripcion", type="string", example="Usuario de prueba"),
     *                 @OA\Property(property="horas_saldo", type="integer", example=5),
     *                 @OA\Property(property="valoracion", type="integer", example=0)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales inválidas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Credenciales inválidas")
     *         )
     *     )
     * )
     */
    public function login(Request $request): JsonResponse
    {
        // Validación de credenciales
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        // Búsqueda del usuario con relaciones
        $user = User::with(['provincia', 'ciudad', 'rol'])
                    ->where('email', $request['email'])
                    ->firstOrFail();

        // Creación del token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => '¡Login exitoso ' . $user->nombre . '!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="Cerrar sesión",
     *     description="Cierra la sesión del usuario e invalida el token actual",
     *     operationId="logout",
     *     tags={"Autenticación"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Sesión cerrada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sesión cerrada exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        // Revoca el token que se usó para autenticar la solicitud actual
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }
}
