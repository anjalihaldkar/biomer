@extends('layout.layout')

@php
    $title    = 'Site Settings';
    $subTitle = 'Manage your website settings';
@endphp

@section('content')

  {{-- Breadcrumb --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h6 class="mb-0">⚙️ Site Settings</h6>
      <small class="text-muted">Configure your website information</small>
    </div>
  </div>

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      ✓ {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <form action="{{ route('dashboard.site-settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">

      {{-- ── LEFT COLUMN ─────────────────────────────────────────────── --}}
      <div class="col-lg-8">

        {{-- Basic Information --}}
        <div class="card basic-data-table mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0">📌 Basic Information</h5>
          </div>
          <div class="card-body">
            <div class="row g-3">

              <div class="col-12">
                <label class="form-label fw-semibold">Site Name</label>
                <input type="text" name="site_name" class="form-control @error('site_name') is-invalid @enderror"
                       value="{{ old('site_name', $settings->site_name ?? 'Bharat Biomer') }}">
                @error('site_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">Tagline</label>
                <textarea name="tagline" rows="2" class="form-control @error('tagline') is-invalid @enderror"
                          placeholder="Your website tagline">{{ old('tagline', $settings->tagline ?? 'Advanced biological solutions for sustainable farming.') }}</textarea>
                @error('tagline')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">About</label>
                <textarea name="about" rows="4" class="form-control @error('about') is-invalid @enderror"
                          placeholder="About your company...">{{ old('about', $settings->about ?? '') }}</textarea>
                @error('about')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

            </div>
          </div>
        </div>

        {{-- Contact Information --}}
        <div class="card basic-data-table mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0">📞 Contact Information</h5>
          </div>
          <div class="card-body">
            <div class="row g-3">

              <div class="col-12 col-md-6">
                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $settings->email ?? 'admin@bharatbiomer.com') }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                       value="{{ old('phone', $settings->phone ?? '+91 7828333334') }}" required>
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">Address</label>
                <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror"
                          placeholder="Your business address">{{ old('address', $settings->address ?? '') }}</textarea>
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

            </div>
          </div>
        </div>

        {{-- Social Media Links --}}
        <div class="card basic-data-table mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0">🌐 Social Media Links</h5>
          </div>
          <div class="card-body">
            <div class="row g-3">

              <div class="col-12">
                <label class="form-label fw-semibold">Facebook URL</label>
                <input type="url" name="facebook_url" class="form-control @error('facebook_url') is-invalid @enderror"
                       value="{{ old('facebook_url', $settings->facebook_url ?? '') }}" placeholder="https://facebook.com/yourpage">
                @error('facebook_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">Twitter URL</label>
                <input type="url" name="twitter_url" class="form-control @error('twitter_url') is-invalid @enderror"
                       value="{{ old('twitter_url', $settings->twitter_url ?? '') }}" placeholder="https://twitter.com/yourprofile">
                @error('twitter_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">Instagram URL</label>
                <input type="url" name="instagram_url" class="form-control @error('instagram_url') is-invalid @enderror"
                       value="{{ old('instagram_url', $settings->instagram_url ?? '') }}" placeholder="https://instagram.com/yourprofile">
                @error('instagram_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">LinkedIn URL</label>
                <input type="url" name="linkedin_url" class="form-control @error('linkedin_url') is-invalid @enderror"
                       value="{{ old('linkedin_url', $settings->linkedin_url ?? '') }}" placeholder="https://linkedin.com/company/yourcompany">
                @error('linkedin_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

            </div>
          </div>
        </div>

      </div>

      {{-- ── RIGHT COLUMN ────────────────────────────────────────────── --}}
      <div class="col-lg-4">

        {{-- Logos --}}
        <div class="card basic-data-table mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0">🖼️ Logos</h5>
          </div>
          <div class="card-body">

            <div class="mb-3">
              <label class="form-label fw-semibold">Header Logo</label>
              @if($settings->logo_path)
                <div class="mb-2">
                  <img src="{{ asset('storage/'.$settings->logo_path) }}" alt="Logo" style="max-width:100%; max-height:60px;">
                </div>
              @endif
              <input type="file" name="logo_path" class="form-control" accept="image/*">
              <small class="text-muted d-block mt-1">PNG, JPG recommended. Max 2MB</small>
            </div>

            <div>
              <label class="form-label fw-semibold">Footer Logo</label>
              @if($settings->footer_logo_path)
                <div class="mb-2">
                  <img src="{{ asset('storage/'.$settings->footer_logo_path) }}" alt="Footer Logo" style="max-width:100%; max-height:60px;">
                </div>
              @endif
              <input type="file" name="footer_logo_path" class="form-control" accept="image/*">
              <small class="text-muted d-block mt-1">PNG, JPG recommended. Max 2MB</small>
            </div>

          </div>
        </div>

        {{-- Footer Text --}}
        <div class="card basic-data-table mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0">📝 Footer Text</h5>
          </div>
          <div class="card-body">
            <textarea name="footer_text" rows="4" class="form-control @error('footer_text') is-invalid @enderror"
                      placeholder="Copyright and other footer information">{{ old('footer_text', $settings->footer_text ?? '© '.date('Y').' Bharat Biomer. All rights reserved.') }}</textarea>
            @error('footer_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>

      </div>

    </div>

    {{-- Submit Button --}}
    <div class="d-flex gap-2 justify-content-between mt-4">
      <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">← Back</a>
      <button type="submit" class="btn btn-primary">
        <iconify-icon icon="lucide:save"></iconify-icon> Save Settings
      </button>
    </div>

  </form>

@endsection
