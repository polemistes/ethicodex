<?php

namespace App\Http\Controllers;

use App\Models\Work;
use App\Models\Author;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Illuminate\Support\Facades\Redirect;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {     
        $this->authorize('viewAny', Work::class);
        $works = Work::with('author')->orderBy('name')->get();
        $authors = Author::orderBy('name')->get();
        return Inertia::render('Works', [
            'works' => $works,
            'authors' => $authors,
        ]);
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
        $this->authorize('create', Work::class);

        $work = Work::create($request->validate([
            'name' => '',
            'altnames' => 'nullable',
            'author_id' => 'nullable',
            'description' => 'nullable',
        ]));
        $work->save();

        return Redirect::route('Works');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function show(Work $work)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function edit(Work $work)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Work $work)
    {
        $this->authorize('update', $work);

        $fields =  $request->validate([
            'name' => '',
            'altnames' => 'nullable',
            'author_id' => 'nullable',
            'description' => 'nullable',
        ]);

        $work->name = $fields['name'];
        $work->altnames = $fields['altnames'];
        $work->author_id = $fields['author_id'];
        $work->description = $fields['description'];
        $work->save();

        return Redirect::route('Works');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function destroy(Work $work)
    {
        $work->documents()->detach();
        $work->delete();
    }
}
