<?php

use App\Http\Controllers\PostsController;
use App\Http\Controllers\TaggedPostsController;
use App\Http\Controllers\TagsController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [PostsController::class, 'index'])->name('home');

Route::get('/tagged', [TaggedPostsController::class, 'index'])->name('tagged');

Route::get('/tags', TagsController::class)->name('tags');
