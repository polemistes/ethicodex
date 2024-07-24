<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionPartyController;
use App\Http\Controllers\ModernCollectionController;

use App\Models\User;

use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/user_register', [UserController::class, 'create']);
Route::post('/user_store', [UserController::class, 'store']);
Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'store']);
Route::post('logout', [LoginController::class, 'destroy'])->middleware('auth');

Route::get('/', function () { return (Inertia::render('Home')); })->name('Home');
Route::get('/about', function () { return (Inertia::render('About')); });

Route::get('/codices', [DocumentController::class, 'index'])->name('Codices');
Route::post('/codices', [DocumentController::class, 'index'])->name('Codices');

Route::post('/codex_show/{document}', [DocumentController::class, 'show']);
Route::get('/codex_show/{document}', [DocumentController::class, 'show']);
Route::post('/codex_edit/{document}', [DocumentController::class, 'edit'])->name('CodexEdit');
Route::get('/codex_edit/{document}', [DocumentController::class, 'edit'])->name('CodexEdit');
Route::put('/codex_edit/{document}', [DocumentController::class, 'update']);
Route::get('/codex_new', [DocumentController::class, 'create']);
Route::post('/codex_store', [DocumentController::class, 'store']);
Route::post('/codex_delete/{document}', [DocumentController::class, 'destroy']);

Route::get('/transactions', [TransactionController::class, 'index'])->name('Transactions');
Route::get('/transaction_edit/{transaction}', [TransactionController::class, 'edit'])->name('TransactionEdit');
Route::post('/transaction_update/{transaction}', [TransactionController::class, 'update']);
Route::post('/transaction_new', [TransactionController::class, 'create']);
Route::post('/transaction_store', [TransactionController::class, 'store']);
Route::post('/transaction_delete/{transaction}', [TransactionController::class, 'destroy']);

Route::get('/transaction_parties', [TransactionPartyController::class, 'index'])->name('TransactionParties');
Route::get('/transaction_party_edit/{transactionParty}', [TransactionPartyController::class, 'edit'])->name('TransactionPartyEdit');
Route::post('/transaction_party_update/{transactionParty}', [TransactionPartyController::class, 'update']);
Route::post('/transaction_party_new', [TransactionPartyController::class, 'create']);
Route::post('/transaction_party_store', [TransactionPartyController::class, 'store']);
Route::post('/transaction_party_delete/{transactionParty}', [TransactionPartyController::class, 'destroy']);

Route::get('/modern_collections', [ModernCollectionController::class, 'index'])->name('ModernCollections');
Route::get('/modern_collection_edit/{modernCollection}', [ModernCollectionController::class, 'edit'])->name('ModernCollectionEdit');
Route::post('/modern_collection_update/{modernCollection}', [ModernCollectionController::class, 'update']);
Route::post('/modern_collection_new', [ModernCollectionController::class, 'create']);
Route::post('/modern_collection_store', [ModernCollectionController::class, 'store']);
Route::post('/modern_collection_delete/{modernCollection}', [ModernCollectionController::class, 'destroy']);

Route::post('addimages', [ImageController::class, 'store']);
Route::post('/delimage/{image}', [ImageController::class, 'destroy']);
