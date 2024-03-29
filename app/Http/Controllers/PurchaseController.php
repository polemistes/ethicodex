<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseParty;
use App\Models\Document;

use Illuminate\Http\Request;

use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Purchase::class);

        $purchases = Purchase::with('purchase_parties', 'documents')->orderBy('year')->orderBy('month')->orderBy('day')->get();

        return (Inertia::render('Purchases', [
            'purchases' => $purchases,
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Purchase::class);

        return (Inertia::render('PurchaseNew', [
            'documents_all' => Document::all(),
            'purchase_parties_all' => PurchaseParty::all(),
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
        $this->authorize('create', Purchase::class);

        $purchase = Purchase::create($request->validate([
            'name' => 'nullable',
            'year' => 'nullable',
            'month' => 'nullable',
            'day' => 'nullable',
            'description' => 'nullable',
            'bibliography' => 'nullable',
        ]));
        $purchase->save();
        $purchase->documents()->sync(array_column($request->validate(['documents' => 'nullable'])['documents'], 'id'));

        $purchase_parties = [];

        foreach ($request->validate(['purchase_parties' => 'nullable'])['purchase_parties'] as $party) {
            $purchase_parties[$party['id']] = ['party_role' => $party['party_role']];
        }

        $purchase->purchase_parties()->sync($purchase_parties);

        return Redirect::route('Purchases');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        $this->authorize('update', $purchase);

        return (Inertia::render('PurchaseEdit', [
            'purchase' => $purchase,
            'documents' => $purchase->documents()->get(['documents.id', 'standard_name', 'trismegistos_id'])->makeHidden('pivot'),
            'documents_all' => Document::all(['id', 'standard_name', 'trismegistos_id']),
            'purchase_parties' => $purchase->purchase_parties()->get(),
            'purchase_parties_all' => PurchaseParty::all(),
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        $this->authorize('update', $purchase);

        $fields = $request->validate([
            'name' => 'nullable',
            'year' => 'nullable',
            'month' => 'nullable',
            'day' => 'nullable',
            'description' => 'nullable',
            'bibliography' => 'nullable',
            'documents' => 'nullable',
            'purchase_parties' => 'nullable',
        ]);

        $purchase->name = $fields['name'];
        $purchase->year = $fields['year'];
        $purchase->month = $fields['month'];
        $purchase->day = $fields['day'];
        $purchase->description = $fields['description'];
        $purchase->bibliography = $fields['bibliography'];
        $purchase->save();

        $documents = $fields['documents'];

        $purchase_parties = [];
        foreach ($fields['purchase_parties'] as $party) {
            $purchase_parties[$party['id']] = ['party_role' => $party['party_role']];
        }

        $purchase->documents()->sync(array_column($documents, 'id'));
        $purchase->purchase_parties()->sync($purchase_parties);

        return Redirect::route('Purchases');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        $this->authorize('delete', $purchase);

        $purchase->documents()->detach();
        $purchase->purchase_parties()->detach();
        $purchase->delete();
        return Redirect::route('Purchases');
    }
}
