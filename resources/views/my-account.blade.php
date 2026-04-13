@extends('layout.frontlayout')
@section('title', 'My Account – Bharat Biomer')

@push('styles')
<style>
* { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }

.ma__card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e8f0e4;
    box-shadow: 0 2px 16px rgba(60,120,60,0.06);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.ma__header {
    background: #f4faf0;
    border-bottom: 1px solid #e8f0e4;
    padding: 1.25rem 1.5rem;
}
.ma__title { font-size: 1.1rem; font-weight: 800; color: #1a2e1a; margin-bottom: 0.25rem; }
.ma__subtitle { font-size: 0.85rem; color: #6b7c6b; }

.ma__body { padding: 1.5rem; }
.ma__section { margin-bottom: 2rem; }
.ma__section-title { font-size: 1rem; font-weight: 700; color: #1a2e1a; margin-bottom: 1rem; }

.ma__profile-info { display: grid; gap: 1rem; }
.ma__info-row { display: flex; justify-content: space-between; align-items: center; }
.ma__info-label { font-size: 0.9rem; color: #6b7c6b; }
.ma__info-value { font-size: 0.9rem; font-weight: 600; color: #1a2e1a; }

.ma__form .form-label { font-size: 0.9rem; font-weight: 600; color: #1a2e1a; margin-bottom: 0.5rem; }
.ma__form .form-control {
    border: 1.5px solid #e8f0e4; border-radius: 8px;
    padding: 0.75rem; font-size: 0.9rem;
}
.ma__form .form-control:focus {
    border-color: #2d7a45; box-shadow: 0 0 0 0.2rem rgba(45,122,69,0.1);
}

.ma__actions {
    display: flex; gap: 1rem; justify-content: flex-end;
    padding-top: 1rem; border-top: 1px solid #f0f5ee;
}
.ma__btn {
    padding: 0.75rem 2rem; border-radius: 8px;
    font-size: 0.9rem; font-weight: 600; border: none;
    text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;
    transition: all 0.2s;
}
.ma__btn--primary { background: #2d7a45; color: #fff; }
.ma__btn--primary:hover { background: #245e36; color: #fff; }
.ma__btn--outline { background: transparent; color: #2d7a45; border: 1.5px solid #2d7a45; }
.ma__btn--outline:hover { background: #f0faf4; }

.ma__alert {
    background: #e8f5ed; border: 1px solid #a8d5b5;
    border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;
}
.ma__alert-icon { color: #2d7a45; margin-right: 0.5rem; }
.ma__alert-text { color: #1a2e1a; font-size: 0.9rem; }

@media (max-width: 768px) {
    .ma__info-row { flex-direction: column; align-items: flex-start; gap: 0.25rem; }
    .ma__actions { flex-direction: column; }
    .ma__btn { width: 100%; justify-content: center; }
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
        <div class="col-lg-9">
            <h1 style="font-size:1.6rem; font-weight:800; color:#1a2e1a; margin-bottom:0.2rem;">My Account</h1>
            <p style="font-size:0.9rem; color:#6b7c6b; margin-bottom:1.75rem;">Manage your account settings and preferences</p>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-3">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="ma__card">
                <div class="ma__header">
                    <div class="ma__title">Account Information</div>
                    <div class="ma__subtitle">Your personal details and account status</div>
                </div>

                <div class="ma__body">
                    <div class="ma__section">
                        <h3 class="ma__section-title">Profile Details</h3>
                        <div class="ma__profile-info">
                            <div class="ma__info-row">
                                <span class="ma__info-label">Full Name:</span>
                                <span class="ma__info-value">{{ $customer->name }}</span>
                            </div>
                            <div class="ma__info-row">
                                <span class="ma__info-label">Email Address:</span>
                                <span class="ma__info-value">{{ $customer->email }}</span>
                            </div>
                            <div class="ma__info-row">
                                <span class="ma__info-label">Phone Number:</span>
                                <span class="ma__info-value">{{ $customer->phone ?? 'Not provided' }}</span>
                            </div>
                            <div class="ma__info-row">
                                <span class="ma__info-label">Member Since:</span>
                                <span class="ma__info-value">{{ $customer->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="ma__info-row">
                                <span class="ma__info-label">Account Status:</span>
                                <span class="ma__info-value">
                                    <span style="color: #2d7a45; font-weight: 700;">Active</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="ma__alert">
                        <span class="ma__alert-icon">✓</span>
                        <span class="ma__alert-text">You can now easily update your account information below.</span>
                    </div>

                    <div class="ma__actions">
                        <a href="{{ route('customer.account.edit') }}" class="ma__btn ma__btn--primary">
                            Edit Account
                        </a>
                        <a href="{{ route('customer.dashboard') }}" class="ma__btn ma__btn--outline">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection