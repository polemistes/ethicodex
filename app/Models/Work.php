<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class)->withPivot('passages');
    }
}
