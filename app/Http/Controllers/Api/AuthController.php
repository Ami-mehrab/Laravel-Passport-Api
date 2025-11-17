<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        // Validate incoming request with password confirmation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // Ensure "confirmed" is here for password confirmation
        ]);

        // Return validation errors if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create the user in the database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Hashing the password
        ]);

        // Create a Passport token for the newly created user
        $token = $user->createToken('API Token')->accessToken;

        // Return the response with the token and user details
        return response()->json([
            'message' => 'User registered successfully!',
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    /**
     * Login user and create token
     */
    public function login(Request $request)
    {
        // Validate the incoming credentials
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Create a token for the authenticated user
        $token = Auth::user()->createToken('API Token')->accessToken;

        // Return the success response with token and user details
        return response()->json([
            'message' => 'Login successful!',
            'token' => $token,
            'user' => Auth::user(),
        ]);
    }

    /**
     * Get logged-in user info
     */
    public function userProfile()
    {
        // Return the currently authenticated user's details
        return response()->json(Auth::user());

        
    }



    /**
     * Logout user (revoke token)
     */
    public function logout(Request $request)
    {
        // Revoke the user's current token to log them out
        $request->user()->token()->revoke();

        // Return a success response
        return response()->json(['message' => 'User logged out successfully']);
    }



    /**
     * Refresh the token (if using refresh tokens)
     */
    public function refreshToken(Request $request)
    {
        // Revoke the current token and create a new one
        $request->user()->token()->revoke();

        // Generate a new token
        $token = $request->user()->createToken('API Token')->accessToken;

        // Return the new token
        return response()->json([
            'message' => 'Token refreshed successfully!',
            'token' => $token,
        ]);
    }
}
