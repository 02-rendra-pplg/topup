@extends('layouts.app') 

@section('content')
<div class="container my-5">
    <h3 class="mb-4 fw-bold text-center">Masukkan Kode Verifikasi</h3>

    <p class="text-center">
        Nomor Pesanan: <strong>{{ $order->trx_id }}</strong>
    </p>

    @if($errors->any())
        <div class="alert alert-danger">{{ implode(', ', $errors->all()) }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.orders.verify', $order->trx_id) }}" method="POST" class="mx-auto" style="max-width:400px;">
        @csrf
        <div class="mb-3">
            <label for="verification_code" class="form-label">Kode Verifikasi</label>
            <input type="text" name="verification_code" id="verification_code" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Kirim Kode</button>
    </form>
</div>
@endsection
