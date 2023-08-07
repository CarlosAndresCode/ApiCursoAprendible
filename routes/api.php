<?php

use App\Http\Controllers\Api\ArticleController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::get('/articles', [ArticleController::class, 'index'])->name('api.v1.articles.index');

Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('api.v1.articles.show');

Route::post('/articles', [ArticleController::class, 'store'])->name('api.v1.articles.store');



