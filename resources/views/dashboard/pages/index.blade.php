@extends('layout.layout')

@php
    $title    = 'Pages';
    $subTitle = 'Website Pages';
    $script   = '<script>
                    let table = new DataTable("#pagesTable", {
                        responsive: true,
                        scrollX: false,
                        autoWidth: false,
                        columnDefs: [
                            { orderable: false, targets: [0, 5] }
                        ],
                        pageLength: 10,
                        language: {
                            search: "Search pages:",
                            lengthMenu: "Show _MENU_ pages",
                            info: "Showing _START_ to _END_ of _TOTAL_ pages",
                            paginate: {
                                first: "First",
                                last: "Last",
                                next: "Next",
                                previous: "Previous"
                            }
                        }
                    });

                    document.getElementById("checkAll").addEventListener("change", function () {
                        document.querySelectorAll(".row-check").forEach(cb => cb.checked = this.checked);
                    });
                 </script>';
@endphp

@section('content')

<div class="card basic-data-table">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">🌐 Website Pages</h5>
        <a href="{{ route('dashboard.pages.create') }}" class="btn btn-primary btn-sm">
            + Add New Page
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table mb-0" id="pagesTable" data-page-length='10' style="width:100%">
                <thead>
                    <tr>
                        <th scope="col">
                            <div class="form-check style-check d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" id="checkAll">
                                <label class="form-check-label">S.L</label>
                            </div>
                        </th>
                        <th scope="col">Page Title</th>
                        <th scope="col">Slug</th>
                        <th scope="col">Meta Title</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $i => $page)
                    <tr>
                        {{-- Checkbox + Serial --}}
                        <td>
                            <div class="form-check style-check d-flex align-items-center">
                                <input class="form-check-input row-check" type="checkbox">
                                <label class="form-check-label">
                                    {{ str_pad($pages->firstItem() + $i, 2, '0', STR_PAD_LEFT) }}
                                </label>
                            </div>
                        </td>

                        {{-- Page Title --}}
                        <td>
                            <span class="bg-info-focus text-info-main px-12 py-4 rounded-pill fw-medium text-sm">
                                {{ $page->title }}
                            </span>
                        </td>

                        {{-- Slug --}}
                        <td><code class="text-secondary-light text-sm">{{ $page->slug }}</code></td>

                        {{-- Meta Title --}}
                        <td>
                            <span style="font-size: 13px; color: #666;">
                                {{ Str::limit($page->meta_title ?? 'Not set', 50) }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td>
                            <span class="badge {{ $page->status ? 'bg-success-focus text-success-main' : 'bg-danger-focus text-danger-main' }}">
                                {{ $page->status ? '✓ Active' : '✕ Inactive' }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td>
                            {{-- Edit --}}
                            <a href="{{ route('dashboard.pages.edit', $page) }}"
                               class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                               title="Edit Page">
                                <iconify-icon icon="lucide:edit"></iconify-icon>
                            </a>

                            {{-- Delete --}}
                            <form action="{{ route('dashboard.pages.destroy', $page) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this page?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center border-0"
                                        style="cursor: pointer; padding: 0; background: none; color: #e74c3c;"
                                        title="Delete Page">
                                    <iconify-icon icon="lucide:trash-2"></iconify-icon>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <p style="font-size: 16px; margin: 20px 0;">📄 No pages found</p>
                            <a href="{{ route('dashboard.pages.create') }}" class="btn btn-primary btn-sm">Create First Page</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
{!! str_replace("'", '"', $script) !!}
@endpush
