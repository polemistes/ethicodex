<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

use App\Models\Document;
use App\Models\Analysis;
use App\Models\Language;
use App\Models\DatingCertainty;
use App\Models\DatingMethod;
use App\Models\AncientProvenance;
use App\Models\AncientProvenanceCertainty;
use App\Models\Cover;
use App\Models\PurchaseParty;
use App\Models\Decoration;
use App\Models\FirstProcurement;
use App\Models\Genre;
use App\Models\Ink;
use App\Models\Image;
use App\Models\LegalClassification;
use App\Models\Material;
use App\Models\ModernCollection;
use App\Models\Pagination;
use App\Models\Paratext;
use App\Models\Purchase;
use App\Models\QuireSignature;
use App\Models\QuireStructure;
use App\Models\Script;
use App\Models\StorageCondition;
use App\Models\Tag;
use App\Models\License;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Document::class);

        $search_standard = $request->get('search_standard', "");
        $search_shelf = $request->get('search_shelf', "");
        $search_pub = $request->get('search_pub', "");
        $search_lang = $request->get('search_lang', "");
        $search_from = $request->get('search_from', "");
        $search_to = $request->get('search_to', "");
        $documents = Document::query()
            ->when($search_standard, function ($query, $search_standard) {
                $query->where('standard_name', 'like', "%{$search_standard}%");
            })
            ->when($search_shelf, function ($query, $search_shelf) {
                $query->where('current_shelfmarks', 'like', "%{$search_shelf}%");
            })
            ->when($search_pub, function ($query, $search_pub) {
                $query->where('publication', 'like', "%{$search_pub}%");
            })
            ->when($search_lang, function ($query, $search_lang) {
                $query->where('language_comment', 'like', "%{$search_lang}%");
            })
            ->when($search_from, function ($query, $search_from) {
                $query->where('end_year', '>=', $search_from);
            })
            ->when($search_to, function ($query, $search_to) {
                $query->where('start_year', '<=', $search_to);
            })
            ->paginate(10)->withQueryString();

        return (Inertia::render('Codices', [
            'documents' => $documents,
        ]));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Document::class);

        $document = Document::create();
        return Redirect::route('CodexEdit', $document);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document  $document)
    {
    $this->authorize('view', $document);

    return (Inertia::render('CodexShow', [
            'document' => $document,
            'analyses' => $document->analyses()->get()->makeHidden('pivot'),
            'analyses_all' => Analysis::all(),
            'ancient_provenances' => AncientProvenance::all(),
            'ancient_provenance' => $document->ancient_provenance()->get(),
            'ancient_provenance_certainties' => AncientProvenanceCertainty::all(),
            'ancient_provenance_certainty' => $document->ancient_provenance_certainty()->get(),
            'covers' => Cover::all(),
            'cover' => $document->cover()->get(),
            'dating_certainties' => DatingCertainty::all(),
            'dating_certainty' => $document->dating_certainty()->get(),
            'dating_methods' => DatingMethod::all(),
            'dating_method' => $document->dating_method()->get(),
            'decorations' => $document->decorations()->get()->makeHidden('pivot'),
            'decorations_all' => Decoration::all(),
            'first_procurement' => $document->first_procurement()->get(),
            'first_procurements' => FirstProcurement::all(),
            'genres' => $document->genres()->get()->makeHidden('pivot'),
            'genres_all' => Genre::all(),
            'images' => $document->images()->get(),
            'inks' => Ink::all(),
            'ink' => $document->ink()->get(),
            'languages' => $document->languages()->get()->makeHidden('pivot'),
            'languages_all' => Language::all(),
            'legal_classifications' => LegalClassification::all(),
            'legal_classification' => $document->legal_classification()->get(),
            'licenses' => License::all(),
            'materials' => Material::all(),
            'material' => $document->material()->get(),
            'modern_collections' => ModernCollection::all(),
            'modern_collection' => $document->modern_collection()->get(),
            'paginations' => Pagination::all(),
            'pagination' => $document->pagination()->get(),
            'paratexts' => $document->paratexts()->get()->makeHidden('pivot'),
            'paratexts_all' => Paratext::all(),
            'purchases' => $document->purchases()->get()->makeHidden('pivot'),
            'purchases_all' => Purchase::all(),
            'quire_signatures' => QuireSignature::all(),
            'quire_signature' => $document->quire_signature()->get(),
            'quire_structures' => QuireStructure::all(),
            'quire_structure' => $document->quire_structure()->get(),
            'scripts' => $document->scripts()->get()->makeHidden('pivot'),
            'scripts_all' => Script::all(),
            'storage_conditions' => StorageCondition::all(),
            'storage_condition' => $document->storage_condition()->get(),
            'tags' => $document->tags()->get()->makeHidden('pivot'),
            'tags_all' => Tag::all(),
        ]));
    }

    public function edit(Document $document)
    {
        $this->authorize('update', $document);

        return (Inertia::render('CodexEdit', [
            'document' => $document,
            'analyses' => $document->analyses()->get()->makeHidden('pivot'),
            'analyses_all' => Analysis::all(),
            'ancient_provenances' => AncientProvenance::all(),
            'ancient_provenance' => $document->ancient_provenance()->get(),
            'ancient_provenance_certainties' => AncientProvenanceCertainty::all(),
            'ancient_provenance_certainty' => $document->ancient_provenance()->get(),
            'covers' => Cover::all(),
            'cover' => $document->cover()->get(),
            'dating_certainties' => DatingCertainty::all(),
            'dating_certainty' => $document->dating_certainty()->get(),
            'dating_methods' => DatingMethod::all(),
            'dating_method' => $document->dating_method()->get(),
            'decorations' => $document->decorations()->get()->makeHidden('pivot'),
            'decorations_all' => Decoration::all(),
            'first_procurement' => $document->first_procurement()->get(),
            'first_procurements' => FirstProcurement::all(),
            'genres' => $document->genres()->get()->makeHidden('pivot'),
            'genres_all' => Genre::all(),
            'images' => $document->images()->get(),
            'inks' => Ink::all(),
            'ink' => $document->ink()->get(),
            'languages' => $document->languages()->get()->makeHidden('pivot'),
            'languages_all' => Language::all(),
            'legal_classifications' => LegalClassification::all(),
            'legal_classification' => $document->legal_classification()->get(),
            'licenses' => License::all(),
            'materials' => Material::all(),
            'material' => $document->material()->get(),
            'modern_collections' => ModernCollection::all(),
            'modern_collection' => $document->modern_collection()->get(),
            'paginations' => Pagination::all(),
            'pagination' => $document->pagination()->get(),
            'paratexts' => $document->paratexts()->get()->makeHidden('pivot'),
            'paratexts_all' => Paratext::all(),
            'purchases' => $document->purchases()->get()->makeHidden('pivot'),
            'purchases_all' => Purchase::all(),
            'quire_signatures' => QuireSignature::all(),
            'quire_signature' => $document->quire_signature()->get(),
            'quire_structures' => QuireStructure::all(),
            'quire_structure' => $document->quire_structure()->get(),
            'scripts' => $document->scripts()->get()->makeHidden('pivot'),
            'scripts_all' => Script::all(),
            'storage_conditions' => StorageCondition::all(),
            'storage_condition' => $document->storage_condition()->get(),
            'tags' => $document->tags()->get()->makeHidden('pivot'),
            'tags_all' => Tag::all(),
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
        $this->authorize('update', $document);
        $fields = $request->validate([
            'id' => 'required',
            'standard_name' => 'nullable',
            'other_names' => 'nullable',
            'publication' => 'nullable',
            'current_shelfmarks' => 'nullable',
            'trismegistos_id' => 'nullable',
            'title' => 'nullable',
            'genres' => 'nullable',
            'ancient_author' => 'nullable',
            'tags' => 'nullable',
            'content_description' => 'nullable',
            'start_year' => 'nullable',
            'end_year' => 'nullable',
            'languages' => 'nullable',
            'dating_method_id' => 'nullable',
            'dating_certainty_id' => 'nullable',
            'dating_comment' => 'nullable',
            'bibliography' => 'nullable',
            'internal_comment' => 'nullable',
            'general_comment' => 'nullable',
            'material_id' => 'nullable',
            'full_page_width' => 'nullable',
            'full_page_height' => 'nullable',
            'upper_margin' => 'nullable',
            'lower_margin' => 'nullable',
            'inner_margin' => 'nullable',
            'outer_margin' => 'nullable',
            'full_text_block_width' => 'nullable',
            'full_text_block_height' => 'nullable',
            'measurement_comment' => 'nullable',
            'scripts' => 'nullable',
            'hand_number' => 'nullable',
            'script_description' => 'nullable',
            'paratexts' => 'nullable',
            'decorations' => 'nullable',
            'pagination_id' => 'nullable',
            'cover_id' => 'nullable',
            'ink_id' => 'nullable',
            'quire_signature_id' => 'nullable',
            'quire_structure_id' => 'nullable',
            'quire_number' => 'nullable',
            'bifolia' => 'nullable',
            'quire_comment' => 'nullable',
            'binding_description' => 'nullable',
            'storage_condition_id' => 'nullable',
            'conservation_history' => 'nullable',
            'analyses' => 'nullable',
            'analyses_comment' => 'nullable',
            'ancient_provenance_id' => 'nullable',
            'ancient_provenance_certainty_id' => 'nullable',
            'ancient_provenance_comment' => 'nullable',
            'first_procurement_id' => 'nullable',
            'modern_collection_id' => 'nullable',
            'legal_classification_id' => 'nullable',
            'legal_classification_explanation' => 'nullable',
            'purchases' => 'nullable',
            'images_info' => 'nullable',
            'images' => 'nullable',
        ]);

        $document->standard_name = $fields['standard_name'];
        $document->other_names = $fields['other_names'];
        $document->publication = $fields['publication'];
        $document->current_shelfmarks = $fields['current_shelfmarks'];
        $document->trismegistos_id = $fields['trismegistos_id'];
        $document->title = $fields['title'];
        $document->ancient_author = $fields['ancient_author'];
        $document->content_description = $fields['content_description'];
        $document->start_year = $fields['start_year'];
        $document->end_year = $fields['end_year'];
        $document->dating_method_id = $fields['dating_method_id'];
        $document->dating_certainty_id = $fields['dating_certainty_id'];
        $document->dating_comment = $fields['dating_comment'];
        $document->bibliography = $fields['bibliography'];
        $document->internal_comment = $fields['internal_comment'];
        $document->general_comment = $fields['general_comment'];
        $document->material_id = $fields['material_id'];
        $document->full_page_width = $fields['full_page_width'];
        $document->full_page_height = $fields['full_page_height'];
        $document->upper_margin = $fields['upper_margin'];
        $document->lower_margin = $fields['lower_margin'];
        $document->inner_margin = $fields['inner_margin'];
        $document->outer_margin = $fields['outer_margin'];
        $document->full_text_block_width = $fields['full_text_block_width'];
        $document->full_text_block_height = $fields['full_text_block_height'];
        $document->measurement_comment = $fields['measurement_comment'];
        $document->hand_number = $fields['hand_number'];
        $document->script_description = $fields['script_description'];
        $document->pagination_id = $fields['pagination_id'];
        $document->cover_id = $fields['cover_id'];
        $document->ink_id = $fields['ink_id'];
        $document->quire_signature_id = $fields['quire_signature_id'];
        $document->quire_structure_id = $fields['quire_structure_id'];
        $document->quire_number = $fields['quire_number'];
        $document->bifolia = $fields['bifolia'];
        $document->quire_comment = $fields['quire_comment'];
        $document->binding_description = $fields['binding_description'];
        $document->storage_condition_id = $fields['storage_condition_id'];
        $document->conservation_history = $fields['conservation_history'];
        $document->analyses_comment = $fields['analyses_comment'];
        $document->ancient_provenance_id = $fields['ancient_provenance_id'];
        $document->ancient_provenance_certainty_id = $fields['ancient_provenance_certainty_id'];
        $document->ancient_provenance_comment = $fields['ancient_provenance_comment'];
        $document->first_procurement_id = $fields['first_procurement_id'];
        $document->modern_collection_id = $fields['modern_collection_id'];
        $document->legal_classification_id = $fields['legal_classification_id'];
        $document->legal_classification_explanation = $fields['legal_classification_explanation'];
        $document->images_info = $fields['images_info'];

        $document->languages()->sync(array_column($fields['languages'], 'id'));
        $document->scripts()->sync(array_column($fields['scripts'], 'id'));
        $document->paratexts()->sync(array_column($fields['paratexts'], 'id'));
        $document->decorations()->sync(array_column($fields['decorations'], 'id'));
        $document->analyses()->sync(array_column($fields['analyses'], 'id'));
        $document->purchases()->sync(array_column($fields['purchases'], 'id'));
        $document->tags()->sync(array_column($fields['tags'], 'id'));
        $document->genres()->sync(array_column($fields['genres'], 'id'));
        
        $document->save();

        $img_fields = $request->validate([
            'images' => 'nullable',
        ]);
    
        foreach($document->images as $image) {
            $image->delete();
        }

        foreach($img_fields['images'] as $image) {
            Image::create([
                'description' => $image['description'],
                'filename' => $image['filename'],
                'document_id' => $image['document_id'],
                'license_id' => $image['license_id'],
                'source' => $image['source'],
            ]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        $document->delete();
        return redirect()->back();
    }
}
