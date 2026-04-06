<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta_title',
        'meta_description',
        'meta_keyword',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->slug ??= Str::slug($m->title));
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
