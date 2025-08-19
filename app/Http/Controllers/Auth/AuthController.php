<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Company;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Registro con rol por defecto (client).
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        if ($request->type === 'company') {
            // Crear empresa en estado pendiente
            $company = Company::create([
                'name'   => $request->company_name,
                'email'  => $request->company_email,
                'status' => 'pending',
                'nit'    => $request->nit,
                'phone'  => $request->phone,
                'address' => $request->address,
            ]);

            // 🔑 crea el rol si no existe
            $role = Role::firstOrCreate(['name' => 'company_admin']);

            $user = User::create([
                'name'     => $request->name,
                'username' => $request->username,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // relaciones pivote
            $user->roles()->attach($role->id);
            $user->companies()->attach($company->id);
        } else {
            // Registro normal (cliente)
            // 🔑 crea el rol si no existe
            $role = Role::firstOrCreate(['name' => 'client']);

            $user = User::create([
                'name'     => $request->name,
                'username' => $request->username,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->roles()->attach($role->id);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user'  => $user->load('roles', 'companies'),
            'token' => $token
        ], 201);
    }




    /**
     * Login con JWT.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token,
            'user'  => Auth::user()->load('roles')
        ]);
    }

    /**
     * Usuario autenticado.
     */
    public function me(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json($user->load('roles'));
    }

    /**
     * Logout e invalidar token.
     */
    public function logout(): JsonResponse
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Successfully logged out']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to logout, token invalid'], 500);
        }
    }

    /**
     * Refrescar token.
     */
    public function refresh(): JsonResponse
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());
            return response()->json(['token' => $newToken]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to refresh token'], 500);
        }
    }
}
