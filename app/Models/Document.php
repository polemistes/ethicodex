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

    public function first_procurement()
    {
        return $this->belongsTo(FirstProcurement::class);
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

    public function dating_method()
    {
        return $this->belongsTo(DatingMethod::class);
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

    public function modern_collection()
    {
        return $this->belongsTo(ModernCollection::class);
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

    public function license()
    {
        return $this->belongsTo(License::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }
}
