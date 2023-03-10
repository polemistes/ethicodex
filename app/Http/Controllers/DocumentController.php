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
use App\Models\CriticalSymbol;
use App\Models\Punctuation;
use App\Models\Diacritic;

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
            'standard_name' => 'nullable',
            'publication' => 'nullable',
            'current_shelfmarks' => 'nullable',
            'trismegistos_id' => 'nullable',
            'title' => 'nullable',
            'ancient_author' => 'nullable',
            'genres' => 'nullable',
            'tags' => 'nullable',
            'earliest_date' => 'nullable',
            'latest_date' => 'nullable',
            'exclusive_date' => 'nullable',
            'dating_methods' => 'nullable',
            'dating_certainties' => 'nullable',
            'materials' => 'nullable',
            'inks' => 'nullable',
            'covers' => 'nullable',
            'quire_structures' => 'nullable',
            'quirenum_min' => 'nullable',
            'quirenum_max' => 'nullable',
            'bifolianum_min' => 'nullable',
            'bifolianum_max' => 'nullable',
            'full_page_width_min' => 'nullable',
            'full_page_width_max' => 'nullable',
            'full_page_height_min' => 'nullable',
            'full_page_height_max' => 'nullable',
            'full_text_block_width_min' => 'nullable',
            'full_text_block_width_max' => 'nullable',
            'full_text_block_height_min' => 'nullable',
            'full_text_block_height_max' => 'nullable',
            'upper_margin_min' => 'nullable',
            'upper_margin_max' => 'nullable',
            'lower_margin_min' => 'nullable',
            'lower_margin_max' => 'nullable',
            'inner_margin_min' => 'nullable',
            'inner_margin_max' => 'nullable',
            'outer_margin_min' => 'nullable',
            'outer_margin_max' => 'nullable',
            'hand_number_min' => 'nullable',
            'hand_number_max' => 'nullable',
            'scripts' => 'nullable',
            'diacritics' => 'nullable',
            'punctuations' => 'nullable',
            'paratexts' => 'nullable',
            'decorations' => 'nullable',
            'critical_symbols' => 'nullable',
            'paginations' => 'nullable',
            'quire_signatures' => 'nullable',
            'storage_conditions' => 'nullable',
            'analyses' => 'nullable',
            'scientifically_excavated' => 'nullable',
            'ancient_provenances' => 'nullable',
            'ancient_provenance_certainties' => 'nullable',
            'transactions' => 'nullable',
            'transaction_parties' => 'nullable',
            'legal_classifications' => 'nullable',
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

        $fulltext = array_key_exists('fulltext', $search) ? $search['fulltext'] : null;
        $standard_name = array_key_exists('standard_name', $search) ? $search['standard_name'] : null;
        $publication = array_key_exists('publication', $search) ? $search['publication'] : null;
        $current_shelfmarks = array_key_exists('current_shelfmarks', $search) ? $search['current_shelfmarks'] : null;
        $trismegistos_id = array_key_exists('trismegistos_id', $search) ? $search['trismegistos_id'] : null;
        $title = array_key_exists('title', $search) ? $search['title'] : null;
        $ancient_author = array_key_exists('ancient_author', $search) ? $search['ancient_author'] : null;
        $genres = array_key_exists('genres', $search) ? $search['genres'] : null;
        $tags = array_key_exists('tags', $search) ? $search['tags'] : null;
        $earliest_date = array_key_exists('earliest_date', $search) ? $search['earliest_date'] : null;
        $latest_date = array_key_exists('latest_date', $search) ? $search['latest_date'] : null;
        $exclusive_date = array_key_exists('exclusive_date', $search) ? $search['exclusive_date'] : false;
        $dating_methods = array_key_exists('dating_methods', $search) ? $search['dating_methods'] : null;
        $dating_certainties = array_key_exists('dating_certainties', $search) ? $search['dating_certainties'] : null;
        $materials = array_key_exists('materials', $search) ? $search['materials'] : null;
        $inks = array_key_exists('inks', $search) ? $search['inks'] : null;
        $covers = array_key_exists('covers', $search) ? $search['covers'] : null;
        $quire_structures = array_key_exists('quire_structures', $search) ? $search['quire_structures'] : null;
        $quirenum_min = array_key_exists('quirenum_min', $search) ? $search['quirenum_min'] : null;
        $quirenum_max = array_key_exists('quirenum_max', $search) ? $search['quirenum_max'] : null;
        $bifolianum_min = array_key_exists('bifolianum_min', $search) ? $search['bifolianum_min'] : null;
        $bifolianum_max = array_key_exists('bifolianum_max', $search) ? $search['bifolianum_max'] : null;
        $full_page_width_min = array_key_exists('full_page_width_min', $search) ? $search['full_page_width_min'] : null;
        $full_page_width_max = array_key_exists('full_page_width_max', $search) ? $search['full_page_width_max'] : null;
        $full_page_height_min = array_key_exists('full_page_height_min', $search) ? $search['full_page_height_min'] : null;
        $full_page_height_max = array_key_exists('full_page_height_max', $search) ? $search['full_page_height_max'] : null;
        $full_text_block_width_min = array_key_exists('full_text_block_width_min', $search) ? $search['full_text_block_width_min'] : null;
        $full_text_block_width_max = array_key_exists('full_text_block_width_max', $search) ? $search['full_text_block_width_max'] : null;
        $full_text_block_height_min = array_key_exists('full_text_block_height_min', $search) ? $search['full_text_block_height_min'] : null;
        $full_text_block_height_max = array_key_exists('full_text_block_height_max', $search) ? $search['full_text_block_height_max'] : null;
        $upper_margin_min = array_key_exists('upper_margin_min', $search) ? $search['upper_margin_min'] : null;
        $upper_margin_max = array_key_exists('upper_margin_max', $search) ? $search['upper_margin_max'] : null;
        $lower_margin_min = array_key_exists('lower_margin_min', $search) ? $search['lower_margin_min'] : null;
        $lower_margin_max = array_key_exists('lower_margin_max', $search) ? $search['lower_margin_max'] : null;
        $inner_margin_min = array_key_exists('inner_margin_min', $search) ? $search['inner_margin_min'] : null;
        $inner_margin_max = array_key_exists('inner_margin_max', $search) ? $search['inner_margin_max'] : null;
        $outer_margin_min = array_key_exists('outer_margin_min', $search) ? $search['outer_margin_min'] : null;
        $outer_margin_max = array_key_exists('outer_margin_max', $search) ? $search['outer_margin_max'] : null;
        $hand_number_min = array_key_exists('hand_number_min', $search) ? $search['hand_number_min'] : null;
        $hand_number_max = array_key_exists('hand_number_max', $search) ? $search['hand_number_max'] : null;
        $scripts = array_key_exists('scripts', $search) ? $search['scripts'] : null;
        $diacritics = array_key_exists('diacritics', $search) ? $search['diacritics'] : null;
        $punctuations = array_key_exists('punctuations', $search) ? $search['punctuations'] : null;
        $paratexts = array_key_exists('paratexts', $search) ? $search['paratexts'] : null;
        $decorations = array_key_exists('decorations', $search) ? $search['decorations'] : null;
        $critical_symbols = array_key_exists('critical_symbols', $search) ? $search['critical_symbols'] : null;
        $paginations = array_key_exists('pagination', $search) ? $search['paginations'] : null;
        $quire_signatures = array_key_exists('quire_signatures', $search) ? $search['quire_signatures'] : null;
        $storage_conditions = array_key_exists('storage_conditions', $search) ? $search['storage_conditions'] : null;
        $analyses = array_key_exists('analyses', $search) ? $search['analyses'] : null;
        $scientifically_excavated = array_key_exists('scientifically_excavated', $search) ? $search['scientifically_excavated'] : null;
        $ancient_provenances = array_key_exists('ancient_provenances', $search) ? $search['ancient_provenances'] : null;
        $ancient_provenance_certainties = array_key_exists('ancient_provenance_certainties', $search) ? $search['ancient_provenance_certainties'] : null;
        $transactions = array_key_exists('transactions', $search) ? $search['transactions'] : null;
        $transaction_parties = array_key_exists('transaction_parties', $search) ? $search['transaction_parties'] : null;
        $legal_classifications = array_key_exists('legal_classifications', $search) ? $search['legal_classifications'] : null;

        $documents = Document::query()
            ->when($role_id < 2, function ($query) {
                $query->where('published', '=', true);
            })
            ->when($fulltext, function ($query, $fulltext) {
                $query->where(function ($query) use ($fulltext) {
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
                        ->orWhere('conservation_history', 'like', "%{$fulltext}%")
                        ->orWhere('analyses_comment', 'like', "%{$fulltext}%")
                        ->orWhere('ancient_provenance_comment', 'like', "%{$fulltext}%")
                        ->orWhere('legal_classification_explanation', 'like', "%{$fulltext}%")
                        ->orWhere('bibliography', 'like', "%{$fulltext}%")
                        ->orWhere('images_info', 'like', "%{$fulltext}%")
                        ->orWhere('excavation_comment', 'like', "%{$fulltext}%");
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
                $tags
            ) {
                $query->when($title, function ($query, $title) {
                    $query->where('title', 'like', "%{$title}%");
                })
                    ->when($ancient_author, function ($query, $ancient_author) {
                        $query->where('ancient_author', 'like', "%{$ancient_author}%");
                    })
                    ->when($genres, function ($query, $genres) {
                        $query->whereHas('genres', function ($query) use ($genres) {
                            $query->whereIn('genres.id', array_column($genres, 'id'));
                        });
                    })
                    ->when($tags, function ($query, $tags) {
                        $query->whereHas('tags', function ($query) use ($tags) {
                            $query->whereIn('tags.id', array_column($tags, 'id'));
                        });
                    });
            })
            ->when($show_dating == "true", function ($query) use (
                $earliest_date,
                $latest_date,
                $exclusive_date,
                $dating_methods,
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
                    ->when($dating_methods, function ($query, $dating_methods) {
                        $query->whereHas('dating_methods', function ($query) use ($dating_methods) {
                            $query->whereIn('dating_methods.id', array_column($dating_methods, 'id'));
                        });
                    })
                    ->when($dating_certainties, function ($query, $dating_certainties) {
                        $query->whereIn('dating_certainty_id', array_column($dating_certainties, 'id'));
                    });
            })
            ->when(
                $show_materiality == "true",
                function ($query) use (
                    $materials,
                    $inks,
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
                        ->when($inks, function ($query, $inks) {
                            $query->whereHas('inks', function ($query) use ($inks) {
                                $query->whereIn('inks.id', array_column($inks, 'id'));
                            });
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
                    $outer_margin_max
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
                        });
                }
            )
            ->when(
                $show_palaeography == "true",
                function ($query) use (
                    $hand_number_min,
                    $hand_number_max,
                    $scripts,
                    $diacritics,
                    $punctuations,
                    $paratexts,
                    $decorations,
                    $critical_symbols,
                    $paginations,
                    $quire_signatures
                ) {
                    $query->when($hand_number_min, function ($query, $hand_number_min) {
                        $query->where('hand_number', '>=', $hand_number_min);
                    })
                        ->when($hand_number_max, function ($query, $hand_number_max) {
                            $query->where('hand_number', '<=', $hand_number_max);
                        })
                        ->when($scripts, function ($query, $scripts) {
                            $query->whereHas('scripts', function ($query) use ($scripts) {
                                $query->whereIn('scripts.id', array_column($scripts, 'id'));
                            });
                        })
                        ->when($diacritics, function ($query, $diacritics) {
                            $query->whereHas('diacritics', function ($query) use ($diacritics) {
                                $query->whereIn('diacritics.id', array_column($diacritics, 'id'));
                            });
                        })
                        ->when($punctuations, function ($query, $punctuations) {
                            $query->whereHas('punctuations', function ($query) use ($punctuations) {
                                $query->whereIn('punctuations.id', array_column($punctuations, 'id'));
                            });
                        })
                        ->when($paratexts, function ($query, $paratexts) {
                            $query->whereHas('paratexts', function ($query) use ($paratexts) {
                                $query->whereIn('paratexts.id', array_column($paratexts, 'id'));
                            });
                        })
                        ->when($decorations, function ($query, $decorations) {
                            $query->whereHas('decorations', function ($query) use ($decorations) {
                                $query->whereIn('decorations.id', array_column($decorations, 'id'));
                            });
                        })
                        ->when($critical_symbols, function ($query, $critical_symbols) {
                            $query->whereHas('critical_symbols', function ($query) use ($critical_symbols) {
                                $query->whereIn('critical_symbols.id', array_column($critical_symbols, 'id'));
                            });
                        })
                        ->when($paginations, function ($query, $paginations) {
                            $query->whereHas('paginations', function ($query) use ($paginations) {
                                $query->whereIn('paginations.id', array_column($paginations, 'id'));
                            });
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
                    $analyses
                ) {
                    $query->when($storage_conditions, function ($query, $storage_conditions) {
                        $query->whereIn('storage_condition_id', array_column($storage_conditions, 'id'));
                    })
                        ->when($analyses, function ($query, $analyses) {
                            $query->whereHas('analyses', function ($query) use ($analyses) {
                                $query->whereIn('analyses.id', array_column($analyses, 'id'));
                            });
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
                    $transaction_parties,
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
                        ->when($transactions, function ($query, $transactions) {
                            $query->whereHas('purchases', function ($query) use ($transactions) {
                                $query->whereIn('purchases.id', array_column($transactions, 'id'));
                            });
                        })
                        ->when($transaction_parties, function ($query, $transaction_parties) {
                            $tactions = Purchase::query()
                                ->whereHas('purchase_parties', function ($query) use ($transaction_parties) {
                                    $query->whereIn('purchase_parties.id', array_column($transaction_parties, 'id'));
                                })->get()->all();
                            $query->whereHas('purchases', function ($query) use ($tactions) {
                                $query->whereIn('purchases.id', array_column($tactions, 'id'));
                            });
                        })
                        ->when($legal_classifications, function ($query, $legal_classifications) {
                            $query->whereIn('legal_classification_id', array_column($legal_classifications, 'id'));
                        });
                }
            )

            ->orderBy('start_year')
            ->orderBy('end_year')
            ->orderBy('standard_name')
            ->paginate(10)->withQueryString();

        return Inertia::render('Codices', [
            'show_publication' => $show_publication == "true" ? true : false,
            'show_content' => $show_content == "true" ? true : false,
            'show_dating' => $show_dating == "true" ? true : false,
            'show_materiality' => $show_materiality == "true" ? true : false,
            'show_measurement' => $show_measurement == "true" ? true : false,
            'show_palaeography' => $show_palaeography == "true" ? true : false,
            'show_consanal' => $show_consanal == "true" ? true : false,
            'show_provenance' => $show_provenance == "true" ? true : false,
            'documents' => $documents,
            'analyses' => Analysis::all(),
            'critical_symbols' => CriticalSymbol::all(),
            'covers' => Cover::all(),
            'dating_certainties' => DatingCertainty::all(),
            'dating_methods' => DatingMethod::all(),
            'decorations' => Decoration::all(),
            'diacritics' => Diacritic::all(),
            'genres' => Genre::all(),
            'inks' => Ink::all(),
            'languages' => Language::all(),
            'legal_classifications' => LegalClassification::all(),
            'materials' => Material::all(),
            'paginations' => Pagination::all(),
            'paratexts' => Paratext::all(),
            'punctuations' => Punctuation::all(),
            'purchases' => Purchase::all(),
            'quire_signatures' => QuireSignature::all(),
            'quire_structures' => QuireStructure::all(),
            'scripts' => Script::all(),
            'storage_conditions' => StorageCondition::all(),
            'tags' => Tag::all(),
            'ancient_provenances' => AncientProvenance::all(),
            'ancient_provenance_certainties' => AncientProvenanceCertainty::all(),
            'transactions' => Purchase::all(),
            'transaction_parties' => PurchaseParty::all(),

            'fulltext' => $fulltext,
            'standard_name' => $standard_name,
            'publication' => $publication,
            'current_shelfmarks' => $current_shelfmarks,
            'trismegistos_id' => $trismegistos_id,
            'title' => $title,
            'ancient_author' => $ancient_author,
            'genres_search' => $genres,
            'tags_search' => $tags,
            'earliest_date' => $earliest_date,
            'latest_date' => $latest_date,
            'exclusive_date' => $exclusive_date == "true" ? true : false,
            'dating_methods_search' => $dating_methods,
            'dating_certainties_search' => $dating_certainties,
            'materials_search' => $materials,
            'inks_search' => $inks,
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
            'hand_number_min' => $hand_number_min,
            'hand_number_max' => $hand_number_max,
            'scripts_search' => $scripts,
            'diacritics_search' => $diacritics,
            'punctuations_search' => $punctuations,
            'paratexts_search' => $paratexts,
            'decorations_search' => $decorations,
            'critical_symbols_search' => $critical_symbols,
            'paginations_search' => $paginations,
            'quire_signatures_search' => $quire_signatures,
            'storage_conditions_search' => $storage_conditions,
            'analyses_search' => $analyses,
            'scientifically_excavated' => $scientifically_excavated,
            'ancient_provenances_search' => $ancient_provenances,
            'ancient_provenance_certainties_search' => $ancient_provenance_certainties,
            'transactions_search' => $transactions,
            'transaction_parties_search' => $transaction_parties,
            'legal_classifications_search' => $legal_classifications,
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
            'ancient_provenance' => $document->ancient_provenance()->get(),
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
            'modern_collections' => $document->modern_collections()->get()->makeHidden('pivot'),
            'pagination' => $document->pagination()->get(),
            'paratexts' => $document->paratexts()->get()->makeHidden('pivot'),
            'punctuations' => $document->punctuations()->get()->makeHidden('pivot'),
            'purchases' => $document->purchases()->with('purchase_parties')->with('documents')->get()->makeHidden('pivot'),
            'quire_signature' => $document->quire_signature()->get(),
            'quire_structure' => $document->quire_structure()->get(),
            'scripts' => $document->scripts()->get()->makeHidden('pivot'),
            'storage_condition' => $document->storage_condition()->get(),
            'tags' => $document->tags()->get()->makeHidden('pivot'),
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
            'purchases' => 'nullable',
            'images_info' => 'nullable',
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
        $document->purchases()->sync(array_column($fields['purchases'], 'id'));
        $document->tags()->sync(array_column($fields['tags'], 'id'));
        $document->genres()->sync(array_column($fields['genres'], 'id'));
        $document->dating_methods()->sync(array_column($fields['dating_methods'], 'id'));
        $document->inks()->sync(array_column($fields['inks'], 'id'));

        $document->save();

        $img_fields = $request->validate([
            'images' => 'nullable',
        ]);

        foreach ($document->images as $image) {
            $image->delete();
        }

        foreach ($img_fields['images'] as $image) {
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
