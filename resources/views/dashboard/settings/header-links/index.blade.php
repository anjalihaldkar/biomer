@extends('layout.layout')

@php
    $title    = 'Header Navigation';
    $subTitle = 'Manage navigation menu items';
@endphp

@section('content')

  {{-- Breadcrumb --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h6 class="mb-0">📋 Header Navigation</h6>
      <small class="text-muted">Configure your website navigation menu</small>
    </div>
    <a href="{{ route('dashboard.header-links.create') }}" class="btn btn-primary btn-sm">+ Add Link</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      ✓ {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card">
    <div class="table-responsive">
      <table class="table table-striped table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>Position</th>
            <th>Label</th>
            <th>URL</th>
            <th>Icon</th>
            <th>Target</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($links as $link)
            <tr>
              <td><span class="badge bg-secondary">{{ $link->position }}</span></td>
              <td><strong>{{ $link->label }}</strong></td>
              <td><code style="font-size:0.8rem;">{{ $link->url }}</code></td>
              <td>{{ $link->icon ?? '-' }}</td>
              <td><small>{{ $link->target }}</small></td>
              <td>
                @if($link->is_active)
                  <span class="badge bg-success">Active</span>
                @else
                  <span class="badge bg-secondary">Inactive</span>
                @endif
              </td>
              <td>
                <a href="{{ route('dashboard.header-links.edit', $link) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                <form action="{{ route('dashboard.header-links.destroy', $link) }}" method="POST" style="display:inline;" 
                      onsubmit="return confirm('Delete this link?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-4">No navigation links added yet</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Pagination --}}
  <div class="d-flex justify-content-center mt-4">
    {{ $links->links() }}
  </div>

@endsection
