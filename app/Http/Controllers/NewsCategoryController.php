<?php

namespace App\Http\Controllers;

use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsCategoryController extends Controller
{
    public function index()
    {
        $categories = NewsCategory::withCount('newsPosts')->latest()->paginate(10);
        return view('speednews.admin.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:news_categories',
            'icon' => 'nullable|string|max:50',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        NewsCategory::create($validated);
        return redirect()->route('admin.news.categories.index')->with('success', 'Kategori berita ditambahkan');
    }

    public function update(Request $request, NewsCategory $category)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:news_categories,name,' . $category->id,
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);
        return redirect()->route('admin.news.categories.index')->with('success', 'Kategori berita diperbarui');
    }

    public function destroy(NewsCategory $category)
    {
        $category->delete();
        return redirect()->route('admin.news.categories.index')->with('success', 'Kategori berita dihapus');
    }
}
