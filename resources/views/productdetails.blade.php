@extends('layout.frontlayout')
@section('title', $product->name . ' – Bharat Biomer')

@section('content')

  <!-- ========================
       SECTION 1: Hero
  ======================== -->
  <section class="prodh__section">
    <div class="container">
      <div class="row">
        <div class="col-12 col-lg-8">

          <div class="prodh__badge mb-3">
            <img src="{{ asset('assets/images/flask-icon.svg') }}" alt="flask" class="prodh__badge-icon"/>
            <span class="prodh__badge-text">
              {{ $product->category->name ?? 'Product Details' }}
            </span>
          </div>

          <h1 class="prodh__heading">{{ $product->name }}</h1>
          <p class="prodh__desc">{{ $product->short_description }}</p>

        </div>
      </div>
    </div>
  </section>

  <!-- ========================
       SECTION 2: Product Detail
  ======================== -->
  <section class="avan__section">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="avan__card">
            <div class="row g-0">

              <!-- ── LEFT: Images ── -->
              <div class="col-12 col-md-5">

                {{-- Main Image --}}
                <div class="pd__img-main-wrap">
                  @if($product->featured_image)
                    <img src="{{ Storage::url($product->featured_image) }}"
                         alt="{{ $product->name }}"
                         class="avan__product-img"
                         id="mainImage">
                  @else
                    <img src="{{ asset('assets/images/product-bottle.svg') }}"
                         alt="{{ $product->name }}"
                         class="avan__product-img"
                         id="mainImage">
                  @endif
                </div>

                {{-- Gallery Thumbnails --}}
                @if($product->images->count())
                <div class="pd__thumbs">
                  @if($product->featured_image)
                    <img src="{{ Storage::url($product->featured_image) }}"
                         class="pd__thumb pd__thumb--active"
                         onclick="changeImage(this, '{{ Storage::url($product->featured_image) }}')">
                  @endif
                  @foreach($product->images as $img)
                    <img src="{{ Storage::url($img->image_path) }}"
                         class="pd__thumb"
                         onclick="changeImage(this, '{{ Storage::url($img->image_path) }}')">
                  @endforeach
                </div>
                @endif

              </div>

              <!-- ── RIGHT: Content ── -->
              <div class="col-12 col-md-7">
                <div class="avan__content">

                  {{-- Brand & Tags --}}
                  <div class="avan__tags-wrap mb-3">
                    @if($product->brand)
                      <span class="avan__tag">{{ $product->brand->name }}</span>
                    @endif
                    @foreach($product->tags as $tag)
                      <span class="avan__tag">{{ $tag->name }}</span>
                    @endforeach
                  </div>

                  <h3 class="avan__product-title">{{ $product->name }}</h3>

                  @if($product->technical_content)
                    <p class="pd__technical">{{ $product->technical_content }}</p>
                  @endif

                  {{-- ── Price Box ── --}}
                  <div class="pd__price-box">
                    <span class="pd__price-label">Price</span>
                    <div class="pd__price-row">
                      <span class="pd__price" id="displayPrice">
                        ₹{{ number_format($product->variations->count() ? $product->variations->min('price') : $product->base_price, 2) }}
                      </span>
                      @if($product->variations->count())
                        <span class="pd__price-note" id="priceNote">Starting price</span>
                      @endif
                    </div>
                  </div>

                  {{-- ── Variation Selector ── --}}
                  @if($product->variations->count())
                  <div class="pd__variation-wrap">
                    <h5 class="avan__features-heading">
                      Select
                      {{ $product->variations->first()->attribute_name ?? 'Variant' }}
                    </h5>

                    <div class="pd__variation-grid" id="variationGrid">
                      @foreach($product->variations as $var)
                        @if($var->is_active)
                        <button type="button"
                                class="pd__var-btn {{ $loop->first ? 'pd__var-btn--active' : '' }}"
                                data-id="{{ $var->id }}"
                                data-price="{{ $var->price }}"
                                data-stock="{{ $var->stock_quantity }}"
                                data-sku="{{ $var->sku }}"
                                data-value="{{ $var->attribute_value }}"
                                data-image="{{ $var->image_path ? Storage::url($var->image_path) : '' }}"
                                onclick="selectVariation(this)">
                          {{ $var->attribute_value }}
                        </button>
                        @endif
                      @endforeach
                    </div>

                    {{-- Stock indicator --}}
                    <p class="pd__stock" id="stockInfo">
                      @php $firstVar = $product->variations->where('is_active', true)->first(); @endphp
                      @if($firstVar)
                        @if($firstVar->stock_quantity > 10)
                          <span class="pd__stock--in">✓ In Stock ({{ $firstVar->stock_quantity }} available)</span>
                        @elseif($firstVar->stock_quantity > 0)
                          <span class="pd__stock--low">⚠ Low Stock ({{ $firstVar->stock_quantity }} left)</span>
                        @else
                          <span class="pd__stock--out">✕ Out of Stock</span>
                        @endif
                      @endif
                    </p>
                  </div>
                  @else
                    {{-- No variations --}}
                    <p class="pd__stock">
                      <span class="pd__stock--in">✓ Available</span>
                    </p>
                  @endif

                  {{-- ── Key Features ── --}}
                  @if($product->description)
                  <h5 class="avan__features-heading mt-3">Description</h5>
                  <p class="avan__product-desc">{{ $product->description }}</p>
                  @endif

                  {{-- ── CTA Buttons ── --}}
                  <div class="pd__cta-wrap">
                    <button class="pd__cta-btn pd__cta-btn--primary" id="addToCartBtn"
                            data-product-id="{{ $product->id }}">
                      🛒 Add to Cart
                    </button>
                    <a href="{{ route('products.index') }}" class="pd__cta-btn pd__cta-btn--outline">
                      ← Back to Products
                    </a>
                  </div>

                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================
       SECTION 3: Key Features
  ======================== -->
  @if($product->technical_content || $product->tags->count())
  <section class="avan__section" style="padding-top:0;">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="avan__header">
            <div class="avan__header-top">
              <span class="avan__check">✓</span>
              <h3 class="avan__header-title">Product Details</h3>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-3">

        @if($product->technical_content)
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="avan__feature-item">
            <div class="avan__feature-icon-wrap">
              <img src="{{ asset('assets/images/dosage-icon.svg') }}" alt="Technical" class="avan__feature-icon"/>
            </div>
            <div>
              <p class="avan__feature-title">Technical Content</p>
              <p class="avan__feature-desc">{{ $product->technical_content }}</p>
            </div>
          </div>
        </div>
        @endif

        @if($product->brand)
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="avan__feature-item">
            <div class="avan__feature-icon-wrap">
              <img src="{{ asset('assets/images/compatible-icon.svg') }}" alt="Brand" class="avan__feature-icon"/>
            </div>
            <div>
              <p class="avan__feature-title">Brand</p>
              <p class="avan__feature-desc">{{ $product->brand->name }}</p>
            </div>
          </div>
        </div>
        @endif

        @if($product->category)
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="avan__feature-item">
            <div class="avan__feature-icon-wrap">
              <img src="{{ asset('assets/images/multicrop-icon.svg') }}" alt="Category" class="avan__feature-icon"/>
            </div>
            <div>
              <p class="avan__feature-title">Category</p>
              <p class="avan__feature-desc">{{ $product->category->name }}</p>
            </div>
          </div>
        </div>
        @endif

        @if($product->variations->count())
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="avan__feature-item">
            <div class="avan__feature-icon-wrap">
              <img src="{{ asset('assets/images/foliar-icon.svg') }}" alt="Variants" class="avan__feature-icon"/>
            </div>
            <div>
              <p class="avan__feature-title">Pack Sizes</p>
              <p class="avan__feature-desc">{{ $product->variations->count() }} options available</p>
            </div>
          </div>
        </div>
        @endif

      </div>

      {{-- Tags / Crop Suitability --}}
      @if($product->tags->count())
      <div class="row mt-4">
        <div class="col-12">
          <h5 class="avan__features-heading">Tags</h5>
          <div class="avan__tags-wrap">
            @foreach($product->tags as $tag)
              <span class="avan__tag">{{ $tag->name }}</span>
            @endforeach
          </div>
        </div>
      </div>
      @endif

    </div>
  </section>
  @endif

  <!-- ========================
       SECTION 4: Pipeline
  ======================== -->
  <section class="ppip__section">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="ppip__header-top">
            <img src="{{ asset('assets/images/clock-icon.svg') }}" alt="clock" class="ppip__header-icon"/>
            <h3 class="ppip__header-title">More Coming Soon</h3>
          </div>
          <p class="ppip__header-desc">Next-generation solutions under development</p>
        </div>
      </div>
      <div class="row g-4 mt-2">
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="ppip__card">
            <span class="ppip__badge">Coming Soon</span>
            <div class="ppip__icon-wrap">
              <img src="{{ asset('assets/images/fertilizer-icon.svg') }}" alt="Smart Fertilizers" class="ppip__icon"/>
            </div>
            <h4 class="ppip__card-title">Smart Fertilizers</h4>
            <p class="ppip__card-desc">Intelligent nutrient delivery with controlled release</p>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="ppip__card">
            <span class="ppip__badge">Coming Soon</span>
            <div class="ppip__icon-wrap">
              <img src="{{ asset('assets/images/consortia-icon.svg') }}" alt="Microbial Consortia" class="ppip__icon"/>
            </div>
            <h4 class="ppip__card-title">Microbial Consortia</h4>
            <p class="ppip__card-desc">Advanced multi-strain formulations for soil health</p>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="ppip__card">
            <span class="ppip__badge">Coming Soon</span>
            <div class="ppip__icon-wrap">
              <img src="{{ asset('assets/images/biopolymer-icon.svg') }}" alt="Biopolymer" class="ppip__icon"/>
            </div>
            <h4 class="ppip__card-title">Biopolymer Inputs</h4>
            <p class="ppip__card-desc">Sustainable polymer-based agri enhancement</p>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="ppip__card">
            <span class="ppip__badge">Coming Soon</span>
            <div class="ppip__icon-wrap">
              <img src="{{ asset('assets/images/climate-icon.svg') }}" alt="Climate" class="ppip__icon"/>
            </div>
            <h4 class="ppip__card-title">Climate-Resilient</h4>
            <p class="ppip__card-desc">Formulations for extreme weather stress</p>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection

@push('styles')
<style>
  /* ── Image Gallery ─────────────────────────── */
  .pd__img-main-wrap {
    position: relative;
    overflow: hidden;
  }
  .pd__thumbs {
    display: flex;
    gap: 10px;
    padding: 12px 16px;
    flex-wrap: wrap;
  }
  .pd__thumb {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #e8f0e4;
    cursor: pointer;
    transition: border-color 0.2s;
  }
  .pd__thumb--active,
  .pd__thumb:hover {
    border-color: #2d7a45;
  }

  /* ── Price Box ─────────────────────────────── */
  .pd__price-box {
    background: #f4faf0;
    border: 1px solid #c8e6c9;
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 20px;
    display: inline-block;
    min-width: 200px;
  }
  .pd__price-label {
    font-size: 0.78rem;
    color: #7aab7a;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    display: block;
    margin-bottom: 2px;
  }
  .pd__price-row {
    display: flex;
    align-items: baseline;
    gap: 8px;
  }
  .pd__price {
    font-size: 2rem;
    font-weight: 800;
    color: #2d7a45;
    line-height: 1;
  }
  .pd__price-note {
    font-size: 0.78rem;
    color: #9aab9a;
  }

  /* ── Technical label ───────────────────────── */
  .pd__technical {
    font-size: 0.85rem;
    color: #7aab7a;
    margin-bottom: 12px;
    font-style: italic;
  }

  /* ── Variation buttons ─────────────────────── */
  .pd__variation-wrap {
    margin-bottom: 18px;
  }
  .pd__variation-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 10px;
  }
  .pd__var-btn {
    padding: 8px 20px;
    border-radius: 8px;
    border: 2px solid #c8e6c9;
    background: #fff;
    color: #2d7a45;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  .pd__var-btn:hover {
    border-color: #2d7a45;
    background: #f4faf0;
  }
  .pd__var-btn--active {
    border-color: #2d7a45;
    background: #2d7a45;
    color: #fff;
  }

  /* ── Stock ─────────────────────────────────── */
  .pd__stock {
    font-size: 0.85rem;
    margin-bottom: 0;
  }
  .pd__stock--in   { color: #2d7a45; font-weight: 600; }
  .pd__stock--low  { color: #b45309; font-weight: 600; }
  .pd__stock--out  { color: #dc3545; font-weight: 600; }

  /* ── CTA Buttons ───────────────────────────── */
  .pd__cta-wrap {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 24px;
  }
  .pd__cta-btn {
    padding: 12px 28px;
    border-radius: 10px;
    font-size: 0.95rem;
    font-weight: 700;
    cursor: pointer;
    border: none;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.2s ease;
  }
  .pd__cta-btn--primary {
    background: #2d7a45;
    color: #fff;
    flex: 1;
  }
  .pd__cta-btn--primary:hover { background: #245e36; }
  .pd__cta-btn--outline {
    background: transparent;
    color: #2d7a45;
    border: 2px solid #2d7a45;
  }
  .pd__cta-btn--outline:hover { background: #f4faf0; }
</style>
@endpush

@push('scripts')
<script>
  // ── Variation selector ───────────────────────────────────────────────
  function selectVariation(btn) {

    // Remove active from all
    document.querySelectorAll('.pd__var-btn').forEach(b => b.classList.remove('pd__var-btn--active'));
    btn.classList.add('pd__var-btn--active');

    // Update price
    const price = parseFloat(btn.dataset.price).toLocaleString('en-IN', {
      minimumFractionDigits: 2, maximumFractionDigits: 2
    });
    document.getElementById('displayPrice').textContent = '₹' + price;
    document.getElementById('priceNote') && (document.getElementById('priceNote').textContent = btn.dataset.value);

    // Update stock
    const stock   = parseInt(btn.dataset.stock);
    const stockEl = document.getElementById('stockInfo');
    if (stock > 10) {
      stockEl.innerHTML = `<span class="pd__stock--in">✓ In Stock (${stock} available)</span>`;
    } else if (stock > 0) {
      stockEl.innerHTML = `<span class="pd__stock--low">⚠ Low Stock (${stock} left)</span>`;
    } else {
      stockEl.innerHTML = `<span class="pd__stock--out">✕ Out of Stock</span>`;
    }

    // Update image if variation has one
    if (btn.dataset.image) {
      document.getElementById('mainImage').src = btn.dataset.image;
      document.querySelectorAll('.pd__thumb').forEach(t => t.classList.remove('pd__thumb--active'));
    }

    // Store selected variation id on cart button
    document.getElementById('addToCartBtn').dataset.variationId = btn.dataset.id;
  }

  // ── Thumbnail gallery ────────────────────────────────────────────────
  function changeImage(thumb, src) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.pd__thumb').forEach(t => t.classList.remove('pd__thumb--active'));
    thumb.classList.add('pd__thumb--active');
  }

  // ── Add to Cart ──────────────────────────────────────────────────────
  document.getElementById('addToCartBtn').addEventListener('click', function () {
    const productId   = this.dataset.productId;
    const variationId = this.dataset.variationId || null;

    fetch('/cart/add', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ product_id: productId, variation_id: variationId, quantity: 1 })
    })
    .then(r => r.json())
    .then(d => {
      if (d.success) {
        const btn = document.getElementById('addToCartBtn');
        btn.textContent = '✓ Added to Cart!';
        btn.style.background = '#4caf72';
        setTimeout(() => {
          btn.textContent = '🛒 Add to Cart';
          btn.style.background = '';
        }, 2500);
      }
    })
    .catch(() => alert('Could not add to cart. Please try again.'));
  });

  // ── Auto-select first variation on load ──────────────────────────────
  const firstVar = document.querySelector('.pd__var-btn');
  if (firstVar) {
    document.getElementById('addToCartBtn').dataset.variationId = firstVar.dataset.id;
  }
</script>
@endpush