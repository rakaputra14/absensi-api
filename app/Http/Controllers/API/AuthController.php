<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Claims\JwtId;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        }
        // return response()->json(['message' => 'Success'], 200);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);
        try {
            $token = JWTAuth::fromUser($user);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        }

        $credential = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credential)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'message' => 'User logged in successfully',
            'token' => $token,
        ]);

        // return response()->json([
        //     'message' => 'User logged in successfully',
        //     'token' => $token,
        // ], 200);
    }

    public function logout()
    {

        auth('api')->logout();
        return response()->json(['message' => 'User logged out successfully']);

        // try {
        //     auth()->guard('api')->logout();
        //     return response()->json(['message' => 'User logged out successfully'], 200);
        // } catch (\Throwable $th) {
        //     return response()->json(['error' => $th->getMessage()], 500);
        // }
    }

    public function me()
    {
        try {
            $user = auth('api')->user();
            $employee = auth('api')->user()->employee;
            return response()->json(
                [
                    'message' => 'User retrieved successfully',
                    'user' => $user,
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
