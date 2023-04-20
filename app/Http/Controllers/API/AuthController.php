<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = $request->validated();
        $user['password'] = Hash::make($request->password);
        $createdUser = User::create($user);
        $token = $createdUser->createToken('user_token')->plainTextToken;

        return response()->json([
            'user' => $createdUser,
            'token' => $token
        ], Response::HTTP_OK);
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->firstOrFail();
            if(Hash::check($request->password, $user->password)) {
                $token = $user->createToken('user_token')->plainTextToken;

                return response()->json([
                    'user' => $user,
                    'token' => $token
                ], Response::HTTP_OK);
            }
            return response()->json([
                'message' => 'Email or password is not correct'
            ], Response::HTTP_UNAUTHORIZED);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(LogoutRequest $request)
    {
        try {
            $user = User::findOrFail($request->id);

            $user->tokens()->delete();

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
