<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePageSetting extends Model
{
    protected $fillable = [
        'problem_heading',
        'problem_paragraph',
        'problem_items',
    ];

    protected $casts = [
        'problem_items' => 'array',
    ];

    public static function defaults(): array
    {
        return [
            'problem_heading' => 'Farming today needs more than fertilizers',
            'problem_paragraph' => 'Modern farmers face multiple crop and soil challenges every season.',
            'problem_items' => [
                [
                    'image_path' => 'assets/bharat-biomer/plant.png',
                    'heading' => 'Soil degradation',
                    'paragraph' => 'Continuous farming reduces soil organic matter and health.',
                ],
                [
                    'image_path' => 'assets/bharat-biomer/root.png',
                    'heading' => 'Weak root development',
                    'paragraph' => 'Poor root growth limits nutrient and water absorption.',
                ],
                [
                    'image_path' => 'assets/bharat-biomer/npk.png',
                    'heading' => 'Nutrient inefficiency',
                    'paragraph' => 'Nutrients are not utilized effectively by the crop.',
                ],
                [
                    'image_path' => 'assets/bharat-biomer/pest.png',
                    'heading' => 'Pest and stress impact',
                    'paragraph' => 'Pests, diseases and climate stress reduce crop potential.',
                ],
                [
                    'image_path' => 'assets/bharat-biomer/plant-1.png',
                    'heading' => 'Lower yield and quality',
                    'paragraph' => 'All these factors lead to low productivity and poor produce quality.',
                ],
            ],
        ];
    }

    public static function currentMerged(): array
    {
        $defaults = static::defaults();
        $settings = static::query()->latest('id')->first();

        if (! $settings) {
            return $defaults;
        }

        return [
            'problem_heading' => $settings->problem_heading ?: $defaults['problem_heading'],
            'problem_paragraph' => $settings->problem_paragraph ?: $defaults['problem_paragraph'],
            'problem_items' => static::mergeProblemItems($settings->problem_items, $defaults['problem_items']),
        ];
    }

    public static function imageUrl(?string $path): string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return asset('assets/bharat-biomer/plant.png');
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $normalizedPath = ltrim($path, '/');

        if (str_starts_with($normalizedPath, 'storage/')) {
            return asset($normalizedPath);
        }

        if (str_starts_with($normalizedPath, 'assets/')) {
            return asset($normalizedPath);
        }

        return asset('storage/' . $normalizedPath);
    }

    protected static function mergeProblemItems(?array $items, array $defaults): array
    {
        $items = is_array($items) ? array_values($items) : [];
        $merged = [];

        foreach ($defaults as $index => $default) {
            $item = $items[$index] ?? [];

            $merged[] = [
                'image_path' => trim((string) ($item['image_path'] ?? $default['image_path'])),
                'heading' => trim((string) ($item['heading'] ?? $default['heading'])),
                'paragraph' => trim((string) ($item['paragraph'] ?? $default['paragraph'])),
            ];
        }

        return $merged;
    }
}
