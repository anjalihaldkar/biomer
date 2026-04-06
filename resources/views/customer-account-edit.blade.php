@extends('layout.frontlayout')
@section('title', 'Edit Account – Bharat Biomer')

@push('styles')
<style>
* { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }

.cae__card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e8f0e4;
    box-shadow: 0 2px 16px rgba(60,120,60,0.06);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.cae__header {
    background: #f4faf0;
    border-bottom: 1px solid #e8f0e4;
    padding: 1.25rem 1.5rem;
}
.cae__title { font-size: 1.1rem; font-weight: 800; color: #1a2e1a; margin-bottom: 0.25rem; }
.cae__subtitle { font-size: 0.85rem; color: #6b7c6b; }

.cae__body { padding: 1.5rem; }

.cae__form .form-group { margin-bottom: 1.5rem; }
.cae__form .form-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: #1a2e1a;
    margin-bottom: 0.5rem;
    display: block;
}
.cae__form .form-control {
    width: 100%;
    border: 1.5px solid #e8f0e4;
    border-radius: 8px;
    padding: 0.75rem;
    font-size: 0.9rem;
    transition: all 0.2s;
    box-sizing: border-box;
}
.cae__form .form-control:focus {
    outline: none;
    border-color: #2d7a45;
    box-shadow: 0 0 0 0.2rem rgba(45,122,69,0.1);
}
.cae__form .form-control.is-invalid {
    border-color: #dc3545;
}
.cae__form .invalid-feedback {
    color: #dc3545;
    font-size: 0.85rem;
    margin-top: 0.25rem;
    display: block;
}

.cae__actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding-top: 1rem;
    border-top: 1px solid #f0f5ee;
}
.cae__btn {
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    border: none;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s;
    cursor: pointer;
}
.cae__btn--primary {
    background: #2d7a45;
    color: #fff;
}
.cae__btn--primary:hover {
    background: #245e36;
    color: #fff;
}
.cae__btn--outline {
    background: transparent;
    color: #2d7a45;
    border: 1.5px solid #2d7a45;
}
.cae__btn--outline:hover {
    background: #f0faf4;
}

.cae__alert {
    background: #e3f2fd;
    border: 1px solid #90caf9;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}
.cae__alert-icon { color: #1976d2; margin-right: 0.5rem; }
.cae__alert-text { color: #0d47a1; font-size: 0.9rem; }

@media (max-width: 768px) {
    .cae__actions { flex-direction: column; }
    .cae__btn { width: 100%; justify-content: center; }
}
</style>
@endpush

@section('content')
<div class="container my-4">
    <div class="row g-4">
        {{-- Sidebar --}}
        <div class="col-lg-3">
            @include('components.customer-sidebar')
        </div>

        {{-- Main Content --}}
        <div class="col-lg-6">
            <h1 style="font-size:1.6rem; font-weight:800; color:#1a2e1a; margin-bottom:0.2rem;">Edit Account</h1>
            <p style="font-size:0.9rem; color:#6b7c6b; margin-bottom:1.75rem;">Update your account information</p>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-3">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('customer.account.update') }}" method="POST" class="cae__form">
                @csrf

                <div class="cae__card">
                    <div class="cae__header">
                        <div class="cae__title">Personal Information</div>
                        <div class="cae__subtitle">Update your personal details below</div>
                    </div>

                    <div class="cae__body">
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $customer->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $customer->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted" style="display: block; margin-top: 0.25rem;">We'll never share your email.</small>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone', $customer->phone) }}"
                                   placeholder="e.g., +91 98765 43210">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="cae__alert">
                            <span class="cae__alert-icon">ℹ️</span>
                            <span class="cae__alert-text">To change your password, please use the password reset feature from the login page.</span>
                        </div>

                        <div class="cae__actions">
                            <a href="{{ route('customer.account') }}" class="cae__btn cae__btn--outline">Cancel</a>
                            <button type="submit" class="cae__btn cae__btn--primary">Save Changes</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
