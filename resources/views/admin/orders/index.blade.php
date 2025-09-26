    @extends('admin.admin')

    @section('content')
    <div class="container mt-4">
        <h3 class="fw-bold mb-4">📋 Daftar Pesanan</h3>

        {{-- Pesan sukses --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Jika ada error --}}
        @if($errors->any())
            <div class="alert alert-danger">{{ implode(', ', $errors->all()) }}</div>
        @endif

        <div class="card shadow">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Trx ID</th>
                            <th>Game</th>
                            <th>User ID</th>
                            <th>Server ID</th>
                            <th>Nominal</th>
                            <th>Total Bayar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $index => $order)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $order->trx_id }}</td>
                                <td>{{ $order->game_name }}</td>
                                <td>{{ $order->user_id }}</td>
                                <td>{{ $order->server_id ?? '-' }}</td>
                                <td>{{ $order->nominal ?? '-' }}</td>
                                <td>Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($order->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($order->status == 'success')
                                        <span class="badge bg-success">Success</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($order->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($order->status == 'pending')
                                <form action="{{ route('admin.orders.verify', ['trx_id' => $order->trx_id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Verifikasi</button>
                                </form> 
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Verifikasi</button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled>Sudah Diverifikasi</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Belum ada pesanan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endsection
