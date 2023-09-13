<?php

use App\Http\Controllers\AchievementsController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\CommentController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users/{user}/achievements', [AchievementsController::class, 'index']);

Route::post('/watch-lesson', [LessonController::class, 'watch'])->name('watch-lesson');

Route::post('/write-comment', [CommentController::class, 'write'])->name('write-comment');


