<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ReferenceController;

Route::get('/', [ReferenceController::class, 'index']);
Route::post('/references', [ReferenceController::class, 'store']);
Route::get('/export', [ReferenceController::class, 'export']);
Route::get('/references/{id}/edit', [ReferenceController::class, 'edit'])->name('references.edit');
Route::put('/references/{id}', [ReferenceController::class, 'update'])->name('references.update');
Route::delete('/references/{id}', [ReferenceController::class, 'destroy'])->name('references.destroy');

require __DIR__.'/auth.php';
