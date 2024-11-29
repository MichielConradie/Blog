<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return response()->json(['user' => $user], 201);
    }
    public function login(Request $request)
    {
        // dd($request->all());
        // $userData = $request->validate([
        //     'email'=> 'required|string|email',
        //     'password'=> 'required'
        // ]);
        // if (!Auth::attempt($userData)) {
        //     // throw ValidationException::withMessages(['email' => 'Invalid credentials.']);
        //     return response()->json(['error' => 'Invalid credentials'], 401);
        // }
        // $user = Auth::user();
        // $token = $user->createToken('authToken')->plainTextToken;
        // return response()->json(['token' => $token], 200);

        // $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required|string',
        // ]);

        // // Find user by email
        // $user = User::where('email', $request->email)->first();

        // // If user exists and password is correct
        // if ($user && Hash::check($request->password, $user->password)) {
        //     // Generate token for authenticated user
        //     $token = $user->createToken('YourAppName')->plainTextToken;

        //     return response()->json([
        //         'token' => $token
        //     ]);
        // }

        // return response()->json(['error' => 'Invalid credentials'], 401);

         // Validate the incoming request
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        // Attempt to log in the user with the provided credentials
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Return a response for invalid credentials
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // If authentication is successful, get the authenticated user
        $user = Auth::user();

        // Create a new token for the user
        $token = $user->createToken('authToken')->plainTextToken;

        // Return the token in the response
        return response()->json(['token' => $token], 200);

    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
