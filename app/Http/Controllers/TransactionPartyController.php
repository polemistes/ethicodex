<?php

namespace App\Http\Controllers;

use App\Models\TransactionParty;
use App\Models\Transaction;

use Illuminate\Http\Request;

use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;


class TransactionPartyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', TransactionParty::class);

        $transaction_parties = TransactionParty::with('transactions')->orderBy('name')->get();

        return (Inertia::render('TransactionParties', [
            'transaction_parties' => $transaction_parties,
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', TransactionParty::class);

        return (Inertia::render('TransactionPartyNew', [
            'transactions_all' => Transaction::all(),
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
        $this->authorize('create', TransactionParty::class);

        $transactionParty = TransactionParty::create($request->validate([
            'name' => 'nullable',
            'description' => 'nullable',
            'institution' => 'nullable',
        ]));
        $transactionParty->save();

        $transactions = [];
        foreach($request->validate(['transactions' => 'nullable'])['transactions'] as $transaction) {
            $transactions[$transaction['id']] = ['party_role' => $transaction['party_role']];
        }

        $transactionParty->transactions()->sync($transactions);
 
        return Redirect::route('TransactionParties');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TransactionParty  $transactionParty
     * @return \Illuminate\Http\Response
     */
    public function show(TransactionParty $transactionParty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TransactionParty  $transactionParty
     * @return \Illuminate\Http\Response
     */
    public function edit(TransactionParty $transactionParty)
    {
        $this->authorize('update', $transactionParty);

        return (Inertia::render('TransactionPartyEdit', [
            'transaction_party' => $transactionParty,
            'transactions' => $transactionParty->transactions()->get(),
            'transactions_all' => Transaction::all(),
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TransactionParty  $transactionParty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TransactionParty $transactionParty)
    {
        $this->authorize('update', $transactionParty);

        $fields = $request->validate([
            'name' => 'nullable',
            'description' => 'nullable',
            'institution' => 'nullable',
            'transactions' => 'nullable',
        ]);

        $transactionParty->name = $fields['name'];
        $transactionParty->description = $fields['description'];
        $transactionParty->institution = $fields['institution'] ? 1 : 0;
        $transactionParty->save();

        $transactions = [];
        foreach($fields['transactions'] as $transaction) {
            $transactions[$transaction['id']] = ['party_role' => $transaction['party_role']];
        }

        $transactionParty->transactions()->sync($transactions);

        return Redirect::route('TransactionParties');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TransactionParty  $transactionParty
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransactionParty $transactionParty)
    {
        $this->authorize('delete', $transactionParty);

        $transactionParty->transactions()->detach();
        $transactionParty->delete();
        return Redirect::route('TransactionParties');
    }
}
