<?php

use App\Http\Controllers\AuthController;
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
    Route::get('/', [KnowledgeBaseController::class, 'home'])->name('kb.home');
    Route::get('/manifest.json', [KnowledgeBaseController::class, 'manifest'])->name('kb.manifest');
    Route::get('/svg/{name}', [KnowledgeBaseController::class, 'svg'])->name('kb.svg');
    Route::get('/article/{article}', [KnowledgeBaseController::class, 'article'])->name('kb.article');
});

Route::get('/', HomeController::class)->name('home');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/auth/redirect', [AuthController::class, 'redirect'])->name('auth.redirect');
Route::get('/auth/callback', [AuthController::class, 'callback'])->name('auth.callback');

Route::middleware('auth.session')->group(function () {
    Route::get('/kb/preview/{slug}', [KnowledgeBaseController::class, 'preview'])
        ->name('kb.preview');
    Route::get('/kb/preview/{slug}/article/{article}', [KnowledgeBaseController::class, 'previewArticle'])
        ->name('kb.preview.article');
});
