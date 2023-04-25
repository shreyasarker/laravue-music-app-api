<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::query()->get();
            return PostResource::collection($posts);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function postsByUserId()
    {
        try {
            $posts = Post::where('user_id', Auth::user()->id)->get();
            return PostResource::collection($posts);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(PostRequest $request, ImageService $imageService)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::user()->id;

            if ($request->image) {
                $path = $imageService->uploadImage($request->image, Auth::user()->name, 'public/images/posts');
                $data['image'] = $path;
            }
            Post::create($data);

             return response()->json([
                'message' => 'Post created successfully!',
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $post = Post::findOrFail($id);
            return new PostResource($post);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(PostRequest $request, $id, ImageService $imageService)
    {
        try {
            $post = Post::findOrFail($id);
            $data = $request->validated();
            if (isset($data['image'])) {
                if($post->image){
                    $imageService->removeImage($post->image);
                }
                $path = $imageService->uploadImage($data['image'], Auth::user()->name, 'public/images/posts');
                $data['image'] = $path;
            }
            $post->update($data);

             return response()->json([
                'message' => 'Post updated successfully!',
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id, ImageService $imageService)
    {
        try {
            $post = Post::findOrFail($id);
            $imageService->removeImage($post->image);

            $post->delete();

            return response()->json([
                'message' => 'Post deleted successfully!'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
