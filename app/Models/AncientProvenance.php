<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AncientProvenance extends Model
{
    use HasFactory;

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

/**
     * This will give model's Parent 
     * @return BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(AncientProvenance::class, 'parent_id');
    }

    /**
     * This will give model's Parent, Parent's parent, and so on until root.  
     * @return BelongsTo
     */
    public function parentRecursive()
    {
        return $this->belongsTo(AncientProvenance::class, 'parent_id')->with('parentRecursive');
    }

    /**
     * Get current model's all recursive parents in a collection in flat structure.
     */
    public function parentRecursiveFlatten()
    {
        $result = collect();
        $item = $this->parentRecursive;
        if ($item instanceof AncientProvenance) {
            $result->push($item);
            $result = $result->merge($item->parentRecursiveFlatten());
        }
        return $result;
    }

    /**
     * This will give model's Children
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * This will give model's Children, Children's Children and so on until last node. 
     * @return HasMany
     */
    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }




}
