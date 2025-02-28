<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\User;

class AuthController extends Controller
{
    // User Registration
    public function register(Request $request): JsonResponse
    {   
        $validatedData = Validator::make($request->all(),[
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors'  => $validatedData->errors(),
            ], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user'    => array_merge($user->toArray(), ['token' => $token]),
        ], 201);
    }

    // User Login
    public function login(Request $request): JsonResponse
    {
        $validatedData = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors'  => $validatedData->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user
            || ! Hash::check($request->password, $user->password)
        ) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 422);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user'    => array_merge($user->toArray(), ['token' => $token]),
        ], 200);
    }
}
