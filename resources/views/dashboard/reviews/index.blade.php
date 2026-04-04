@extends('layout.layout')
@section('title', 'Product Reviews')

@section('content')
<div class="dashboard-main-body">

    {{-- ── Page Header ─────────────────────────────────────────────── --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Product Reviews</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Reviews</li>
        </ul>
    </div>

    {{-- ── Alerts ──────────────────────────────────────────────────── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <iconify-icon icon="ep:success-filled" class="me-2"></iconify-icon>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Stats Cards ─────────────────────────────────────────────── --}}
    <div class="row g-3 mb-24">
        @foreach([
            ['label'=>'Total','value'=>$counts['all'],   'color'=>'primary',  'icon'=>'ri-star-line'],
            ['label'=>'Pending','value'=>$counts['pending'], 'color'=>'warning', 'icon'=>'ri-time-line'],
            ['label'=>'Approved','value'=>$counts['approved'],'color'=>'success','icon'=>'ri-checkbox-circle-line'],
            ['label'=>'Rejected','value'=>$counts['rejected'],'color'=>'danger', 'icon'=>'ri-close-circle-line'],
        ] as $stat)
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="w-48-px h-48-px d-flex justify-content-center align-items-center rounded-circle bg-{{ $stat['color'] }}-focus text-{{ $stat['color'] }}-main fs-24">
                        <i class="{{ $stat['icon'] }}"></i>
                    </div>
                    <div>
                        <p class="fw-medium text-secondary-light mb-1">{{ $stat['label'] }}</p>
                        <h6 class="fw-semibold mb-0">{{ $stat['value'] }}</h6>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Filter Tabs ──────────────────────────────────────────────── --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header border-bottom bg-base d-flex flex-wrap gap-2 align-items-center">
            <ul class="nav nav-pills gap-2 mb-0">
                @foreach(['all'=>'All','pending'=>'Pending','approved'=>'Approved','rejected'=>'Rejected'] as $key=>$label)
                <li class="nav-item">
                    <a class="nav-link {{ $status === $key ? 'active' : '' }}"
                       href="{{ route('dashboard.reviews.index', ['status' => $key]) }}">
                        {{ $label }}
                        <span class="badge {{ $status === $key ? 'bg-white text-primary-600' : 'bg-neutral-200 text-secondary-light' }} ms-1">
                            {{ $counts[$key] }}
                        </span>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Rating</th>
                            <th>Review</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-center pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr>
                            <td class="ps-3 text-secondary-light">{{ $review->id }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-medium">{{ $review->customer->name ?? '—' }}</span>
                                    <small class="text-secondary-light">{{ $review->customer->email ?? '' }}</small>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('products.show', $review->product->slug ?? '#') }}"
                                   class="text-primary-600 fw-medium text-decoration-none" target="_blank">
                                    {{ Str::limit($review->product->name ?? '—', 30) }}
                                </a>
                            </td>
                            <td>
                                @php $r = $review->rating; @endphp
                                <div class="d-flex align-items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="ri-star-{{ $i <= $r ? 'fill' : 'line' }} text-warning-main fs-14"></i>
                                    @endfor
                                    <span class="text-secondary-light ms-1 fs-12">({{ $r }}/5)</span>
                                </div>
                            </td>
                            <td style="max-width:220px;">
                                {{ $review->review_text ? Str::limit($review->review_text, 80) : '—' }}
                            </td>
                            <td>
                                @if($review->status === 'approved')
                                    <span class="badge bg-success-focus text-success-main">Approved</span>
                                @elseif($review->status === 'rejected')
                                    <span class="badge bg-danger-focus text-danger-main">Rejected</span>
                                @else
                                    <span class="badge bg-warning-focus text-warning-main">Pending</span>
                                @endif
                            </td>
                            <td class="text-secondary-light">
                                {{ $review->created_at->format('d M Y') }}
                            </td>
                            <td class="pe-3">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Approve --}}
                                    @if($review->status !== 'approved')
                                    <form action="{{ route('dashboard.reviews.approve', $review) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success-focus text-success-main px-10 py-4"
                                                title="Approve">
                                            <i class="ri-checkbox-circle-line"></i>
                                        </button>
                                    </form>
                                    @endif

                                    {{-- Reject --}}
                                    @if($review->status !== 'rejected')
                                    <form action="{{ route('dashboard.reviews.reject', $review) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning-focus text-warning-main px-10 py-4"
                                                title="Reject">
                                            <i class="ri-close-circle-line"></i>
                                        </button>
                                    </form>
                                    @endif

                                    {{-- Delete --}}
                                    <form action="{{ route('dashboard.reviews.destroy', $review) }}" method="POST"
                                          onsubmit="return confirm('Delete this review?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger-focus text-danger-main px-10 py-4"
                                                title="Delete">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-secondary-light">
                                <iconify-icon icon="ri:star-off-line" class="fs-40 mb-2 d-block"></iconify-icon>
                                No reviews found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($reviews->hasPages())
            <div class="px-16 py-12 border-top">
                {{ $reviews->links() }}
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
