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
use App\Models\TransactionParty;
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
use App\Models\Transaction;
use App\Models\QuireSignature;
use App\Models\QuireStructure;
use App\Models\Script;
use App\Models\StorageCondition;
use App\Models\Tag;
use App\Models\License;
use App\Models\CriticalSymbol;
use App\Models\Punctuation;
use App\Models\Diacritic;
use App\Models\GregorysRule;

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
        //      $this->authorize('viewAny', Document::class)

        $role_id = $request->user() ? $request->user()->role_id : 0;

        $search = $request->validate([
            'show_publication' => 'nullable',
            'show_content' => 'nullable',
            'show_dating' => 'nullable',
            'show_materiality' => 'nullable',
            'show_measurement' => 'nullable',
            'show_palaeography' => 'nullable',
            'show_consanal' => 'nullable',
            'show_provenance' => 'nullable',

            'fulltext' => 'nullable',
            's_standard_name' => 'nullable',
            's_publication' => 'nullable',
            's_current_shelfmarks' => 'nullable',
            's_trismegistos_id' => 'nullable',
            's_title' => 'nullable',
            's_ancient_author' => 'nullable',
            's_genres' => 'nullable',
            's_genres_incl' => 'nullable',
            's_tags' => 'nullable',
            's_tags_incl' => 'nullable',
            's_earliest_date' => 'nullable',
            's_latest_date' => 'nullable',
            's_exclusive_date' => 'nullable',
            's_dating_methods' => 'nullable',
            's_dating_methods_incl' => 'nullable',
            's_dating_certainties' => 'nullable',
            's_materials' => 'nullable',
            's_inks' => 'nullable',
            's_inks_incl' => 'nullable',
            's_covers' => 'nullable',
            's_quire_structures' => 'nullable',
            's_quirenum_min' => 'nullable',
            's_quirenum_max' => 'nullable',
            's_bifolianum_min' => 'nullable',
            's_bifolianum_max' => 'nullable',
            's_full_page_width_min' => 'nullable',
            's_full_page_width_max' => 'nullable',
            's_full_page_height_min' => 'nullable',
            's_full_page_height_max' => 'nullable',
            's_full_text_block_width_min' => 'nullable',
            's_full_text_block_width_max' => 'nullable',
            's_full_text_block_height_min' => 'nullable',
            's_full_text_block_height_max' => 'nullable',
            's_upper_margin_min' => 'nullable',
            's_upper_margin_max' => 'nullable',
            's_lower_margin_min' => 'nullable',
            's_lower_margin_max' => 'nullable',
            's_inner_margin_min' => 'nullable',
            's_inner_margin_max' => 'nullable',
            's_outer_margin_min' => 'nullable',
            's_outer_margin_max' => 'nullable',

            's_full_page_ratio_min' => 'nullable',
            's_full_page_ratio_max' => 'nullable',
            's_full_text_block_ratio_min' => 'nullable',
            's_full_text_block_ratio_max' => 'nullable',
            's_uplow_margins_ratio_min' => 'nullable',
            's_uplow_margins_ratio_max' => 'nullable',
            's_inout_margins_ratio_min' => 'nullable',
            's_inout_margins_ratio_max' => 'nullable',

            's_gregorys_rules' => 'nullable',
            's_columns_min' => 'nullable',
            's_columns_max'=> 'nullable',
            's_columnlines_min'=> 'nullable',
            's_columnlines_max'=> 'nullable',

            's_hand_number_min' => 'nullable',
            's_hand_number_max' => 'nullable',
            's_scripts' => 'nullable',
            's_scripts_incl' => 'nullable',
            's_diacritics' => 'nullable',
            's_diacritics_incl' => 'nullable',
            's_punctuations' => 'nullable',
            's_punctuations_incl' => 'nullable',
            's_paratexts' => 'nullable',
            's_paratexts_incl' => 'nullable',
            's_decorations' => 'nullable',
            's_decorations_incl' => 'nullable',
            's_critical_symbols' => 'nullable',
            's_critical_symbols_incl' => 'nullable',
            's_paginations' => 'nullable',
            's_quire_signatures' => 'nullable',
            's_storage_conditions' => 'nullable',
            's_analyses' => 'nullable',
            's_analyses_incl' => 'nullable',
            's_scientifically_excavated' => 'nullable',
            's_ancient_provenances' => 'nullable',
            's_ancient_provenance_certainties' => 'nullable',
            's_transactions' => 'nullable',
            's_transactions_incl' => 'nullable',
            's_transaction_parties' => 'nullable',
            's_transaction_parties_incl' => 'nullable',
            's_legal_classifications' => 'nullable',
            'sortfield' => 'nullable',
            'reverse' => 'nullable',
        ]);

        $show_publication = $request->session()->get('show_publication');
        $show_content = $request->session()->get('show_content');
        $show_dating = $request->session()->get('show_dating');
        $show_materiality = $request->session()->get('show_materiality');
        $show_measurement = $request->session()->get('show_measurement');
        $show_palaeography = $request->session()->get('show_palaeography');
        $show_consanal = $request->session()->get('show_consanal');
        $show_provenance = $request->session()->get('show_provenance');

        $show_publication = array_key_exists('show_publication', $search) ? $search['show_publication'] : $show_publication;
        $show_content = array_key_exists('show_content', $search) ? $search['show_content'] : $show_content;
        $show_dating = array_key_exists('show_dating', $search) ? $search['show_dating'] : $show_dating;
        $show_materiality = array_key_exists('show_materiality', $search) ? $search['show_materiality'] : $show_materiality;
        $show_measurement = array_key_exists('show_measurement', $search) ? $search['show_measurement'] : $show_measurement;
        $show_palaeography = array_key_exists('show_palaeography', $search) ? $search['show_palaeography'] : $show_palaeography;
        $show_consanal = array_key_exists('show_consanal', $search) ? $search['show_consanal'] : $show_consanal;
        $show_provenance = array_key_exists('show_provenance', $search) ? $search['show_provenance'] : $show_provenance;

        $request->session()->put('show_publication', $show_publication);
        $request->session()->put('show_content', $show_content);
        $request->session()->put('show_dating', $show_dating);
        $request->session()->put('show_materiality', $show_materiality);
        $request->session()->put('show_measurement', $show_measurement);
        $request->session()->put('show_palaeography', $show_palaeography);
        $request->session()->put('show_consanal', $show_consanal);
        $request->session()->put('show_provenance', $show_provenance);

        $sortfield = $request->session()->get('sortfield');
        $sortfield = array_key_exists('sortfield', $search) ? $search['sortfield'] : $sortfield;
  
        $request->session()->put('sortfield', $sortfield);
        switch ($sortfield) {
            case 1:
                $sortby = "standard_name";
                break;
            case 2:
                $sortby = "ancient_author";
                break;
            case 3:
                $sortby = "title";
                break;
            case 4:
                $sortby = "start_year";
                break;
            case 5:
                $sortby = "end_year";
                break;
            default:
                $sortby = "start_year";
        }
 
        $reverse = $request->session()->get('reverse');
        $reverse = array_key_exists('reverse', $search) ? $search['reverse'] : $reverse;
        $request->session()->put('reverse', $reverse);

        $direction = $reverse ? "desc" : "asc";

        $fulltext = array_key_exists('fulltext', $search) ? $search['fulltext'] : null;
        $standard_name = array_key_exists('s_standard_name', $search) ? $search['s_standard_name'] : null;
        $publication = array_key_exists('s_publication', $search) ? $search['s_publication'] : null;
        $current_shelfmarks = array_key_exists('s_current_shelfmarks', $search) ? $search['s_current_shelfmarks'] : null;
        $trismegistos_id = array_key_exists('s_trismegistos_id', $search) ? $search['s_trismegistos_id'] : null;
        $title = array_key_exists('s_title', $search) ? $search['s_title'] : null;
        $ancient_author = array_key_exists('s_ancient_author', $search) ? $search['s_ancient_author'] : null;
        $genres = array_key_exists('s_genres', $search) ? $search['s_genres'] : null;
        $genres_incl = array_key_exists('s_genres_incl', $search) ? $search['s_genres_incl'] : null;
        $tags = array_key_exists('s_tags', $search) ? $search['s_tags'] : null;
        $tags_incl = array_key_exists('s_tags_incl', $search) ? $search['s_tags_incl'] : null;
        $earliest_date = array_key_exists('s_earliest_date', $search) ? $search['s_earliest_date'] : null;
        $latest_date = array_key_exists('s_latest_date', $search) ? $search['s_latest_date'] : null;
        $exclusive_date = array_key_exists('s_exclusive_date', $search) ? $search['s_exclusive_date'] : false;
        $dating_methods = array_key_exists('s_dating_methods', $search) ? $search['s_dating_methods'] : null;
        $dating_methods_incl = array_key_exists('s_dating_methods_incl', $search) ? $search['s_dating_methods_incl'] : null;
        $dating_certainties = array_key_exists('s_dating_certainties', $search) ? $search['s_dating_certainties'] : null;
        $materials = array_key_exists('s_materials', $search) ? $search['s_materials'] : null;
        $inks = array_key_exists('s_inks', $search) ? $search['s_inks'] : null;
        $inks_incl = array_key_exists('s_inks_incl', $search) ? $search['s_inks_incl'] : null;
        $covers = array_key_exists('s_covers', $search) ? $search['s_covers'] : null;
        $quire_structures = array_key_exists('s_quire_structures', $search) ? $search['s_quire_structures'] : null;
        $quirenum_min = array_key_exists('s_quirenum_min', $search) ? $search['s_quirenum_min'] : null;
        $quirenum_max = array_key_exists('s_quirenum_max', $search) ? $search['s_quirenum_max'] : null;
        $bifolianum_min = array_key_exists('s_bifolianum_min', $search) ? $search['s_bifolianum_min'] : null;
        $bifolianum_max = array_key_exists('s_bifolianum_max', $search) ? $search['s_bifolianum_max'] : null;
        $full_page_width_min = array_key_exists('s_full_page_width_min', $search) ? $search['s_full_page_width_min'] : null;
        $full_page_width_max = array_key_exists('s_full_page_width_max', $search) ? $search['s_full_page_width_max'] : null;
        $full_page_height_min = array_key_exists('s_full_page_height_min', $search) ? $search['s_full_page_height_min'] : null;
        $full_page_height_max = array_key_exists('s_full_page_height_max', $search) ? $search['s_full_page_height_max'] : null;
        $full_text_block_width_min = array_key_exists('s_full_text_block_width_min', $search) ? $search['s_full_text_block_width_min'] : null;
        $full_text_block_width_max = array_key_exists('s_full_text_block_width_max', $search) ? $search['s_full_text_block_width_max'] : null;
        $full_text_block_height_min = array_key_exists('s_full_text_block_height_min', $search) ? $search['s_full_text_block_height_min'] : null;
        $full_text_block_height_max = array_key_exists('s_full_text_block_height_max', $search) ? $search['s_full_text_block_height_max'] : null;
        $upper_margin_min = array_key_exists('s_upper_margin_min', $search) ? $search['s_upper_margin_min'] : null;
        $upper_margin_max = array_key_exists('s_upper_margin_max', $search) ? $search['s_upper_margin_max'] : null;
        $lower_margin_min = array_key_exists('s_lower_margin_min', $search) ? $search['s_lower_margin_min'] : null;
        $lower_margin_max = array_key_exists('s_lower_margin_max', $search) ? $search['s_lower_margin_max'] : null;
        $inner_margin_min = array_key_exists('s_inner_margin_min', $search) ? $search['s_inner_margin_min'] : null;
        $inner_margin_max = array_key_exists('s_inner_margin_max', $search) ? $search['s_inner_margin_max'] : null;
        $outer_margin_min = array_key_exists('s_outer_margin_min', $search) ? $search['s_outer_margin_min'] : null;
        $outer_margin_max = array_key_exists('s_outer_margin_max', $search) ? $search['s_outer_margin_max'] : null;

        $full_page_ratio_min = array_key_exists('s_full_page_ratio_min', $search) ? $search['s_full_page_ratio_min'] : null;
        $full_page_ratio_max = array_key_exists('s_full_page_ratio_max', $search) ? $search['s_full_page_ratio_max'] : null;
        $full_text_block_ratio_min = array_key_exists('s_full_text_block_ratio_min', $search) ? $search['s_full_text_block_ratio_min'] : null;
        $full_text_block_ratio_max = array_key_exists('s_full_text_block_ratio_max', $search) ? $search['s_full_text_block_ratio_max'] : null;
        $uplow_margins_ratio_min = array_key_exists('s_uplow_margins_ratio_min', $search) ? $search['s_uplow_margins_ratio_min'] : null;
        $uplow_margins_ratio_max = array_key_exists('s_uplow_margins_ratio_max', $search) ? $search['s_uplow_margins_ratio_max'] : null;
        $inout_margins_ratio_min = array_key_exists('s_inout_margins_ratio_min', $search) ? $search['s_inout_margins_ratio_min'] : null;
        $inout_margins_ratio_max = array_key_exists('s_inout_margins_ratio_max', $search) ? $search['s_inout_margins_ratio_max'] : null;

        $gregorys_rules = array_key_exists('s_gregorys_rules', $search) ? $search['s_gregorys_rules'] : null;
        $columns_min = array_key_exists('s_columns_min', $search) ? $search['s_columns_min'] : null;
        $columns_max = array_key_exists('s_columns_max', $search) ? $search['s_columns_max'] : null;
        $columnlines_min = array_key_exists('s_columnlines_min', $search) ? $search['s_columnlines_min'] : null;
        $columnlines_max = array_key_exists('s_columnlines_max', $search) ? $search['s_columnlines_max'] : null;
        
        $hand_number_min = array_key_exists('s_hand_number_min', $search) ? $search['s_hand_number_min'] : null;
        $hand_number_max = array_key_exists('s_hand_number_max', $search) ? $search['s_hand_number_max'] : null;
        $scripts = array_key_exists('s_scripts', $search) ? $search['s_scripts'] : null;
        $scripts_incl = array_key_exists('s_scripts_incl', $search) ? $search['s_scripts_incl'] : null;
        $diacritics = array_key_exists('s_diacritics', $search) ? $search['s_diacritics'] : null;
        $diacritics_incl = array_key_exists('s_diacritics_incl', $search) ? $search['s_diacritics_incl'] : null;
        $punctuations = array_key_exists('s_punctuations', $search) ? $search['s_punctuations'] : null;
        $punctuations_incl = array_key_exists('s_punctuations_incl', $search) ? $search['s_punctuations_incl'] : null;
        $paratexts = array_key_exists('s_paratexts', $search) ? $search['s_paratexts'] : null;
        $paratexts_incl = array_key_exists('s_paratexts_incl', $search) ? $search['s_paratexts_incl'] : null;
        $decorations = array_key_exists('s_decorations', $search) ? $search['s_decorations'] : null;
        $decorations_incl = array_key_exists('s_decorations_incl', $search) ? $search['s_decorations_incl'] : null;
        $critical_symbols = array_key_exists('s_critical_symbols', $search) ? $search['s_critical_symbols'] : null;
        $critical_symbols_incl = array_key_exists('s_critical_symbols_incl', $search) ? $search['s_critical_symbols_incl'] : null;
        $paginations = array_key_exists('s_paginations', $search) ? $search['s_paginations'] : null;
        $quire_signatures = array_key_exists('s_quire_signatures', $search) ? $search['s_quire_signatures'] : null;
        $storage_conditions = array_key_exists('s_storage_conditions', $search) ? $search['s_storage_conditions'] : null;
        $analyses = array_key_exists('s_analyses', $search) ? $search['s_analyses'] : null;
        $analyses_incl = array_key_exists('s_analyses_incl', $search) ? $search['s_analyses_incl'] : null;
        $scientifically_excavated = array_key_exists('s_scientifically_excavated', $search) ? $search['s_scientifically_excavated'] : null;
        $ancient_provenances = array_key_exists('s_ancient_provenances', $search) ? $search['s_ancient_provenances'] : null;
        $ancient_provenance_certainties = array_key_exists('s_ancient_provenance_certainties', $search) ? $search['s_ancient_provenance_certainties'] : null;
        $transactions = array_key_exists('s_transactions', $search) ? $search['s_transactions'] : null;
        $transactions_incl = array_key_exists('s_transactions_incl', $search) ? $search['s_transactions_incl'] : null;
        $transaction_parties = array_key_exists('s_transaction_parties', $search) ? $search['s_transaction_parties'] : null;
        $transaction_parties_incl = array_key_exists('s_transaction_parties_incl', $search) ? $search['s_transaction_parties_incl'] : null;
        $legal_classifications = array_key_exists('s_legal_classifications', $search) ? $search['s_legal_classifications'] : null;

        $ancient_provenances_original = $ancient_provenances;
        
        if($show_provenance AND $ancient_provenances) {
            $children = [];

            foreach($ancient_provenances as $ancient_provenance) {
                $prov = AncientProvenance::find($ancient_provenance['id']);
                $childrec = $prov->flatChildren();

                foreach($childrec as $child) {
                    $childarr = [
                        "id" => $child->id,
                        "name" => $child->name
                    ];
                    array_push($ancient_provenances, $childarr);
                } 
            }
        }

        $all_documents = Document::query()
            ->when($role_id < 2, function ($query) {
                $query->where('published', '=', true);
            }) 
            ->when($fulltext, function ($query) use ($fulltext, $role_id) {
                $query->where(function ($query) use ($fulltext, $role_id) {
                    $query->where('standard_name', 'like', "%{$fulltext}%")
                        ->orWhere('standard_name', 'like', "%{$fulltext}%")
                        ->orWhere('other_names', 'like', "%{$fulltext}%")
                        ->orWhere('trismegistos_id', 'like', "%{$fulltext}%")
                        ->orWhere('publication', 'like', "%{$fulltext}%")
                        ->orWhere('current_shelfmarks', 'like', "%{$fulltext}%")
                        ->orWhere('title', 'like', "%{$fulltext}%")
                        ->orWhere('ancient_author', 'like', "%{$fulltext}%")
                        ->orWhere('content_description', 'like', "%{$fulltext}%")
                        ->orWhere('language_comment', 'like', "%{$fulltext}%")
                        ->orWhere('dating_comment', 'like', "%{$fulltext}%")
                        ->orWhere('general_comment', 'like', "%{$fulltext}%")
                        ->orWhere('measurement_comment', 'like', "%{$fulltext}%")
                        ->orWhere('full_text_block_comment', 'like', "%{$fulltext}%")
                        ->orWhere('script_description', 'like', "%{$fulltext}%")
                        ->orWhere('decoration_description', 'like', "%{$fulltext}%")
                        ->orWhere('paratext_description', 'like', "%{$fulltext}%")
                        ->orWhere('diacritic_description', 'like', "%{$fulltext}%")
                        ->orWhere('punctuation_description', 'like', "%{$fulltext}%")
                        ->orWhere('critical_symbols_description', 'like', "%{$fulltext}%")
                        ->orWhere('pagination_description', 'like', "%{$fulltext}%")
                        ->orWhere('quire_signatures_description', 'like', "%{$fulltext}%")
                        ->orWhere('quire_comment', 'like', "%{$fulltext}%")
                        ->orWhere('binding_description', 'like', "%{$fulltext}%")
                        ->orWhere('inks_description', 'like', "%{$fulltext}%")
                        ->orWhere('cover_description', 'like', "%{$fulltext}%")
                        ->orWhere('gregorys_rule_comment', 'like', "%{$fulltext}%")
                        ->orWhere('conservation_history', 'like', "%{$fulltext}%")
                        ->orWhere('analyses_comment', 'like', "%{$fulltext}%")
                        ->orWhere('ancient_provenance_comment', 'like', "%{$fulltext}%")
                        ->orWhere('legal_classification_explanation', 'like', "%{$fulltext}%")
                        ->orWhere('bibliography', 'like', "%{$fulltext}%")
                        ->orWhere('images_info', 'like', "%{$fulltext}%")
                        ->orWhere('excavation_comment', 'like', "%{$fulltext}%")
                        ->orWhere('internal_comment', 'like', $role_id >= 2 ? "%{$fulltext}%" : "notsomethinganyonewouldwrite");
                });
            })
            ->when($show_publication == "true", function ($query) use (
                $standard_name,
                $publication,
                $current_shelfmarks,
                $trismegistos_id
            ) {
                $query->when($standard_name, function ($query, $standard_name) {
                    $query->where('standard_name', 'like', "%{$standard_name}%");
                })
                    ->when($publication, function ($query, $publication) {
                        $query->where('publication', 'like', "%{$publication}%");
                    })
                    ->when($current_shelfmarks, function ($query, $current_shelfmarks) {
                        $query->where('current_shelfmarks', 'like', "%{$current_shelfmarks}%");
                    })
                    ->when($trismegistos_id, function ($query, $trismegistos_id) {
                        $query->where('trismegistos_id', '=', $trismegistos_id);
                    });
            })
            ->when($show_content == "true", function ($query) use (
                $title,
                $ancient_author,
                $genres,
                $genres_incl,
                $tags,
                $tags_incl,
            ) {
                $query->when($title, function ($query, $title) {
                    $query->where('title', 'like', "%{$title}%");
                })
                    ->when($ancient_author, function ($query, $ancient_author) {
                        $query->where('ancient_author', 'like', "%{$ancient_author}%");
                    })
                    ->when($genres && !$genres_incl, function ($query) use ($genres) {
                        $query->whereHas('genres', function ($query) use ($genres) {
                            $query->whereIn('genres.id', array_column($genres, 'id'));
                        });
                    })
                    ->when($genres && $genres_incl, function ($query) use ($genres) {
                        foreach($genres as $genre) {                                
                            $query->whereHas('genres', function($query) use ($genre) {
                                $query->where('genres.id', '=', $genre['id']);
                            });
                        }
                    }) 
                    ->when($tags && !$tags_incl, function ($query) use ($tags) {
                        $query->whereHas('tags', function ($query) use ($tags) {
                            $query->whereIn('tags.id', array_column($tags, 'id'));
                        });
                    })
                    ->when($tags && $tags_incl, function ($query) use ($tags) {
                        foreach($tags as $tag) {                                
                            $query->whereHas('tags', function($query) use ($tag) {
                                $query->where('tags.id', '=', $tag['id']);
                            });
                        }
                    }); 
            })
            ->when($show_dating == "true", function ($query) use (
                $earliest_date,
                $latest_date,
                $exclusive_date,
                $dating_methods,
                $dating_methods_incl,
                $dating_certainties
            ) {
                $query->when($exclusive_date == "true", function ($query) use ($earliest_date, $latest_date, $exclusive_date) {
                    $query->when($earliest_date, function ($query, $earliest_date) {
                        $query->where('start_year', '>=', $earliest_date);
                    })
                        ->when($latest_date, function ($query, $latest_date) {
                            $query->where('end_year', '<=', $latest_date);
                        });
                }, function ($query) use ($earliest_date, $latest_date) {
                    $query->when($earliest_date, function ($query, $earliest_date) {
                        $query->where('end_year', '>=', $earliest_date);
                    })
                        ->when($latest_date, function ($query, $latest_date) {
                            $query->where('start_year', '<=', $latest_date);
                        });
                })
                    ->when($dating_methods && !$dating_methods_incl, function ($query) use ($dating_methods) {
                        $query->whereHas('dating_methods', function ($query) use ($dating_methods) {
                            $query->whereIn('dating_methods.id', array_column($dating_methods, 'id'));
                        });
                    })
                    ->when($dating_methods && $dating_methods_incl, function ($query) use ($dating_methods) {
                        foreach($dating_methods as $dating_method) {                                
                            $query->whereHas('dating_methods', function($query) use ($dating_method) {
                                $query->where('dating_methods.id', '=', $dating_method['id']);
                            });
                        }
                    }) 
                    ->when($dating_certainties, function ($query, $dating_certainties) {
                        $query->whereIn('dating_certainty_id', array_column($dating_certainties, 'id'));
                    });
            })
            ->when(
                $show_materiality == "true",
                function ($query) use (
                    $materials,
                    $gregorys_rules,
                    $inks,
                    $inks_incl,
                    $covers,
                    $quire_structures,
                    $quirenum_min,
                    $quirenum_max,
                    $bifolianum_min,
                    $bifolianum_max
                ) {
                        $query->when($materials, function ($query, $materials) {
                            $query->whereIn('material_id', array_column($materials, 'id'));
                        })
                        ->when($gregorys_rules, function ($query, $gregorys_rules) {
                            $query->whereIn('gregorys_rule_id', array_column($gregorys_rules, 'id'));
                        })
                        ->when(($inks && !$inks_incl), function ($query) use ($inks) {
                            $query->whereHas('inks', function ($query) use ($inks) {
                                $query->whereIn('inks.id', array_column($inks, 'id'));
                            });
                        })
                        ->when($inks && $inks_incl, function ($query) use ($inks) {
                            foreach($inks as $ink) {                                
                                $query->whereHas('inks', function($query) use ($ink) {
                                    $query->where('inks.id', '=', $ink['id']);
                                });
                            }
                        })                      
                        ->when($covers, function ($query, $covers) {
                            $query->whereIn('cover_id', array_column($covers, 'id'));
                        })
                        ->when($quire_structures, function ($query, $quire_structures) {
                            $query->whereIn('quire_structure_id', array_column($quire_structures, 'id'));
                        })
                        ->when($quirenum_min, function ($query, $quirenum_min) {
                            $query->where('quire_number', '>=', $quirenum_min);
                        })
                        ->when($quirenum_max, function ($query, $quirenum_max) {
                            $query->where('quire_number', '<=', $quirenum_max);
                        })
                        ->when($bifolianum_min, function ($query, $bifolianum_min) {
                            $query->where('bifolia_number', '>=', $bifolianum_min);
                        })
                        ->when($bifolianum_max, function ($query, $bifolianum_max) {
                            $query->where('bifolia_number', '<=', $bifolianum_max);
                        });
                }
            )

            ->when(
                $show_measurement == "true",
                function ($query) use (
                    $full_page_width_min,
                    $full_page_width_max,
                    $full_page_height_min,
                    $full_page_height_max,
                    $full_text_block_width_min,
                    $full_text_block_width_max,
                    $full_text_block_height_min,
                    $full_text_block_height_max,
                    $upper_margin_min,
                    $upper_margin_max,
                    $lower_margin_min,
                    $lower_margin_max,
                    $inner_margin_min,
                    $inner_margin_max,
                    $outer_margin_min,
                    $outer_margin_max,

                    $full_page_ratio_min,
                    $full_page_ratio_max,
                    $full_text_block_ratio_min,
                    $full_text_block_ratio_max,
                    $uplow_margins_ratio_min,
                    $uplow_margins_ratio_max,
                    $inout_margins_ratio_min,
                    $inout_margins_ratio_max,

                    $columns_min,
                    $columns_max,
                    $columnlines_min,
                    $columnlines_max,
                ) {
                    $query->when($full_page_width_min, function ($query, $full_page_width_min) {
                        $query->where('full_page_width', '>=', $full_page_width_min);
                    })
                        ->when($full_page_width_max, function ($query, $full_page_width_max) {
                            $query->where('full_page_width', '<=', $full_page_width_max);
                        })
                        ->when($full_page_height_min, function ($query, $full_page_height_min) {
                            $query->where('full_page_height', '>=', $full_page_height_min);
                        })
                        ->when($full_page_height_max, function ($query, $full_page_height_max) {
                            $query->where('full_page_height', '<=', $full_page_height_max);
                        })
                        ->when($full_text_block_width_min, function ($query, $full_text_block_width_min) {
                            $query->where('full_text_block_width', '>=', $full_text_block_width_min);
                        })
                        ->when($full_text_block_width_max, function ($query, $full_text_block_width_max) {
                            $query->where('full_text_block_width', '<=', $full_text_block_width_max);
                        })
                        ->when($full_text_block_height_min, function ($query, $full_text_block_height_min) {
                            $query->where('full_text_block_height', '>=', $full_text_block_height_min);
                        })
                        ->when($full_text_block_height_max, function ($query, $full_text_block_height_max) {
                            $query->where('full_text_block_height', '<=', $full_text_block_height_max);
                        })
                        ->when($upper_margin_min, function ($query, $upper_margin_min) {
                            $query->where('upper_margin', '>=', $upper_margin_min);
                        })
                        ->when($upper_margin_max, function ($query, $upper_margin_max) {
                            $query->where('upper_margin', '<=', $upper_margin_max);
                        })
                        ->when($lower_margin_min, function ($query, $lower_margin_min) {
                            $query->where('lower_margin', '>=', $lower_margin_min);
                        })
                        ->when($lower_margin_max, function ($query, $lower_margin_max) {
                            $query->where('lower_margin', '<=', $lower_margin_max);
                        })
                        ->when($inner_margin_min, function ($query, $inner_margin_min) {
                            $query->where('inner_margin', '>=', $inner_margin_min);
                        })
                        ->when($inner_margin_max, function ($query, $inner_margin_max) {
                            $query->where('inner_margin', '<=', $inner_margin_max);
                        })
                        ->when($outer_margin_min, function ($query, $outer_margin_min) {
                            $query->where('outer_margin', '>=', $outer_margin_min);
                        })
                        ->when($outer_margin_max, function ($query, $outer_margin_max) {
                            $query->where('outer_margin', '<=', $outer_margin_max);
                        })


                        ->when($full_page_ratio_min, function ($query, $full_page_ratio_min) {
                            $query->where(DB::raw('full_page_width / NULLIF(full_page_height, 0)'), '>=', $full_page_ratio_min);
                        })
                        ->when($full_page_ratio_max, function ($query, $full_page_ratio_max) {
                            $query->where(DB::raw('full_page_width / NULLIF(full_page_height, 0)'), '<=', $full_page_ratio_max);
                        })
                        ->when($full_text_block_ratio_min, function ($query, $full_text_block_ratio_min) {
                            $query->where(DB::raw('full_text_block_width / NULLIF(full_text_block_height, 0)'), '>=', $full_text_block_ratio_min);
                        })
                        ->when($full_text_block_ratio_max, function ($query, $full_text_block_ratio_max) {
                            $query->where(DB::raw('full_text_block_width / NULLIF(full_text_block_height, 0)'), '<=', $full_text_block_ratio_max);
                        })
                        ->when($uplow_margins_ratio_min, function ($query, $uplow_margins_ratio_min) {
                            $query->where(DB::raw('upper_margin / NULLIF(lower_margin, 0)'), '>=', $uplow_margins_ratio_min);
                        })
                        ->when($uplow_margins_ratio_max, function ($query, $uplow_margins_ratio_max) {
                            $query->where(DB::raw('upper_margin / NULLIF(lower_margin, 0)'), '<=', $uplow_margins_ratio_max);
                        })
                        ->when($inout_margins_ratio_min, function ($query, $inout_margins_ratio_min) {
                            $query->where(DB::raw('inner_margin / NULLIF(outer_margin, 0)'), '>=', $inout_margins_ratio_min);
                        })
                        ->when($inout_margins_ratio_max, function ($query, $inout_margins_ratio_max) {
                            $query->where(DB::raw('inner_margin / NULLIF(outer_margin, 0)'), '<=', $inout_margins_ratio_max);
                        })

                        ->when($columns_min, function ($query, $columns_min) {
                            $query->where('columns', '>=', $columns_min);
                        })
                        ->when($columns_max, function ($query, $columns_max) {
                            $query->where('columns', '<=', $columns_max);
                        })
                        ->when($columnlines_min, function ($query, $columnlines_min) {
                            $query->where('columnlines', '>=', $columnlines_min);
                        })
                        ->when($columnlines_max, function ($query, $columnlines_max) {
                            $query->where('columnlines', '<=', $columnlines_max);
                        });
                }
            )
            ->when(
                $show_palaeography == "true",
                function ($query) use (
                    $hand_number_min,
                    $hand_number_max,
                    $scripts,
                    $scripts_incl,
                    $diacritics,
                    $diacritics_incl,
                    $punctuations,
                    $punctuations_incl,
                    $paratexts,
                    $paratexts_incl,
                    $decorations,
                    $decorations_incl,
                    $critical_symbols,
                    $critical_symbols_incl,
                    $paginations,
                    $quire_signatures
                ) {
                    $query->when($hand_number_min, function ($query, $hand_number_min) {
                        $query->where('hand_number', '>=', $hand_number_min);
                    })
                        ->when($hand_number_max, function ($query, $hand_number_max) {
                            $query->where('hand_number', '<=', $hand_number_max);
                        })
                        ->when($scripts && !$scripts_incl, function ($query) use ($scripts) {
                            $query->whereHas('scripts', function ($query) use ($scripts) {
                                $query->whereIn('scripts.id', array_column($scripts, 'id'));
                            });
                        })
                        ->when($scripts && $scripts_incl, function ($query) use ($scripts) {
                            foreach($scripts as $script) {                                
                                $query->whereHas('scripts', function($query) use ($script) {
                                    $query->where('scripts.id', '=', $script['id']);
                                });
                            }
                        }) 
                        ->when($diacritics && !$diacritics_incl, function ($query) use ($diacritics) {
                            $query->whereHas('diacritics', function ($query) use ($diacritics) {
                                $query->whereIn('diacritics.id', array_column($diacritics, 'id'));
                            });
                        })
                        ->when($diacritics && $diacritics_incl, function ($query) use ($diacritics) {
                            foreach($diacritics as $diacritic) {                                
                                $query->whereHas('diacritics', function($query) use ($diacritic) {
                                    $query->where('diacritics.id', '=', $diacritic['id']);
                                });
                            }
                        }) 
                        ->when($punctuations && !$punctuations_incl, function ($query) use ($punctuations) {
                            $query->whereHas('punctuations', function ($query) use ($punctuations) {
                                $query->whereIn('punctuations.id', array_column($punctuations, 'id'));
                            });
                        })
                        ->when($punctuations && $punctuations_incl, function ($query) use ($punctuations) {
                            foreach($punctuations as $punctuation) {                                
                                $query->whereHas('punctuations', function($query) use ($punctuation) {
                                    $query->where('punctuations.id', '=', $punctuation['id']);
                                });
                            }
                        }) 
                        ->when($paratexts && !$paratexts_incl, function ($query) use ($paratexts) {
                            $query->whereHas('paratexts', function ($query) use ($paratexts) {
                                $query->whereIn('paratexts.id', array_column($paratexts, 'id'));
                            });
                        })
                        ->when($paratexts && $paratexts_incl, function ($query) use ($paratexts) {
                            foreach($paratexts as $paratext) {                                
                                $query->whereHas('paratexts', function($query) use ($paratext) {
                                    $query->where('paratexts.id', '=', $paratext['id']);
                                });
                            }
                        }) 
                        ->when($decorations && !$decorations_incl, function ($query) use ($decorations) {
                            $query->whereHas('decorations', function ($query) use ($decorations) {
                                $query->whereIn('decorations.id', array_column($decorations, 'id'));
                            });
                        })
                        ->when($decorations && $decorations_incl, function ($query) use ($decorations) {
                            foreach($decorations as $decoration) {                                
                                $query->whereHas('decorations', function($query) use ($decoration) {
                                    $query->where('decorations.id', '=', $decoration['id']);
                                });
                            }
                        }) 
                        ->when($critical_symbols && !$critical_symbols_incl, function ($query) use ($critical_symbols) {
                            $query->whereHas('critical_symbols', function ($query) use ($critical_symbols) {
                                $query->whereIn('critical_symbols.id', array_column($critical_symbols, 'id'));
                            });
                        })
                        ->when($critical_symbols && $critical_symbols_incl, function ($query) use ($critical_symbols) {
                            foreach($critical_symbols as $critical_symbol) {                                
                                $query->whereHas('critical_symbols', function($query) use ($critical_symbol) {
                                    $query->where('critical_symbols.id', '=', $critical_symbol['id']);
                                });
                            }
                        }) 
                        ->when($paginations, function ($query, $paginations) {
                            $query->whereIn('pagination_id', array_column($paginations, 'id'));
                      })
                        ->when($quire_signatures, function ($query, $quire_signatures) {
                            $query->whereIn('quire_signature_id', array_column($quire_signatures, 'id'));
                        });
                }
            )
            ->when(
                $show_consanal == "true",
                function ($query) use (
                    $storage_conditions,
                    $analyses,
                    $analyses_incl,
                ) {
                    $query->when($storage_conditions, function ($query, $storage_conditions) {
                        $query->whereIn('storage_condition_id', array_column($storage_conditions, 'id'));
                    })
                        ->when($analyses && !$analyses_incl, function ($query) use ($analyses) {
                            $query->whereHas('analyses', function ($query) use ($analyses) {
                                $query->whereIn('analyses.id', array_column($analyses, 'id'));
                            });
                        })
                        ->when($analyses && $analyses_incl, function ($query) use ($analyses) {
                            foreach($analyses as $analysis) {                                
                                $query->whereHas('analyses', function($query) use ($analysis) {
                                    $query->where('analyses.id', '=', $analysis['id']);
                                });
                            }
                        });
                }
            )
            ->when(
                $show_provenance == "true",
                function ($query) use (
                    $scientifically_excavated,
                    $ancient_provenances,
                    $ancient_provenance_certainties,
                    $transactions,
                    $transactions_incl,
                    $transaction_parties,
                    $transaction_parties_incl,
                    $legal_classifications
                ) {
                    $query->when($scientifically_excavated, function ($query, $scientifically_excavated) {
                        $query->where('scientifically_excavated', '=', 1);
                    })
                        ->when($ancient_provenances, function ($query, $ancient_provenances) {
                            $query->whereIn('ancient_provenance_id', array_column($ancient_provenances, 'id'));
                        })
                        ->when($ancient_provenance_certainties, function ($query, $ancient_provenance_certainties) {
                            $query->whereIn('ancient_provenance_certainty_id', array_column($ancient_provenance_certainties, 'id'));
                        })
                        ->when($transactions && !$transactions_incl, function ($query) use ($transactions) {
                            $query->whereHas('transactions', function ($query) use ($transactions) {
                                $query->whereIn('transactions.id', array_column($transactions, 'id'));
                            });
                        })
                        ->when($transactions && $transactions_incl, function ($query) use ($transactions) {
                            foreach($transactions as $transaction) {                                
                                $query->whereHas('transactions', function($query) use ($transaction) {
                                    $query->where('transactions.id', '=', $transaction['id']);
                                });
                            }
                        }) 

                        ->when($transaction_parties && !$transaction_parties_incl, function ($query) use ($transaction_parties) {
                            $tactions = Transaction::query()
                                ->whereHas('transaction_parties', function ($query) use ($transaction_parties) {
                                    $query->whereIn('transaction_parties.id', array_column($transaction_parties, 'id'));
                                })->get()->all();
                            $query->whereHas('transactions', function ($query) use ($tactions) {
                                $query->whereIn('transactions.id', array_column($tactions, 'id'));
                            });
                        })

                        ->when($transaction_parties && $transaction_parties_incl, function ($query) use ($transaction_parties) {
                            
                            $query->whereHas('transactions', function ($query) use ($transaction_parties) {
                                foreach($transaction_parties as $transaction_party) {
                                    $query->whereHas('transaction_parties', function ($query) use ($transaction_party) {
                                        $query->where('transaction_parties.id', '=', $transaction_party['id']);
                                    });
                                }
                            });
                        })
                        ->when($legal_classifications, function ($query, $legal_classifications) {
                            $query->whereIn('legal_classification_id', array_column($legal_classifications, 'id'));
                        });
                    })

            ->orderBy($sortby, $direction)
            ->orderBy('end_year', $direction);
            $documents = $all_documents->paginate(10)->withQueryString();

        $ancient_provenances = $ancient_provenances_original;

        return Inertia::render('Codices', [
            'documents' => $documents,

            'show_publication' => $show_publication == "true" ? true : false,
            'show_content' => $show_content == "true" ? true : false,
            'show_dating' => $show_dating == "true" ? true : false,
            'show_materiality' => $show_materiality == "true" ? true : false,
            'show_measurement' => $show_measurement == "true" ? true : false,
            'show_palaeography' => $show_palaeography == "true" ? true : false,
            'show_consanal' => $show_consanal == "true" ? true : false,
            'show_provenance' => $show_provenance == "true" ? true : false,

            'fulltext' => $fulltext,
            'standard_name' => $standard_name,
            'publication' => $publication,
            'current_shelfmarks' => $current_shelfmarks,
            'trismegistos_id' => $trismegistos_id,
            'title' => $title,
            'ancient_author' => $ancient_author,
            'genres_search' => $genres,
            'genres_incl' => $genres_incl,
            'tags_search' => $tags,
            'tags_incl' => $tags_incl,
            'earliest_date' => $earliest_date,
            'latest_date' => $latest_date,
            'exclusive_date' => $exclusive_date == "true" ? true : false,
            'dating_methods_search' => $dating_methods,
            'dating_methods_incl' => $dating_methods_incl,
            'dating_certainties_search' => $dating_certainties,
            'materials_search' => $materials,
            'inks_search' => $inks,
            'inks_incl' => $inks_incl == "true" ? true : false,
            'covers_search' => $covers,
            'quire_structures_search' => $quire_structures,
            'quirenum_min' => $quirenum_min,
            'quirenum_max' => $quirenum_max,
            'bifolianum_min' => $bifolianum_min,
            'bifolianum_max' => $bifolianum_max,
            'full_page_width_min' => $full_page_width_min,
            'full_page_width_max' => $full_page_width_max,
            'full_page_height_min' => $full_page_height_min,
            'full_page_height_max' => $full_page_height_max,
            'full_text_block_width_min' => $full_text_block_width_min,
            'full_text_block_width_max' => $full_text_block_width_max,
            'full_text_block_height_min' => $full_text_block_height_min,
            'full_text_block_height_max' => $full_text_block_height_max,
            'upper_margin_min' => $upper_margin_min,
            'upper_margin_max' => $upper_margin_max,
            'lower_margin_min' => $lower_margin_min,
            'lower_margin_max' => $lower_margin_max,
            'inner_margin_min' => $inner_margin_min,
            'inner_margin_max' => $inner_margin_max,
            'outer_margin_min' => $outer_margin_min,
            'outer_margin_max' => $outer_margin_max,

            'full_page_ratio_min' => $full_page_ratio_min,
            'full_page_ratio_max' => $full_page_ratio_max,
            'full_text_block_ratio_min' => $full_text_block_ratio_min,
            'full_text_block_ratio_max' => $full_text_block_ratio_max,
            'uplow_margins_ratio_min' => $uplow_margins_ratio_min,
            'uplow_margins_ratio_max' => $uplow_margins_ratio_max,
            'inout_margins_ratio_min' => $inout_margins_ratio_min,
            'inout_margins_ratio_max' => $inout_margins_ratio_max,

            'gregorys_rules_search' => $gregorys_rules,
            'columns_min' => $columns_min,
            'columns_max' => $columns_max,
            'columnlines_min' => $columnlines_min,
            'columnlines_max' => $columnlines_max,
        
            'hand_number_min' => $hand_number_min,
            'hand_number_max' => $hand_number_max,
            'scripts_search' => $scripts,
            'scripts_incl' => $scripts_incl,
            'diacritics_search' => $diacritics,
            'diacritics_incl' => $diacritics_incl,
            'punctuations_search' => $punctuations,
            'punctuations_incl' => $punctuations_incl,
            'paratexts_search' => $paratexts,
            'paratexts_incl' => $paratexts_incl,
            'decorations_search' => $decorations,
            'decorations_incl' => $decorations_incl,
            'critical_symbols_search' => $critical_symbols,
            'critical_symbols_incl' => $critical_symbols_incl,
            'paginations_search' => $paginations,
            'quire_signatures_search' => $quire_signatures,
            'storage_conditions_search' => $storage_conditions,
            'analyses_search' => $analyses,
            'analyses_incl' => $analyses_incl,
            'scientifically_excavated' => $scientifically_excavated,
            'ancient_provenances_search' => $ancient_provenances,
            'ancient_provenance_certainties_search' => $ancient_provenance_certainties,
            'transactions_search' => $transactions,
            'transactions_incl' => $transactions_incl,
            'transaction_parties_search' => $transaction_parties,
            'transaction_parties_incl' => $transaction_parties_incl,
            'legal_classifications_search' => $legal_classifications,
            'sortfield' => $sortfield,
            'reverse' => $reverse,

            'analyses' => Analysis::all(),
            'critical_symbols' => CriticalSymbol::all(),
            'covers' => Cover::all(),
            'dating_certainties' => DatingCertainty::all(),
            'dating_methods' => DatingMethod::all(),
            'decorations' => Decoration::all(),
            'diacritics' => Diacritic::all(),
            'genres' => Genre::all(),
            'gregorys_rules' => GregorysRule::all(),
            'inks' => Ink::all(),
            'languages' => Language::all(),
            'legal_classifications' => LegalClassification::all(),
            'materials' => Material::all(),
            'paginations' => Pagination::all(),
            'paratexts' => Paratext::all(),
            'punctuations' => Punctuation::all(),
            'transactions' => Transaction::all(),
            'quire_signatures' => QuireSignature::all(),
            'quire_structures' => QuireStructure::all(),
            'scripts' => Script::all(),
            'storage_conditions' => StorageCondition::all(),
            'tags' => Tag::all(),
            'ancient_provenances' => AncientProvenance::all(),
            'ancient_provenance_certainties' => AncientProvenanceCertainty::all(),
            'transaction_parties' => TransactionParty::all(),
        ]);
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
        return Redirect::route('CodexEdit', (['tab' => 'general', 'document' => $document]));
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
    public function show(Document  $document, Request $request)
    {
        $this->authorize('view', $document);

        $role_id = $request->user() ? $request->user()->role_id : 0;

        $search = $request->validate([
            'show_publication' => 'nullable',
            'show_content' => 'nullable',
            'show_dating' => 'nullable',
            'show_materiality' => 'nullable',
            'show_measurement' => 'nullable',
            'show_palaeography' => 'nullable',
            'show_consanal' => 'nullable',
            'show_provenance' => 'nullable',

            'fulltext' => 'nullable',
            's_standard_name' => 'nullable',
            's_publication' => 'nullable',
            's_current_shelfmarks' => 'nullable',
            's_trismegistos_id' => 'nullable',
            's_title' => 'nullable',
            's_ancient_author' => 'nullable',
            's_genres' => 'nullable',
            's_genres_incl' => 'nullable',
            's_tags' => 'nullable',
            's_tags_incl' => 'nullable',
            's_earliest_date' => 'nullable',
            's_latest_date' => 'nullable',
            's_exclusive_date' => 'nullable',
            's_dating_methods' => 'nullable',
            's_dating_methods_incl' => 'nullable',
            's_dating_certainties' => 'nullable',
            's_materials' => 'nullable',
            's_inks' => 'nullable',
            's_inks_incl' => 'nullable',
            's_covers' => 'nullable',
            's_quire_structures' => 'nullable',
            's_quirenum_min' => 'nullable',
            's_quirenum_max' => 'nullable',
            's_bifolianum_min' => 'nullable',
            's_bifolianum_max' => 'nullable',
            's_full_page_width_min' => 'nullable',
            's_full_page_width_max' => 'nullable',
            's_full_page_height_min' => 'nullable',
            's_full_page_height_max' => 'nullable',
            's_full_text_block_width_min' => 'nullable',
            's_full_text_block_width_max' => 'nullable',
            's_full_text_block_height_min' => 'nullable',
            's_full_text_block_height_max' => 'nullable',
            's_upper_margin_min' => 'nullable',
            's_upper_margin_max' => 'nullable',
            's_lower_margin_min' => 'nullable',
            's_lower_margin_max' => 'nullable',
            's_inner_margin_min' => 'nullable',
            's_inner_margin_max' => 'nullable',
            's_outer_margin_min' => 'nullable',
            's_outer_margin_max' => 'nullable',

            's_full_page_ratio_min' => 'nullable',
            's_full_page_ratio_max' => 'nullable',
            's_full_text_block_ratio_min' => 'nullable',
            's_full_text_block_ratio_max' => 'nullable',
            's_uplow_margins_ratio_min' => 'nullable',
            's_uplow_margins_ratio_max' => 'nullable',
            's_inout_margins_ratio_min' => 'nullable',
            's_inout_margins_ratio_max' => 'nullable',

            's_gregorys_rules' => 'nullable',
            's_columns_min' => 'nullable',
            's_columns_max'=> 'nullable',
            's_columnlines_min'=> 'nullable',
            's_columnlines_max'=> 'nullable',

            's_hand_number_min' => 'nullable',
            's_hand_number_max' => 'nullable',
            's_scripts' => 'nullable',
            's_scripts_incl' => 'nullable',
            's_diacritics' => 'nullable',
            's_diacritics_incl' => 'nullable',
            's_punctuations' => 'nullable',
            's_punctuations_incl' => 'nullable',
            's_paratexts' => 'nullable',
            's_paratexts_incl' => 'nullable',
            's_decorations' => 'nullable',
            's_decorations_incl' => 'nullable',
            's_critical_symbols' => 'nullable',
            's_critical_symbols_incl' => 'nullable',
            's_paginations' => 'nullable',
            's_quire_signatures' => 'nullable',
            's_storage_conditions' => 'nullable',
            's_analyses' => 'nullable',
            's_analyses_incl' => 'nullable',
            's_scientifically_excavated' => 'nullable',
            's_ancient_provenances' => 'nullable',
            's_ancient_provenance_certainties' => 'nullable',
            's_transactions' => 'nullable',
            's_transactions_incl' => 'nullable',
            's_transaction_parties' => 'nullable',
            's_transaction_parties_incl' => 'nullable',
            's_legal_classifications' => 'nullable',
            'sortfield' => 'nullable',
            'reverse' => 'nullable',
            'tab' => 'nullable',
        ]);

        $show_publication = $request->session()->get('show_publication');
        $show_content = $request->session()->get('show_content');
        $show_dating = $request->session()->get('show_dating');
        $show_materiality = $request->session()->get('show_materiality');
        $show_measurement = $request->session()->get('show_measurement');
        $show_palaeography = $request->session()->get('show_palaeography');
        $show_consanal = $request->session()->get('show_consanal');
        $show_provenance = $request->session()->get('show_provenance');

        $sortfield = $request->session()->get('sortfield');
        $sortfield = array_key_exists('sortfield', $search) ? $search['sortfield'] : $sortfield;
  
        $request->session()->put('sortfield', $sortfield);
        switch ($sortfield) {
            case 1:
                $sortby = "standard_name";
                break;
            case 2:
                $sortby = "ancient_author";
                break;
            case 3:
                $sortby = "title";
                break;
            case 4:
                $sortby = "start_year";
                break;
            case 5:
                $sortby = "end_year";
                break;
            default:
                $sortby = "start_year";
        }

        $tab = array_key_exists('tab', $search) ? $search['tab'] : 'general';

        $reverse = $request->session()->get('reverse');
        $reverse = array_key_exists('reverse', $search) ? $search['reverse'] : $reverse;

        $direction = $reverse ? "desc" : "asc";

        $fulltext = array_key_exists('fulltext', $search) ? $search['fulltext'] : null;
        $standard_name = array_key_exists('s_standard_name', $search) ? $search['s_standard_name'] : null;
        $publication = array_key_exists('s_publication', $search) ? $search['s_publication'] : null;
        $current_shelfmarks = array_key_exists('s_current_shelfmarks', $search) ? $search['s_current_shelfmarks'] : null;
        $trismegistos_id = array_key_exists('s_trismegistos_id', $search) ? $search['s_trismegistos_id'] : null;
        $title = array_key_exists('s_title', $search) ? $search['s_title'] : null;
        $ancient_author = array_key_exists('s_ancient_author', $search) ? $search['s_ancient_author'] : null;
        $genres = array_key_exists('s_genres', $search) ? $search['s_genres'] : null;
        $genres_incl = array_key_exists('s_genres_incl', $search) ? $search['s_genres_incl'] : null;
        $tags = array_key_exists('s_tags', $search) ? $search['s_tags'] : null;
        $tags_incl = array_key_exists('s_tags_incl', $search) ? $search['s_tags_incl'] : null;
        $earliest_date = array_key_exists('s_earliest_date', $search) ? $search['s_earliest_date'] : null;
        $latest_date = array_key_exists('s_latest_date', $search) ? $search['s_latest_date'] : null;
        $exclusive_date = array_key_exists('s_exclusive_date', $search) ? $search['s_exclusive_date'] : false;
        $dating_methods = array_key_exists('s_dating_methods', $search) ? $search['s_dating_methods'] : null;
        $dating_methods_incl = array_key_exists('s_dating_methods_incl', $search) ? $search['s_dating_methods_incl'] : null;
        $dating_certainties = array_key_exists('s_dating_certainties', $search) ? $search['s_dating_certainties'] : null;
        $materials = array_key_exists('s_materials', $search) ? $search['s_materials'] : null;
        $inks = array_key_exists('s_inks', $search) ? $search['s_inks'] : null;
        $inks_incl = array_key_exists('s_inks_incl', $search) ? $search['s_inks_incl'] : null;
        $covers = array_key_exists('s_covers', $search) ? $search['s_covers'] : null;
        $quire_structures = array_key_exists('s_quire_structures', $search) ? $search['s_quire_structures'] : null;
        $quirenum_min = array_key_exists('s_quirenum_min', $search) ? $search['s_quirenum_min'] : null;
        $quirenum_max = array_key_exists('s_quirenum_max', $search) ? $search['s_quirenum_max'] : null;
        $bifolianum_min = array_key_exists('s_bifolianum_min', $search) ? $search['s_bifolianum_min'] : null;
        $bifolianum_max = array_key_exists('s_bifolianum_max', $search) ? $search['s_bifolianum_max'] : null;
        $full_page_width_min = array_key_exists('s_full_page_width_min', $search) ? $search['s_full_page_width_min'] : null;
        $full_page_width_max = array_key_exists('s_full_page_width_max', $search) ? $search['s_full_page_width_max'] : null;
        $full_page_height_min = array_key_exists('s_full_page_height_min', $search) ? $search['s_full_page_height_min'] : null;
        $full_page_height_max = array_key_exists('s_full_page_height_max', $search) ? $search['s_full_page_height_max'] : null;
        $full_text_block_width_min = array_key_exists('s_full_text_block_width_min', $search) ? $search['s_full_text_block_width_min'] : null;
        $full_text_block_width_max = array_key_exists('s_full_text_block_width_max', $search) ? $search['s_full_text_block_width_max'] : null;
        $full_text_block_height_min = array_key_exists('s_full_text_block_height_min', $search) ? $search['s_full_text_block_height_min'] : null;
        $full_text_block_height_max = array_key_exists('s_full_text_block_height_max', $search) ? $search['s_full_text_block_height_max'] : null;
        $upper_margin_min = array_key_exists('s_upper_margin_min', $search) ? $search['s_upper_margin_min'] : null;
        $upper_margin_max = array_key_exists('s_upper_margin_max', $search) ? $search['s_upper_margin_max'] : null;
        $lower_margin_min = array_key_exists('s_lower_margin_min', $search) ? $search['s_lower_margin_min'] : null;
        $lower_margin_max = array_key_exists('s_lower_margin_max', $search) ? $search['s_lower_margin_max'] : null;
        $inner_margin_min = array_key_exists('s_inner_margin_min', $search) ? $search['s_inner_margin_min'] : null;
        $inner_margin_max = array_key_exists('s_inner_margin_max', $search) ? $search['s_inner_margin_max'] : null;
        $outer_margin_min = array_key_exists('s_outer_margin_min', $search) ? $search['s_outer_margin_min'] : null;
        $outer_margin_max = array_key_exists('s_outer_margin_max', $search) ? $search['s_outer_margin_max'] : null;

        $full_page_ratio_min = array_key_exists('s_full_page_ratio_min', $search) ? $search['s_full_page_ratio_min'] : null;
        $full_page_ratio_max = array_key_exists('s_full_page_ratio_max', $search) ? $search['s_full_page_ratio_max'] : null;
        $full_text_block_ratio_min = array_key_exists('s_full_text_block_ratio_min', $search) ? $search['s_full_text_block_ratio_min'] : null;
        $full_text_block_ratio_max = array_key_exists('s_full_text_block_ratio_max', $search) ? $search['s_full_text_block_ratio_max'] : null;
        $uplow_margins_ratio_min = array_key_exists('s_uplow_margins_ratio_min', $search) ? $search['s_uplow_margins_ratio_min'] : null;
        $uplow_margins_ratio_max = array_key_exists('s_uplow_margins_ratio_max', $search) ? $search['s_uplow_margins_ratio_max'] : null;
        $inout_margins_ratio_min = array_key_exists('s_inout_margins_ratio_min', $search) ? $search['s_inout_margins_ratio_min'] : null;
        $inout_margins_ratio_max = array_key_exists('s_inout_margins_ratio_max', $search) ? $search['s_inout_margins_ratio_max'] : null;

        $gregorys_rules = array_key_exists('s_gregorys_rules', $search) ? $search['s_gregorys_rules'] : null;
        $columns_min = array_key_exists('s_columns_min', $search) ? $search['s_columns_min'] : null;
        $columns_max = array_key_exists('s_columns_max', $search) ? $search['s_columns_max'] : null;
        $columnlines_min = array_key_exists('s_columnlines_min', $search) ? $search['s_columnlines_min'] : null;
        $columnlines_max = array_key_exists('s_columnlines_max', $search) ? $search['s_columnlines_max'] : null;

        $hand_number_min = array_key_exists('s_hand_number_min', $search) ? $search['s_hand_number_min'] : null;
        $hand_number_max = array_key_exists('s_hand_number_max', $search) ? $search['s_hand_number_max'] : null;
        $scripts = array_key_exists('s_scripts', $search) ? $search['s_scripts'] : null;
        $scripts_incl = array_key_exists('s_scripts_incl', $search) ? $search['s_scripts_incl'] : null;
        $diacritics = array_key_exists('s_diacritics', $search) ? $search['s_diacritics'] : null;
        $diacritics_incl = array_key_exists('s_diacritics_incl', $search) ? $search['s_diacritics_incl'] : null;
        $punctuations = array_key_exists('s_punctuations', $search) ? $search['s_punctuations'] : null;
        $punctuations_incl = array_key_exists('s_punctuations_incl', $search) ? $search['s_punctuations_incl'] : null;
        $paratexts = array_key_exists('s_paratexts', $search) ? $search['s_paratexts'] : null;
        $paratexts_incl = array_key_exists('s_paratexts_incl', $search) ? $search['s_paratexts_incl'] : null;
        $decorations = array_key_exists('s_decorations', $search) ? $search['s_decorations'] : null;
        $decorations_incl = array_key_exists('s_decorations_incl', $search) ? $search['s_decorations_incl'] : null;
        $critical_symbols = array_key_exists('s_critical_symbols', $search) ? $search['s_critical_symbols'] : null;
        $critical_symbols_incl = array_key_exists('s_critical_symbols_incl', $search) ? $search['s_critical_symbols_incl'] : null;
        $paginations = array_key_exists('s_paginations', $search) ? $search['s_paginations'] : null;
        $quire_signatures = array_key_exists('s_quire_signatures', $search) ? $search['s_quire_signatures'] : null;
        $storage_conditions = array_key_exists('s_storage_conditions', $search) ? $search['s_storage_conditions'] : null;
        $analyses = array_key_exists('s_analyses', $search) ? $search['s_analyses'] : null;
        $analyses_incl = array_key_exists('s_analyses_incl', $search) ? $search['s_analyses_incl'] : null;
        $scientifically_excavated = array_key_exists('s_scientifically_excavated', $search) ? $search['s_scientifically_excavated'] : null;
        $ancient_provenances = array_key_exists('s_ancient_provenances', $search) ? $search['s_ancient_provenances'] : null;
        $ancient_provenance_certainties = array_key_exists('s_ancient_provenance_certainties', $search) ? $search['s_ancient_provenance_certainties'] : null;
        $transactions = array_key_exists('s_transactions', $search) ? $search['s_transactions'] : null;
        $transactions_incl = array_key_exists('s_transactions_incl', $search) ? $search['s_transactions_incl'] : null;
        $transaction_parties = array_key_exists('s_transaction_parties', $search) ? $search['s_transaction_parties'] : null;
        $transaction_parties_incl = array_key_exists('s_transaction_parties_incl', $search) ? $search['s_transaction_parties_incl'] : null;
        $legal_classifications = array_key_exists('s_legal_classifications', $search) ? $search['s_legal_classifications'] : null;
        
        if($show_provenance AND $ancient_provenances) {
            $children = [];

            foreach($ancient_provenances as $ancient_provenance) {
                $prov = AncientProvenance::find($ancient_provenance['id']);
                $childrec = $prov->flatChildren();

                foreach($childrec as $child) {
                    $childarr = [
                        "id" => $child->id,
                        "name" => $child->name
                    ];
                    array_push($ancient_provenances, $childarr);
                } 
            }
        }
 
        $all_documents = Document::query()
            ->when($role_id < 2, function ($query) {
                $query->where('published', '=', true);
            }) 
            ->when($fulltext, function ($query) use ($fulltext, $role_id) {
                $query->where(function ($query) use ($fulltext, $role_id) {
                    $query->where('standard_name', 'like', "%{$fulltext}%")
                        ->orWhere('standard_name', 'like', "%{$fulltext}%")
                        ->orWhere('other_names', 'like', "%{$fulltext}%")
                        ->orWhere('trismegistos_id', 'like', "%{$fulltext}%")
                        ->orWhere('publication', 'like', "%{$fulltext}%")
                        ->orWhere('current_shelfmarks', 'like', "%{$fulltext}%")
                        ->orWhere('title', 'like', "%{$fulltext}%")
                        ->orWhere('ancient_author', 'like', "%{$fulltext}%")
                        ->orWhere('content_description', 'like', "%{$fulltext}%")
                        ->orWhere('language_comment', 'like', "%{$fulltext}%")
                        ->orWhere('dating_comment', 'like', "%{$fulltext}%")
                        ->orWhere('general_comment', 'like', "%{$fulltext}%")
                        ->orWhere('measurement_comment', 'like', "%{$fulltext}%")
                        ->orWhere('full_text_block_comment', 'like', "%{$fulltext}%")
                        ->orWhere('script_description', 'like', "%{$fulltext}%")
                        ->orWhere('decoration_description', 'like', "%{$fulltext}%")
                        ->orWhere('paratext_description', 'like', "%{$fulltext}%")
                        ->orWhere('diacritic_description', 'like', "%{$fulltext}%")
                        ->orWhere('punctuation_description', 'like', "%{$fulltext}%")
                        ->orWhere('critical_symbols_description', 'like', "%{$fulltext}%")
                        ->orWhere('pagination_description', 'like', "%{$fulltext}%")
                        ->orWhere('quire_signatures_description', 'like', "%{$fulltext}%")
                        ->orWhere('quire_comment', 'like', "%{$fulltext}%")
                        ->orWhere('binding_description', 'like', "%{$fulltext}%")
                        ->orWhere('inks_description', 'like', "%{$fulltext}%")
                        ->orWhere('cover_description', 'like', "%{$fulltext}%")
                        ->orWhere('gregorys_rule_comment', 'like', "%{$fulltext}%")
                        ->orWhere('conservation_history', 'like', "%{$fulltext}%")
                        ->orWhere('analyses_comment', 'like', "%{$fulltext}%")
                        ->orWhere('ancient_provenance_comment', 'like', "%{$fulltext}%")
                        ->orWhere('legal_classification_explanation', 'like', "%{$fulltext}%")
                        ->orWhere('bibliography', 'like', "%{$fulltext}%")
                        ->orWhere('images_info', 'like', "%{$fulltext}%")
                        ->orWhere('excavation_comment', 'like', "%{$fulltext}%")
                        ->orWhere('internal_comment', 'like', $role_id >= 2 ? "%{$fulltext}%" : "notsomethinganyonewouldwrite");
                });
            })
            ->when($show_publication == "true", function ($query) use (
                $standard_name,
                $publication,
                $current_shelfmarks,
                $trismegistos_id
            ) {
                $query->when($standard_name, function ($query, $standard_name) {
                    $query->where('standard_name', 'like', "%{$standard_name}%");
                })
                    ->when($publication, function ($query, $publication) {
                        $query->where('publication', 'like', "%{$publication}%");
                    })
                    ->when($current_shelfmarks, function ($query, $current_shelfmarks) {
                        $query->where('current_shelfmarks', 'like', "%{$current_shelfmarks}%");
                    })
                    ->when($trismegistos_id, function ($query, $trismegistos_id) {
                        $query->where('trismegistos_id', '=', $trismegistos_id);
                    });
            })
            ->when($show_content == "true", function ($query) use (
                $title,
                $ancient_author,
                $genres,
                $genres_incl,
                $tags,
                $tags_incl,
            ) {
                $query->when($title, function ($query, $title) {
                    $query->where('title', 'like', "%{$title}%");
                })
                    ->when($ancient_author, function ($query, $ancient_author) {
                        $query->where('ancient_author', 'like', "%{$ancient_author}%");
                    })
                    ->when($genres && !$genres_incl, function ($query) use ($genres) {
                        $query->whereHas('genres', function ($query) use ($genres) {
                            $query->whereIn('genres.id', array_column($genres, 'id'));
                        });
                    })
                    ->when($genres && $genres_incl, function ($query) use ($genres) {
                        foreach($genres as $genre) {                                
                            $query->whereHas('genres', function($query) use ($genre) {
                                $query->where('genres.id', '=', $genre['id']);
                            });
                        }
                    }) 
                    ->when($tags && !$tags_incl, function ($query) use ($tags) {
                        $query->whereHas('tags', function ($query) use ($tags) {
                            $query->whereIn('tags.id', array_column($tags, 'id'));
                        });
                    })
                    ->when($tags && $tags_incl, function ($query) use ($tags) {
                        foreach($tags as $tag) {                                
                            $query->whereHas('tags', function($query) use ($tag) {
                                $query->where('tags.id', '=', $tag['id']);
                            });
                        }
                    }); 
            })
            ->when($show_dating == "true", function ($query) use (
                $earliest_date,
                $latest_date,
                $exclusive_date,
                $dating_methods,
                $dating_methods_incl,
                $dating_certainties
            ) {
                $query->when($exclusive_date == "true", function ($query) use ($earliest_date, $latest_date, $exclusive_date) {
                    $query->when($earliest_date, function ($query, $earliest_date) {
                        $query->where('start_year', '>=', $earliest_date);
                    })
                        ->when($latest_date, function ($query, $latest_date) {
                            $query->where('end_year', '<=', $latest_date);
                        });
                }, function ($query) use ($earliest_date, $latest_date) {
                    $query->when($earliest_date, function ($query, $earliest_date) {
                        $query->where('end_year', '>=', $earliest_date);
                    })
                        ->when($latest_date, function ($query, $latest_date) {
                            $query->where('start_year', '<=', $latest_date);
                        });
                })
                    ->when($dating_methods && !$dating_methods_incl, function ($query) use ($dating_methods) {
                        $query->whereHas('dating_methods', function ($query) use ($dating_methods) {
                            $query->whereIn('dating_methods.id', array_column($dating_methods, 'id'));
                        });
                    })
                    ->when($dating_methods && $dating_methods_incl, function ($query) use ($dating_methods) {
                        foreach($dating_methods as $dating_method) {                                
                            $query->whereHas('dating_methods', function($query) use ($dating_method) {
                                $query->where('dating_methods.id', '=', $dating_method['id']);
                            });
                        }
                    }) 
                    ->when($dating_certainties, function ($query, $dating_certainties) {
                        $query->whereIn('dating_certainty_id', array_column($dating_certainties, 'id'));
                    });
            })
            ->when(
                $show_materiality == "true",
                function ($query) use (
                    $materials,
                    $gregorys_rules,
                    $inks,
                    $inks_incl,
                    $covers,
                    $quire_structures,
                    $quirenum_min,
                    $quirenum_max,
                    $bifolianum_min,
                    $bifolianum_max
                ) {
                        $query->when($materials, function ($query, $materials) {
                            $query->whereIn('material_id', array_column($materials, 'id'));
                        })
                        ->when($gregorys_rules, function ($query, $gregorys_rules) {
                            $query->whereIn('gregorys_rule_id', array_column($gregorys_rules, 'id'));
                        })
                        ->when(($inks && !$inks_incl), function ($query) use ($inks) {
                            $query->whereHas('inks', function ($query) use ($inks) {
                                $query->whereIn('inks.id', array_column($inks, 'id'));
                            });
                        })
                        ->when($inks && $inks_incl, function ($query) use ($inks) {
                            foreach($inks as $ink) {                                
                                $query->whereHas('inks', function($query) use ($ink) {
                                    $query->where('inks.id', '=', $ink['id']);
                                });
                            }
                        })                      
                        ->when($covers, function ($query, $covers) {
                            $query->whereIn('cover_id', array_column($covers, 'id'));
                        })
                        ->when($quire_structures, function ($query, $quire_structures) {
                            $query->whereIn('quire_structure_id', array_column($quire_structures, 'id'));
                        })
                        ->when($quirenum_min, function ($query, $quirenum_min) {
                            $query->where('quire_number', '>=', $quirenum_min);
                        })
                        ->when($quirenum_max, function ($query, $quirenum_max) {
                            $query->where('quire_number', '<=', $quirenum_max);
                        })
                        ->when($bifolianum_min, function ($query, $bifolianum_min) {
                            $query->where('bifolia_number', '>=', $bifolianum_min);
                        })
                        ->when($bifolianum_max, function ($query, $bifolianum_max) {
                            $query->where('bifolia_number', '<=', $bifolianum_max);
                        });
                }
            )

            ->when(
                $show_measurement == "true",
                function ($query) use (
                    $full_page_width_min,
                    $full_page_width_max,
                    $full_page_height_min,
                    $full_page_height_max,
                    $full_text_block_width_min,
                    $full_text_block_width_max,
                    $full_text_block_height_min,
                    $full_text_block_height_max,
                    $upper_margin_min,
                    $upper_margin_max,
                    $lower_margin_min,
                    $lower_margin_max,
                    $inner_margin_min,
                    $inner_margin_max,
                    $outer_margin_min,
                    $outer_margin_max,

                    $full_page_ratio_min,
                    $full_page_ratio_max,
                    $full_text_block_ratio_min,
                    $full_text_block_ratio_max,
                    $uplow_margins_ratio_min,
                    $uplow_margins_ratio_max,
                    $inout_margins_ratio_min,
                    $inout_margins_ratio_max,

                    $columns_min,
                    $columns_max,
                    $columnlines_min,
                    $columnlines_max,
                ) {
                    $query->when($full_page_width_min, function ($query, $full_page_width_min) {
                        $query->where('full_page_width', '>=', $full_page_width_min);
                    })
                        ->when($full_page_width_max, function ($query, $full_page_width_max) {
                            $query->where('full_page_width', '<=', $full_page_width_max);
                        })
                        ->when($full_page_height_min, function ($query, $full_page_height_min) {
                            $query->where('full_page_height', '>=', $full_page_height_min);
                        })
                        ->when($full_page_height_max, function ($query, $full_page_height_max) {
                            $query->where('full_page_height', '<=', $full_page_height_max);
                        })
                        ->when($full_text_block_width_min, function ($query, $full_text_block_width_min) {
                            $query->where('full_text_block_width', '>=', $full_text_block_width_min);
                        })
                        ->when($full_text_block_width_max, function ($query, $full_text_block_width_max) {
                            $query->where('full_text_block_width', '<=', $full_text_block_width_max);
                        })
                        ->when($full_text_block_height_min, function ($query, $full_text_block_height_min) {
                            $query->where('full_text_block_height', '>=', $full_text_block_height_min);
                        })
                        ->when($full_text_block_height_max, function ($query, $full_text_block_height_max) {
                            $query->where('full_text_block_height', '<=', $full_text_block_height_max);
                        })
                        ->when($upper_margin_min, function ($query, $upper_margin_min) {
                            $query->where('upper_margin', '>=', $upper_margin_min);
                        })
                        ->when($upper_margin_max, function ($query, $upper_margin_max) {
                            $query->where('upper_margin', '<=', $upper_margin_max);
                        })
                        ->when($lower_margin_min, function ($query, $lower_margin_min) {
                            $query->where('lower_margin', '>=', $lower_margin_min);
                        })
                        ->when($lower_margin_max, function ($query, $lower_margin_max) {
                            $query->where('lower_margin', '<=', $lower_margin_max);
                        })
                        ->when($inner_margin_min, function ($query, $inner_margin_min) {
                            $query->where('inner_margin', '>=', $inner_margin_min);
                        })
                        ->when($inner_margin_max, function ($query, $inner_margin_max) {
                            $query->where('inner_margin', '<=', $inner_margin_max);
                        })
                        ->when($outer_margin_min, function ($query, $outer_margin_min) {
                            $query->where('outer_margin', '>=', $outer_margin_min);
                        })
                        ->when($outer_margin_max, function ($query, $outer_margin_max) {
                            $query->where('outer_margin', '<=', $outer_margin_max);
                        })
                        ->when($full_page_ratio_min, function ($query, $full_page_ratio_min) {
                            $query->where(DB::raw('full_page_width / NULLIF(full_page_height, 0)'), '>=', $full_page_ratio_min);
                        })
                        ->when($full_page_ratio_max, function ($query, $full_page_ratio_max) {
                            $query->where(DB::raw('full_page_width / NULLIF(full_page_height, 0)'), '<=', $full_page_ratio_max);
                        })
                        ->when($full_text_block_ratio_min, function ($query, $full_text_block_ratio_min) {
                            $query->where(DB::raw('full_text_block_width / NULLIF(full_text_block_height, 0)'), '>=', $full_text_block_ratio_min);
                        })
                        ->when($full_text_block_ratio_max, function ($query, $full_text_block_ratio_max) {
                            $query->where(DB::raw('full_text_block_width / NULLIF(full_text_block_height, 0)'), '<=', $full_text_block_ratio_max);
                        })
                        ->when($uplow_margins_ratio_min, function ($query, $uplow_margins_ratio_min) {
                            $query->where(DB::raw('upper_margin / NULLIF(lower_margin, 0)'), '>=', $uplow_margins_ratio_min);
                        })
                        ->when($uplow_margins_ratio_max, function ($query, $uplow_margins_ratio_max) {
                            $query->where(DB::raw('upper_margin / NULLIF(lower_margin, 0)'), '<=', $uplow_margins_ratio_max);
                        })
                        ->when($inout_margins_ratio_min, function ($query, $inout_margins_ratio_min) {
                            $query->where(DB::raw('inner_margin / NULLIF(outer_margin, 0)'), '>=', $inout_margins_ratio_min);
                        })
                        ->when($inout_margins_ratio_max, function ($query, $inout_margins_ratio_max) {
                            $query->where(DB::raw('inner_margin / NULLIF(outer_margin, 0)'), '<=', $inout_margins_ratio_max);
                        })
                        ->when($columns_min, function ($query, $columns_min) {
                            $query->where('columns', '>=', $columns_min);
                        })
                        ->when($columns_max, function ($query, $columns_max) {
                            $query->where('columns', '>=', $columns_max);
                        })
                        ->when($columnlines_min, function ($query, $columnlines_min) {
                            $query->where('columnlines', '>=', $columnlines_min);
                        })
                        ->when($columnlines_max, function ($query, $columnlines_max) {
                            $query->where('columnlines', '>=', $columnlines_max);
                        });
                }
            )
            ->when(
                $show_palaeography == "true",
                function ($query) use (
                    $hand_number_min,
                    $hand_number_max,
                    $scripts,
                    $scripts_incl,
                    $diacritics,
                    $diacritics_incl,
                    $punctuations,
                    $punctuations_incl,
                    $paratexts,
                    $paratexts_incl,
                    $decorations,
                    $decorations_incl,
                    $critical_symbols,
                    $critical_symbols_incl,
                    $paginations,
                    $quire_signatures
                ) {
                    $query->when($hand_number_min, function ($query, $hand_number_min) {
                        $query->where('hand_number', '>=', $hand_number_min);
                    })
                        ->when($hand_number_max, function ($query, $hand_number_max) {
                            $query->where('hand_number', '<=', $hand_number_max);
                        })
                        ->when($scripts && !$scripts_incl, function ($query) use ($scripts) {
                            $query->whereHas('scripts', function ($query) use ($scripts) {
                                $query->whereIn('scripts.id', array_column($scripts, 'id'));
                            });
                        })
                        ->when($scripts && $scripts_incl, function ($query) use ($scripts) {
                            foreach($scripts as $script) {                                
                                $query->whereHas('scripts', function($query) use ($script) {
                                    $query->where('scripts.id', '=', $script['id']);
                                });
                            }
                        }) 
                        ->when($diacritics && !$diacritics_incl, function ($query) use ($diacritics) {
                            $query->whereHas('diacritics', function ($query) use ($diacritics) {
                                $query->whereIn('diacritics.id', array_column($diacritics, 'id'));
                            });
                        })
                        ->when($diacritics && $diacritics_incl, function ($query) use ($diacritics) {
                            foreach($diacritics as $diacritic) {                                
                                $query->whereHas('diacritics', function($query) use ($diacritic) {
                                    $query->where('diacritics.id', '=', $diacritic['id']);
                                });
                            }
                        }) 
                        ->when($punctuations && !$punctuations_incl, function ($query) use ($punctuations) {
                            $query->whereHas('punctuations', function ($query) use ($punctuations) {
                                $query->whereIn('punctuations.id', array_column($punctuations, 'id'));
                            });
                        })
                        ->when($punctuations && $punctuations_incl, function ($query) use ($punctuations) {
                            foreach($punctuations as $punctuation) {                                
                                $query->whereHas('punctuations', function($query) use ($punctuation) {
                                    $query->where('punctuations.id', '=', $punctuation['id']);
                                });
                            }
                        }) 
                        ->when($paratexts && !$paratexts_incl, function ($query) use ($paratexts) {
                            $query->whereHas('paratexts', function ($query) use ($paratexts) {
                                $query->whereIn('paratexts.id', array_column($paratexts, 'id'));
                            });
                        })
                        ->when($paratexts && $paratexts_incl, function ($query) use ($paratexts) {
                            foreach($paratexts as $paratext) {                                
                                $query->whereHas('paratexts', function($query) use ($paratext) {
                                    $query->where('paratexts.id', '=', $paratext['id']);
                                });
                            }
                        }) 
                        ->when($decorations && !$decorations_incl, function ($query) use ($decorations) {
                            $query->whereHas('decorations', function ($query) use ($decorations) {
                                $query->whereIn('decorations.id', array_column($decorations, 'id'));
                            });
                        })
                        ->when($decorations && $decorations_incl, function ($query) use ($decorations) {
                            foreach($decorations as $decoration) {                                
                                $query->whereHas('decorations', function($query) use ($decoration) {
                                    $query->where('decorations.id', '=', $decoration['id']);
                                });
                            }
                        }) 
                        ->when($critical_symbols && !$critical_symbols_incl, function ($query) use ($critical_symbols) {
                            $query->whereHas('critical_symbols', function ($query) use ($critical_symbols) {
                                $query->whereIn('critical_symbols.id', array_column($critical_symbols, 'id'));
                            });
                        })
                        ->when($critical_symbols && $critical_symbols_incl, function ($query) use ($critical_symbols) {
                            foreach($critical_symbols as $critical_symbol) {                                
                                $query->whereHas('critical_symbols', function($query) use ($critical_symbol) {
                                    $query->where('critical_symbols.id', '=', $critical_symbol['id']);
                                });
                            }
                        }) 
                        ->when($paginations, function ($query, $paginations) {
                            $query->whereIn('pagination_id', array_column($paginations, 'id'));
                        })
                        ->when($quire_signatures, function ($query, $quire_signatures) {
                            $query->whereIn('quire_signature_id', array_column($quire_signatures, 'id'));
                        });
                }
            )
            ->when(
                $show_consanal == "true",
                function ($query) use (
                    $storage_conditions,
                    $analyses,
                    $analyses_incl,
                ) {
                    $query->when($storage_conditions, function ($query, $storage_conditions) {
                        $query->whereIn('storage_condition_id', array_column($storage_conditions, 'id'));
                    })
                        ->when($analyses && !$analyses_incl, function ($query) use ($analyses) {
                            $query->whereHas('analyses', function ($query) use ($analyses) {
                                $query->whereIn('analyses.id', array_column($analyses, 'id'));
                            });
                        })
                        ->when($analyses && $analyses_incl, function ($query) use ($analyses) {
                            foreach($analyses as $analysis) {                                
                                $query->whereHas('analyses', function($query) use ($analysis) {
                                    $query->where('analyses.id', '=', $analysis['id']);
                                });
                            }
                        });
                }
            )
            ->when(
                $show_provenance == "true",
                function ($query) use (
                    $scientifically_excavated,
                    $ancient_provenances,
                    $ancient_provenance_certainties,
                    $transactions,
                    $transactions_incl,
                    $transaction_parties,
                    $transaction_parties_incl,
                    $legal_classifications
                ) {
                    $query->when($scientifically_excavated, function ($query, $scientifically_excavated) {
                        $query->where('scientifically_excavated', '=', 1);
                    })
                        ->when($ancient_provenances, function ($query, $ancient_provenances) {
                            $query->whereIn('ancient_provenance_id', array_column($ancient_provenances, 'id'));
                        })
                        ->when($ancient_provenance_certainties, function ($query, $ancient_provenance_certainties) {
                            $query->whereIn('ancient_provenance_certainty_id', array_column($ancient_provenance_certainties, 'id'));
                        })
                        ->when($transactions && !$transactions_incl, function ($query) use ($transactions) {
                            $query->whereHas('transactions', function ($query) use ($transactions) {
                                $query->whereIn('transactions.id', array_column($transactions, 'id'));
                            });
                        })
                        ->when($transactions && $transactions_incl, function ($query) use ($transactions) {
                            foreach($transactions as $transaction) {                                
                                $query->whereHas('transactions', function($query) use ($transaction) {
                                    $query->where('transactions.id', '=', $transaction['id']);
                                });
                            }
                        }) 

                        ->when($transaction_parties && !$transaction_parties_incl, function ($query) use ($transaction_parties) {
                            $tactions = Transaction::query()
                                ->whereHas('transaction_parties', function ($query) use ($transaction_parties) {
                                    $query->whereIn('transaction_parties.id', array_column($transaction_parties, 'id'));
                                })->get()->all();
                            $query->whereHas('transactions', function ($query) use ($tactions) {
                                $query->whereIn('transactions.id', array_column($tactions, 'id'));
                            });
                        })

                        ->when($transaction_parties && $transaction_parties_incl, function ($query) use ($transaction_parties) {
                            
                            $query->whereHas('transactions', function ($query) use ($transaction_parties) {
                                foreach($transaction_parties as $transaction_party) {
                                    $query->whereHas('transaction_parties', function ($query) use ($transaction_party) {
                                        $query->where('transaction_parties.id', '=', $transaction_party['id']);
                                    });
                                }
                            });
                        })
                        ->when($legal_classifications, function ($query, $legal_classifications) {
                            $query->whereIn('legal_classification_id', array_column($legal_classifications, 'id'));
                        });
                    })

            ->orderBy($sortby, $direction)
            ->orderBy('end_year', $direction)->get();

            $prev = -1;
            $next = -1;
            $current = -1;
            $savedprev = -1;
            $found = false;
            $count = 0;
            $total = $all_documents->count();

            foreach ($all_documents as $doc){
                if($found) {
                    $next = $doc['id'];
                    break;
                }

                $count++;

                if($doc['id'] === $document['id']) {
                    $found = true;
                    $prev = $savedprev;
                    $current = $count;
                }
                else {
                    $found = false;
                }
                $savedprev = $doc['id'];
            }


        return (Inertia::render('CodexShow', [
            'document' => $document,
            'analyses' => $document->analyses()->get()->makeHidden('pivot'),
            'ancient_provenance' => $document->ancient_provenance()->with('parentRecursive')->get(),
            'ancient_provenance_certainty' => $document->ancient_provenance_certainty()->get(),
            'critical_symbols' => $document->critical_symbols()->get()->makeHidden('pivot'),
            'cover' => $document->cover()->get(),
            'dating_certainty' => $document->dating_certainty()->get(),
            'dating_methods' => $document->dating_methods()->get()->makeHidden('pivot'),
            'decorations' => $document->decorations()->get()->makeHidden('pivot'),
            'diacritics' => $document->diacritics()->get()->makeHidden('pivot'),
            'genres' => $document->genres()->get()->makeHidden('pivot'),
            'images' => $document->images()->with('license')->get(),
            'inks' => $document->inks()->get()->makeHidden('pivot'),
            'languages' => $document->languages()->get()->makeHidden('pivot'),
            'legal_classification' => $document->legal_classification()->get(),
            'material' => $document->material()->get(),
            'gregorys_rule' => $document->gregorys_rule()->get(),
            'modern_collections' => $document->modern_collections()->get()->makeHidden('pivot'),
            'pagination' => $document->pagination()->get(),
            'paratexts' => $document->paratexts()->get()->makeHidden('pivot'),
            'punctuations' => $document->punctuations()->get()->makeHidden('pivot'),
            'transactions' => $document->transactions()
                                        ->with('transaction_parties')->with('documents')->get()
                                        ->sortBy('year')
                                        ->sortBy('month')
                                        ->sortBy('day')                                
                                        ->makeHidden('pivot'),
            'quire_signature' => $document->quire_signature()->get(),
            'quire_structure' => $document->quire_structure()->get(),
            'scripts' => $document->scripts()->get()->makeHidden('pivot'),
            'storage_condition' => $document->storage_condition()->get(),
            'tags' => $document->tags()->get()->makeHidden('pivot'),

            'fulltext' => $fulltext,
            'standard_name' => $standard_name,
            'publication' => $publication,
            'current_shelfmarks' => $current_shelfmarks,
            'trismegistos_id' => $trismegistos_id,
            'title' => $title,
            'ancient_author' => $ancient_author,
            'genres_search' => $genres,
            'genres_incl' => $genres_incl,
            'tags_search' => $tags,
            'tags_incl' => $tags_incl,
            'earliest_date' => $earliest_date,
            'latest_date' => $latest_date,
            'exclusive_date' => $exclusive_date == "true" ? true : false,
            'dating_methods_search' => $dating_methods,
            'dating_methods_incl' => $dating_methods_incl,
            'dating_certainties_search' => $dating_certainties,
            'materials_search' => $materials,
            'inks_search' => $inks,
            'inks_incl' => $inks_incl == "true" ? true : false,
            'covers_search' => $covers,
            'quire_structures_search' => $quire_structures,
            'quirenum_min' => $quirenum_min,
            'quirenum_max' => $quirenum_max,
            'bifolianum_min' => $bifolianum_min,
            'bifolianum_max' => $bifolianum_max,
            'full_page_width_min' => $full_page_width_min,
            'full_page_width_max' => $full_page_width_max,
            'full_page_height_min' => $full_page_height_min,
            'full_page_height_max' => $full_page_height_max,
            'full_text_block_width_min' => $full_text_block_width_min,
            'full_text_block_width_max' => $full_text_block_width_max,
            'full_text_block_height_min' => $full_text_block_height_min,
            'full_text_block_height_max' => $full_text_block_height_max,
            'upper_margin_min' => $upper_margin_min,
            'upper_margin_max' => $upper_margin_max,
            'lower_margin_min' => $lower_margin_min,
            'lower_margin_max' => $lower_margin_max,
            'inner_margin_min' => $inner_margin_min,
            'inner_margin_max' => $inner_margin_max,
            'outer_margin_min' => $outer_margin_min,
            'outer_margin_max' => $outer_margin_max,
            'full_page_ratio_min' => $full_page_ratio_min,
            'full_page_ratio_max' => $full_page_ratio_max,
            'full_text_block_ratio_min' => $full_text_block_ratio_min,
            'full_text_block_ratio_max' => $full_text_block_ratio_max,
            'uplow_margins_ratio_min' => $uplow_margins_ratio_min,
            'uplow_margins_ratio_max' => $uplow_margins_ratio_max,
            'inout_margins_ratio_min' => $inout_margins_ratio_min,
            'inout_margins_ratio_max' => $inout_margins_ratio_max,
            'hand_number_min' => $hand_number_min,

            'gregorys_rules_search' => $gregorys_rules,
            'columns_min' => $columns_min,
            'columns_max' => $columns_max,
            'columnlines_min' => $columnlines_min,
            'columnlines_max' => $columnlines_max,
        
            'hand_number_max' => $hand_number_max,
            'scripts_search' => $scripts,
            'scripts_incl' => $scripts_incl,
            'diacritics_search' => $diacritics,
            'diacritics_incl' => $diacritics_incl,
            'punctuations_search' => $punctuations,
            'punctuations_incl' => $punctuations_incl,
            'paratexts_search' => $paratexts,
            'paratexts_incl' => $paratexts_incl,
            'decorations_search' => $decorations,
            'decorations_incl' => $decorations_incl,
            'critical_symbols_search' => $critical_symbols,
            'critical_symbols_incl' => $critical_symbols_incl,
            'paginations_search' => $paginations,
            'quire_signatures_search' => $quire_signatures,
            'storage_conditions_search' => $storage_conditions,
            'analyses_search' => $analyses,
            'analyses_incl' => $analyses_incl,
            'scientifically_excavated' => $scientifically_excavated,
            'ancient_provenances_search' => $ancient_provenances,
            'ancient_provenance_certainties_search' => $ancient_provenance_certainties,
            'transactions_search' => $transactions,
            'transactions_incl' => $transactions_incl,
            'transaction_parties_search' => $transaction_parties,
            'transaction_parties_incl' => $transaction_parties_incl,
            'legal_classifications_search' => $legal_classifications,

            'sortfield' => $sortfield,
            'reverse' => $reverse,
            'tab' => $tab,
            'prev' => $prev,
            'next' => $next,
            'current' => $current,
            'total' => $total, 
        ]));
    }

    public function edit(Document $document, Request $request)
    {
        $this->authorize('update', $document);

        $role_id = $request->user() ? $request->user()->role_id : 0;

        $search = $request->validate([
            'show_publication' => 'nullable',
            'show_content' => 'nullable',
            'show_dating' => 'nullable',
            'show_materiality' => 'nullable',
            'show_measurement' => 'nullable',
            'show_palaeography' => 'nullable',
            'show_consanal' => 'nullable',
            'show_provenance' => 'nullable',

            'fulltext' => 'nullable',
            's_standard_name' => 'nullable',
            's_publication' => 'nullable',
            's_current_shelfmarks' => 'nullable',
            's_trismegistos_id' => 'nullable',
            's_title' => 'nullable',
            's_ancient_author' => 'nullable',
            's_genres' => 'nullable',
            's_genres_incl' => 'nullable',
            's_tags' => 'nullable',
            's_tags_incl' => 'nullable',
            's_earliest_date' => 'nullable',
            's_latest_date' => 'nullable',
            's_exclusive_date' => 'nullable',
            's_dating_methods' => 'nullable',
            's_dating_methods_incl' => 'nullable',
            's_dating_certainties' => 'nullable',
            's_materials' => 'nullable',
            's_inks' => 'nullable',
            's_inks_incl' => 'nullable',
            's_covers' => 'nullable',
            's_quire_structures' => 'nullable',
            's_quirenum_min' => 'nullable',
            's_quirenum_max' => 'nullable',
            's_bifolianum_min' => 'nullable',
            's_bifolianum_max' => 'nullable',
            's_full_page_width_min' => 'nullable',
            's_full_page_width_max' => 'nullable',
            's_full_page_height_min' => 'nullable',
            's_full_page_height_max' => 'nullable',
            's_full_text_block_width_min' => 'nullable',
            's_full_text_block_width_max' => 'nullable',
            's_full_text_block_height_min' => 'nullable',
            's_full_text_block_height_max' => 'nullable',
            's_upper_margin_min' => 'nullable',
            's_upper_margin_max' => 'nullable',
            's_lower_margin_min' => 'nullable',
            's_lower_margin_max' => 'nullable',
            's_inner_margin_min' => 'nullable',
            's_inner_margin_max' => 'nullable',
            's_outer_margin_min' => 'nullable',
            's_outer_margin_max' => 'nullable',

            's_full_page_ratio_min' => 'nullable',
            's_full_page_ratio_max' => 'nullable',
            's_full_text_block_ratio_min' => 'nullable',
            's_full_text_block_ratio_max' => 'nullable',
            's_uplow_margins_ratio_min' => 'nullable',
            's_uplow_margins_ratio_max' => 'nullable',
            's_inout_margins_ratio_min' => 'nullable',
            's_inout_margins_ratio_max' => 'nullable',

            's_gregorys_rules' => 'nullable',
            's_columns_min' => 'nullable',
            's_columns_max'=> 'nullable',
            's_columnlines_min'=> 'nullable',
            's_columnlines_max'=> 'nullable',

            's_hand_number_min' => 'nullable',
            's_hand_number_max' => 'nullable',
            's_scripts' => 'nullable',
            's_scripts_incl' => 'nullable',
            's_diacritics' => 'nullable',
            's_diacritics_incl' => 'nullable',
            's_punctuations' => 'nullable',
            's_punctuations_incl' => 'nullable',
            's_paratexts' => 'nullable',
            's_paratexts_incl' => 'nullable',
            's_decorations' => 'nullable',
            's_decorations_incl' => 'nullable',
            's_critical_symbols' => 'nullable',
            's_critical_symbols_incl' => 'nullable',
            's_paginations' => 'nullable',
            's_quire_signatures' => 'nullable',
            's_storage_conditions' => 'nullable',
            's_analyses' => 'nullable',
            's_analyses_incl' => 'nullable',
            's_scientifically_excavated' => 'nullable',
            's_ancient_provenances' => 'nullable',
            's_ancient_provenance_certainties' => 'nullable',
            's_transactions' => 'nullable',
            's_transactions_incl' => 'nullable',
            's_transaction_parties' => 'nullable',
            's_transaction_parties_incl' => 'nullable',
            's_legal_classifications' => 'nullable',
            'sortfield' => 'nullable',
            'reverse' => 'nullable',
            'tab' => 'nullable',
        ]);
     
        $show_publication = $request->session()->get('show_publication');
        $show_content = $request->session()->get('show_content');
        $show_dating = $request->session()->get('show_dating');
        $show_materiality = $request->session()->get('show_materiality');
        $show_measurement = $request->session()->get('show_measurement');
        $show_palaeography = $request->session()->get('show_palaeography');
        $show_consanal = $request->session()->get('show_consanal');
        $show_provenance = $request->session()->get('show_provenance');

        $sortfield = $request->session()->get('sortfield');
        $sortfield = array_key_exists('sortfield', $search) ? $search['sortfield'] : $sortfield;
  
        $request->session()->put('sortfield', $sortfield);
        switch ($sortfield) {
            case 1:
                $sortby = "standard_name";
                break;
            case 2:
                $sortby = "ancient_author";
                break;
            case 3:
                $sortby = "title";
                break;
            case 4:
                $sortby = "start_year";
                break;
            case 5:
                $sortby = "end_year";
                break;
            default:
                $sortby = "start_year";
        }

        $tab = array_key_exists('tab', $search) ? $search['tab'] : 'general';

        $reverse = $request->session()->get('reverse');
        $reverse = array_key_exists('reverse', $search) ? $search['reverse'] : $reverse;
        $request->session()->put('reverse', $reverse);

        $direction = $reverse ? "desc" : "asc";

        $fulltext = array_key_exists('fulltext', $search) ? $search['fulltext'] : null;
        $standard_name = array_key_exists('s_standard_name', $search) ? $search['s_standard_name'] : null;
        $publication = array_key_exists('s_publication', $search) ? $search['s_publication'] : null;
        $current_shelfmarks = array_key_exists('s_current_shelfmarks', $search) ? $search['s_current_shelfmarks'] : null;
        $trismegistos_id = array_key_exists('s_trismegistos_id', $search) ? $search['s_trismegistos_id'] : null;
        $title = array_key_exists('s_title', $search) ? $search['s_title'] : null;
        $ancient_author = array_key_exists('s_ancient_author', $search) ? $search['s_ancient_author'] : null;
        $genres = array_key_exists('s_genres', $search) ? $search['s_genres'] : null;
        $genres_incl = array_key_exists('s_genres_incl', $search) ? $search['s_genres_incl'] : null;
        $tags = array_key_exists('s_tags', $search) ? $search['s_tags'] : null;
        $tags_incl = array_key_exists('s_tags_incl', $search) ? $search['s_tags_incl'] : null;
        $earliest_date = array_key_exists('s_earliest_date', $search) ? $search['s_earliest_date'] : null;
        $latest_date = array_key_exists('s_latest_date', $search) ? $search['s_latest_date'] : null;
        $exclusive_date = array_key_exists('s_exclusive_date', $search) ? $search['s_exclusive_date'] : false;
        $dating_methods = array_key_exists('s_dating_methods', $search) ? $search['s_dating_methods'] : null;
        $dating_methods_incl = array_key_exists('s_dating_methods_incl', $search) ? $search['s_dating_methods_incl'] : null;
        $dating_certainties = array_key_exists('s_dating_certainties', $search) ? $search['s_dating_certainties'] : null;
        $materials = array_key_exists('s_materials', $search) ? $search['s_materials'] : null;
        $inks = array_key_exists('s_inks', $search) ? $search['s_inks'] : null;
        $inks_incl = array_key_exists('s_inks_incl', $search) ? $search['s_inks_incl'] : null;
        $covers = array_key_exists('s_covers', $search) ? $search['s_covers'] : null;
        $quire_structures = array_key_exists('s_quire_structures', $search) ? $search['s_quire_structures'] : null;
        $quirenum_min = array_key_exists('s_quirenum_min', $search) ? $search['s_quirenum_min'] : null;
        $quirenum_max = array_key_exists('s_quirenum_max', $search) ? $search['s_quirenum_max'] : null;
        $bifolianum_min = array_key_exists('s_bifolianum_min', $search) ? $search['s_bifolianum_min'] : null;
        $bifolianum_max = array_key_exists('s_bifolianum_max', $search) ? $search['s_bifolianum_max'] : null;
        $full_page_width_min = array_key_exists('s_full_page_width_min', $search) ? $search['s_full_page_width_min'] : null;
        $full_page_width_max = array_key_exists('s_full_page_width_max', $search) ? $search['s_full_page_width_max'] : null;
        $full_page_height_min = array_key_exists('s_full_page_height_min', $search) ? $search['s_full_page_height_min'] : null;
        $full_page_height_max = array_key_exists('s_full_page_height_max', $search) ? $search['s_full_page_height_max'] : null;
        $full_text_block_width_min = array_key_exists('s_full_text_block_width_min', $search) ? $search['s_full_text_block_width_min'] : null;
        $full_text_block_width_max = array_key_exists('s_full_text_block_width_max', $search) ? $search['s_full_text_block_width_max'] : null;
        $full_text_block_height_min = array_key_exists('s_full_text_block_height_min', $search) ? $search['s_full_text_block_height_min'] : null;
        $full_text_block_height_max = array_key_exists('s_full_text_block_height_max', $search) ? $search['s_full_text_block_height_max'] : null;
        $upper_margin_min = array_key_exists('s_upper_margin_min', $search) ? $search['s_upper_margin_min'] : null;
        $upper_margin_max = array_key_exists('s_upper_margin_max', $search) ? $search['s_upper_margin_max'] : null;
        $lower_margin_min = array_key_exists('s_lower_margin_min', $search) ? $search['s_lower_margin_min'] : null;
        $lower_margin_max = array_key_exists('s_lower_margin_max', $search) ? $search['s_lower_margin_max'] : null;
        $inner_margin_min = array_key_exists('s_inner_margin_min', $search) ? $search['s_inner_margin_min'] : null;
        $inner_margin_max = array_key_exists('s_inner_margin_max', $search) ? $search['s_inner_margin_max'] : null;
        $outer_margin_min = array_key_exists('s_outer_margin_min', $search) ? $search['s_outer_margin_min'] : null;
        $outer_margin_max = array_key_exists('s_outer_margin_max', $search) ? $search['s_outer_margin_max'] : null;

        $full_page_ratio_min = array_key_exists('s_full_page_ratio_min', $search) ? $search['s_full_page_ratio_min'] : null;
        $full_page_ratio_max = array_key_exists('s_full_page_ratio_max', $search) ? $search['s_full_page_ratio_max'] : null;
        $full_text_block_ratio_min = array_key_exists('s_full_text_block_ratio_min', $search) ? $search['s_full_text_block_ratio_min'] : null;
        $full_text_block_ratio_max = array_key_exists('s_full_text_block_ratio_max', $search) ? $search['s_full_text_block_ratio_max'] : null;
        $uplow_margins_ratio_min = array_key_exists('s_uplow_margins_ratio_min', $search) ? $search['s_uplow_margins_ratio_min'] : null;
        $uplow_margins_ratio_max = array_key_exists('s_uplow_margins_ratio_max', $search) ? $search['s_uplow_margins_ratio_max'] : null;
        $inout_margins_ratio_min = array_key_exists('s_inout_margins_ratio_min', $search) ? $search['s_inout_margins_ratio_min'] : null;
        $inout_margins_ratio_max = array_key_exists('s_inout_margins_ratio_max', $search) ? $search['s_inout_margins_ratio_max'] : null;

        $gregorys_rules = array_key_exists('s_gregorys_rules', $search) ? $search['s_gregorys_rules'] : null;
        $columns_min = array_key_exists('s_columns_min', $search) ? $search['s_columns_min'] : null;
        $columns_max = array_key_exists('s_columns_max', $search) ? $search['s_columns_max'] : null;
        $columnlines_min = array_key_exists('s_columnlines_min', $search) ? $search['s_columnlines_min'] : null;
        $columnlines_max = array_key_exists('s_columnlines_max', $search) ? $search['s_columnlines_max'] : null;

        $hand_number_min = array_key_exists('s_hand_number_min', $search) ? $search['s_hand_number_min'] : null;
        $hand_number_max = array_key_exists('s_hand_number_max', $search) ? $search['s_hand_number_max'] : null;
        $scripts = array_key_exists('s_scripts', $search) ? $search['s_scripts'] : null;
        $scripts_incl = array_key_exists('s_scripts_incl', $search) ? $search['s_scripts_incl'] : null;
        $diacritics = array_key_exists('s_diacritics', $search) ? $search['s_diacritics'] : null;
        $diacritics_incl = array_key_exists('s_diacritics_incl', $search) ? $search['s_diacritics_incl'] : null;
        $punctuations = array_key_exists('s_punctuations', $search) ? $search['s_punctuations'] : null;
        $punctuations_incl = array_key_exists('s_punctuations_incl', $search) ? $search['s_punctuations_incl'] : null;
        $paratexts = array_key_exists('s_paratexts', $search) ? $search['s_paratexts'] : null;
        $paratexts_incl = array_key_exists('s_paratexts_incl', $search) ? $search['s_paratexts_incl'] : null;
        $decorations = array_key_exists('s_decorations', $search) ? $search['s_decorations'] : null;
        $decorations_incl = array_key_exists('s_decorations_incl', $search) ? $search['s_decorations_incl'] : null;
        $critical_symbols = array_key_exists('s_critical_symbols', $search) ? $search['s_critical_symbols'] : null;
        $critical_symbols_incl = array_key_exists('s_critical_symbols_incl', $search) ? $search['s_critical_symbols_incl'] : null;
        $paginations = array_key_exists('s_paginations', $search) ? $search['s_paginations'] : null;
        $quire_signatures = array_key_exists('s_quire_signatures', $search) ? $search['s_quire_signatures'] : null;
        $storage_conditions = array_key_exists('s_storage_conditions', $search) ? $search['s_storage_conditions'] : null;
        $analyses = array_key_exists('s_analyses', $search) ? $search['s_analyses'] : null;
        $analyses_incl = array_key_exists('s_analyses_incl', $search) ? $search['s_analyses_incl'] : null;
        $scientifically_excavated = array_key_exists('s_scientifically_excavated', $search) ? $search['s_scientifically_excavated'] : null;
        $ancient_provenances = array_key_exists('s_ancient_provenances', $search) ? $search['s_ancient_provenances'] : null;
        $ancient_provenance_certainties = array_key_exists('s_ancient_provenance_certainties', $search) ? $search['s_ancient_provenance_certainties'] : null;
        $transactions = array_key_exists('s_transactions', $search) ? $search['s_transactions'] : null;
        $transactions_incl = array_key_exists('s_transactions_incl', $search) ? $search['s_transactions_incl'] : null;
        $transaction_parties = array_key_exists('s_transaction_parties', $search) ? $search['s_transaction_parties'] : null;
        $transaction_parties_incl = array_key_exists('s_transaction_parties_incl', $search) ? $search['s_transaction_parties_incl'] : null;
        $legal_classifications = array_key_exists('s_legal_classifications', $search) ? $search['s_legal_classifications'] : null;
        
        if($show_provenance AND $ancient_provenances) {
            $children = [];

            foreach($ancient_provenances as $ancient_provenance) {
                $prov = AncientProvenance::find($ancient_provenance['id']);
                $childrec = $prov->flatChildren();

                foreach($childrec as $child) {
                    $childarr = [
                        "id" => $child->id,
                        "name" => $child->name
                    ];
                    array_push($ancient_provenances, $childarr);
                } 
            }
        }
 
        $all_documents = Document::query()
            ->when($role_id < 2, function ($query) {
                $query->where('published', '=', true);
            }) 
            ->when($fulltext, function ($query) use ($fulltext, $role_id) {
                $query->where(function ($query) use ($fulltext, $role_id) {
                    $query->where('standard_name', 'like', "%{$fulltext}%")
                        ->orWhere('standard_name', 'like', "%{$fulltext}%")
                        ->orWhere('other_names', 'like', "%{$fulltext}%")
                        ->orWhere('trismegistos_id', 'like', "%{$fulltext}%")
                        ->orWhere('publication', 'like', "%{$fulltext}%")
                        ->orWhere('current_shelfmarks', 'like', "%{$fulltext}%")
                        ->orWhere('title', 'like', "%{$fulltext}%")
                        ->orWhere('ancient_author', 'like', "%{$fulltext}%")
                        ->orWhere('content_description', 'like', "%{$fulltext}%")
                        ->orWhere('language_comment', 'like', "%{$fulltext}%")
                        ->orWhere('dating_comment', 'like', "%{$fulltext}%")
                        ->orWhere('general_comment', 'like', "%{$fulltext}%")
                        ->orWhere('measurement_comment', 'like', "%{$fulltext}%")
                        ->orWhere('full_text_block_comment', 'like', "%{$fulltext}%")
                        ->orWhere('script_description', 'like', "%{$fulltext}%")
                        ->orWhere('decoration_description', 'like', "%{$fulltext}%")
                        ->orWhere('paratext_description', 'like', "%{$fulltext}%")
                        ->orWhere('diacritic_description', 'like', "%{$fulltext}%")
                        ->orWhere('punctuation_description', 'like', "%{$fulltext}%")
                        ->orWhere('critical_symbols_description', 'like', "%{$fulltext}%")
                        ->orWhere('pagination_description', 'like', "%{$fulltext}%")
                        ->orWhere('quire_signatures_description', 'like', "%{$fulltext}%")
                        ->orWhere('quire_comment', 'like', "%{$fulltext}%")
                        ->orWhere('binding_description', 'like', "%{$fulltext}%")
                        ->orWhere('inks_description', 'like', "%{$fulltext}%")
                        ->orWhere('cover_description', 'like', "%{$fulltext}%")
                        ->orWhere('gregorys_rule_comment', 'like', "%{$fulltext}%")
                        ->orWhere('conservation_history', 'like', "%{$fulltext}%")
                        ->orWhere('analyses_comment', 'like', "%{$fulltext}%")
                        ->orWhere('ancient_provenance_comment', 'like', "%{$fulltext}%")
                        ->orWhere('legal_classification_explanation', 'like', "%{$fulltext}%")
                        ->orWhere('bibliography', 'like', "%{$fulltext}%")
                        ->orWhere('images_info', 'like', "%{$fulltext}%")
                        ->orWhere('excavation_comment', 'like', "%{$fulltext}%")
                        ->orWhere('internal_comment', 'like', $role_id >= 2 ? "%{$fulltext}%" : "notsomethinganyonewouldwrite");
                });
            })
            ->when($show_publication == "true", function ($query) use (
                $standard_name,
                $publication,
                $current_shelfmarks,
                $trismegistos_id
            ) {
                $query->when($standard_name, function ($query, $standard_name) {
                    $query->where('standard_name', 'like', "%{$standard_name}%");
                })
                    ->when($publication, function ($query, $publication) {
                        $query->where('publication', 'like', "%{$publication}%");
                    })
                    ->when($current_shelfmarks, function ($query, $current_shelfmarks) {
                        $query->where('current_shelfmarks', 'like', "%{$current_shelfmarks}%");
                    })
                    ->when($trismegistos_id, function ($query, $trismegistos_id) {
                        $query->where('trismegistos_id', '=', $trismegistos_id);
                    });
            })
            ->when($show_content == "true", function ($query) use (
                $title,
                $ancient_author,
                $genres,
                $genres_incl,
                $tags,
                $tags_incl,
            ) {
                $query->when($title, function ($query, $title) {
                    $query->where('title', 'like', "%{$title}%");
                })
                    ->when($ancient_author, function ($query, $ancient_author) {
                        $query->where('ancient_author', 'like', "%{$ancient_author}%");
                    })
                    ->when($genres && !$genres_incl, function ($query) use ($genres) {
                        $query->whereHas('genres', function ($query) use ($genres) {
                            $query->whereIn('genres.id', array_column($genres, 'id'));
                        });
                    })
                    ->when($genres && $genres_incl, function ($query) use ($genres) {
                        foreach($genres as $genre) {                                
                            $query->whereHas('genres', function($query) use ($genre) {
                                $query->where('genres.id', '=', $genre['id']);
                            });
                        }
                    }) 
                    ->when($tags && !$tags_incl, function ($query) use ($tags) {
                        $query->whereHas('tags', function ($query) use ($tags) {
                            $query->whereIn('tags.id', array_column($tags, 'id'));
                        });
                    })
                    ->when($tags && $tags_incl, function ($query) use ($tags) {
                        foreach($tags as $tag) {                                
                            $query->whereHas('tags', function($query) use ($tag) {
                                $query->where('tags.id', '=', $tag['id']);
                            });
                        }
                    }); 
            })
            ->when($show_dating == "true", function ($query) use (
                $earliest_date,
                $latest_date,
                $exclusive_date,
                $dating_methods,
                $dating_methods_incl,
                $dating_certainties
            ) {
                $query->when($exclusive_date == "true", function ($query) use ($earliest_date, $latest_date, $exclusive_date) {
                    $query->when($earliest_date, function ($query, $earliest_date) {
                        $query->where('start_year', '>=', $earliest_date);
                    })
                        ->when($latest_date, function ($query, $latest_date) {
                            $query->where('end_year', '<=', $latest_date);
                        });
                }, function ($query) use ($earliest_date, $latest_date) {
                    $query->when($earliest_date, function ($query, $earliest_date) {
                        $query->where('end_year', '>=', $earliest_date);
                    })
                        ->when($latest_date, function ($query, $latest_date) {
                            $query->where('start_year', '<=', $latest_date);
                        });
                })
                    ->when($dating_methods && !$dating_methods_incl, function ($query) use ($dating_methods) {
                        $query->whereHas('dating_methods', function ($query) use ($dating_methods) {
                            $query->whereIn('dating_methods.id', array_column($dating_methods, 'id'));
                        });
                    })
                    ->when($dating_methods && $dating_methods_incl, function ($query) use ($dating_methods) {
                        foreach($dating_methods as $dating_method) {                                
                            $query->whereHas('dating_methods', function($query) use ($dating_method) {
                                $query->where('dating_methods.id', '=', $dating_method['id']);
                            });
                        }
                    }) 
                    ->when($dating_certainties, function ($query, $dating_certainties) {
                        $query->whereIn('dating_certainty_id', array_column($dating_certainties, 'id'));
                    });
            })
            ->when(
                $show_materiality == "true",
                function ($query) use (
                    $materials,
                    $gregorys_rules,
                    $inks,
                    $inks_incl,
                    $covers,
                    $quire_structures,
                    $quirenum_min,
                    $quirenum_max,
                    $bifolianum_min,
                    $bifolianum_max
                ) {
                        $query->when($materials, function ($query, $materials) {
                            $query->whereIn('material_id', array_column($materials, 'id'));
                        })
                        ->when($gregorys_rules, function ($query, $gregorys_rules) {
                            $query->whereIn('gregorys_rule_id', array_column($gregorys_rules, 'id'));
                        })
                        ->when(($inks && !$inks_incl), function ($query) use ($inks) {
                            $query->whereHas('inks', function ($query) use ($inks) {
                                $query->whereIn('inks.id', array_column($inks, 'id'));
                            });
                        })
                        ->when($inks && $inks_incl, function ($query) use ($inks) {
                            foreach($inks as $ink) {                                
                                $query->whereHas('inks', function($query) use ($ink) {
                                    $query->where('inks.id', '=', $ink['id']);
                                });
                            }
                        })                      
                        ->when($covers, function ($query, $covers) {
                            $query->whereIn('cover_id', array_column($covers, 'id'));
                        })
                        ->when($quire_structures, function ($query, $quire_structures) {
                            $query->whereIn('quire_structure_id', array_column($quire_structures, 'id'));
                        })
                        ->when($quirenum_min, function ($query, $quirenum_min) {
                            $query->where('quire_number', '>=', $quirenum_min);
                        })
                        ->when($quirenum_max, function ($query, $quirenum_max) {
                            $query->where('quire_number', '<=', $quirenum_max);
                        })
                        ->when($bifolianum_min, function ($query, $bifolianum_min) {
                            $query->where('bifolia_number', '>=', $bifolianum_min);
                        })
                        ->when($bifolianum_max, function ($query, $bifolianum_max) {
                            $query->where('bifolia_number', '<=', $bifolianum_max);
                        });
                }
            )

            ->when(
                $show_measurement == "true",
                function ($query) use (
                    $full_page_width_min,
                    $full_page_width_max,
                    $full_page_height_min,
                    $full_page_height_max,
                    $full_text_block_width_min,
                    $full_text_block_width_max,
                    $full_text_block_height_min,
                    $full_text_block_height_max,
                    $upper_margin_min,
                    $upper_margin_max,
                    $lower_margin_min,
                    $lower_margin_max,
                    $inner_margin_min,
                    $inner_margin_max,
                    $outer_margin_min,
                    $outer_margin_max,

                    $full_page_ratio_min,
                    $full_page_ratio_max,
                    $full_text_block_ratio_min,
                    $full_text_block_ratio_max,
                    $uplow_margins_ratio_min,
                    $uplow_margins_ratio_max,
                    $inout_margins_ratio_min,
                    $inout_margins_ratio_max,

                    $columns_min,
                    $columns_max,
                    $columnlines_min,
                    $columnlines_max,
                ) {
                    $query->when($full_page_width_min, function ($query, $full_page_width_min) {
                        $query->where('full_page_width', '>=', $full_page_width_min);
                    })
                        ->when($full_page_width_max, function ($query, $full_page_width_max) {
                            $query->where('full_page_width', '<=', $full_page_width_max);
                        })
                        ->when($full_page_height_min, function ($query, $full_page_height_min) {
                            $query->where('full_page_height', '>=', $full_page_height_min);
                        })
                        ->when($full_page_height_max, function ($query, $full_page_height_max) {
                            $query->where('full_page_height', '<=', $full_page_height_max);
                        })
                        ->when($full_text_block_width_min, function ($query, $full_text_block_width_min) {
                            $query->where('full_text_block_width', '>=', $full_text_block_width_min);
                        })
                        ->when($full_text_block_width_max, function ($query, $full_text_block_width_max) {
                            $query->where('full_text_block_width', '<=', $full_text_block_width_max);
                        })
                        ->when($full_text_block_height_min, function ($query, $full_text_block_height_min) {
                            $query->where('full_text_block_height', '>=', $full_text_block_height_min);
                        })
                        ->when($full_text_block_height_max, function ($query, $full_text_block_height_max) {
                            $query->where('full_text_block_height', '<=', $full_text_block_height_max);
                        })
                        ->when($upper_margin_min, function ($query, $upper_margin_min) {
                            $query->where('upper_margin', '>=', $upper_margin_min);
                        })
                        ->when($upper_margin_max, function ($query, $upper_margin_max) {
                            $query->where('upper_margin', '<=', $upper_margin_max);
                        })
                        ->when($lower_margin_min, function ($query, $lower_margin_min) {
                            $query->where('lower_margin', '>=', $lower_margin_min);
                        })
                        ->when($lower_margin_max, function ($query, $lower_margin_max) {
                            $query->where('lower_margin', '<=', $lower_margin_max);
                        })
                        ->when($inner_margin_min, function ($query, $inner_margin_min) {
                            $query->where('inner_margin', '>=', $inner_margin_min);
                        })
                        ->when($inner_margin_max, function ($query, $inner_margin_max) {
                            $query->where('inner_margin', '<=', $inner_margin_max);
                        })
                        ->when($outer_margin_min, function ($query, $outer_margin_min) {
                            $query->where('outer_margin', '>=', $outer_margin_min);
                        })
                        ->when($outer_margin_max, function ($query, $outer_margin_max) {
                            $query->where('outer_margin', '<=', $outer_margin_max);
                        })
                        ->when($full_page_ratio_min, function ($query, $full_page_ratio_min) {
                            $query->where(DB::raw('full_page_width / NULLIF(full_page_height, 0)'), '>=', $full_page_ratio_min);
                        })
                        ->when($full_page_ratio_max, function ($query, $full_page_ratio_max) {
                            $query->where(DB::raw('full_page_width / NULLIF(full_page_height, 0)'), '<=', $full_page_ratio_max);
                        })
                        ->when($full_text_block_ratio_min, function ($query, $full_text_block_ratio_min) {
                            $query->where(DB::raw('full_text_block_width / NULLIF(full_text_block_height, 0)'), '>=', $full_text_block_ratio_min);
                        })
                        ->when($full_text_block_ratio_max, function ($query, $full_text_block_ratio_max) {
                            $query->where(DB::raw('full_text_block_width / NULLIF(full_text_block_height, 0)'), '<=', $full_text_block_ratio_max);
                        })
                        ->when($uplow_margins_ratio_min, function ($query, $uplow_margins_ratio_min) {
                            $query->where(DB::raw('upper_margin / NULLIF(lower_margin, 0)'), '>=', $uplow_margins_ratio_min);
                        })
                        ->when($uplow_margins_ratio_max, function ($query, $uplow_margins_ratio_max) {
                            $query->where(DB::raw('upper_margin / NULLIF(lower_margin, 0)'), '<=', $uplow_margins_ratio_max);
                        })
                        ->when($inout_margins_ratio_min, function ($query, $inout_margins_ratio_min) {
                            $query->where(DB::raw('inner_margin / NULLIF(outer_margin, 0)'), '>=', $inout_margins_ratio_min);
                        })
                        ->when($inout_margins_ratio_max, function ($query, $inout_margins_ratio_max) {
                            $query->where(DB::raw('inner_margin / NULLIF(outer_margin, 0)'), '<=', $inout_margins_ratio_max);
                        })
                        ->when($columns_min, function ($query, $columns_min) {
                            $query->where('columns', '>=', $columns_min);
                        })
                        ->when($columns_max, function ($query, $columns_max) {
                            $query->where('columns', '>=', $columns_max);
                        })
                        ->when($columnlines_min, function ($query, $columnlines_min) {
                            $query->where('columnlines', '>=', $columnlines_min);
                        })
                        ->when($columnlines_max, function ($query, $columnlines_max) {
                            $query->where('columnlines', '>=', $columnlines_max);
                        });
                }
            )
            ->when(
                $show_palaeography == "true",
                function ($query) use (
                    $hand_number_min,
                    $hand_number_max,
                    $scripts,
                    $scripts_incl,
                    $diacritics,
                    $diacritics_incl,
                    $punctuations,
                    $punctuations_incl,
                    $paratexts,
                    $paratexts_incl,
                    $decorations,
                    $decorations_incl,
                    $critical_symbols,
                    $critical_symbols_incl,
                    $paginations,
                    $quire_signatures
                ) {
                    $query->when($hand_number_min, function ($query, $hand_number_min) {
                        $query->where('hand_number', '>=', $hand_number_min);
                    })
                        ->when($hand_number_max, function ($query, $hand_number_max) {
                            $query->where('hand_number', '<=', $hand_number_max);
                        })
                        ->when($scripts && !$scripts_incl, function ($query) use ($scripts) {
                            $query->whereHas('scripts', function ($query) use ($scripts) {
                                $query->whereIn('scripts.id', array_column($scripts, 'id'));
                            });
                        })
                        ->when($scripts && $scripts_incl, function ($query) use ($scripts) {
                            foreach($scripts as $script) {                                
                                $query->whereHas('scripts', function($query) use ($script) {
                                    $query->where('scripts.id', '=', $script['id']);
                                });
                            }
                        }) 
                        ->when($diacritics && !$diacritics_incl, function ($query) use ($diacritics) {
                            $query->whereHas('diacritics', function ($query) use ($diacritics) {
                                $query->whereIn('diacritics.id', array_column($diacritics, 'id'));
                            });
                        })
                        ->when($diacritics && $diacritics_incl, function ($query) use ($diacritics) {
                            foreach($diacritics as $diacritic) {                                
                                $query->whereHas('diacritics', function($query) use ($diacritic) {
                                    $query->where('diacritics.id', '=', $diacritic['id']);
                                });
                            }
                        }) 
                        ->when($punctuations && !$punctuations_incl, function ($query) use ($punctuations) {
                            $query->whereHas('punctuations', function ($query) use ($punctuations) {
                                $query->whereIn('punctuations.id', array_column($punctuations, 'id'));
                            });
                        })
                        ->when($punctuations && $punctuations_incl, function ($query) use ($punctuations) {
                            foreach($punctuations as $punctuation) {                                
                                $query->whereHas('punctuations', function($query) use ($punctuation) {
                                    $query->where('punctuations.id', '=', $punctuation['id']);
                                });
                            }
                        }) 
                        ->when($paratexts && !$paratexts_incl, function ($query) use ($paratexts) {
                            $query->whereHas('paratexts', function ($query) use ($paratexts) {
                                $query->whereIn('paratexts.id', array_column($paratexts, 'id'));
                            });
                        })
                        ->when($paratexts && $paratexts_incl, function ($query) use ($paratexts) {
                            foreach($paratexts as $paratext) {                                
                                $query->whereHas('paratexts', function($query) use ($paratext) {
                                    $query->where('paratexts.id', '=', $paratext['id']);
                                });
                            }
                        }) 
                        ->when($decorations && !$decorations_incl, function ($query) use ($decorations) {
                            $query->whereHas('decorations', function ($query) use ($decorations) {
                                $query->whereIn('decorations.id', array_column($decorations, 'id'));
                            });
                        })
                        ->when($decorations && $decorations_incl, function ($query) use ($decorations) {
                            foreach($decorations as $decoration) {                                
                                $query->whereHas('decorations', function($query) use ($decoration) {
                                    $query->where('decorations.id', '=', $decoration['id']);
                                });
                            }
                        }) 
                        ->when($critical_symbols && !$critical_symbols_incl, function ($query) use ($critical_symbols) {
                            $query->whereHas('critical_symbols', function ($query) use ($critical_symbols) {
                                $query->whereIn('critical_symbols.id', array_column($critical_symbols, 'id'));
                            });
                        })
                        ->when($critical_symbols && $critical_symbols_incl, function ($query) use ($critical_symbols) {
                            foreach($critical_symbols as $critical_symbol) {                                
                                $query->whereHas('critical_symbols', function($query) use ($critical_symbol) {
                                    $query->where('critical_symbols.id', '=', $critical_symbol['id']);
                                });
                            }
                        }) 
                        ->when($paginations, function ($query, $paginations) {
                            $query->whereIn('pagination_id', array_column($paginations, 'id'));
                        })
                        ->when($quire_signatures, function ($query, $quire_signatures) {
                            $query->whereIn('quire_signature_id', array_column($quire_signatures, 'id'));
                        });
                }
            )
            ->when(
                $show_consanal == "true",
                function ($query) use (
                    $storage_conditions,
                    $analyses,
                    $analyses_incl,
                ) {
                    $query->when($storage_conditions, function ($query, $storage_conditions) {
                        $query->whereIn('storage_condition_id', array_column($storage_conditions, 'id'));
                    })
                        ->when($analyses && !$analyses_incl, function ($query) use ($analyses) {
                            $query->whereHas('analyses', function ($query) use ($analyses) {
                                $query->whereIn('analyses.id', array_column($analyses, 'id'));
                            });
                        })
                        ->when($analyses && $analyses_incl, function ($query) use ($analyses) {
                            foreach($analyses as $analysis) {                                
                                $query->whereHas('analyses', function($query) use ($analysis) {
                                    $query->where('analyses.id', '=', $analysis['id']);
                                });
                            }
                        });
                }
            )
            ->when(
                $show_provenance == "true",
                function ($query) use (
                    $scientifically_excavated,
                    $ancient_provenances,
                    $ancient_provenance_certainties,
                    $transactions,
                    $transactions_incl,
                    $transaction_parties,
                    $transaction_parties_incl,
                    $legal_classifications
                ) {
                    $query->when($scientifically_excavated, function ($query, $scientifically_excavated) {
                        $query->where('scientifically_excavated', '=', 1);
                    })
                        ->when($ancient_provenances, function ($query, $ancient_provenances) {
                            $query->whereIn('ancient_provenance_id', array_column($ancient_provenances, 'id'));
                        })
                        ->when($ancient_provenance_certainties, function ($query, $ancient_provenance_certainties) {
                            $query->whereIn('ancient_provenance_certainty_id', array_column($ancient_provenance_certainties, 'id'));
                        })
                        ->when($transactions && !$transactions_incl, function ($query) use ($transactions) {
                            $query->whereHas('transactions', function ($query) use ($transactions) {
                                $query->whereIn('transactions.id', array_column($transactions, 'id'));
                            });
                        })
                        ->when($transactions && $transactions_incl, function ($query) use ($transactions) {
                            foreach($transactions as $transaction) {                                
                                $query->whereHas('transactions', function($query) use ($transaction) {
                                    $query->where('transactions.id', '=', $transaction['id']);
                                });
                            }
                        }) 

                        ->when($transaction_parties && !$transaction_parties_incl, function ($query) use ($transaction_parties) {
                            $tactions = Transaction::query()
                                ->whereHas('transaction_parties', function ($query) use ($transaction_parties) {
                                    $query->whereIn('transaction_parties.id', array_column($transaction_parties, 'id'));
                                })->get()->all();
                            $query->whereHas('transactions', function ($query) use ($tactions) {
                                $query->whereIn('transactions.id', array_column($tactions, 'id'));
                            });
                        })

                        ->when($transaction_parties && $transaction_parties_incl, function ($query) use ($transaction_parties) {
                            
                            $query->whereHas('transactions', function ($query) use ($transaction_parties) {
                                foreach($transaction_parties as $transaction_party) {
                                    $query->whereHas('transaction_parties', function ($query) use ($transaction_party) {
                                        $query->where('transaction_parties.id', '=', $transaction_party['id']);
                                    });
                                }
                            });
                        })
                        ->when($legal_classifications, function ($query, $legal_classifications) {
                            $query->whereIn('legal_classification_id', array_column($legal_classifications, 'id'));
                        });
                    })
            ->orderBy($sortby, $direction)
            ->orderBy('end_year', $direction)->get();

            $prev = -1;
            $next = -1;
            $current = -1;
            $savedprev = -1;
            $found = false;
            $count = 0;
            $total = $all_documents->count();

            foreach ($all_documents as $doc){
                if($found) {
                    $next = $doc['id'];
                }

                $count++;

                if($doc['id'] === $document['id']) {
                    $found = true;
                    $prev = $savedprev;
                    $current = $count;
                }
                else {
                    $found = false;
                }
                $savedprev = $doc['id'];
            }

        $transactions_all = DB::table('transactions')->orderBy('year')->orderBy('month')->orderBy('day')->get();

        return (Inertia::render('CodexEdit', [
            'document' => $document,
            'analyses' => $document->analyses()->get()->makeHidden('pivot'),
            'analyses_all' => Analysis::all(),
            'ancient_provenances' => AncientProvenance::all(),
            'ancient_provenance' => $document->ancient_provenance()->get(),
            'ancient_provenance_certainties' => AncientProvenanceCertainty::all(),
            'ancient_provenance_certainty' => $document->ancient_provenance_certainty()->get(),
            'critical_symbols' => $document->critical_symbols()->get()->makeHidden('pivot'),
            'critical_symbols_all' => CriticalSymbol::all(),
            'covers' => Cover::all(),
            'cover' => $document->cover()->get(),
            'dating_certainties' => DatingCertainty::all(),
            'dating_certainty' => $document->dating_certainty()->get(),
            'dating_methods' => $document->dating_methods()->get()->makeHidden('pivot'),
            'dating_methods_all' => DatingMethod::all(),
            'decorations' => $document->decorations()->get()->makeHidden('pivot'),
            'decorations_all' => Decoration::all(),
            'diacritics' => $document->diacritics()->get()->makeHidden('pivot'),
            'diacritics_all' => Diacritic::all(),
            'genres' => $document->genres()->get()->makeHidden('pivot'),
            'genres_all' => Genre::all(),
            'gregorys_rules' => GregorysRule::all(),
            'gregorys_rule' => $document->gregorys_rule()->get(),
            'images' => $document->images()->with('license')->get(),
            'inks' => $document->inks()->get()->makeHidden('pivot'),
            'inks_all' => Ink::all(),
            'languages' => $document->languages()->get()->makeHidden('pivot'),
            'languages_all' => Language::all(),
            'legal_classifications' => LegalClassification::all(),
            'legal_classification' => $document->legal_classification()->get(),
            'licenses' => License::all(),
            'materials' => Material::all(),
            'material' => $document->material()->get(),
            'modern_collections' => $document->modern_collections()->get()->makeHidden('pivot'),
            'modern_collections_all' => ModernCollection::all(),
            'paginations' => Pagination::all(),
            'pagination' => $document->pagination()->get(),
            'paratexts' => $document->paratexts()->get()->makeHidden('pivot'),
            'paratexts_all' => Paratext::all(),
            'punctuations' => $document->punctuations()->get()->makeHidden('pivot'),
            'punctuations_all' => Punctuation::all(),
            'transactions' => $document->transactions()
                                            ->get()->makeHidden('pivot')
                                            ->sortBy('year')
                                            ->sortBy('month')
                                            ->sortBy('day'),
            'transactions_all' => $transactions_all,
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

            'fulltext' => $fulltext,
            'standard_name' => $standard_name,
            'publication' => $publication,
            'current_shelfmarks' => $current_shelfmarks,
            'trismegistos_id' => $trismegistos_id,
            'title' => $title,
            'ancient_author' => $ancient_author,
            'genres_search' => $genres,
            'genres_incl' => $genres_incl,
            'tags_search' => $tags,
            'tags_incl' => $tags_incl,
            'earliest_date' => $earliest_date,
            'latest_date' => $latest_date,
            'exclusive_date' => $exclusive_date == "true" ? true : false,
            'dating_methods_search' => $dating_methods,
            'dating_methods_incl' => $dating_methods_incl,
            'dating_certainties_search' => $dating_certainties,
            'materials_search' => $materials,
            'inks_search' => $inks,
            'inks_incl' => $inks_incl == "true" ? true : false,
            'covers_search' => $covers,
            'quire_structures_search' => $quire_structures,
            'quirenum_min' => $quirenum_min,
            'quirenum_max' => $quirenum_max,
            'bifolianum_min' => $bifolianum_min,
            'bifolianum_max' => $bifolianum_max,
            'full_page_width_min' => $full_page_width_min,
            'full_page_width_max' => $full_page_width_max,
            'full_page_height_min' => $full_page_height_min,
            'full_page_height_max' => $full_page_height_max,
            'full_text_block_width_min' => $full_text_block_width_min,
            'full_text_block_width_max' => $full_text_block_width_max,
            'full_text_block_height_min' => $full_text_block_height_min,
            'full_text_block_height_max' => $full_text_block_height_max,
            'upper_margin_min' => $upper_margin_min,
            'upper_margin_max' => $upper_margin_max,
            'lower_margin_min' => $lower_margin_min,
            'lower_margin_max' => $lower_margin_max,
            'inner_margin_min' => $inner_margin_min,
            'inner_margin_max' => $inner_margin_max,
            'outer_margin_min' => $outer_margin_min,
            'outer_margin_max' => $outer_margin_max,
            'full_page_ratio_min' => $full_page_ratio_min,
            'full_page_ratio_max' => $full_page_ratio_max,
            'full_text_block_ratio_min' => $full_text_block_ratio_min,
            'full_text_block_ratio_max' => $full_text_block_ratio_max,
            'uplow_margins_ratio_min' => $uplow_margins_ratio_min,
            'uplow_margins_ratio_max' => $uplow_margins_ratio_max,
            'inout_margins_ratio_min' => $inout_margins_ratio_min,
            'inout_margins_ratio_max' => $inout_margins_ratio_max,

            'gregorys_rules_search' => $gregorys_rules,
            'columns_min' => $columns_min,
            'columns_max' => $columns_max,
            'columnlines_min' => $columnlines_min,
            'columnlines_max' => $columnlines_max,
        
            'hand_number_min' => $hand_number_min,
            'hand_number_max' => $hand_number_max,
            'scripts_search' => $scripts,
            'scripts_incl' => $scripts_incl,
            'diacritics_search' => $diacritics,
            'diacritics_incl' => $diacritics_incl,
            'punctuations_search' => $punctuations,
            'punctuations_incl' => $punctuations_incl,
            'paratexts_search' => $paratexts,
            'paratexts_incl' => $paratexts_incl,
            'decorations_search' => $decorations,
            'decorations_incl' => $decorations_incl,
            'critical_symbols_search' => $critical_symbols,
            'critical_symbols_incl' => $critical_symbols_incl,
            'paginations_search' => $paginations,
            'quire_signatures_search' => $quire_signatures,
            'storage_conditions_search' => $storage_conditions,
            'analyses_search' => $analyses,
            'analyses_incl' => $analyses_incl,
            'scientifically_excavated' => $scientifically_excavated,
            'ancient_provenances_search' => $ancient_provenances,
            'ancient_provenance_certainties_search' => $ancient_provenance_certainties,
            'transactions_search' => $transactions,
            'transactions_incl' => $transactions_incl,
            'transaction_parties_search' => $transaction_parties,
            'transaction_parties_incl' => $transaction_parties_incl,
            'legal_classifications_search' => $legal_classifications,

            'sortfield' => $sortfield,
            'reverse' => $reverse,
            'tab' => $tab,
            'prev' => $prev,
            'next' => $next,
            'current' => $current,
            'total' => $total,
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

        $role_id = $request->user() ? $request->user()->role_id : 0;

        $fields = $request->validate([
            'id' => 'required',
            'published' => 'nullable',
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
            'dating_methods' => 'nullable',
            'dating_certainty_id' => 'nullable',
            'dating_comment' => 'nullable',
            'bibliography' => 'nullable',
            'internal_comment' => 'nullable',
            'general_comment' => 'nullable',
            'material_id' => 'nullable',
            'page_dimensions_known' => 'nullable',
            'fragment_width' => 'nullable',
            'fragment_height' => 'nullable',
            'textbox_size_stable' => 'nullable',
            'full_page_width' => 'nullable',
            'full_page_height' => 'nullable',
            'upper_margin' => 'nullable',
            'lower_margin' => 'nullable',
            'inner_margin' => 'nullable',
            'outer_margin' => 'nullable',
            'full_text_block_width' => 'nullable',
            'full_text_block_height' => 'nullable',
            'full_text_block_comment' => 'nullable',
            'measurement_comment' => 'nullable',
            'gregorys_rule_id' => 'nullable',
            'gregorys_rule_comment' => 'nullable',
            'columns' => 'nullable',
            'columnlines' => 'nullable',
            'scripts' => 'nullable',
            'hand_number' => 'nullable',
            'script_description' => 'nullable',
            'paratexts' => 'nullable',
            'paratext_description' => 'nullable',
            'punctuations' => 'nullable',
            'critical_symbols' => 'nullable',
            'decorations' => 'nullable',
            'decoration_description' => 'nullable',
            'pagination_id' => 'nullable',
            'diacritics' => 'nullable',
            'diacritic_description' => 'nullable',
            'punctuation_description' => 'nullable',
            'critical_symbols_description' => 'nullable',
            'pagination_description' => 'nullable',
            'quire_signatures_description' => 'nullable',
            'cover_id' => 'nullable',
            'inks' => 'nullable',
            'quire_signature_id' => 'nullable',
            'quire_structure_id' => 'nullable',
            'quire_number' => 'nullable',
            'bifolia' => 'nullable',
            'quire_comment' => 'nullable',
            'binding_description' => 'nullable',
            'inks_description' => 'nullable',
            'cover_description' => 'nullable',
            'storage_condition_id' => 'nullable',
            'conservation_history' => 'nullable',
            'analyses' => 'nullable',
            'analyses_comment' => 'nullable',
            'ancient_provenance_id' => 'nullable',
            'ancient_provenance_certainty_id' => 'nullable',
            'ancient_provenance_comment' => 'nullable',
            'scientifically_excavated' => 'nullable',
            'excavation_comment' => 'nullable',
            'modern_collections' => 'nullable',
            'legal_classification_id' => 'nullable',
            'legal_classification_explanation' => 'nullable',
            'transactions' => 'nullable',
            'images_info' => 'nullable',
            'images' => 'nullable',
        ]);

        $img_fields = $request->validate([
            'images' => 'nullable',
        ]);


        $document->published = $fields['published'];
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
        $document->dating_certainty_id = $fields['dating_certainty_id'];
        $document->dating_comment = $fields['dating_comment'];
        $document->bibliography = $fields['bibliography'];
        $document->internal_comment = $fields['internal_comment'];
        $document->general_comment = $fields['general_comment'];
        $document->material_id = $fields['material_id'];
        $document->page_dimensions_known = $fields['page_dimensions_known'];
        $document->fragment_width = $fields['fragment_width'];
        $document->fragment_height = $fields['fragment_height'];
        $document->full_page_width = $fields['full_page_width'];
        $document->full_page_height = $fields['full_page_height'];
        $document->textbox_size_stable = $fields['textbox_size_stable'];
        $document->upper_margin = $fields['upper_margin'];
        $document->lower_margin = $fields['lower_margin'];
        $document->inner_margin = $fields['inner_margin'];
        $document->outer_margin = $fields['outer_margin'];
        $document->full_text_block_width = $fields['full_text_block_width'];
        $document->full_text_block_height = $fields['full_text_block_height'];
        $document->full_text_block_comment = $fields['full_text_block_comment'];
        $document->measurement_comment = $fields['measurement_comment'];
        $document->gregorys_rule_id = $fields['gregorys_rule_id'];
        $document->gregorys_rule_comment = $fields['gregorys_rule_comment'];
        $document->columns = $fields['columns'];
        $document->columnlines = $fields['columnlines'];
        $document->hand_number = $fields['hand_number'];
        $document->script_description = $fields['script_description'];
        $document->paratext_description = $fields['paratext_description'];
        $document->diacritic_description = $fields['diacritic_description'];
        $document->decoration_description = $fields['decoration_description'];
        $document->inks_description = $fields['inks_description'];
        $document->cover_description = $fields['cover_description'];
        $document->punctuation_description = $fields['punctuation_description'];
        $document->critical_symbols_description = $fields['critical_symbols_description'];
        $document->pagination_description = $fields['pagination_description'];
        $document->quire_signatures_description = $fields['quire_signatures_description'];
        $document->pagination_id = $fields['pagination_id'];
        $document->cover_id = $fields['cover_id'];
        $document->quire_signature_id = $fields['quire_signature_id'];
        $document->quire_structure_id = $fields['quire_structure_id'];
        if ($fields['quire_structure_id'] == 1) {
            $fields['quire_number'] = 1;
            $fields['bifolia'] = array_slice($fields['bifolia'], 0, 1);
        } elseif ($fields['quire_structure_id'] == 2) {
            $fields['bifolia'] = array_slice($fields['bifolia'], 0, $fields['quire_number']);
        } elseif ($fields['quire_structure_id'] == 3) {
            $fields['bifolia'] = array_slice($fields['bifolia'], 0, 1);
        } else {
            $fields['quire_number'] = null;
            $fields['bifolia'] = ["1"];
        }
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
        $document->scientifically_excavated = $fields['scientifically_excavated'];
        $document->excavation_comment = $fields['excavation_comment'];
        $document->legal_classification_id = $fields['legal_classification_id'];
        $document->legal_classification_explanation = $fields['legal_classification_explanation'];
        $document->images_info = $fields['images_info'];

        $document->languages()->sync(array_column($fields['languages'], 'id'));
        $document->scripts()->sync(array_column($fields['scripts'], 'id'));
        $document->paratexts()->sync(array_column($fields['paratexts'], 'id'));
        $document->diacritics()->sync(array_column($fields['diacritics'], 'id'));
        $document->punctuations()->sync(array_column($fields['punctuations'], 'id'));
        $document->critical_symbols()->sync(array_column($fields['critical_symbols'], 'id'));
        $document->decorations()->sync(array_column($fields['decorations'], 'id'));
        $document->analyses()->sync(array_column($fields['analyses'], 'id'));
        $document->modern_collections()->sync(array_column($fields['modern_collections'], 'id'));
        $document->transactions()->sync(array_column($fields['transactions'], 'id'));
        $document->tags()->sync(array_column($fields['tags'], 'id'));
        $document->genres()->sync(array_column($fields['genres'], 'id'));
        $document->dating_methods()->sync(array_column($fields['dating_methods'], 'id'));
        $document->inks()->sync(array_column($fields['inks'], 'id'));

        $document->save();

        foreach ($img_fields['images'] as $fields) {
 
            $image = Image::find($fields['id']);

            $image->description = $fields['description'];
            $image->micrograph = $fields['micrograph'];
            $image->license_id = $fields['license_id'];
            $image->source = $fields['source'];
            $image->save();
        }

        $search = $request->validate([
            'show_publication' => 'nullable',
            'show_content' => 'nullable',
            'show_dating' => 'nullable',
            'show_materiality' => 'nullable',
            'show_measurement' => 'nullable',
            'show_palaeography' => 'nullable',
            'show_consanal' => 'nullable',
            'show_provenance' => 'nullable',

            'fulltext' => 'nullable',
            's_standard_name' => 'nullable',
            's_publication' => 'nullable',
            's_current_shelfmarks' => 'nullable',
            's_trismegistos_id' => 'nullable',
            's_title' => 'nullable',
            's_ancient_author' => 'nullable',
            's_genres' => 'nullable',
            's_genres_incl' => 'nullable',
            's_tags' => 'nullable',
            's_tags_incl' => 'nullable',
            's_earliest_date' => 'nullable',
            's_latest_date' => 'nullable',
            's_exclusive_date' => 'nullable',
            's_dating_methods' => 'nullable',
            's_dating_methods_incl' => 'nullable',
            's_dating_certainties' => 'nullable',
            's_materials' => 'nullable',
            's_inks' => 'nullable',
            's_inks_incl' => 'nullable',
            's_covers' => 'nullable',
            's_quire_structures' => 'nullable',
            's_quirenum_min' => 'nullable',
            's_quirenum_max' => 'nullable',
            's_bifolianum_min' => 'nullable',
            's_bifolianum_max' => 'nullable',
            's_full_page_width_min' => 'nullable',
            's_full_page_width_max' => 'nullable',
            's_full_page_height_min' => 'nullable',
            's_full_page_height_max' => 'nullable',
            's_full_text_block_width_min' => 'nullable',
            's_full_text_block_width_max' => 'nullable',
            's_full_text_block_height_min' => 'nullable',
            's_full_text_block_height_max' => 'nullable',
            's_upper_margin_min' => 'nullable',
            's_upper_margin_max' => 'nullable',
            's_lower_margin_min' => 'nullable',
            's_lower_margin_max' => 'nullable',
            's_inner_margin_min' => 'nullable',
            's_inner_margin_max' => 'nullable',
            's_outer_margin_min' => 'nullable',
            's_outer_margin_max' => 'nullable',

            's_full_page_ratio_min' => 'nullable',
            's_full_page_ratio_max' => 'nullable',
            's_full_text_block_ratio_min' => 'nullable',
            's_full_text_block_ratio_max' => 'nullable',
            's_uplow_margins_ratio_min' => 'nullable',
            's_uplow_margins_ratio_max' => 'nullable',
            's_inout_margins_ratio_min' => 'nullable',
            's_inout_margins_ratio_max' => 'nullable',

            's_gregorys_rules' => 'nullable',
            's_columns_min' => 'nullable',
            's_columns_max'=> 'nullable',
            's_columnlines_min'=> 'nullable',
            's_columnlines_max'=> 'nullable',

            's_hand_number_min' => 'nullable',
            's_hand_number_max' => 'nullable',
            's_scripts' => 'nullable',
            's_scripts_incl' => 'nullable',
            's_diacritics' => 'nullable',
            's_diacritics_incl' => 'nullable',
            's_punctuations' => 'nullable',
            's_punctuations_incl' => 'nullable',
            's_paratexts' => 'nullable',
            's_paratexts_incl' => 'nullable',
            's_decorations' => 'nullable',
            's_decorations_incl' => 'nullable',
            's_critical_symbols' => 'nullable',
            's_critical_symbols_incl' => 'nullable',
            's_paginations' => 'nullable',
            's_quire_signatures' => 'nullable',
            's_storage_conditions' => 'nullable',
            's_analyses' => 'nullable',
            's_analyses_incl' => 'nullable',
            's_scientifically_excavated' => 'nullable',
            's_ancient_provenances' => 'nullable',
            's_ancient_provenance_certainties' => 'nullable',
            's_transactions' => 'nullable',
            's_transactions_incl' => 'nullable',
            's_transaction_parties' => 'nullable',
            's_transaction_parties_incl' => 'nullable',
            's_legal_classifications' => 'nullable',
            'sortfield' => 'nullable',
            'reverse' => 'nullable',
            'tab' => 'nullable',
        ]);

        $show_publication = $request->session()->get('show_publication');
        $show_content = $request->session()->get('show_content');
        $show_dating = $request->session()->get('show_dating');
        $show_materiality = $request->session()->get('show_materiality');
        $show_measurement = $request->session()->get('show_measurement');
        $show_palaeography = $request->session()->get('show_palaeography');
        $show_consanal = $request->session()->get('show_consanal');
        $show_provenance = $request->session()->get('show_provenance');

        $sortfield = $request->session()->get('sortfield');
        $sortfield = array_key_exists('sortfield', $search) ? $search['sortfield'] : $sortfield;
  
        $request->session()->put('sortfield', $sortfield);
        switch ($sortfield) {
            case 1:
                $sortby = "standard_name";
                break;
            case 2:
                $sortby = "ancient_author";
                break;
            case 3:
                $sortby = "title";
                break;
            case 4:
                $sortby = "start_year";
                break;
            case 5:
                $sortby = "end_year";
                break;
            default:
                $sortby = "start_year";
        }

        $tab = array_key_exists('tab', $search) ? $search['tab'] : 'general';


        $reverse = $request->session()->get('reverse');
        $reverse = array_key_exists('reverse', $search) ? $search['reverse'] : $reverse;
        $request->session()->put('reverse', $reverse);

        $direction = $reverse ? "desc" : "asc";

        $fulltext = array_key_exists('fulltext', $search) ? $search['fulltext'] : null;
        $standard_name = array_key_exists('s_standard_name', $search) ? $search['s_standard_name'] : null;
        $publication = array_key_exists('s_publication', $search) ? $search['s_publication'] : null;
        $current_shelfmarks = array_key_exists('s_current_shelfmarks', $search) ? $search['s_current_shelfmarks'] : null;
        $trismegistos_id = array_key_exists('s_trismegistos_id', $search) ? $search['s_trismegistos_id'] : null;
        $title = array_key_exists('s_title', $search) ? $search['s_title'] : null;
        $ancient_author = array_key_exists('s_ancient_author', $search) ? $search['s_ancient_author'] : null;
        $genres = array_key_exists('s_genres', $search) ? $search['s_genres'] : null;
        $genres_incl = array_key_exists('s_genres_incl', $search) ? $search['s_genres_incl'] : null;
        $tags = array_key_exists('s_tags', $search) ? $search['s_tags'] : null;
        $tags_incl = array_key_exists('s_tags_incl', $search) ? $search['s_tags_incl'] : null;
        $earliest_date = array_key_exists('s_earliest_date', $search) ? $search['s_earliest_date'] : null;
        $latest_date = array_key_exists('s_latest_date', $search) ? $search['s_latest_date'] : null;
        $exclusive_date = array_key_exists('s_exclusive_date', $search) ? $search['s_exclusive_date'] : false;
        $dating_methods = array_key_exists('s_dating_methods', $search) ? $search['s_dating_methods'] : null;
        $dating_methods_incl = array_key_exists('s_dating_methods_incl', $search) ? $search['s_dating_methods_incl'] : null;
        $dating_certainties = array_key_exists('s_dating_certainties', $search) ? $search['s_dating_certainties'] : null;
        $materials = array_key_exists('s_materials', $search) ? $search['s_materials'] : null;
        $inks = array_key_exists('s_inks', $search) ? $search['s_inks'] : null;
        $inks_incl = array_key_exists('s_inks_incl', $search) ? $search['s_inks_incl'] : null;
        $covers = array_key_exists('s_covers', $search) ? $search['s_covers'] : null;
        $quire_structures = array_key_exists('s_quire_structures', $search) ? $search['s_quire_structures'] : null;
        $quirenum_min = array_key_exists('s_quirenum_min', $search) ? $search['s_quirenum_min'] : null;
        $quirenum_max = array_key_exists('s_quirenum_max', $search) ? $search['s_quirenum_max'] : null;
        $bifolianum_min = array_key_exists('s_bifolianum_min', $search) ? $search['s_bifolianum_min'] : null;
        $bifolianum_max = array_key_exists('s_bifolianum_max', $search) ? $search['s_bifolianum_max'] : null;
        $full_page_width_min = array_key_exists('s_full_page_width_min', $search) ? $search['s_full_page_width_min'] : null;
        $full_page_width_max = array_key_exists('s_full_page_width_max', $search) ? $search['s_full_page_width_max'] : null;
        $full_page_height_min = array_key_exists('s_full_page_height_min', $search) ? $search['s_full_page_height_min'] : null;
        $full_page_height_max = array_key_exists('s_full_page_height_max', $search) ? $search['s_full_page_height_max'] : null;
        $full_text_block_width_min = array_key_exists('s_full_text_block_width_min', $search) ? $search['s_full_text_block_width_min'] : null;
        $full_text_block_width_max = array_key_exists('s_full_text_block_width_max', $search) ? $search['s_full_text_block_width_max'] : null;
        $full_text_block_height_min = array_key_exists('s_full_text_block_height_min', $search) ? $search['s_full_text_block_height_min'] : null;
        $full_text_block_height_max = array_key_exists('s_full_text_block_height_max', $search) ? $search['s_full_text_block_height_max'] : null;
        $upper_margin_min = array_key_exists('s_upper_margin_min', $search) ? $search['s_upper_margin_min'] : null;
        $upper_margin_max = array_key_exists('s_upper_margin_max', $search) ? $search['s_upper_margin_max'] : null;
        $lower_margin_min = array_key_exists('s_lower_margin_min', $search) ? $search['s_lower_margin_min'] : null;
        $lower_margin_max = array_key_exists('s_lower_margin_max', $search) ? $search['s_lower_margin_max'] : null;
        $inner_margin_min = array_key_exists('s_inner_margin_min', $search) ? $search['s_inner_margin_min'] : null;
        $inner_margin_max = array_key_exists('s_inner_margin_max', $search) ? $search['s_inner_margin_max'] : null;
        $outer_margin_min = array_key_exists('s_outer_margin_min', $search) ? $search['s_outer_margin_min'] : null;
        $outer_margin_max = array_key_exists('s_outer_margin_max', $search) ? $search['s_outer_margin_max'] : null;

        $gregorys_rules = array_key_exists('s_gregorys_rules', $search) ? $search['s_gregorys_rules'] : null;
        $columns_min = array_key_exists('s_columns_min', $search) ? $search['s_columns_min'] : null;
        $columns_max = array_key_exists('s_columns_max', $search) ? $search['s_columns_max'] : null;
        $columnlines_min = array_key_exists('s_columnlines_min', $search) ? $search['s_columnlines_min'] : null;
        $columnlines_max = array_key_exists('s_columnlines_max', $search) ? $search['s_columnlines_max'] : null;

        $full_page_ratio_min = array_key_exists('s_full_page_ratio_min', $search) ? $search['s_full_page_ratio_min'] : null;
        $full_page_ratio_max = array_key_exists('s_full_page_ratio_max', $search) ? $search['s_full_page_ratio_max'] : null;
        $full_text_block_ratio_min = array_key_exists('s_full_text_block_ratio_min', $search) ? $search['s_full_text_block_ratio_min'] : null;
        $full_text_block_ratio_max = array_key_exists('s_full_text_block_ratio_max', $search) ? $search['s_full_text_block_ratio_max'] : null;
        $uplow_margins_ratio_min = array_key_exists('s_uplow_margins_ratio_min', $search) ? $search['s_uplow_margins_ratio_min'] : null;
        $uplow_margins_ratio_max = array_key_exists('s_uplow_margins_ratio_max', $search) ? $search['s_uplow_margins_ratio_max'] : null;
        $inout_margins_ratio_min = array_key_exists('s_inout_margins_ratio_min', $search) ? $search['s_inout_margins_ratio_min'] : null;
        $inout_margins_ratio_max = array_key_exists('s_inout_margins_ratio_max', $search) ? $search['s_inout_margins_ratio_max'] : null;

        $hand_number_min = array_key_exists('s_hand_number_min', $search) ? $search['s_hand_number_min'] : null;
        $hand_number_max = array_key_exists('s_hand_number_max', $search) ? $search['s_hand_number_max'] : null;
        $scripts = array_key_exists('s_scripts', $search) ? $search['s_scripts'] : null;
        $scripts_incl = array_key_exists('s_scripts_incl', $search) ? $search['s_scripts_incl'] : null;
        $diacritics = array_key_exists('s_diacritics', $search) ? $search['s_diacritics'] : null;
        $diacritics_incl = array_key_exists('s_diacritics_incl', $search) ? $search['s_diacritics_incl'] : null;
        $punctuations = array_key_exists('s_punctuations', $search) ? $search['s_punctuations'] : null;
        $punctuations_incl = array_key_exists('s_punctuations_incl', $search) ? $search['s_punctuations_incl'] : null;
        $paratexts = array_key_exists('s_paratexts', $search) ? $search['s_paratexts'] : null;
        $paratexts_incl = array_key_exists('s_paratexts_incl', $search) ? $search['s_paratexts_incl'] : null;
        $decorations = array_key_exists('s_decorations', $search) ? $search['s_decorations'] : null;
        $decorations_incl = array_key_exists('s_decorations_incl', $search) ? $search['s_decorations_incl'] : null;
        $critical_symbols = array_key_exists('s_critical_symbols', $search) ? $search['s_critical_symbols'] : null;
        $critical_symbols_incl = array_key_exists('s_critical_symbols_incl', $search) ? $search['s_critical_symbols_incl'] : null;
        $paginations = array_key_exists('s_paginations', $search) ? $search['s_paginations'] : null;
        $quire_signatures = array_key_exists('s_quire_signatures', $search) ? $search['s_quire_signatures'] : null;
        $storage_conditions = array_key_exists('s_storage_conditions', $search) ? $search['s_storage_conditions'] : null;
        $analyses = array_key_exists('s_analyses', $search) ? $search['s_analyses'] : null;
        $analyses_incl = array_key_exists('s_analyses_incl', $search) ? $search['s_analyses_incl'] : null;
        $scientifically_excavated = array_key_exists('s_scientifically_excavated', $search) ? $search['s_scientifically_excavated'] : null;
        $ancient_provenances = array_key_exists('s_ancient_provenances', $search) ? $search['s_ancient_provenances'] : null;
        $ancient_provenance_certainties = array_key_exists('s_ancient_provenance_certainties', $search) ? $search['s_ancient_provenance_certainties'] : null;
        $transactions = array_key_exists('s_transactions', $search) ? $search['s_transactions'] : null;
        $transactions_incl = array_key_exists('s_transactions_incl', $search) ? $search['s_transactions_incl'] : null;
        $transaction_parties = array_key_exists('s_transaction_parties', $search) ? $search['s_transaction_parties'] : null;
        $transaction_parties_incl = array_key_exists('s_transaction_parties_incl', $search) ? $search['s_transaction_parties_incl'] : null;
        $legal_classifications = array_key_exists('s_legal_classifications', $search) ? $search['s_legal_classifications'] : null;
        
        if($show_provenance AND $ancient_provenances) {
            $children = [];

            foreach($ancient_provenances as $ancient_provenance) {
                $prov = AncientProvenance::find($ancient_provenance['id']);
                $childrec = $prov->flatChildren();

                foreach($childrec as $child) {
                    $childarr = [
                        "id" => $child->id,
                        "name" => $child->name
                    ];
                    array_push($ancient_provenances, $childarr);
                } 
            }
        }
 
        $all_documents = Document::query()
            ->when($role_id < 2, function ($query) {
                $query->where('published', '=', true);
            }) 
            ->when($fulltext, function ($query) use ($fulltext, $role_id) {
                $query->where(function ($query) use ($fulltext, $role_id) {
                    $query->where('standard_name', 'like', "%{$fulltext}%")
                        ->orWhere('standard_name', 'like', "%{$fulltext}%")
                        ->orWhere('other_names', 'like', "%{$fulltext}%")
                        ->orWhere('trismegistos_id', 'like', "%{$fulltext}%")
                        ->orWhere('publication', 'like', "%{$fulltext}%")
                        ->orWhere('current_shelfmarks', 'like', "%{$fulltext}%")
                        ->orWhere('title', 'like', "%{$fulltext}%")
                        ->orWhere('ancient_author', 'like', "%{$fulltext}%")
                        ->orWhere('content_description', 'like', "%{$fulltext}%")
                        ->orWhere('language_comment', 'like', "%{$fulltext}%")
                        ->orWhere('dating_comment', 'like', "%{$fulltext}%")
                        ->orWhere('general_comment', 'like', "%{$fulltext}%")
                        ->orWhere('measurement_comment', 'like', "%{$fulltext}%")
                        ->orWhere('full_text_block_comment', 'like', "%{$fulltext}%")
                        ->orWhere('script_description', 'like', "%{$fulltext}%")
                        ->orWhere('decoration_description', 'like', "%{$fulltext}%")
                        ->orWhere('paratext_description', 'like', "%{$fulltext}%")
                        ->orWhere('diacritic_description', 'like', "%{$fulltext}%")
                        ->orWhere('punctuation_description', 'like', "%{$fulltext}%")
                        ->orWhere('critical_symbols_description', 'like', "%{$fulltext}%")
                        ->orWhere('pagination_description', 'like', "%{$fulltext}%")
                        ->orWhere('quire_signatures_description', 'like', "%{$fulltext}%")
                        ->orWhere('quire_comment', 'like', "%{$fulltext}%")
                        ->orWhere('binding_description', 'like', "%{$fulltext}%")
                        ->orWhere('inks_description', 'like', "%{$fulltext}%")
                        ->orWhere('cover_description', 'like', "%{$fulltext}%")
                        ->orWhere('gregorys_rule_comment', 'like', "%{$fulltext}%")
                        ->orWhere('conservation_history', 'like', "%{$fulltext}%")
                        ->orWhere('analyses_comment', 'like', "%{$fulltext}%")
                        ->orWhere('ancient_provenance_comment', 'like', "%{$fulltext}%")
                        ->orWhere('legal_classification_explanation', 'like', "%{$fulltext}%")
                        ->orWhere('bibliography', 'like', "%{$fulltext}%")
                        ->orWhere('images_info', 'like', "%{$fulltext}%")
                        ->orWhere('excavation_comment', 'like', "%{$fulltext}%")
                        ->orWhere('internal_comment', 'like', $role_id >= 2 ? "%{$fulltext}%" : "notsomethinganyonewouldwrite");
                });
            })
            ->when($show_publication == "true", function ($query) use (
                $standard_name,
                $publication,
                $current_shelfmarks,
                $trismegistos_id
            ) {
                $query->when($standard_name, function ($query, $standard_name) {
                    $query->where('standard_name', 'like', "%{$standard_name}%");
                })
                    ->when($publication, function ($query, $publication) {
                        $query->where('publication', 'like', "%{$publication}%");
                    })
                    ->when($current_shelfmarks, function ($query, $current_shelfmarks) {
                        $query->where('current_shelfmarks', 'like', "%{$current_shelfmarks}%");
                    })
                    ->when($trismegistos_id, function ($query, $trismegistos_id) {
                        $query->where('trismegistos_id', '=', $trismegistos_id);
                    });
            })
            ->when($show_content == "true", function ($query) use (
                $title,
                $ancient_author,
                $genres,
                $genres_incl,
                $tags,
                $tags_incl,
            ) {
                $query->when($title, function ($query, $title) {
                    $query->where('title', 'like', "%{$title}%");
                })
                    ->when($ancient_author, function ($query, $ancient_author) {
                        $query->where('ancient_author', 'like', "%{$ancient_author}%");
                    })
                    ->when($genres && !$genres_incl, function ($query) use ($genres) {
                        $query->whereHas('genres', function ($query) use ($genres) {
                            $query->whereIn('genres.id', array_column($genres, 'id'));
                        });
                    })
                    ->when($genres && $genres_incl, function ($query) use ($genres) {
                        foreach($genres as $genre) {                                
                            $query->whereHas('genres', function($query) use ($genre) {
                                $query->where('genres.id', '=', $genre['id']);
                            });
                        }
                    }) 
                    ->when($tags && !$tags_incl, function ($query) use ($tags) {
                        $query->whereHas('tags', function ($query) use ($tags) {
                            $query->whereIn('tags.id', array_column($tags, 'id'));
                        });
                    })
                    ->when($tags && $tags_incl, function ($query) use ($tags) {
                        foreach($tags as $tag) {                                
                            $query->whereHas('tags', function($query) use ($tag) {
                                $query->where('tags.id', '=', $tag['id']);
                            });
                        }
                    }); 
            })
            ->when($show_dating == "true", function ($query) use (
                $earliest_date,
                $latest_date,
                $exclusive_date,
                $dating_methods,
                $dating_methods_incl,
                $dating_certainties
            ) {
                $query->when($exclusive_date == "true", function ($query) use ($earliest_date, $latest_date, $exclusive_date) {
                    $query->when($earliest_date, function ($query, $earliest_date) {
                        $query->where('start_year', '>=', $earliest_date);
                    })
                        ->when($latest_date, function ($query, $latest_date) {
                            $query->where('end_year', '<=', $latest_date);
                        });
                }, function ($query) use ($earliest_date, $latest_date) {
                    $query->when($earliest_date, function ($query, $earliest_date) {
                        $query->where('end_year', '>=', $earliest_date);
                    })
                        ->when($latest_date, function ($query, $latest_date) {
                            $query->where('start_year', '<=', $latest_date);
                        });
                })
                    ->when($dating_methods && !$dating_methods_incl, function ($query) use ($dating_methods) {
                        $query->whereHas('dating_methods', function ($query) use ($dating_methods) {
                            $query->whereIn('dating_methods.id', array_column($dating_methods, 'id'));
                        });
                    })
                    ->when($dating_methods && $dating_methods_incl, function ($query) use ($dating_methods) {
                        foreach($dating_methods as $dating_method) {                                
                            $query->whereHas('dating_methods', function($query) use ($dating_method) {
                                $query->where('dating_methods.id', '=', $dating_method['id']);
                            });
                        }
                    }) 
                    ->when($dating_certainties, function ($query, $dating_certainties) {
                        $query->whereIn('dating_certainty_id', array_column($dating_certainties, 'id'));
                    });
            })
            ->when(
                $show_materiality == "true",
                function ($query) use (
                    $materials,
                    $gregorys_rules,
                    $inks,
                    $inks_incl,
                    $covers,
                    $quire_structures,
                    $quirenum_min,
                    $quirenum_max,
                    $bifolianum_min,
                    $bifolianum_max
                ) {
                        $query->when($materials, function ($query, $materials) {
                            $query->whereIn('material_id', array_column($materials, 'id'));
                        })
                        ->when($gregorys_rules, function ($query, $gregorys_rules) {
                            $query->whereIn('gregorys_rule_id', array_column($gregorys_rules, 'id'));
                        })
                        ->when(($inks && !$inks_incl), function ($query) use ($inks) {
                            $query->whereHas('inks', function ($query) use ($inks) {
                                $query->whereIn('inks.id', array_column($inks, 'id'));
                            });
                        })
                        ->when($inks && $inks_incl, function ($query) use ($inks) {
                            foreach($inks as $ink) {                                
                                $query->whereHas('inks', function($query) use ($ink) {
                                    $query->where('inks.id', '=', $ink['id']);
                                });
                            }
                        })                      
                        ->when($covers, function ($query, $covers) {
                            $query->whereIn('cover_id', array_column($covers, 'id'));
                        })
                        ->when($quire_structures, function ($query, $quire_structures) {
                            $query->whereIn('quire_structure_id', array_column($quire_structures, 'id'));
                        })
                        ->when($quirenum_min, function ($query, $quirenum_min) {
                            $query->where('quire_number', '>=', $quirenum_min);
                        })
                        ->when($quirenum_max, function ($query, $quirenum_max) {
                            $query->where('quire_number', '<=', $quirenum_max);
                        })
                        ->when($bifolianum_min, function ($query, $bifolianum_min) {
                            $query->where('bifolia_number', '>=', $bifolianum_min);
                        })
                        ->when($bifolianum_max, function ($query, $bifolianum_max) {
                            $query->where('bifolia_number', '<=', $bifolianum_max);
                        });
                }
            )

            ->when(
                $show_measurement == "true",
                function ($query) use (
                    $full_page_width_min,
                    $full_page_width_max,
                    $full_page_height_min,
                    $full_page_height_max,
                    $full_text_block_width_min,
                    $full_text_block_width_max,
                    $full_text_block_height_min,
                    $full_text_block_height_max,
                    $upper_margin_min,
                    $upper_margin_max,
                    $lower_margin_min,
                    $lower_margin_max,
                    $inner_margin_min,
                    $inner_margin_max,
                    $outer_margin_min,
                    $outer_margin_max,

                    $full_page_ratio_min,
                    $full_page_ratio_max,
                    $full_text_block_ratio_min,
                    $full_text_block_ratio_max,
                    $uplow_margins_ratio_min,
                    $uplow_margins_ratio_max,
                    $inout_margins_ratio_min,
                    $inout_margins_ratio_max,
                    
                    $columns_min,
                    $columns_max,
                    $columnlines_min,
                    $columnlines_max,
                ) {
                    $query->when($full_page_width_min, function ($query, $full_page_width_min) {
                        $query->where('full_page_width', '>=', $full_page_width_min);
                    })
                        ->when($full_page_width_max, function ($query, $full_page_width_max) {
                            $query->where('full_page_width', '<=', $full_page_width_max);
                        })
                        ->when($full_page_height_min, function ($query, $full_page_height_min) {
                            $query->where('full_page_height', '>=', $full_page_height_min);
                        })
                        ->when($full_page_height_max, function ($query, $full_page_height_max) {
                            $query->where('full_page_height', '<=', $full_page_height_max);
                        })
                        ->when($full_text_block_width_min, function ($query, $full_text_block_width_min) {
                            $query->where('full_text_block_width', '>=', $full_text_block_width_min);
                        })
                        ->when($full_text_block_width_max, function ($query, $full_text_block_width_max) {
                            $query->where('full_text_block_width', '<=', $full_text_block_width_max);
                        })
                        ->when($full_text_block_height_min, function ($query, $full_text_block_height_min) {
                            $query->where('full_text_block_height', '>=', $full_text_block_height_min);
                        })
                        ->when($full_text_block_height_max, function ($query, $full_text_block_height_max) {
                            $query->where('full_text_block_height', '<=', $full_text_block_height_max);
                        })
                        ->when($upper_margin_min, function ($query, $upper_margin_min) {
                            $query->where('upper_margin', '>=', $upper_margin_min);
                        })
                        ->when($upper_margin_max, function ($query, $upper_margin_max) {
                            $query->where('upper_margin', '<=', $upper_margin_max);
                        })
                        ->when($lower_margin_min, function ($query, $lower_margin_min) {
                            $query->where('lower_margin', '>=', $lower_margin_min);
                        })
                        ->when($lower_margin_max, function ($query, $lower_margin_max) {
                            $query->where('lower_margin', '<=', $lower_margin_max);
                        })
                        ->when($inner_margin_min, function ($query, $inner_margin_min) {
                            $query->where('inner_margin', '>=', $inner_margin_min);
                        })
                        ->when($inner_margin_max, function ($query, $inner_margin_max) {
                            $query->where('inner_margin', '<=', $inner_margin_max);
                        })
                        ->when($outer_margin_min, function ($query, $outer_margin_min) {
                            $query->where('outer_margin', '>=', $outer_margin_min);
                        })
                        ->when($outer_margin_max, function ($query, $outer_margin_max) {
                            $query->where('outer_margin', '<=', $outer_margin_max);
                        })
                        ->when($full_page_ratio_min, function ($query, $full_page_ratio_min) {
                            $query->where(DB::raw('full_page_width / NULLIF(full_page_height, 0)'), '>=', $full_page_ratio_min);
                        })
                        ->when($full_page_ratio_max, function ($query, $full_page_ratio_max) {
                            $query->where(DB::raw('full_page_width / NULLIF(full_page_height, 0)'), '<=', $full_page_ratio_max);
                        })
                        ->when($full_text_block_ratio_min, function ($query, $full_text_block_ratio_min) {
                            $query->where(DB::raw('full_text_block_width / NULLIF(full_text_block_height, 0)'), '>=', $full_text_block_ratio_min);
                        })
                        ->when($full_text_block_ratio_max, function ($query, $full_text_block_ratio_max) {
                            $query->where(DB::raw('full_text_block_width / NULLIF(full_text_block_height, 0)'), '<=', $full_text_block_ratio_max);
                        })
                        ->when($uplow_margins_ratio_min, function ($query, $uplow_margins_ratio_min) {
                            $query->where(DB::raw('upper_margin / NULLIF(lower_margin, 0)'), '>=', $uplow_margins_ratio_min);
                        })
                        ->when($uplow_margins_ratio_max, function ($query, $uplow_margins_ratio_max) {
                            $query->where(DB::raw('upper_margin / NULLIF(lower_margin, 0)'), '<=', $uplow_margins_ratio_max);
                        })
                        ->when($inout_margins_ratio_min, function ($query, $inout_margins_ratio_min) {
                            $query->where(DB::raw('inner_margin / NULLIF(outer_margin, 0)'), '>=', $inout_margins_ratio_min);
                        })
                        ->when($inout_margins_ratio_max, function ($query, $inout_margins_ratio_max) {
                            $query->where(DB::raw('inner_margin / NULLIF(outer_margin, 0)'), '<=', $inout_margins_ratio_max);
                        })
                        ->when($columns_min, function ($query, $columns_min) {
                            $query->where('columns', '>=', $columns_min);
                        })
                        ->when($columns_max, function ($query, $columns_max) {
                            $query->where('columns', '>=', $columns_max);
                        })
                        ->when($columnlines_min, function ($query, $columnlines_min) {
                            $query->where('columnlines', '>=', $columnlines_min);
                        })
                        ->when($columnlines_max, function ($query, $columnlines_max) {
                            $query->where('columnlines', '>=', $columnlines_max);
                        });
                }
            )
            ->when(
                $show_palaeography == "true",
                function ($query) use (
                    $hand_number_min,
                    $hand_number_max,
                    $scripts,
                    $scripts_incl,
                    $diacritics,
                    $diacritics_incl,
                    $punctuations,
                    $punctuations_incl,
                    $paratexts,
                    $paratexts_incl,
                    $decorations,
                    $decorations_incl,
                    $critical_symbols,
                    $critical_symbols_incl,
                    $paginations,
                    $quire_signatures
                ) {
                    $query->when($hand_number_min, function ($query, $hand_number_min) {
                        $query->where('hand_number', '>=', $hand_number_min);
                    })
                        ->when($hand_number_max, function ($query, $hand_number_max) {
                            $query->where('hand_number', '<=', $hand_number_max);
                        })
                        ->when($scripts && !$scripts_incl, function ($query) use ($scripts) {
                            $query->whereHas('scripts', function ($query) use ($scripts) {
                                $query->whereIn('scripts.id', array_column($scripts, 'id'));
                            });
                        })
                        ->when($scripts && $scripts_incl, function ($query) use ($scripts) {
                            foreach($scripts as $script) {                                
                                $query->whereHas('scripts', function($query) use ($script) {
                                    $query->where('scripts.id', '=', $script['id']);
                                });
                            }
                        }) 
                        ->when($diacritics && !$diacritics_incl, function ($query) use ($diacritics) {
                            $query->whereHas('diacritics', function ($query) use ($diacritics) {
                                $query->whereIn('diacritics.id', array_column($diacritics, 'id'));
                            });
                        })
                        ->when($diacritics && $diacritics_incl, function ($query) use ($diacritics) {
                            foreach($diacritics as $diacritic) {                                
                                $query->whereHas('diacritics', function($query) use ($diacritic) {
                                    $query->where('diacritics.id', '=', $diacritic['id']);
                                });
                            }
                        }) 
                        ->when($punctuations && !$punctuations_incl, function ($query) use ($punctuations) {
                            $query->whereHas('punctuations', function ($query) use ($punctuations) {
                                $query->whereIn('punctuations.id', array_column($punctuations, 'id'));
                            });
                        })
                        ->when($punctuations && $punctuations_incl, function ($query) use ($punctuations) {
                            foreach($punctuations as $punctuation) {                                
                                $query->whereHas('punctuations', function($query) use ($punctuation) {
                                    $query->where('punctuations.id', '=', $punctuation['id']);
                                });
                            }
                        }) 
                        ->when($paratexts && !$paratexts_incl, function ($query) use ($paratexts) {
                            $query->whereHas('paratexts', function ($query) use ($paratexts) {
                                $query->whereIn('paratexts.id', array_column($paratexts, 'id'));
                            });
                        })
                        ->when($paratexts && $paratexts_incl, function ($query) use ($paratexts) {
                            foreach($paratexts as $paratext) {                                
                                $query->whereHas('paratexts', function($query) use ($paratext) {
                                    $query->where('paratexts.id', '=', $paratext['id']);
                                });
                            }
                        }) 
                        ->when($decorations && !$decorations_incl, function ($query) use ($decorations) {
                            $query->whereHas('decorations', function ($query) use ($decorations) {
                                $query->whereIn('decorations.id', array_column($decorations, 'id'));
                            });
                        })
                        ->when($decorations && $decorations_incl, function ($query) use ($decorations) {
                            foreach($decorations as $decoration) {                                
                                $query->whereHas('decorations', function($query) use ($decoration) {
                                    $query->where('decorations.id', '=', $decoration['id']);
                                });
                            }
                        }) 
                        ->when($critical_symbols && !$critical_symbols_incl, function ($query) use ($critical_symbols) {
                            $query->whereHas('critical_symbols', function ($query) use ($critical_symbols) {
                                $query->whereIn('critical_symbols.id', array_column($critical_symbols, 'id'));
                            });
                        })
                        ->when($critical_symbols && $critical_symbols_incl, function ($query) use ($critical_symbols) {
                            foreach($critical_symbols as $critical_symbol) {                                
                                $query->whereHas('critical_symbols', function($query) use ($critical_symbol) {
                                    $query->where('critical_symbols.id', '=', $critical_symbol['id']);
                                });
                            }
                        }) 
                        ->when($paginations, function ($query, $paginations) {
                            $query->whereIn('pagination_id', array_column($paginations, 'id'));
                        })
                        ->when($quire_signatures, function ($query, $quire_signatures) {
                            $query->whereIn('quire_signature_id', array_column($quire_signatures, 'id'));
                        });
                }
            )
            ->when(
                $show_consanal == "true",
                function ($query) use (
                    $storage_conditions,
                    $analyses,
                    $analyses_incl,
                ) {
                    $query->when($storage_conditions, function ($query, $storage_conditions) {
                        $query->whereIn('storage_condition_id', array_column($storage_conditions, 'id'));
                    })
                        ->when($analyses && !$analyses_incl, function ($query) use ($analyses) {
                            $query->whereHas('analyses', function ($query) use ($analyses) {
                                $query->whereIn('analyses.id', array_column($analyses, 'id'));
                            });
                        })
                        ->when($analyses && $analyses_incl, function ($query) use ($analyses) {
                            foreach($analyses as $analysis) {                                
                                $query->whereHas('analyses', function($query) use ($analysis) {
                                    $query->where('analyses.id', '=', $analysis['id']);
                                });
                            }
                        });
                }
            )
            ->when(
                $show_provenance == "true",
                function ($query) use (
                    $scientifically_excavated,
                    $ancient_provenances,
                    $ancient_provenance_certainties,
                    $transactions,
                    $transactions_incl,
                    $transaction_parties,
                    $transaction_parties_incl,
                    $legal_classifications
                ) {
                    $query->when($scientifically_excavated, function ($query, $scientifically_excavated) {
                        $query->where('scientifically_excavated', '=', 1);
                    })
                        ->when($ancient_provenances, function ($query, $ancient_provenances) {
                            $query->whereIn('ancient_provenance_id', array_column($ancient_provenances, 'id'));
                        })
                        ->when($ancient_provenance_certainties, function ($query, $ancient_provenance_certainties) {
                            $query->whereIn('ancient_provenance_certainty_id', array_column($ancient_provenance_certainties, 'id'));
                        })
                        ->when($transactions && !$transactions_incl, function ($query) use ($transactions) {
                            $query->whereHas('transactions', function ($query) use ($transactions) {
                                $query->whereIn('transactions.id', array_column($transactions, 'id'));
                            });
                        })
                        ->when($transactions && $transactions_incl, function ($query) use ($transactions) {
                            foreach($transactions as $transaction) {                                
                                $query->whereHas('transactions', function($query) use ($transaction) {
                                    $query->where('transactions.id', '=', $transaction['id']);
                                });
                            }
                        }) 

                        ->when($transaction_parties && !$transaction_parties_incl, function ($query) use ($transaction_parties) {
                            $tactions = Transaction::query()
                                ->whereHas('transaction_parties', function ($query) use ($transaction_parties) {
                                    $query->whereIn('transaction_parties.id', array_column($transaction_parties, 'id'));
                                })->get()->all();
                            $query->whereHas('transactions', function ($query) use ($tactions) {
                                $query->whereIn('transactions.id', array_column($tactions, 'id'));
                            });
                        })

                        ->when($transaction_parties && $transaction_parties_incl, function ($query) use ($transaction_parties) {
                            
                            $query->whereHas('transactions', function ($query) use ($transaction_parties) {
                                foreach($transaction_parties as $transaction_party) {
                                    $query->whereHas('transaction_parties', function ($query) use ($transaction_party) {
                                        $query->where('transaction_parties.id', '=', $transaction_party['id']);
                                    });
                                }
                            });
                        })
                        ->when($legal_classifications, function ($query, $legal_classifications) {
                            $query->whereIn('legal_classification_id', array_column($legal_classifications, 'id'));
                        });
                    })

            ->orderBy($sortby, $direction)
            ->orderBy('end_year', $direction)->get();

            $prev = -1;
            $next = -1;
            $current = -1;
            $savedprev = -1;
            $found = false;
            $count = 0;
            $total = $all_documents->count();

            foreach ($all_documents as $doc){
                if($found) {
                    $next = $doc['id'];
                }

                $count++;

                if($doc['id'] === $document['id']) {
                    $found = true;
                    $prev = $savedprev;
                    $current = $count;
                }
                else {
                    $found = false;
                }
                $savedprev = $doc['id'];
            }

        $transactions_all = DB::table('transactions')->orderBy('year')->orderBy('month')->orderBy('day')->get();

        return Inertia::render('CodexEdit', [
            'document' => $document,
            'analyses' => $document->analyses()->get()->makeHidden('pivot'),
            'analyses_all' => Analysis::all(),
            'ancient_provenances' => AncientProvenance::all(),
            'ancient_provenance' => $document->ancient_provenance()->get(),
            'ancient_provenance_certainties' => AncientProvenanceCertainty::all(),
            'ancient_provenance_certainty' => $document->ancient_provenance_certainty()->get(),
            'critical_symbols' => $document->critical_symbols()->get()->makeHidden('pivot'),
            'critical_symbols_all' => CriticalSymbol::all(),
            'covers' => Cover::all(),
            'cover' => $document->cover()->get(),
            'dating_certainties' => DatingCertainty::all(),
            'dating_certainty' => $document->dating_certainty()->get(),
            'dating_methods' => $document->dating_methods()->get()->makeHidden('pivot'),
            'dating_methods_all' => DatingMethod::all(),
            'decorations' => $document->decorations()->get()->makeHidden('pivot'),
            'decorations_all' => Decoration::all(),
            'diacritics' => $document->diacritics()->get()->makeHidden('pivot'),
            'diacritics_all' => Diacritic::all(),
            'genres' => $document->genres()->get()->makeHidden('pivot'),
            'genres_all' => Genre::all(),
            'images' => $document->images()->with('license')->get(),
            'inks' => $document->inks()->get()->makeHidden('pivot'),
            'inks_all' => Ink::all(),
            'languages' => $document->languages()->get()->makeHidden('pivot'),
            'languages_all' => Language::all(),
            'legal_classifications' => LegalClassification::all(),
            'legal_classification' => $document->legal_classification()->get(),
            'licenses' => License::all(),
            'materials' => Material::all(),
            'material' => $document->material()->get(),
            'modern_collections' => $document->modern_collections()->get()->makeHidden('pivot'),
            'modern_collections_all' => ModernCollection::all(),
            'paginations' => Pagination::all(),
            'pagination' => $document->pagination()->get(),
            'paratexts' => $document->paratexts()->get()->makeHidden('pivot'),
            'paratexts_all' => Paratext::all(),
            'punctuations' => $document->punctuations()->get()->makeHidden('pivot'),
            'punctuations_all' => Punctuation::all(),
            'transactions' => $document->transactions()
                                            ->get()->makeHidden('pivot')
                                            ->sortBy('year')
                                            ->sortBy('month')
                                            ->sortBy('day'),
            'transactions_all' => $transactions_all,
            'quire_signatures' => QuireSignature::all(),
            'quire_signature' => $document->quire_signature()->get(),
            'quire_structures' => QuireStructure::all(),
            'quire_structure' => $document->quire_structure()->get(),
            'gregorys_rules' => GregorysRule::all(),
            'gregorys_rule' => $document->gregorys_rule()->get(),
            'scripts' => $document->scripts()->get()->makeHidden('pivot'),
            'scripts_all' => Script::all(),
            'storage_conditions' => StorageCondition::all(),
            'storage_condition' => $document->storage_condition()->get(),
            'tags' => $document->tags()->get()->makeHidden('pivot'),
            'tags_all' => Tag::all(),

            'fulltext' => $fulltext,
            'standard_name' => $standard_name,
            'publication' => $publication,
            'current_shelfmarks' => $current_shelfmarks,
            'trismegistos_id' => $trismegistos_id,
            'title' => $title,
            'ancient_author' => $ancient_author,
            'genres_search' => $genres,
            'genres_incl' => $genres_incl,
            'tags_search' => $tags,
            'tags_incl' => $tags_incl,
            'earliest_date' => $earliest_date,
            'latest_date' => $latest_date,
            'exclusive_date' => $exclusive_date == "true" ? true : false,
            'dating_methods_search' => $dating_methods,
            'dating_methods_incl' => $dating_methods_incl,
            'dating_certainties_search' => $dating_certainties,
            'materials_search' => $materials,
            'inks_search' => $inks,
            'inks_incl' => $inks_incl == "true" ? true : false,
            'covers_search' => $covers,
            'quire_structures_search' => $quire_structures,
            'quirenum_min' => $quirenum_min,
            'quirenum_max' => $quirenum_max,
            'bifolianum_min' => $bifolianum_min,
            'bifolianum_max' => $bifolianum_max,
            'full_page_width_min' => $full_page_width_min,
            'full_page_width_max' => $full_page_width_max,
            'full_page_height_min' => $full_page_height_min,
            'full_page_height_max' => $full_page_height_max,
            'full_text_block_width_min' => $full_text_block_width_min,
            'full_text_block_width_max' => $full_text_block_width_max,
            'full_text_block_height_min' => $full_text_block_height_min,
            'full_text_block_height_max' => $full_text_block_height_max,
            'upper_margin_min' => $upper_margin_min,
            'upper_margin_max' => $upper_margin_max,
            'lower_margin_min' => $lower_margin_min,
            'lower_margin_max' => $lower_margin_max,
            'inner_margin_min' => $inner_margin_min,
            'inner_margin_max' => $inner_margin_max,
            'outer_margin_min' => $outer_margin_min,
            'outer_margin_max' => $outer_margin_max,
            'full_page_ratio_min' => $full_page_ratio_min,
            'full_page_ratio_max' => $full_page_ratio_max,
            'full_text_block_ratio_min' => $full_text_block_ratio_min,
            'full_text_block_ratio_max' => $full_text_block_ratio_max,
            'uplow_margins_ratio_min' => $uplow_margins_ratio_min,
            'uplow_margins_ratio_max' => $uplow_margins_ratio_max,
            'inout_margins_ratio_min' => $inout_margins_ratio_min,
            'inout_margins_ratio_max' => $inout_margins_ratio_max,

            'gregorys_rules_search' => $gregorys_rules,
            'columns_min' => $columns_min,
            'columns_max' => $columns_max,
            'columnlines_min' => $columnlines_min,
            'columnlines_max' => $columnlines_max,
        
            'hand_number_min' => $hand_number_min,
            'hand_number_max' => $hand_number_max,
            'scripts_search' => $scripts,
            'scripts_incl' => $scripts_incl,
            'diacritics_search' => $diacritics,
            'diacritics_incl' => $diacritics_incl,
            'punctuations_search' => $punctuations,
            'punctuations_incl' => $punctuations_incl,
            'paratexts_search' => $paratexts,
            'paratexts_incl' => $paratexts_incl,
            'decorations_search' => $decorations,
            'decorations_incl' => $decorations_incl,
            'critical_symbols_search' => $critical_symbols,
            'critical_symbols_incl' => $critical_symbols_incl,
            'paginations_search' => $paginations,
            'quire_signatures_search' => $quire_signatures,
            'storage_conditions_search' => $storage_conditions,
            'analyses_search' => $analyses,
            'analyses_incl' => $analyses_incl,
            'scientifically_excavated' => $scientifically_excavated,
            'ancient_provenances_search' => $ancient_provenances,
            'ancient_provenance_certainties_search' => $ancient_provenance_certainties,
            'transactions_search' => $transactions,
            'transactions_incl' => $transactions_incl,
            'transaction_parties_search' => $transaction_parties,
            'transaction_parties_incl' => $transaction_parties_incl,
            'legal_classifications_search' => $legal_classifications,

            'sortfield' => $sortfield,
            'reverse' => $reverse,
            'tab' => $tab,
            'prev' => $prev,
            'next' => $next,
            'current' => $current,
            'total' => $total,
        ]);
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

        $document->dating_methods()->detach();
        $document->genres()->detach();
        $document->languages()->detach();
        $document->punctuations()->detach();
        $document->tags()->detach();
        $document->decorations()->detach();
        $document->paratexts()->detach();
        $document->scripts()->detach();
        $document->inks()->detach();
        $document->delete();
        return redirect()->back();
    }
}
