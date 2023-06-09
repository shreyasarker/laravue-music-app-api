<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getAuthUser()
    {
        try {
            return new UserResource(Auth::user());

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUserById($userId)
    {
        try {
            $user = User::findOrFail($userId);
            return new UserResource($user);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UserUpdateRequest $request, ImageService $imageService)
    {
        try {
            $user = Auth::user();
            $data = $request->validated();
            if (isset($data['image'])) {
                if($user->image){
                    $imageService->removeImage($user->image);
                }
                $path = $imageService->uploadImage($data['image']);
                $data['image'] = $path;
            }
            $user->update($data);

             return response()->json([
                'message' => 'Profile updated successfully!',
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
