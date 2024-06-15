<?php

namespace App\Http\Controllers;

use App\Models\Choir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\ChoirResource;

class ChoirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $choirs = Choir::all();
        return ChoirResource::collection($choirs);
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
            'name' => 'required|string',
            'members' => 'required|integer',
            'tabernacle_id' => 'required|exists:tabernacles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $choir = Choir::create([
            'name' => $request->name,
            'members' => $request->members,
            'tabernacle_id' => $request->tabernacle_id,
        ]);

        return new ChoirResource($choir);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $choir = Choir::find($id);

        if (!$choir) {
            return response()->json(['message' => 'Choir not found'], Response::HTTP_NOT_FOUND);
        }

        return new ChoirResource($choir);
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
            'name' => 'string',
            'members' => 'integer',
            'tabernacle_id' => 'exists:tabernacles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $choir = Choir::find($id);

        if (!$choir) {
            return response()->json(['message' => 'Choir not found'], Response::HTTP_NOT_FOUND);
        }

        $choir->fill($request->all());
        $choir->save();

        return new ChoirResource($choir);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $choir = Choir::find($id);

        if (!$choir) {
            return response()->json(['message' => 'Choir not found'], Response::HTTP_NOT_FOUND);
        }

        $choir->delete();

        return response()->json(['message' => 'Choir successfully deleted'], Response::HTTP_OK);
    }
}