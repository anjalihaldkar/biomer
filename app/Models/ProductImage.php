<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
// ============================================================
//  app/Models/ProductImage.php
// ============================================================
class ProductImage extends Model
{
    protected $fillable = ['product_id', 'image_path', 'sort_order', 'is_featured'];

    public function product() { return $this->belongsTo(Product::class); }
}
