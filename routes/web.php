<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\KnowledgeBaseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Knowledge Bases
$subdomain = config('knowledge-base.domain');
Route::domain('{slug}.' . $subdomain)->group(function () {
    Route::get('/', [KnowledgeBaseController::class, 'home']);
});

Route::get('/', HomeController::class)->name('home');
Route::get('/login', HomeController::class)->name('login');

Route::middleware('auth.session')->group(function () {
    Route::get('/kb/preview/{slug}', [KnowledgeBaseController::class, 'preview'])
        ->name('kb.preview');
    Route::get('/kb/preview/{slug}/article/{article}', [KnowledgeBaseController::class, 'previewArticle'])
        ->name('kb.preview.article');
});
