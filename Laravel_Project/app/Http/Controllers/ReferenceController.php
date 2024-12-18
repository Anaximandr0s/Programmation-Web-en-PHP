<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reference;

class ReferenceController extends Controller
{
    // Fonction pour afficher la liste des références de l'utilisateur connecté
    public function index()
    {
        // Vérifie si l'utilisateur est authentifié
        if (!auth()->check()) {
            // Si non authentifié, redirige vers la page de connexion
            return redirect()->route('login');
        }

        // Récupère les références de l'utilisateur connecté
        $references = auth()->user()->references;

        // Retourne la vue avec les références
        return view('references.index', compact('references'));
    }

    // Fonction pour ajouter une nouvelle référence
    public function store(Request $request)
    {
        // Valide les données de la requête
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'authors' => 'required|string',
            'journal' => 'nullable|string|max:255',
            'year' => 'required|integer',
            'doi' => 'nullable|string|max:255',
        ]);

        // Crée une référence associée à l'utilisateur connecté
        auth()->user()->references()->create($validated);

        // Redirige avec un message de succès
        return redirect('/RefManager')->with('success', 'Référence ajoutée avec succès !');
    }

    // Fonction pour exporter les références dans différents formats
    public function export($format = 'bibtex')
    {
        // Récupère les références de l'utilisateur connecté
        $references = auth()->user()->references;

        // Génère le contenu selon le format demandé
        if ($format == 'bibtex') {
            $content = $this->generateBibtex($references);
            $contentType = 'text/plain';
            $fileName = 'references.bib';
        } elseif ($format == 'csv') {
            $content = $this->generateCsv($references);
            $contentType = 'text/csv';
            $fileName = 'references.csv';
        } elseif ($format == 'json') {
            $content = $this->generateJson($references);
            $contentType = 'application/json';
            $fileName = 'references.json';
        } elseif ($format == 'endnote') {
            $content = $this->generateEndNote($references);
            $contentType = 'text/plain';
            $fileName = 'references.enw';
        } else {
            // Par défaut, utilise le format BibTeX
            $content = $this->generateBibtex($references);
            $contentType = 'text/plain';
            $fileName = 'references.bib';
        }

        // Retourne la réponse avec le fichier généré
        return response($content)
            ->header('Content-Type', $contentType)
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    // Fonction pour générer le contenu au format BibTeX
    private function generateBibtex($references)
    {
        $bibtex = '';
        foreach ($references as $ref) {
            $bibtex .= "@article{" . uniqid() . ",\n";
            $bibtex .= "    author = {" . $ref->authors . "},\n";
            $bibtex .= "    title = {" . $ref->title . "},\n";
            $bibtex .= "    journal = {" . $ref->journal . "},\n";
            $bibtex .= "    year = {" . $ref->year . "},\n";
            if ($ref->doi) {
                $bibtex .= "    doi = {" . $ref->doi . "},\n";
            }
            $bibtex .= "}\n\n";
        }
        return $bibtex;
    }

    // Fonction pour générer le contenu au format CSV
    private function generateCsv($references)
    {
        $csv = "Title,Authors,Journal,DOI,Year\n";
        foreach ($references as $ref) {
            $csv .= '"' . $ref->title . '",';
            $csv .= '"' . $ref->authors . '",';
            $csv .= '"' . $ref->journal . '",';
            $csv .= '"' . $ref->doi . '",';
            $csv .= '"' . $ref->year . "\"\n";
        }
        return $csv;
    }

    // Fonction pour générer le contenu au format JSON
    private function generateJson($references)
    {
        return $references->toJson();
    }

    // Fonction pour générer le contenu au format EndNote
    private function generateEndNote($references)
    {
        $endnote = '';
        foreach ($references as $ref) {
            $endnote .= "!Type: Journal\n";
            $endnote .= "Primary Author: " . $ref->authors . "\n";
            $endnote .= "Title: " . $ref->title . "\n";
            $endnote .= "Year: " . $ref->year . "\n";
            $endnote .= "Journal: " . $ref->journal . "\n";
            if ($ref->doi) {
                $endnote .= "DOI: " . $ref->doi . "\n";
            }
            $endnote .= "\n";
        }
        return $endnote;
    }

    // Fonction pour afficher le formulaire de modification d'une référence
    public function edit($id)
    {
        $reference = Reference::findOrFail($id);

        // Vérifie que l'utilisateur connecté possède la référence
        if ($reference->user_id !== auth()->id()) {
            return redirect()->route('references.index')->with('error', 'Vous n\'êtes pas autorisé à modifier cette référence.');
        }

        // Retourne la vue d'édition avec la référence
        return view('references.edit', compact('reference'));
    }

    // Fonction pour mettre à jour une référence
    public function update(Request $request, $id)
    {
        $reference = Reference::findOrFail($id);

        // Vérifie que l'utilisateur connecté possède la référence
        if ($reference->user_id !== auth()->id()) {
            return redirect()->route('references.index')->with('error', 'Vous n\'êtes pas autorisé à modifier cette référence.');
        }

        // Valide les données de la requête
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'authors' => 'required|string',
            'journal' => 'nullable|string|max:255',
            'year' => 'required|integer',
            'doi' => 'nullable|string|max:255',
        ]);

        // Met à jour la référence avec les données validées
        $reference->update($validated);

        // Redirige avec un message de succès
        return redirect('/RefManager')->with('success', 'Référence mise à jour avec succès !');
    }

    // Fonction pour supprimer une référence
    public function destroy($id)
    {
        $reference = Reference::findOrFail($id);

        // Vérifie que l'utilisateur connecté possède la référence
        if ($reference->user_id !== auth()->id()) {
            return redirect()->route('references.index')->with('error', 'Vous n\'êtes pas autorisé à supprimer cette référence.');
        }

        // Supprime la référence
        $reference->delete();

        // Redirige avec un message de succès
        return redirect('/RefManager')->with('success', 'Référence supprimée avec succès !');
    }
}
