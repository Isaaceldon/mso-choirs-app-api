<?php

namespace App\Http\Controllers;

use App\Models\Tabernacle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\TabernacleResource;

class TabernacleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tabernacles = Tabernacle::orderBy('created_at', 'desc')->get();
        return TabernacleResource::collection($tabernacles);
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
            'city' => 'required|string',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $tabernacle = Tabernacle::create([
            'name' => $request->name,
            'city' => $request->city,
            'address' => $request->address,
        ]);

        return new TabernacleResource($tabernacle);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tabernacle = Tabernacle::find($id);

        if (!$tabernacle) {
            return response()->json(['message' => 'Tabernacle not found'], Response::HTTP_NOT_FOUND);
        }

        return new TabernacleResource($tabernacle);
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
            'name' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $tabernacle = Tabernacle::find($id);

        if (!$tabernacle) {
            return response()->json(['message' => 'Tabernacle not found'], Response::HTTP_NOT_FOUND);
        }

        $tabernacle->name = $request->name;
        $tabernacle->city = $request->city;
        $tabernacle->address = $request->address;
        $tabernacle->save();

        return new TabernacleResource($tabernacle);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tabernacle = Tabernacle::find($id);

        if (!$tabernacle) {
            return response()->json(['message' => 'Tabernacle not found'], Response::HTTP_NOT_FOUND);
        }

        $tabernacle->delete();

        return response()->json(['message' => 'Tabernacle successfully deleted'], Response::HTTP_OK);
    }
}