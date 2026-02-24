<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // FUNCTION TO REGISTER A NEW USER
    public function register(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create a new user with the validated data
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => 'player',
        ]);

        // Generate a token for the newly created user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return the token in the response
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    // FUNCTION TO LOGIN A USER
    public function login(Request $request)
    {
        // Validate the incoming request data
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Retrieve the authenticated user
        $user = User::where('email', $request['email'])->firstOrFail();

        // Generate token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return the token and user information in the response
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }
}
