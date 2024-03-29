<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function documents()
    {
        return $this->belongsToMany(Document::class);
    }

    public function purchase_parties()
    {
        return $this->belongsToMany(PurchaseParty::class)->withPivot('party_role');
    }

}
