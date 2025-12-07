<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Document;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        $this->authorize('create', Image::class);
        $request->validate([
            'images' => 'required|array',
            'document_id' => 'nullable',
        ]);

        $document_id = $request->document_id;

        foreach($request->images as $image) {
            $path=$image->store('doc_images', 'public');
            $new_image = Image::create([
                'filename' => $path,
                'description' => null,
                'visible' => true,
                'micrograph' => false,
                'license_id' => null,
                'source' => null,
                'document_id' => $document_id,
            ]);
        }
        return Redirect::route('CodexEdit', ['document' => $document_id]);    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Image $image)
    {
        $document_id = $request->document_id;

        $this->authorize('delete', $image);
        Storage::disk('public')->delete($image->filename);
        $image->delete();
        return Redirect::route('CodexEdit', ['document' => $document_id]);    
    }
}
