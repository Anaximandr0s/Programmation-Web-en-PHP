<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reference;

class ReferenceController extends Controller
{
    public function index()
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            // If not authenticated, redirect to the login page
            return redirect()->route('login');
        }

        // Get references of the logged-in user
        $references = auth()->user()->references;

        // Return the view with the references
        return view('references.index', compact('references'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'authors' => 'required|string',
            'journal' => 'nullable|string|max:255',
            'year' => 'required|integer',
            'doi' => 'nullable|string|max:255',
        ]);

        // Create a reference associated with the logged-in user
        auth()->user()->references()->create($validated);

        return redirect('/RefManager')->with('success', 'Reference added successfully!');

    }

    // Function to export references in BibTeX format for the logged-in user
    public function export($format = 'bibtex')
    {
        $references = auth()->user()->references; // Export only the logged-in user's references

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
            // Default to bibtex if format is unknown
            $content = $this->generateBibtex($references);
            $contentType = 'text/plain';
            $fileName = 'references.bib';
        }

        return response($content)
            ->header('Content-Type', $contentType)
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

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

    private function generateJson($references)
    {
        return $references->toJson();
    }

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

    // Function to show the form for updating the reference
    public function edit($id)
    {
        $reference = Reference::findOrFail($id);

        // Check if the logged-in user owns the reference
        if ($reference->user_id !== auth()->id()) {
            return redirect()->route('references.index')->with('error', 'You are not authorized to edit this reference.');
        }

        return view('references.edit', compact('reference'));
    }

    // Function to update the reference in the database
    public function update(Request $request, $id)
    {
        $reference = Reference::findOrFail($id);

        // Check if the logged-in user owns the reference
        if ($reference->user_id !== auth()->id()) {
            return redirect()->route('references.index')->with('error', 'You are not authorized to edit this reference.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'authors' => 'required|string',
            'journal' => 'nullable|string|max:255',
            'year' => 'required|integer',
            'doi' => 'nullable|string|max:255',
        ]);

        $reference->update($validated);

        return redirect('/RefManager')->with('success', 'Reference updated successfully!');
    }

    // Function to delete the reference
    public function destroy($id)
    {
        $reference = Reference::findOrFail($id);

        // Check if the logged-in user owns the reference
        if ($reference->user_id !== auth()->id()) {
            return redirect()->route('references.index')->with('error', 'You are not authorized to delete this reference.');
        }

        $reference->delete();

        return redirect('/RefManager')->with('success', 'Reference deleted successfully!');

    }
}
