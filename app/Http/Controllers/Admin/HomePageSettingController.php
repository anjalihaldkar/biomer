<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomePageSetting;
use Illuminate\Http\Request;

class HomePageSettingController extends Controller
{
    public function edit()
    {
        return view('dashboard.settings.home-page', [
            'homePageConfig' => HomePageSetting::currentMerged(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'problem_heading' => 'nullable|string|max:255',
            'problem_paragraph' => 'nullable|string|max:1000',
            'problem_items' => 'nullable|array|max:5',
            'problem_items.*.image_path' => 'nullable|string|max:1000',
            'problem_items.*.heading' => 'nullable|string|max:255',
            'problem_items.*.paragraph' => 'nullable|string|max:1000',
            'problem_item_images' => 'nullable|array|max:5',
            'problem_item_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $items = $validated['problem_items'] ?? [];

        foreach (range(0, 4) as $index) {
            if ($request->hasFile("problem_item_images.$index")) {
                $items[$index]['image_path'] = $request->file("problem_item_images.$index")
                    ->store('home-page/problem-items', 'public');
            }
        }

        $payload = [
            'problem_heading' => $validated['problem_heading'] ?? null,
            'problem_paragraph' => $validated['problem_paragraph'] ?? null,
            'problem_items' => $this->cleanItems($items),
        ];

        $settings = HomePageSetting::query()->latest('id')->first();

        if ($settings) {
            $settings->update($payload);
        } else {
            HomePageSetting::create($payload);
        }

        return redirect()
            ->route('dashboard.home-page.edit')
            ->with('success', 'Home page section updated successfully.');
    }

    protected function cleanItems(array $items): array
    {
        $defaults = HomePageSetting::defaults()['problem_items'];
        $clean = [];

        foreach (range(0, 4) as $index) {
            $item = $items[$index] ?? [];
            $default = $defaults[$index];

            $clean[] = [
                'image_path' => trim((string) ($item['image_path'] ?? $default['image_path'])),
                'heading' => trim((string) ($item['heading'] ?? $default['heading'])),
                'paragraph' => trim((string) ($item['paragraph'] ?? $default['paragraph'])),
            ];
        }

        return $clean;
    }
}
