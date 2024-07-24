<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionParty;
use App\Models\Document;

use Illuminate\Http\Request;

use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Transaction::class);

        $transactions = Transaction::with('transaction_parties', 'documents')->orderBy('year')->orderBy('month')->orderBy('day')->get();

        return (Inertia::render('Transactions', [
            'transactions' => $transactions,
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Transaction::class);

        return (Inertia::render('TransactionNew', [
            'documents_all' => Document::all(),
            'transaction_parties_all' => TransactionParty::all(),
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
        $this->authorize('create', Transaction::class);

        $transaction = Transaction::create($request->validate([
            'name' => 'nullable',
            'year' => 'nullable',
            'month' => 'nullable',
            'day' => 'nullable',
            'description' => 'nullable',
            'bibliography' => 'nullable',
        ]));
        $transaction->save();
        $transaction->documents()->sync(array_column($request->validate(['documents' => 'nullable'])['documents'], 'id'));

        $transaction_parties = [];

        foreach ($request->validate(['transaction_parties' => 'nullable'])['transaction_parties'] as $party) {
            $transaction_parties[$party['id']] = ['party_role' => $party['party_role']];
        }

        $transaction->transaction_parties()->sync($transaction_parties);

        return Redirect::route('Transactions');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        return (Inertia::render('TransactionEdit', [
            'transaction' => $transaction,
            'documents' => $transaction->documents()->get(['documents.id', 'standard_name', 'trismegistos_id'])->makeHidden('pivot'),
            'documents_all' => Document::all(['id', 'standard_name', 'trismegistos_id']),
            'transaction_parties' => $transaction->transaction_parties()->get(),
            'transaction_parties_all' => TransactionParty::all(),
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $fields = $request->validate([
            'name' => 'nullable',
            'year' => 'nullable',
            'month' => 'nullable',
            'day' => 'nullable',
            'description' => 'nullable',
            'bibliography' => 'nullable',
            'documents' => 'nullable',
            'transaction_parties' => 'nullable',
        ]);

        $transaction->name = $fields['name'];
        $transaction->year = $fields['year'];
        $transaction->month = $fields['month'];
        $transaction->day = $fields['day'];
        $transaction->description = $fields['description'];
        $transaction->bibliography = $fields['bibliography'];
        $transaction->save();

        $documents = $fields['documents'];

        $transaction_parties = [];
        foreach ($fields['transaction_parties'] as $party) {
            $transaction_parties[$party['id']] = ['party_role' => $party['party_role']];
        }

        $transaction->documents()->sync(array_column($documents, 'id'));
        $transaction->transaction_parties()->sync($transaction_parties);

        return Redirect::route('Transactions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        $transaction->documents()->detach();
        $transaction->transaction_parties()->detach();
        $transaction->delete();
        return Redirect::route('Transactions');
    }
}
