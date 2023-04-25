<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Video\VideoRequest;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    public function index()
    {
        try {
            $videos = Video::where('user_id', Auth::user()->id)->get();
            return VideoResource::collection($videos);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getVideosByUserId($userId)
    {
        try {
            $videos = Video::where('user_id', $userId)->get();
            return VideoResource::collection($videos);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(VideoRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::user()->id;

            Video::create($data);

            return response()->json([
                'message' => 'Video saved successfully!'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(string $id)
    {
        try {
            $video = Video::findOrFail($id);

            $video->delete();

            return response()->json([
                'message' => 'Video deleted successfully!'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
