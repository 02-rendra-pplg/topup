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

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Grafik Transaksi Bulanan</h5>
                    <canvas id="transaksiChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Distribusi Games</h5>
                    <canvas id="gameChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const months = {!! json_encode($months ?? ['Jan','Feb','Mar','Apr','Mei','Jun'], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) !!};
    const transactions = {!! json_encode($transactions ?? [12, 19, 3, 5, 2, 3], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) !!};

    const transaksiData = {
        labels: months,
        datasets: [{
            label: 'Jumlah Transaksi',
            data: transactions,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2,
            tension: 0.4,
            fill: true
        }]
    };

    const transaksiConfig = {
        type: 'line',
        data: transaksiData,
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            }
        }
    };

    new Chart(document.getElementById('transaksiChart'), transaksiConfig);

    const gameNames = {!! json_encode($game_names ?? ["ML","FF","PUBG","Genshin"], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) !!};
    const gameCounts = {!! json_encode($game_counts ?? [120,90,70,50], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) !!};

    const gameData = {
        labels: gameNames,
        datasets: [{
            label: 'Jumlah User',
            data: gameCounts,
            backgroundColor: [
                '#3498db',
                '#e74c3c',
                '#2ecc71',
                '#f1c40f'
            ]
        }]
    };

    const gameConfig = {
        type: 'doughnut',
        data: gameData,
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    };

    new Chart(document.getElementById('gameChart'), gameConfig);
</script>
@endpush
