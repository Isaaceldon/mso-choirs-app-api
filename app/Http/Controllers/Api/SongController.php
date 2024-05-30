<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Song::class);

        $request->validate([
            'choir_id' => 'required|exists:choirs,id',
            'category' => 'required|in:adoration,louange,folklore',
            'title' => 'required|string',
            'audio_file_path' => 'required|string',
        ]);

        $song = Song::create([
            'choir_id' => $request->choir_id,
            'recorded_by' => $request->user()->id,
            'category' => $request->category,
            'title' => $request->title,
            'audio_file_path' => $request->audio_file_path,
        ]);

        return response()->json($song, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Song $song)
    {
        $this->authorize('update', $song);

        $request->validate([
            'category' => 'required|in:adoration,louange,folklore',
            'title' => 'required|string',
            'audio_file_path' => 'required|string',
        ]);

        $song->update($request->only(['category', 'title', 'audio_file_path']));

        return response()->json($song, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Song $song)
    {
        $this->authorize('delete', $song);
        $song->delete();
        return response()->json(null, 204);
    }
}
