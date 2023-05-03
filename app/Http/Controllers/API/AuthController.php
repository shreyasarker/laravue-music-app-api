<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = $request->validated();
        $user['password'] = Hash::make($request->password);
        $createdUser = User::create($user);

        return new UserResource($createdUser);
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Email or password is not correct'
                ], Response::HTTP_UNAUTHORIZED);
            }

            $token = $user->createToken('laravue-music-app-api')->plainTextToken;
            return response()->json([
                'user' => new UserResource($user),
                'token' => $token
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            if($user) {
                $request->user()->tokens()->delete();
            }
            return response()->json([
                'message' => 'User logged out'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
