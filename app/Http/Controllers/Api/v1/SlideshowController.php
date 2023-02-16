<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SlideshowRequest;
use App\Models\Slideshow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SlideshowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slideshows = Slideshow::all();
        return response()->json([
            'data' => $slideshows,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SlideshowRequest $request)
    {
        $data = $request->all();

        if ($request->has('photopath')) {
            $fname = time();
            $fexe = $request->file('photopath')->extension();
            $fpath = "$fname.$fexe";

            $request->file('photopath')->move(public_path() . '/public/slideshows/', $fpath);

            $data['photopath'] = 'slideshows/' . $fpath;
        }

        $slideshow = Slideshow::create($data);
        return response()->json([
            'data' => $slideshow,
            'message' => 'Slideshow created successfully',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Slideshow  $slideshow
     * @return \Illuminate\Http\Response
     */
    public function show(Slideshow $slideshow)
    {
        return response()->json([
            'data' => $slideshow,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Slideshow  $slideshow
     * @return \Illuminate\Http\Response
     */
    public function update(SlideshowRequest $request, Slideshow $slideshow)
    {
        $data = $request->all();

        if ($data['photopath'] != '') {
            if ($request->has('photopath')) {
                $fname = time();
                $fexe = $request->file('photopath')->extension();
                $fpath = "$fname.$fexe";

                if ($slideshow->photopath) {
                    File::delete('public/' . $slideshow->photopath);
                }
                $request->file('photopath')->move(public_path() . '/public/slideshows/', $fpath);
                $data['photopath'] = 'slideshows/' . $fpath;
            }
        }

        if ($data['photopath'] == '') {
            $data['photopath'] = $slideshow->photopath;
        }

        $slideshow->update($data);
        return response()->json([
            'data' => $slideshow,
            'message' => 'Slideshow updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slideshow  $slideshow
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slideshow $slideshow)
    {
        File::delete('public/' . $slideshow->photopath);
        $slideshow->delete();
        return response()->json([
            'message' => 'Slideshow deleted successfully',
        ], 200);
    }
}
