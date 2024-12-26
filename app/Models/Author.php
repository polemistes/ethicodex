<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function works()
    {
        return $this->hasMany(Work::class);
    }

    public function documents()
    {
        $works = $this->works()->with('documents')->get();
         foreach ($works as $work) {
            $documents = array_merge($documents, $work->relations->documents->items);
        }
        return $documents;
    }


}
