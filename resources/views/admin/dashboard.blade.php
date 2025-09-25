@extends('admin.admin')
@section('title', 'Dashboard')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 fw-bold">Dashboard</h3>

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
            <div class="card bg-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Metode Pembayaran</h5>
                    <h2>{{ $payments_count ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-danger shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Banner</h5>
                    <h2>{{ $banners_count ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const paymentMethods = {!! json_encode($payment_methods ?? ["Dana","OVO","Gopay","ShopeePay"], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) !!};
    const paymentCounts = {!! json_encode($payment_counts ?? [1,1,1,1], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) !!};

    const paymentData = {
        labels: paymentMethods,
        datasets: [{
            label: 'Jumlah Metode',
            data: paymentCounts,
            backgroundColor: [
                '#3498db',
                '#2ecc71',
                '#e74c3c',
                '#f1c40f',
                '#9b59b6',
                '#1abc9c'
            ]
        }]
    };

    const paymentConfig = {
        type: 'doughnut',
        data: paymentData,
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    };

    new Chart(document.getElementById('paymentChart'), paymentConfig);
</script>
@endpush
