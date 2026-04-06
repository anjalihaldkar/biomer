<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('title')->paginate(10);
        return view('dashboard.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('dashboard.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'              => 'required|string|max:255|unique:pages,title',
            'content'            => 'nullable|string',
            'meta_title'         => 'nullable|string|max:255',
            'meta_description'   => 'nullable|string|max:500',
            'meta_keyword'       => 'nullable|string|max:500',
            'status'             => 'boolean',
        ]);

        Page::create($validated);

        return redirect()->route('dashboard.pages.index')
            ->with('success', 'Page created successfully!');
    }

    public function edit(Page $page)
    {
        return view('dashboard.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title'              => 'required|string|max:255|unique:pages,title,' . $page->id,
            'content'            => 'nullable|string',
            'meta_title'         => 'nullable|string|max:255',
            'meta_description'   => 'nullable|string|max:500',
            'meta_keyword'       => 'nullable|string|max:500',
            'status'             => 'boolean',
        ]);

        $page->update($validated);

        return redirect()->route('dashboard.pages.index')
            ->with('success', 'Page updated successfully!');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('dashboard.pages.index')
            ->with('success', 'Page deleted successfully!');
    }
}
