<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function documents()
    {
        return $this->belongsToMany(Document::class);
    }

    public function transaction_parties()
    {
        return $this->belongsToMany(TransactionParty::class)->withPivot('party_role');
    }

}
