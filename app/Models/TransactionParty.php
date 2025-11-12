<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionParty extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class)->withPivot('party_role')->with('documents');
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class);
    }
    
}
