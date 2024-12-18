<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReferenceController;

// Route pour la page d'accueil
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route pour la page des services
Route::get('/services', function () {
    return view('services');
})->name('services');

// Route pour la page de recherche
Route::get('/search', function () {
    return view('search.search');
})->name('search');

// Route pour afficher le gestionnaire de références
Route::get('/RefManager', [ReferenceController::class, 'index'])->name('RefManager');

// Route pour enregistrer une nouvelle référence
Route::post('/references', [ReferenceController::class, 'store']);

// Route pour exporter les références dans différents formats (BibTeX, CSV, JSON, EndNote)
Route::get('/export/{format?}', [ReferenceController::class, 'export'])->name('export');

// Route pour afficher le formulaire de modification d'une référence
Route::get('/references/{id}/edit', [ReferenceController::class, 'edit'])->name('references.edit');

// Route pour mettre à jour une référence existante
Route::put('/references/{id}', [ReferenceController::class, 'update'])->name('references.update');

// Route pour supprimer une référence
Route::delete('/references/{id}', [ReferenceController::class, 'destroy'])->name('references.destroy');

// Inclusion des routes pour l'authentification
require __DIR__.'/auth.php';

