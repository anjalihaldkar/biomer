<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterLink;
use Illuminate\Http\Request;

class FooterLinkController extends Controller
{
    /**
     * Display all footer links
     */
    public function index()
    {
        $links = FooterLink::orderBy('section')->orderBy('position')->paginate(10);
        return view('dashboard.settings.footer-links.index', compact('links'));
    }

    /**
     * Show create footer link form
     */
    public function create()
    {
        return view('dashboard.settings.footer-links.create');
    }

    /**
     * Store a new footer link
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'section' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'position' => 'required|integer',
            'is_active' => 'boolean',
            'target' => 'required|in:_self,_blank',
        ]);

        FooterLink::create($validated);
        return redirect()->route('dashboard.footer-links.index')->with('success', 'Footer link created successfully!');
    }

    /**
     * Show edit footer link form
     */
    public function edit(FooterLink $footerLink)
    {
        return view('dashboard.settings.footer-links.edit', compact('footerLink'));
    }

    /**
     * Update a footer link
     */
    public function update(Request $request, FooterLink $footerLink)
    {
        $validated = $request->validate([
            'section' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'position' => 'required|integer',
            'is_active' => 'boolean',
            'target' => 'required|in:_self,_blank',
        ]);

        $footerLink->update($validated);
        return redirect()->route('dashboard.footer-links.index')->with('success', 'Footer link updated successfully!');
    }

    /**
     * Delete a footer link
     */
    public function destroy(FooterLink $footerLink)
    {
        $footerLink->delete();
        return redirect()->back()->with('success', 'Footer link deleted successfully!');
    }
}
