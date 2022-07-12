<?php

namespace App\Http\Controllers;

use App\Models\ModernCollection;
use Illuminate\Http\Request;
use App\Models\Document;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class ModernCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', ModernCollection::class);
        $modern_collections = ModernCollection::with('documents')->get()->sortBy('name');

        return (Inertia::render('ModernCollections', [
            'modern_collections' => $modern_collections,
        ]));        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', ModernCollection::class);

        return (Inertia::render('ModernCollectionNew', [
            'modern_collections_all' => ModernCollection::all(),
            'documents_all' => Document::all(),
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', ModernCollection::class);

        $modernCollection = ModernCollection::create($request->validate([
            'name' => 'nullable',
            'description' => 'nullable',
        ]));

        $modernCollection->save();

        $fields = $request->validate([
            'documents' => 'nullable',
        ]);
    
        foreach($fields['documents'] as $document) {
            $doc = Document::find($document['id']);
            $doc->modern_collection_id = $modernCollection->id;
            $doc->save();
        }


        return Redirect::route('ModernCollections');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ModernCollection  $modernCollection
     * @return \Illuminate\Http\Response
     */
    public function show(ModernCollection $modernCollection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ModernCollection  $modernCollection
     * @return \Illuminate\Http\Response
     */
    public function edit(ModernCollection $modernCollection)
    {
        $this->authorize('update', $modernCollection);
        
        return (Inertia::render('ModernCollectionEdit', [
            'modern_collection' => $modernCollection,
            'modern_collections_all' => ModernCollection::all(),
            'documents' => $modernCollection->documents()->get(),
            'documents_all' => Document::all(),
        ]));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ModernCollection  $modernCollection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ModernCollection $modernCollection)
    {
        $this->authorize('update', $modernCollection);

        $fields = $request->validate([
            'name' => 'nullable',
            'description' => 'nullable',
            'documents' => 'nullable',
        ]);

        $modernCollection->name = $fields['name'];
        $modernCollection->description = $fields['description'];
        $modernCollection->save();

        foreach($modernCollection->documents as $doc) {
            $doc->modern_collection_id = null;
            $doc->save();
        }
    
        foreach($fields['documents'] as $document) {
            $doc = Document::find($document['id']);
            $doc->modern_collection_id = $modernCollection->id;
            $doc->save();
        }

        return Redirect::route('ModernCollections');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ModernCollection  $modernCollection
     * @return \Illuminate\Http\Response
     */
    public function destroy(ModernCollection $modernCollection)
    {
        $this->authorize('delete', $modernCollection);
        
        Document::where('modern_collection_id', $modernCollection->id)->update(['modern_collection_id' => null]);
        $modernCollection->delete();

        return Redirect::route('ModernCollections');
    }
}
