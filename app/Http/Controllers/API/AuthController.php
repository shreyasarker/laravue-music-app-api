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

        return response()->json([
            'user' => new UserResource($createdUser)
        ], Response::HTTP_OK);
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();

            if(Auth::attempt($credentials)) {
                return response()->json([
                    'user' => Auth::user(),
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

    public function logout(Request $request)
    {
        try {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

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
