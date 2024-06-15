<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\SongResource;

class SongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $songs = Song::orderBy('created_at', 'desc')->get();
        return SongResource::collection($songs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'choir_id' => 'required|exists:choirs,id',
            'user_id' => 'required|exists:users,id',
            'category' => 'required|in:adoration,louange,folklore',
            'title' => 'required|string',
            'audio_url' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $song = Song::create([
            'choir_id' => $request->choir_id,
            'user_id' => $request->user_id,
            'category' => $request->category,
            'title' => $request->title,
            'audio_url' => $request->audio_url,
        ]);

        return new SongResource($song);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $song = Song::find($id);

        if (!$song) {
            return response()->json(['message' => 'Song not found'], Response::HTTP_NOT_FOUND);
        }

        return new SongResource($song);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'choir_id' => 'required|exists:choirs,id',
            'user_id' => 'required|exists:users,id',
            'category' => 'required|in:adoration,louange,folklore',
            'title' => 'required|string',
            'audio_url' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $song = Song::find($id);

        if (!$song) {
            return response()->json(['message' => 'Song not found'], Response::HTTP_NOT_FOUND);
        }

        $song->choir_id = $request->choir_id;
        $song->user_id = $request->user_id;
        $song->category = $request->category;
        $song->title = $request->title;
        $song->audio_url = $request->audio_url;
        $song->save();

        return new SongResource($song);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $song = Song::find($id);

        if (!$song) {
            return response()->json(['message' => 'Song not found'], Response::HTTP_NOT_FOUND);
        }

        $song->delete();

        return response()->json(['message' => 'Song successfully deleted'], Response::HTTP_OK);
    }
}