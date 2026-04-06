<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    /**
     * Show the form for editing site settings
     */
    public function edit()
    {
        $settings = SiteSetting::first() ?? new SiteSetting();
        return view('dashboard.settings.site-settings', compact('settings'));
    }

    /**
     * Update the specified resource in storage
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'tagline' => 'required|string|max:500',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'about' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'footer_text' => 'nullable|string',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo_path')) {
            $file = $request->file('logo_path');
            $path = $file->store('logos', 'public');
            $validated['logo_path'] = $path;
        }

        // Handle footer logo upload
        if ($request->hasFile('footer_logo_path')) {
            $file = $request->file('footer_logo_path');
            $path = $file->store('logos', 'public');
            $validated['footer_logo_path'] = $path;
        }

        // Update or create
        $settings = SiteSetting::first();
        if ($settings) {
            $settings->update($validated);
        } else {
            SiteSetting::create($validated);
        }

        return redirect()->back()->with('success', 'Site settings updated successfully!');
    }
}
