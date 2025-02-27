<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\V1\Controller;
use App\Http\Requests\V1\RegisterRequest;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Requests\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\V1\User;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      title="API REST Panel de Control de Club Deportivo",
 *      version="1.0.0",
 *      description="API para autenticación y gestión de un club deportivo",
 * )
 *
 * @OA\Tag(
 *     name="Usuarios",
 *     description="Endpoints relacionados con autenticación de usuarios"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     summary="Registrar un nuevo usuario",
     *     tags={"Usuarios"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="12345678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario registrado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9"),
     *             @OA\Property(property="user", ref="#/components/schemas/UserResource")
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => new UserResource($user)
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="Iniciar sesión",
     *     tags={"Usuarios"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="12345678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inicio de sesión exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="user", ref="#/components/schemas/UserResource")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Credenciales incorrectas")
     * )
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas no son correctas.'],
            ]);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => new UserResource($user)
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/me",
     *     summary="Obtener información del usuario autenticado",
     *     tags={"Usuarios"},
     *     security={{"sanctum":{}}}, 
     *     @OA\Response(
     *         response=200,
     *         description="Datos del usuario autenticado",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function me(Request $request)
    {
        return new UserResource($request->user());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     summary="Cerrar sesión",
     *     tags={"Usuarios"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Sesión cerrada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sesión cerrada correctamente")
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/users/update",
     *     summary="Actualizar datos del usuario autenticado",
     *     tags={"Usuarios"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="newpassword")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario actualizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuario actualizado"),
     *             @OA\Property(property="user", ref="#/components/schemas/UserResource")
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();

        $data = $request->only(['name', 'email']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json(['message' => 'Usuario actualizado', 'user' => new UserResource($user)], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/users/delete",
     *     summary="Eliminar usuario autenticado",
     *     tags={"Usuarios"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Usuario eliminado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuario eliminado")
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */
    public function delete(Request $request)
    {
        $request->user()->delete();
        return response()->json(['message' => 'Usuario eliminado']);
    }
}
