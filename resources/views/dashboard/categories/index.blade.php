{{-- resources/views/dashboard/categories/index.blade.php --}}
@extends('layout.layout')

@section('title', 'Categories')

@section('content')
<div class="container-fluid py-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="mb-0 fw-bold">📂 Categories</h2>
      <small class="text-muted">Manage product categories</small>
    </div>
    <a href="{{ route('dashboard.categories.create') }}" class="btn btn-success px-4">+ Add Category</a>
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
            <th>Category Name</th>
            <th>Slug</th>
            <th>Parent</th>
            <th class="text-center">Products</th>
            <th>Created</th>
            <th style="width:150px">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $cat)
          <tr>
            <td class="text-muted small">{{ $cat->id }}</td>
            <td>
              <strong>{{ $cat->name }}</strong>
              @if($cat->parent_id)
                <span class="badge bg-light text-dark border ms-1" style="font-size:.7rem;">sub</span>
              @endif
            </td>
            <td><code class="text-muted small">{{ $cat->slug }}</code></td>
            <td>{{ $cat->parent->name ?? '—' }}</td>
            <td class="text-center"><span class="badge bg-success rounded-pill">{{ $cat->products_count }}</span></td>
            <td class="text-muted small">{{ $cat->created_at->format('d M Y') }}</td>
            <td>
              <a href="{{ route('dashboard.categories.edit', $cat) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
              <form action="{{ route('dashboard.categories.destroy', $cat) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Delete category \'{{ addslashes($cat->name) }}\'?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Delete</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="7" class="text-center py-5 text-muted">No categories yet. <a href="{{ route('dashboard.categories.create') }}">Add one!</a></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="mt-3">{{ $categories->links() }}</div>
</div>
@endsection