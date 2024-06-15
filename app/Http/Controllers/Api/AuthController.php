<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'tabernacle_id' => 'required|exists:tabernacles,id',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|string|confirmed|min:8'
        ]);

        // Vérifiez si le rôle choisi est "CO" (Chef Orchestre)
        $role = Role::find($request->role_id)->first();
        if ($role->name === 'Chef Orchestre') {
            // Vérifiez si un utilisateur avec le rôle "CO" existe déjà pour ce tabernacle
            $existingCO = User::where('role_id', $role->id)
                ->where('tabernacle_id', $request->tabernacle_id)
                ->exists();
            if ($existingCO) {
                return response()->json(['error' => 'There can only be one CO for this tabernacle.'], 400);
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'tabernacle_id' => $request->tabernacle_id,
            'role_id' => $request->role_id, 
            'password' => Hash::make($request->password),
        ]);

        // Créer le token d'authentification
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'status' => true,
            'message' => 'Registered successfully!',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ];

        return response($response, 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string'
        ]);

        // Vérifiez si l'identifiant est un email ou un numéro de téléphone
        $user = User::where('email', $request->identifier)
            ->orWhere('phone', $request->identifier)
            ->first();

        // Vérifiez si l'utilisateur existe et que le mot de passe est correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => false, 'message' => 'Invalid credentials'], 401);
        }

        // Authentifiez l'utilisateur
        Auth::login($user);

        // Générez un token d'authentification
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'status' => true,
            'message' => 'Login successful!',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ];

        return response($response, 200);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        $response = [
            'status' => true,
            'message' => 'Logout successfully',
        ];
        return response($response, 201);
    }
}
