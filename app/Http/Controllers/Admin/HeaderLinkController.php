<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeaderLink;
use Illuminate\Http\Request;

class HeaderLinkController extends Controller
{
    /**
     * Display all header links
     */
    public function index()
    {
        $links = HeaderLink::orderBy('position')->paginate(10);
        return view('dashboard.settings.header-links.index', compact('links'));
    }

    /**
     * Show create header link form
     */
    public function create()
    {
        return view('dashboard.settings.header-links.create');
    }

    /**
     * Store a new header link
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'position' => 'required|integer',
            'is_active' => 'boolean',
            'target' => 'required|in:_self,_blank',
        ]);

        HeaderLink::create($validated);
        return redirect()->route('dashboard.header-links.index')->with('success', 'Header link created successfully!');
    }

    /**
     * Show edit header link form
     */
    public function edit(HeaderLink $headerLink)
    {
        return view('dashboard.settings.header-links.edit', compact('headerLink'));
    }

    /**
     * Update a header link
     */
    public function update(Request $request, HeaderLink $headerLink)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'position' => 'required|integer',
            'is_active' => 'boolean',
            'target' => 'required|in:_self,_blank',
        ]);

        $headerLink->update($validated);
        return redirect()->route('dashboard.header-links.index')->with('success', 'Header link updated successfully!');
    }

    /**
     * Delete a header link
     */
    public function destroy(HeaderLink $headerLink)
    {
        $headerLink->delete();
        return redirect()->back()->with('success', 'Header link deleted successfully!');
    }
}
