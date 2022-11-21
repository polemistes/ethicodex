<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function languages()
    {
        return $this->belongsToMany(Language::class);
    }

    public function decorations()
    {
        return $this->belongsToMany(Decoration::class);
    }

    public function paratexts()
    {
        return $this->belongsToMany(Paratext::class);
    }

    public function scripts()
    {
        return $this->belongsToMany(Script::class);
    }

    public function analyses()
    {
        return $this->belongsToMany(Analysis::class);
    }

    public function purchases()
    {
        return $this->belongsToMany(Purchase::class);
    }

    public function ancient_provenance()
    {
        return $this->belongsTo(AncientProvenance::class);
    }

    public function ancient_provenance_certainty()
    {
        return $this->belongsTo(AncientProvenanceCertainty::class);
    }

    public function cover()
    {
        return $this->belongsTo(Cover::class);
    }

    public function dating_certainty()
    {
        return $this->belongsTo(DatingCertainty::class);
    }

    public function dating_methods()
    {
        return $this->belongsToMany(DatingMethod::class);
    }

    public function ink()
    {
        return $this->belongsTo(Ink::class);
    }

    public function legal_classification()
    {
        return $this->belongsTo(LegalClassification::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function modern_collections()
    {
        return $this->belongsToMany(ModernCollection::class);
    }

    public function pagination()
    {
        return $this->belongsTo(Pagination::class);
    }

    public function quire_signature()
    {
        return $this->belongsTo(QuireSignature::class);
    }

    public function quire_structure()
    {
        return $this->belongsTo(QuireStructure::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function storage_condition()
    {
        return $this->belongsTo(StorageCondition::class);
    }


    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function critical_symbols()
    {
        return $this->belongsToMany(CriticalSymbol::class);
    }

    public function punctuations()
    {
        return $this->belongsToMany(Punctuation::class);
    }

    public function diacritics()
    {
        return $this->belongsToMany(Diacritic::class);
    }
}
