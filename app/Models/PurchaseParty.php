<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseParty extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function purchases()
    {
        return $this->belongsToMany(Purchase::class)->withPivot('party_role');
    }
}
