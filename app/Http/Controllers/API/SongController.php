<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Song\SongRequest;
use App\Http\Resources\SongResource;
use App\Models\Song;
use App\Services\SongService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SongController extends Controller
{
    public function getSongsByUserId($userId)
    {
        try {
            $songs = Song::where('user_id', $userId)->get();
            return SongResource::collection($songs);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(SongRequest $request, SongService $songService)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::user()->id;
            $path = $songService->uploadSong($request->song);
            $data['song'] = $path;
            Song::create($data);

            return response()->json([
                'message' => 'Song saved successfully!'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id, SongService $songService)
    {
        try {
            $song = Song::findOrFail($id);
            $songService->removeSong($song->song);

            $song->delete();

            return response()->json([
                'message' => 'Song deleted successfully!'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
