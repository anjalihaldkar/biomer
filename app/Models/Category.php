<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
// ============================================================
//  app/Models/Category.php
// ============================================================
class Category extends Model
{
    protected $fillable = ['name', 'slug', 'parent_id'];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->slug ??= Str::slug($m->name));
    }

    public function parent()    { return $this->belongsTo(Category::class, 'parent_id'); }
    public function children()  { return $this->hasMany(Category::class, 'parent_id'); }
    public function products()  { return $this->hasMany(Product::class); }
}


