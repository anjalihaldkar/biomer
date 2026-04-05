<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // ── Index ──────────────────────────────────────────────────────────────
    public function index()
    {
        $products = Product::with(['brand', 'category', 'variations'])
            ->latest()
            ->paginate(15);

        return view('dashboard.products.index', compact('products'));
    }

    // ── Create form ────────────────────────────────────────────────────────
    public function create()
    {
        $brands     = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $tags       = Tag::orderBy('name')->get();

        return view('dashboard.products.create', compact('brands', 'categories', 'tags'));
    }

    // ── Store ──────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'sku'               => 'required|string|max:100|unique:products,sku',
            'brand_id'          => 'nullable|exists:brands,id',
            'category_id'       => 'nullable|exists:categories,id',
            'technical_content' => 'nullable|string|max:255',
            'description'       => 'nullable|string',
            'short_description' => 'nullable|string',
            'base_price'        => 'required|numeric|min:0',
            'shipping_charge'   => 'nullable|numeric|min:0',
            'tax_rate'          => 'nullable|numeric|min:0|max:100',
            'status'            => 'required|in:active,inactive,draft',
            'video_url'         => 'nullable|url|max:500',
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string',
            'featured_image'    => 'nullable|image|max:2048',
            'gallery.*'         => 'nullable|image|max:2048',
            'tags'              => 'nullable|array',
            'tags.*'            => 'string',
        ]);

        $variations = $request->input('variations', []);

        if (!empty($variations)) {
            $varRules = [];
            foreach ($variations as $i => $v) {
                $varRules["variations.{$i}.attribute_name"]  = 'required|string|max:100';
                $varRules["variations.{$i}.attribute_value"] = 'required|string|max:100';
                $varRules["variations.{$i}.sku"]             = 'required|string|max:100|distinct|unique:product_variations,sku';
                $varRules["variations.{$i}.price"]           = 'required|numeric|min:0';
                $varRules["variations.{$i}.weight"]          = 'nullable|numeric|min:0';
                $varRules["variations.{$i}.stock_quantity"]  = 'required|integer|min:0';
            }
            $request->validate($varRules);
        }

        // ── try/catch exposes any silent error ─────────────────────────────
        try {
            DB::transaction(function () use ($request, $variations) {

                // 1. Featured image
                $featuredImagePath = null;
                if ($request->hasFile('featured_image')) {
                    $featuredImagePath = $request->file('featured_image')
                        ->store('products/featured', 'public');
                }

                // 2. Create product
                $product = Product::create([
                    'name'              => $request->name,
                    'slug'              => Str::slug($request->name),
                    'sku'               => $request->sku,
                    'brand_id'          => $request->brand_id,
                    'category_id'       => $request->category_id,
                    'technical_content' => $request->technical_content,
                    'description'       => $request->description,
                    'short_description' => $request->short_description,
                    'base_price'        => $request->base_price,
                    'shipping_charge'   => $request->shipping_charge ?? 0,
                    'tax_rate'          => $request->tax_rate ?? 0,
                    'status'            => $request->status,
                    'featured_image'    => $featuredImagePath,
                    'video_url'         => $request->video_url,
                    'meta_title'        => $request->meta_title,
                    'meta_description'  => $request->meta_description,
                ]);

                // 3. Gallery images
                if ($request->hasFile('gallery')) {
                    $order = 0;
                    foreach ($request->file('gallery') as $file) {
                        $path = $file->store('products/gallery', 'public');
                        ProductImage::create([
                            'product_id'  => $product->id,
                            'image_path'  => $path,
                            'sort_order'  => $order,
                            'is_featured' => $order === 0,
                        ]);
                        $order++;
                    }
                }

                // 4. Variations
                if (!empty($variations)) {
                    $varFiles = $request->file('variations', []);

                    foreach ($variations as $index => $varData) {
                        $varImagePath = null;
                        if (
                            isset($varFiles[$index]['image']) &&
                            $varFiles[$index]['image']->isValid()
                        ) {
                            $varImagePath = $varFiles[$index]['image']
                                ->store('products/variations', 'public');
                        }

                        ProductVariation::create([
                            'product_id'      => $product->id,
                            'sku'             => $varData['sku'],
                            'attribute_name'  => $varData['attribute_name'],
                            'attribute_value' => $varData['attribute_value'],
                            'price'           => $varData['price'],
                            'weight'          => $varData['weight'] ?? null,
                            'stock_quantity'  => (int) $varData['stock_quantity'],
                            'is_active'       => true,
                            'image_path'      => $varImagePath,
                        ]);
                    }
                }

                // 5. Tags
                if ($request->filled('tags') && is_array($request->tags)) {
                    $tagIds = [];
                    foreach ($request->tags as $tagName) {
                        $tagName = trim($tagName);
                        if (!$tagName) continue;
                        $tag      = Tag::firstOrCreate(
                            ['slug' => Str::slug($tagName)],
                            ['name' => $tagName]
                        );
                        $tagIds[] = $tag->id;
                    }
                    if (!empty($tagIds)) {
                        $product->tags()->sync($tagIds);
                    }
                }
            });

        } catch (\Exception $e) {
            // ── Shows the REAL error on screen instead of silent redirect ──
            dd([
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);
        }

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product created successfully!');
    }

    // ── Show ───────────────────────────────────────────────────────────────
    public function show(Product $product)
    {
        $product->load(['brand', 'category', 'tags', 'images', 'variations']);
        return view('dashboard.products.show', compact('product'));
    }

    // ── Edit form ──────────────────────────────────────────────────────────
    public function edit(Product $product)
    {
        $product->load(['brand', 'category', 'tags', 'images', 'variations']);
        $brands     = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $tags       = Tag::orderBy('name')->get();

        return view('dashboard.products.edit', compact('product', 'brands', 'categories', 'tags'));
    }

    // ── Update ─────────────────────────────────────────────────────────────
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'sku'               => 'required|string|max:100|unique:products,sku,' . $product->id,
            'brand_id'          => 'nullable|exists:brands,id',
            'category_id'       => 'nullable|exists:categories,id',
            'technical_content' => 'nullable|string|max:255',
            'description'       => 'nullable|string',
            'short_description' => 'nullable|string',
            'base_price'        => 'required|numeric|min:0',
            'shipping_charge'   => 'nullable|numeric|min:0',
            'tax_rate'          => 'nullable|numeric|min:0|max:100',
            'status'            => 'required|in:active,inactive,draft',
            'video_url'         => 'nullable|url|max:500',
            'featured_image'    => 'nullable|image|max:2048',
            'gallery.*'         => 'nullable|image|max:2048',
            'tags'              => 'nullable|array',
            'tags.*'            => 'string',
        ]);

        $variations = $request->input('variations', []);

        try {
            DB::transaction(function () use ($request, $product, $variations) {

                // 1. Featured image
                $featuredImagePath = $product->featured_image;
                if ($request->hasFile('featured_image')) {
                    if ($product->featured_image) {
                        Storage::disk('public')->delete($product->featured_image);
                    }
                    $featuredImagePath = $request->file('featured_image')
                        ->store('products/featured', 'public');
                }

                // 2. Update product
                $product->update([
                    'name'              => $request->name,
                    'slug'              => Str::slug($request->name),
                    'sku'               => $request->sku,
                    'brand_id'          => $request->brand_id,
                    'category_id'       => $request->category_id,
                    'technical_content' => $request->technical_content,
                    'description'       => $request->description,
                    'short_description' => $request->short_description,
                    'base_price'        => $request->base_price,
                    'shipping_charge'   => $request->shipping_charge ?? 0,
                    'tax_rate'          => $request->tax_rate ?? 0,
                    'status'            => $request->status,
                    'featured_image'    => $featuredImagePath,
                    'video_url'         => $request->video_url,
                    'meta_title'        => $request->meta_title,
                    'meta_description'  => $request->meta_description,
                ]);

                // 3. Gallery images
                if ($request->hasFile('gallery')) {
                    $lastOrder = $product->images()->max('sort_order') ?? -1;
                    foreach ($request->file('gallery') as $file) {
                        $path = $file->store('products/gallery', 'public');
                        ProductImage::create([
                            'product_id'  => $product->id,
                            'image_path'  => $path,
                            'sort_order'  => ++$lastOrder,
                            'is_featured' => false,
                        ]);
                    }
                }

                // 4. Variations — UPDATE existing, CREATE new
                if (!empty($variations)) {
                    $varFiles = $request->file('variations', []);

                    foreach ($variations as $index => $varData) {
                        $varImagePath = null;
                        if (
                            isset($varFiles[$index]['image']) &&
                            $varFiles[$index]['image']->isValid()
                        ) {
                            $varImagePath = $varFiles[$index]['image']
                                ->store('products/variations', 'public');
                        }

                        if (!empty($varData['id'])) {
                            // UPDATE existing variation
                            $existing = ProductVariation::find($varData['id']);
                            if ($existing) {
                                if ($varImagePath && $existing->image_path) {
                                    Storage::disk('public')->delete($existing->image_path);
                                }
                                $existing->update([
                                    'sku'             => $varData['sku'],
                                    'attribute_name'  => $varData['attribute_name'],
                                    'attribute_value' => $varData['attribute_value'],
                                    'price'           => $varData['price'],
                                    'weight'          => $varData['weight'] ?? null,
                                    'stock_quantity'  => (int) $varData['stock_quantity'],
                                    'image_path'      => $varImagePath ?: $existing->image_path,
                                ]);
                            }
                        } else {
                            // CREATE new variation
                            ProductVariation::create([
                                'product_id'      => $product->id,
                                'sku'             => $varData['sku'],
                                'attribute_name'  => $varData['attribute_name'],
                                'attribute_value' => $varData['attribute_value'],
                                'price'           => $varData['price'],
                                'weight'          => $varData['weight'] ?? null,
                                'stock_quantity'  => (int) $varData['stock_quantity'],
                                'is_active'       => true,
                                'image_path'      => $varImagePath,
                            ]);
                        }
                    }
                }

                // 5. Tags sync
                if ($request->filled('tags') && is_array($request->tags)) {
                    $tagIds = [];
                    foreach ($request->tags as $tagName) {
                        $tagName = trim($tagName);
                        if (!$tagName) continue;
                        $tag      = Tag::firstOrCreate(
                            ['slug' => Str::slug($tagName)],
                            ['name' => $tagName]
                        );
                        $tagIds[] = $tag->id;
                    }
                    $product->tags()->sync($tagIds);
                } else {
                    $product->tags()->detach();
                }
            });

        } catch (\Exception $e) {
            dd([
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);
        }

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product updated successfully!');
    }

    // ── Destroy ────────────────────────────────────────────────────────────
    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img->image_path);
            }
            if ($product->featured_image) {
                Storage::disk('public')->delete($product->featured_image);
            }
            $product->delete();
        });

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product deleted.');
    }

    // ── Delete single gallery image (AJAX) ────────────────────────────────
    public function destroyImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        return response()->json(['success' => true]);
    }

    // ── Delete single variation (AJAX) ────────────────────────────────────
    public function destroyVariation(ProductVariation $variation)
    {
        if ($variation->image_path) {
            Storage::disk('public')->delete($variation->image_path);
        }
        $variation->delete();
        return response()->json(['success' => true]);
    }

    // ── Frontend: Product Listing ──────────────────────────────────────────
public function shopIndex(Request $request)
{
    $query = Product::with(['brand', 'category', 'variations'])
        ->where('status', 'active');

    // Search functionality
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('short_description', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Category filter
    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }

    // Brand filter
    if ($request->filled('brand')) {
        $query->where('brand_id', $request->brand);
    }

    // Price range filter
    if ($request->filled('min_price') || $request->filled('max_price')) {
        $query->where(function ($q) use ($request) {
            $q->where(function ($subQ) use ($request) {
                // Products with variations - check variation prices
                $subQ->whereHas('variations', function ($vq) use ($request) {
                    if ($request->filled('min_price')) {
                        $vq->where('price', '>=', $request->min_price);
                    }
                    if ($request->filled('max_price')) {
                        $vq->where('price', '<=', $request->max_price);
                    }
                });
            })->orWhere(function ($subQ) use ($request) {
                // Products without variations - check base price
                $subQ->whereDoesntHave('variations')
                     ->where(function ($priceQ) use ($request) {
                         if ($request->filled('min_price')) {
                             $priceQ->where('base_price', '>=', $request->min_price);
                         }
                         if ($request->filled('max_price')) {
                             $priceQ->where('base_price', '<=', $request->max_price);
                         }
                     });
            });
        });
    }

    // Sorting
    $sortBy = $request->get('sort', 'latest');
    switch ($sortBy) {
        case 'price_low':
            $query->orderByRaw('(SELECT MIN(price) FROM product_variations WHERE product_variations.product_id = products.id) ASC, base_price ASC');
            break;
        case 'price_high':
            $query->orderByRaw('(SELECT MAX(price) FROM product_variations WHERE product_variations.product_id = products.id) DESC, base_price DESC');
            break;
        case 'name':
            $query->orderBy('name');
            break;
        case 'latest':
        default:
            $query->latest();
            break;
    }

    $products = $query->paginate(12)->withQueryString();

    // Get filter options
    $categories = \App\Models\Category::orderBy('name')->get();
    $brands = \App\Models\Brand::orderBy('name')->get();

    return view('product', compact('products', 'categories', 'brands'));
}

// ── Frontend: Product Detail ───────────────────────────────────────────
// ── Frontend: Product Detail ───────────────────────────────────────────
public function shopShow(Product $product)
{
    abort_if($product->status !== 'active', 404);

    $product->load([
        'brand',
        'category',
        'tags',
        'images',
        'variations' => function ($q) {
            $q->where('is_active', true)->orderBy('price');
        },
        'reviews' => function ($q) {
            $q->with('customer')->where('status', 'approved')->latest();
        }
    ]);

    return view('productdetails', compact('product'));
}
}