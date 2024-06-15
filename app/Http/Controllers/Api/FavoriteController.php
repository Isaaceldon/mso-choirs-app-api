<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\FavoriteResource;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $favorites = Favorite::all();
        return FavoriteResource::collection($favorites);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'song_id' => 'required|exists:songs,id',
        ]);

        $user = Auth::user();

        // Check if the favorite already exists for the user and song
        $existingFavorite = Favorite::where('user_id', $user->id)
            ->where('song_id', $request->song_id)
            ->first();

        if ($existingFavorite) {
            return response()->json(['message' => 'Song already favorited'], Response::HTTP_BAD_REQUEST);
        }

        $favorite = Favorite::create([
            'user_id' => $user->id,
            'song_id' => $request->song_id,
        ]);

        return new FavoriteResource($favorite);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $favorite = Favorite::find($id);

        if (!$favorite) {
            return response()->json(['message' => 'Favorite not found'], Response::HTTP_NOT_FOUND);
        }

        return new FavoriteResource($favorite);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $favorite = Favorite::find($id);

        if (!$favorite) {
            return response()->json(['message' => 'Favorite not found'], Response::HTTP_NOT_FOUND);
        }

        $favorite->delete();

        return response()->json(['message' => 'Favorite successfully deleted'], Response::HTTP_OK);
    }
}