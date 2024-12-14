<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reference;

class ReferenceController extends Controller
{
    public function index()
    {
        $references = Reference::all();
        return view('references.index', compact('references'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'authors' => 'required',
            'year' => 'required|integer',
        ]);

        Reference::create($request->all());
        return redirect('/')->with('success', 'Reference added successfully!');
    }

    // Function to export references in BibTeX format
    public function export()
    {
        $references = Reference::all();
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

        return response($bibtex)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="references.bib"');
    }

    // Function to show the form for updating the reference
    public function edit($id)
    {
        $reference = Reference::findOrFail($id);
        return view('references.edit', compact('reference'));
    }

    // Function to update the reference in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'authors' => 'required',
            'year' => 'required|integer',
        ]);

        $reference = Reference::findOrFail($id);
        $reference->update($request->all());

        return redirect('/')->with('success', 'Reference updated successfully!');
    }

    // Function to delete a reference
    public function destroy($id)
    {
        $reference = Reference::findOrFail($id);
        $reference->delete();

        return redirect('/')->with('success', 'Reference deleted successfully!');
    }
}
