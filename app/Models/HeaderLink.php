<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeaderLink extends Model
{
    protected $fillable = ['label', 'url', 'icon', 'position', 'is_active', 'target'];

    /**
     * Get active header links sorted by position
     */
    public static function getActive()
    {
        return self::where('is_active', true)->orderBy('position')->get();
    }
}
