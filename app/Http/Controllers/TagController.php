<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('products')->latest()->paginate(20);
        return view('dashboard.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('dashboard.tags.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:tags,name',
            'slug' => 'nullable|string|max:100|unique:tags,slug',
        ]);

        Tag::create([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
        ]);

        return redirect()->route('dashboard.tags.index')
            ->with('success', 'Tag created successfully!');
    }

    public function edit(Tag $tag)
    {
        return view('dashboard.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:tags,name,' . $tag->id,
            'slug' => 'nullable|string|max:100|unique:tags,slug,' . $tag->id,
        ]);

        $tag->update([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
        ]);

        return redirect()->route('dashboard.tags.index')
            ->with('success', 'Tag updated successfully!');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('dashboard.tags.index')
            ->with('success', 'Tag deleted.');
    }

    // Bulk add tags (comma or newline separated)
    public function bulkStore(Request $request)
    {
        $request->validate(['bulk_tags' => 'required|string']);

        $raw  = $request->bulk_tags;
        $names = preg_split('/[\n,]+/', $raw);
        $count = 0;

        foreach ($names as $name) {
            $name = trim($name);
            if (!$name) continue;
            $slug = Str::slug($name);
            Tag::firstOrCreate(['slug' => $slug], ['name' => $name]);
            $count++;
        }

        return redirect()->route('dashboard.tags.index')
            ->with('success', "{$count} tag(s) added successfully!");
    }

    // AJAX: search tags for product form autocomplete
    public function search(Request $request)
    {
        $tags = Tag::where('name', 'like', '%' . $request->q . '%')
            ->limit(10)
            ->get(['id', 'name', 'slug']);
        return response()->json($tags);
    }
}