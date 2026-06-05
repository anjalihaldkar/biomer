@extends('layout.layout')

@php
    $title = 'Home Page';
    $subTitle = 'Manage homepage sections';
@endphp

@section('content')

<div class="admin-shell">
    <div class="admin-page-card">
        <div class="admin-page-card__header">
            <div>
                <span class="admin-page-card__eyebrow">Website Setup</span>
                <h2 class="admin-page-card__title">Home Page</h2>
                <p class="admin-page-card__desc">Edit the homepage sections one by one. This step controls the farming challenges section.</p>
            </div>
            <div class="admin-page-card__actions">
                <a href="{{ route('dashboard.site-settings.edit') }}" class="btn btn-outline-secondary">Site Settings</a>
            </div>
        </div>

        <div class="admin-toolbar-tabs">
            <a href="{{ route('dashboard.home-page.edit') }}" class="admin-toolbar-tabs__link active">Home Page</a>
            <a href="{{ route('dashboard.site-settings.edit') }}" class="admin-toolbar-tabs__link">Site Settings</a>
            <a href="{{ route('dashboard.google-analytics.edit') }}" class="admin-toolbar-tabs__link">Google Analytics</a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mt-4">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-4">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('dashboard.home-page.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="admin-section-card mt-4">
                <div class="admin-section-card__header">
                    <div>
                        <h5 class="card-title mb-0">Farming Challenges Section</h5>
                        <small class="text-muted">This is the card row below the hero area.</small>
                    </div>
                </div>
                <div class="admin-section-card__body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-6">
                            <label class="form-label fw-semibold">Main Heading</label>
                            <input type="text" name="problem_heading" class="form-control @error('problem_heading') is-invalid @enderror"
                                value="{{ old('problem_heading', $homePageConfig['problem_heading']) }}"
                                placeholder="Farming today needs more than fertilizers">
                            @error('problem_heading')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-lg-6">
                            <label class="form-label fw-semibold">Paragraph</label>
                            <textarea name="problem_paragraph" rows="2" class="form-control @error('problem_paragraph') is-invalid @enderror"
                                placeholder="Modern farmers face multiple crop and soil challenges every season.">{{ old('problem_paragraph', $homePageConfig['problem_paragraph']) }}</textarea>
                            @error('problem_paragraph')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-section-card mt-4">
                <div class="admin-section-card__header">
                    <div>
                        <h5 class="card-title mb-0">Challenge Cards</h5>
                        <small class="text-muted">Each card has an image, sub heading, and sub paragraph.</small>
                    </div>
                </div>
                <div class="admin-section-card__body">
                    <div class="row g-4">
                        @foreach($homePageConfig['problem_items'] as $index => $item)
                            <div class="col-12 col-xl-6">
                                <div class="admin-nested-card h-100">
                                    <h6 class="mb-3">Card {{ $index + 1 }}</h6>
                                    <input type="hidden" name="problem_items[{{ $index }}][image_path]"
                                        value="{{ old("problem_items.$index.image_path", $item['image_path']) }}">

                                    <div class="row g-3">
                                        <div class="col-12 col-md-4">
                                            <label class="form-label fw-semibold">Image</label>
                                            <div class="admin-logo-preview mb-2">
                                                <img src="{{ \App\Models\HomePageSetting::imageUrl(old("problem_items.$index.image_path", $item['image_path'])) }}"
                                                    alt="Card {{ $index + 1 }} image">
                                            </div>
                                            <input type="file" name="problem_item_images[{{ $index }}]"
                                                class="form-control @error('problem_item_images.' . $index) is-invalid @enderror"
                                                accept="image/*">
                                            <small class="text-muted d-block mt-1">PNG, JPG, or WEBP. Max 2MB.</small>
                                            @error('problem_item_images.' . $index)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-12 col-md-8">
                                            <label class="form-label fw-semibold">Sub Heading</label>
                                            <input type="text" name="problem_items[{{ $index }}][heading]"
                                                class="form-control mb-3 @error('problem_items.' . $index . '.heading') is-invalid @enderror"
                                                value="{{ old("problem_items.$index.heading", $item['heading']) }}"
                                                placeholder="Soil degradation">
                                            @error('problem_items.' . $index . '.heading')<div class="invalid-feedback">{{ $message }}</div>@enderror

                                            <label class="form-label fw-semibold">Sub Paragraph</label>
                                            <textarea name="problem_items[{{ $index }}][paragraph]" rows="3"
                                                class="form-control @error('problem_items.' . $index . '.paragraph') is-invalid @enderror"
                                                placeholder="Continuous farming reduces soil organic matter and health.">{{ old("problem_items.$index.paragraph", $item['paragraph']) }}</textarea>
                                            @error('problem_items.' . $index . '.paragraph')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-between mt-4 flex-wrap">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Save Home Page</button>
            </div>
        </form>
    </div>
</div>

@endsection
