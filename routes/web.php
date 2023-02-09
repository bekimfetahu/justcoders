<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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


Route::get('/questions/ask', [QuestionController::class,'showAsk']);


Route::post('/questions/ask', [QuestionController::class,'ask'])->name('questions.ask');
Route::post('/questions/answer', [QuestionController::class,'answer'])->name('questions.answer');
Route::post('/questions/comment', [QuestionController::class,'comment'])->name('questions.comment');
Route::get('/questions/{id}/{slug}',[QuestionController::class,'showQuestionDetail']);
Route::get('/questions/list', [QuestionController::class,'showQuestionList']);

Route::get('/answer/{id}/edit',[QuestionController::class,'showEditAnswer']);
Route::post('/answer/edit', [QuestionController::class,'editAnswer'])->name('answer.edit');


Route::get('/question/{id}/edit',[QuestionController::class,'showEditQuestion']);
Route::post('/question/edit', [QuestionController::class,'editQuestion'])->name('questions.edit');

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/questions/list', [QuestionController::class,'showQuestionList']);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
