<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Blog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'thumbnail',
        'description',
        'tags',
        'status',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $blog) {
            $blog->slug = self::uniqueSlug($blog->title);
        });

        static::updating(function (self $blog) {
            if ($blog->isDirty('title')) {
                $blog->slug = self::uniqueSlug($blog->title, $blog->id);
            }
        });
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug  = Str::slug($title);
        $query = self::where('slug', $slug);
        if ($ignoreId) $query->where('id', '!=', $ignoreId);
        $count = $query->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : asset('assets/images/user.png');
    }
}
