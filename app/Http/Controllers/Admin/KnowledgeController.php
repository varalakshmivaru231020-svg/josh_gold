<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeArticle;
use Illuminate\Http\Request;

// Admin → Knowledge: the guides on the public /knowledge page
class KnowledgeController extends Controller
{
    public function index()
    {
        $articles = KnowledgeArticle::ordered()->paginate(20);

        return view('admin.knowledge.index', compact('articles'));
    }

    public function create()
    {
        // New articles go to the end by default
        $nextSortOrder = (int) KnowledgeArticle::max('sort_order') + 1;

        return view('admin.knowledge.create', compact('nextSortOrder'));
    }

    public function store(Request $request)
    {
        KnowledgeArticle::create($this->validated($request));

        return redirect()->route('admin.knowledge.index')
            ->with('success', 'Article added successfully.');
    }

    public function edit(KnowledgeArticle $article)
    {
        return view('admin.knowledge.edit', compact('article'));
    }

    public function update(Request $request, KnowledgeArticle $article)
    {
        $article->update($this->validated($request));

        return redirect()->route('admin.knowledge.index')
            ->with('success', 'Article updated successfully.');
    }

    public function destroy(KnowledgeArticle $article)
    {
        $article->delete();

        return redirect()->route('admin.knowledge.index')
            ->with('success', 'Article deleted successfully.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'tag'        => 'required|string|max:60',
            'title'      => 'required|string|max:255',
            'intro'      => 'nullable|string|max:2000',
            'points'     => 'nullable|string|max:10000',
            'sort_order' => 'nullable|integer|min:-100000|max:100000',
        ]);

        $data['points']     = KnowledgeArticle::normalisePoints($data['points'] ?? null);
        $data['sort_order'] = $data['sort_order'] ?? 0; // blank means 0 (the column is NOT NULL)
        $data['is_active']  = $request->boolean('is_active');

        return $data;
    }
}
