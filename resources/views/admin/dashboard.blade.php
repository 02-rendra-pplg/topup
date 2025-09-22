@extends('admin.admin')
@section('title', 'Dashboard')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 fw-bold">Dashboard</h3>

    {{-- Statistik --}}
    <div class="row text-white mb-4">
        <div class="col-md-3">
            <div class="card bg-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Games</h5>
                    <h2>{{ $games_count ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total User</h5>
                    <h2>{{ $users_count ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Transaksi</h5>
                    <h2>{{ $transactions_count ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Card Orders/Verifikasi (model seperti sidebar) --}}
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-info shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                    <h5 class="card-title mb-2">Orders / Verifikasi</h5>
                    <a href="{{ route('admin.orders.index') }}" class="stretched-link text-white"></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush
