<?php

namespace App\Http\Controllers;

use App\Models\PurchaseParty;
use App\Models\Purchase;

use Illuminate\Http\Request;

use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;


class PurchasePartyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', PurchaseParty::class);

        $purchase_parties = PurchaseParty::with('purchases')->orderBy('name')->get();

        return (Inertia::render('PurchaseParties', [
            'purchase_parties' => $purchase_parties,
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', PurchaseParty::class);

        return (Inertia::render('PurchasePartyNew', [
            'purchases_all' => Purchase::all(),
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
        $this->authorize('create', PurchaseParty::class);

        $purchaseParty = PurchaseParty::create($request->validate([
            'name' => 'nullable',
            'description' => 'nullable',
            'institution' => 'nullable',
        ]));
        $purchaseParty->save();

        $purchases = [];
        foreach($request->validate(['purchases' => 'nullable'])['purchases'] as $purchase) {
            $purchases[$purchase['id']] = ['party_role' => $purchase['party_role']];
        }

        $purchaseParty->purchases()->sync($purchases);
 
        return Redirect::route('PurchaseParties');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseParty  $purchaseParty
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseParty $purchaseParty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseParty  $purchaseParty
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseParty $purchaseParty)
    {
        $this->authorize('update', $purchaseParty);

        return (Inertia::render('PurchasePartyEdit', [
            'purchase_party' => $purchaseParty,
            'purchases' => $purchaseParty->purchases()->get(),
            'purchases_all' => Purchase::all(),
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseParty  $purchaseParty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseParty $purchaseParty)
    {
        $this->authorize('update', $purchaseParty);

        $fields = $request->validate([
            'name' => 'nullable',
            'description' => 'nullable',
            'institution' => 'nullable',
            'purchases' => 'nullable',
        ]);

        $purchaseParty->name = $fields['name'];
        $purchaseParty->description = $fields['description'];
        $purchaseParty->institution = $fields['institution'] ? 1 : 0;
        $purchaseParty->save();

        $purchases = [];
        foreach($fields['purchases'] as $purchase) {
            $purchases[$purchase['id']] = ['party_role' => $purchase['party_role']];
        }

        $purchaseParty->purchases()->sync($purchases);

        return Redirect::route('PurchaseParties');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseParty  $purchaseParty
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseParty $purchaseParty)
    {
        $this->authorize('delete', $purchaseParty);

        $purchaseParty->purchases()->detach();
        $purchaseParty->delete();
        return Redirect::route('PurchaseParties');
    }
}
