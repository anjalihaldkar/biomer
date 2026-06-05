@extends('layout.frontlayout')
@section('title', 'Checkout – Bharat Biomer')

@section('content')
<section class="chk__section">
    <div class="container">

        {{-- Back --}}
        <a href="{{ route('cart.index') }}" class="chk__back-link">← Back to Cart</a>

        <h1 class="chk__heading">Checkout</h1>
        <p class="chk__subheading">Complete your details to place your order</p>

        {{-- Steps --}}
        <div class="chk__steps">
            <div class="chk__step done">
                <div class="chk__step-num">✓</div>
                <span>Cart</span>
            </div>
            <div class="chk__step-divider"></div>
            <div class="chk__step active">
                <div class="chk__step-num">2</div>
                <span>Details</span>
            </div>
            <div class="chk__step-divider"></div>
            <div class="chk__step">
                <div class="chk__step-num">3</div>
                <span>Confirm</span>
            </div>
        </div>

        <form id="checkoutForm">
        @csrf
        <div class="row g-4">

            {{-- ══════════════════════════════
                 LEFT — Forms
            ══════════════════════════════ --}}
            <div class="col-12 col-lg-7">

                {{-- Logged in as --}}
                <div class="chk__user-banner">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    Ordering as&nbsp;<strong>{{ $customer->name }}</strong>
                    &nbsp;({{ $customer->email }})
                </div>

                {{-- 1. Contact Details --}}
                <div class="chk__form-card">
                    <div class="chk__form-card-title">
                        <span class="chk__form-card-num">1</span>
                        Contact Details
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="chk__label">Full Name *</label>
                            <input type="text" name="name"
                                   class="chk__input @error('name') is-invalid @enderror"
                                   value="{{ old('name', $customer->name) }}"
                                   placeholder="Your full name" required>
                            @error('name')<span class="chk__error">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="chk__label">Phone Number *</label>
                            <input type="tel" name="phone"
                                   class="chk__input @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $customer->phone) }}"
                                   placeholder="+91 XXXXX XXXXX" required>
                            @error('phone')<span class="chk__error">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-12">
                            <label class="chk__label">Email Address *</label>
                            <input type="email" name="email"
                                   class="chk__input @error('email') is-invalid @enderror"
                                   value="{{ old('email', $customer->email) }}"
                                   placeholder="you@example.com" required>
                            @error('email')<span class="chk__error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                {{-- 2. Shipping Address --}}
                <div class="chk__form-card">
                    <div class="chk__form-card-title">
                        <span class="chk__form-card-num">2</span>
                        Shipping Address
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="chk__label">Full Address *</label>
                            <textarea name="address"
                                      class="chk__textarea @error('address') is-invalid @enderror"
                                      placeholder="House / Flat no., Street, Village, Landmark, Area..."
                                      required>{{ old('address', $customer->address) }}</textarea>
                            @error('address')<span class="chk__error">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="chk__label">City *</label>
                            <input type="text" name="city"
                                   class="chk__input @error('city') is-invalid @enderror"
                                   value="{{ old('city', $customer->city) }}" placeholder="City / Town" required>
                            @error('city')<span class="chk__error">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="chk__label">State *</label>
                            <select name="state"
                                    class="chk__select @error('state') is-invalid @enderror" required>
                                <option value="">— Select State —</option>
                                @foreach([
                                    'Andhra Pradesh','Arunachal Pradesh','Assam','Bihar',
                                    'Chhattisgarh','Goa','Gujarat','Haryana',
                                    'Himachal Pradesh','Jharkhand','Karnataka','Kerala',
                                    'Madhya Pradesh','Maharashtra','Manipur','Meghalaya',
                                    'Mizoram','Nagaland','Odisha','Punjab','Rajasthan',
                                    'Sikkim','Tamil Nadu','Telangana','Tripura',
                                    'Uttar Pradesh','Uttarakhand','West Bengal',
                                    'Delhi','Jammu & Kashmir','Ladakh'
                                ] as $state)
                                    <option value="{{ $state }}"
                                        {{ old('state', $customer->state) == $state ? 'selected' : '' }}>
                                        {{ $state }}
                                    </option>
                                @endforeach
                            </select>
                            @error('state')<span class="chk__error">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="chk__label">PIN Code *</label>
                            <input type="text" name="pincode"
                                   class="chk__input @error('pincode') is-invalid @enderror"
                                   value="{{ old('pincode', $customer->pincode) }}"
                                   placeholder="6-digit PIN" maxlength="6" required>
                            @error('pincode')<span class="chk__error">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="chk__label">Country</label>
                            <input type="text" name="country"
                                   class="chk__input" value="{{ old('country', $customer->country ?: 'India') }}" readonly>
                        </div>
                    </div>
                </div>

                {{-- 3. Notes --}}
                <div class="chk__form-card">
                    <div class="chk__form-card-title">
                        <span class="chk__form-card-num">3</span>
                        Additional Notes
                        <small class="chk__optional">(optional)</small>
                    </div>
                    <textarea name="notes" class="chk__textarea"
                              placeholder="Crop type, field conditions, preferred delivery time...">{{ old('notes') }}</textarea>
                    <div class="chk__note">
                        💡 Mention your crop and soil type — our agronomist will include personalised tips with your order.
                    </div>
                </div>

            </div>

            {{-- ══════════════════════════════
                 RIGHT — Order Summary
            ══════════════════════════════ --}}
            <div class="col-12 col-lg-5">
                <div class="chk__summary-card">
                    <div class="chk__summary-title">
                        Order Summary
                        <span class="chk__summary-count">
                            ({{ collect($cart)->sum('quantity') }} item(s))
                        </span>
                    </div>

                    {{-- Items --}}
                    @foreach($cart as $key => $item)
                    <div class="chk__item">
                        @if(!empty($item['image']))
                            <img src="{{ Storage::url($item['image']) }}"
                                 alt="{{ $item['name'] }}" class="chk__item-img">
                        @else
                            <div class="chk__item-img-placeholder"><iconify-icon icon="mdi:leaf" class="icon"></iconify-icon></div>
                        @endif
                        <div class="chk__item-info">
                            <div class="chk__item-name">{{ $item['name'] }}</div>
                            @if(!empty($item['variation']))
                                <div class="chk__item-meta">{{ $item['variation'] }}</div>
                            @endif
                            <div class="chk__item-meta">Qty: {{ $item['quantity'] }}</div>
                        </div>
                        <div class="chk__item-price">
                            ₹{{ number_format($item['price'] * $item['quantity'], 2) }}
                        </div>
                    </div>
                    @endforeach

                    {{-- Totals --}}
                    <div class="chk__totals">
                        <div class="chk__total-row">
                            <span>Subtotal</span>
                            <span>₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="chk__total-row">
                            <span>Shipping</span>
                            <span class="chk__total-value--success">
                                @if($shippingTotal > 0)
                                    ₹{{ number_format($shippingTotal, 2) }}
                                @else
                                    Free
                                @endif
                            </span>
                        </div>
                        <div class="chk__total-row">
                            <span>Tax (GST)</span>
                            <span>Included</span>
                        </div>
                        <div class="chk__total-row grand">
                            <span>Total</span>
                            <span>₹{{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                   {{-- Payment Method Selector --}}
<div class="chk__form-card-title chk__form-card-title--payment">
    <span class="chk__form-card-num"><iconify-icon icon="fa6-solid:credit-card" class="icon"></iconify-icon></span> Choose Payment Method
</div>
<div class="pay-methods">
    @foreach($paymentGateways as $index => $gateway)
        <label class="pay-method-label">
            <input type="radio" name="payment_method" value="{{ $gateway->gateway_name }}" {{ $index == 0 ? 'checked' : '' }}>
            @if($gateway->logo_url)
                <img src="{{ $gateway->logo_url }}"
                     onerror="this.style.display='none'"
                     class="pay-method-logo--light">
            @elseif($gateway->gateway_name == 'cod')
                <iconify-icon icon="mdi:cash" class="pay-method-icon"></iconify-icon>
            @endif
            {{ $gateway->display_name }}
        </label>
    @endforeach
</div>

<p class="chk__secure-note">
    <iconify-icon icon="ic:outline-lock" class="icon"></iconify-icon> Your information is secure and encrypted
</p>

<button type="button" id="placeOrderBtn" class="chk__place-btn" onclick="startPayment()">
    <iconify-icon icon="fa6-solid:credit-card" class="btn-icon"></iconify-icon>
    <span>Pay Now</span>
</button>

                </div>
            </div>

        </div>
        </form>

    </div>
</section>

@endsection
@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
<script>
const cashfree = Cashfree({ mode: "{{ config('cashfree.environment', 'sandbox') }}" });

function getSelectedGateway() {
    return document.querySelector('input[name="payment_method"]:checked')?.value || 'razorpay';
}

function startPayment() {
    const btn  = document.getElementById('placeOrderBtn');
    const form = document.getElementById('checkoutForm');

    if (!form.checkValidity()) { form.reportValidity(); return; }

    btn.disabled    = true;
        btn.querySelector('span').textContent = 'Processing...';
    const gateway = getSelectedGateway();

    if (gateway === 'cod') {
        startCodPayment(btn, form);
    } else if (gateway === 'cashfree') {
        startCashfreePayment(btn, form);
    } else {
        startRazorpayPayment(btn, form);
    }
}

// ── RAZORPAY ──────────────────────────────────────────────────────────
function startRazorpayPayment(btn, form) {
    const formData = new FormData(form);

    fetch('{{ route("order.razorpay") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: formData,
    })
    .then(res => res.json().then(data => ({ ok: res.ok, data })))
    .then(({ ok, data }) => {
        if (!ok) throw new Error(data.error || data.message || 'Something went wrong.');

        const options = {
            key:         data.key_id,
            amount:      data.amount,
            currency:    data.currency,
            name:        'Bharat Biomer',
            description: 'Order Payment',
            order_id:    data.razorpay_order_id,
            prefill:     { name: data.name, email: data.email, contact: data.phone },
            theme:       { color: '#2d7a45' },
            handler: function (response) {
                btn.querySelector('span').textContent = 'Verifying Payment...';
                fetch('{{ route("order.payment.success") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        razorpay_order_id:   response.razorpay_order_id,
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_signature:  response.razorpay_signature,
                    }),
                })
                .then(res => res.json().then(data => ({ ok: res.ok, data })))
                .then(({ ok, data }) => {
                    if (ok && data.redirect_url) window.location.href = data.redirect_url;
                    else throw new Error(data.error || 'Payment verification failed.');
                })
                .catch(err => { alert(err.message); resetBtn(btn); });
            },
            modal: { ondismiss: () => resetBtn(btn) }
        };

        const rzp = new Razorpay(options);
        rzp.on('payment.failed', r => { alert('Payment failed: ' + r.error.description); resetBtn(btn); });
        rzp.open();
    })
    .catch(err => { alert(err.message); resetBtn(btn); });
}

// ── CASHFREE ──────────────────────────────────────────────────────────
function startCashfreePayment(btn, form) {
    const formData = new FormData(form);

    fetch('{{ route("order.cashfree") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: formData,
    })
    .then(res => res.json().then(data => ({ ok: res.ok, data })))
    .then(({ ok, data }) => {
        if (!ok) throw new Error(data.error || 'Something went wrong.');

        if (!data.payment_session_id) {
            throw new Error('Cashfree session was not created. Please check the server logs.');
        }

        btn.querySelector('span').textContent = 'Opening Payment...';

        cashfree.checkout({
            paymentSessionId: data.payment_session_id,
            redirectTarget:   "_self",   // opens in same tab; Cashfree redirects back to return_url
        });
    })
    .catch(err => { alert(err.message); resetBtn(btn); });
}

// ── COD ───────────────────────────────────────────────────────────────
function startCodPayment(btn, form) {
    if (!confirm('Place order with Cash on Delivery? You will pay when the order is delivered.')) {
        resetBtn(btn);
        return;
    }

    const formData = new FormData(form);

    fetch('{{ route("order.cod") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: formData,
    })
    .then(res => res.json().then(data => ({ ok: res.ok, data })))
    .then(({ ok, data }) => {
        if (ok && data.redirect_url) {
            window.location.href = data.redirect_url;
        } else {
            let msg = data.error || data.message || 'Something went wrong.';
            if (data.errors) {
                // Combine all Laravel validation errors into one string
                msg = Object.values(data.errors).flat().join('\n');
            }
            throw new Error(msg);
        }
    })
    .catch(err => { alert(err.message); resetBtn(btn); });
}

function resetBtn(btn) {
    btn.disabled = false;
    const label = btn.querySelector('span');
    if (label) {
        label.textContent = 'Pay Now';
    }
}
</script>
@endpush

