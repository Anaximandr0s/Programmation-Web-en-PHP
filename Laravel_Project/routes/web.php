<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ReferenceController;
Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/services', function () {
    return view('services');
})->name('services');

Route::get('/search', function () {
    return view('search.search');
})->name('search');


Route::get('/RefManager', [ReferenceController::class, 'index'])->name('RefManager');
Route::post('/references', [ReferenceController::class, 'store']);
Route::get('/export/{format?}', [ReferenceController::class, 'export'])->name('export');

Route::get('/references/{id}/edit', [ReferenceController::class, 'edit'])->name('references.edit');
Route::put('/references/{id}', [ReferenceController::class, 'update'])->name('references.update');
Route::delete('/references/{id}', [ReferenceController::class, 'destroy'])->name('references.destroy');

require __DIR__.'/auth.php';
