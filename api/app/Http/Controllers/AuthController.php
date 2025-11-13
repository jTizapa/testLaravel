<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request){
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
                'remember_me' => 'nullable|boolean'
            ]);

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'error' => true,
                    'message' => 'El nombre o contraseña son incorrectos.'
                ], 401);
            }

            $user = $request->user();

            if (Auth::guard('sanctum')->check()) {
                $user->tokens()->delete();
            }


            $userId = $request->user()->id;
            $userData = User::with('roles')->find($userId); // Cargar relaciones de roles

            // Agregar información de roles al userData
            $userData->roleName = $userData->getRoleNames()[0] ?? 'Sin rol asignado';

            $remember = $request->boolean('remember_me', false);
            $expirationMinutes = $remember ? (60 * 24 * 30) : config('sanctum.expiration');
            $tokenResult = $user->createToken('Personal Access Token', ['*'], now()->addMinutes($expirationMinutes));
            $token = $tokenResult->plainTextToken;



                $permissions = $user->getAllPermissions()->pluck('name')->map(function ($permission) {
                    list($action, $subject) = explode('-', $permission);
                    return [
                        'action' => $action,
                        'subject' => $subject,
                    ];
                })->toArray();

                $data = [
                    'userData' => $userData,
                    'userAbilityRules' => $permissions,
                    'tokenType' => 'Bearer',
                    'accessToken' => $token,
                ];


            return response()->json($data);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Los datos proporcionados no son válidos',
                'errors' => $e->errors()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error.' . $e,
            ], 500);
        }
    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'password' => 'required|string',
        ]);

        $user = new User([
            'name'  => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'api' => $request->api,
            'active' => 1,
        ]);

        if ($user->save()) {
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->plainTextToken;

            return response()->json([
                'message' => 'Successfully created user!',
                'accessToken' => $token,
            ], 201);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }

    public function logout(){
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
