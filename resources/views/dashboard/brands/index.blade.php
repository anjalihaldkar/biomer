
@extends('layout.layout')

@section('title', 'Brands')

@section('content')
<div class="container-fluid py-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="mb-0 fw-bold">🏷️ Brands</h2>
      <small class="text-muted">Manage all product brands</small>
    </div>
    <a href="{{ route('dashboard.brands.create') }}" class="btn btn-success px-4">+ Add Brand</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">✅ {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:60px">#</th>
            <th style="width:80px">Logo</th>
            <th>Name</th>
            <th>Slug</th>
            <th class="text-center">Products</th>
            <th>Created</th>
            <th style="width:150px">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($brands as $brand)
          <tr>
            <td class="text-muted small">{{ $brand->id }}</td>
            <td>
              @if($brand->logo)
                <img src="{{ Storage::url($brand->logo) }}" style="width:48px;height:48px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;">
              @else
                <div style="width:48px;height:48px;border-radius:6px;background:#e8f5ed;display:flex;align-items:center;justify-content:center;font-size:1.4rem;">🏷️</div>
              @endif
            </td>
            <td><strong>{{ $brand->name }}</strong></td>
            <td><code class="text-muted small">{{ $brand->slug }}</code></td>
            <td class="text-center"><span class="badge bg-primary rounded-pill">{{ $brand->products_count }}</span></td>
            <td class="text-muted small">{{ $brand->created_at->format('d M Y') }}</td>
            <td>
              <a href="{{ route('dashboard.brands.edit', $brand) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
              <form action="{{ route('dashboard.brands.destroy', $brand) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Delete brand \'{{ addslashes($brand->name) }}\'?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Delete</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="7" class="text-center py-5 text-muted">No brands yet. <a href="{{ route('dashboard.brands.create') }}">Add the first one!</a></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="mt-3">{{ $brands->links() }}</div>
</div>
@endsection