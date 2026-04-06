<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterLink extends Model
{
    protected $fillable = ['section', 'label', 'url', 'position', 'is_active', 'target'];

    /**
     * Get active footer links by section sorted by position
     */
    public static function getBySection($section)
    {
        return self::where('section', $section)
                   ->where('is_active', true)
                   ->orderBy('position')
                   ->get();
    }

    /**
     * Get all sections
     */
    public static function getSections()
    {
        return self::where('is_active', true)->distinct('section')->pluck('section')->unique();
    }
}
