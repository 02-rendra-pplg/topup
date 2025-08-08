@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h4 class="mb-3">QRIS Payment</h4>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


    @if (!empty($qrisData['qris']))
        <div class="card p-4 shadow">
            <p>ID Transaksi: <strong>{{ $id }}</strong></p>
            <img src="{{ $qrisData['qris'] }}" alt="QR Code" class="img-fluid" style="max-width: 300px;">
            <p class="mt-3">Silakan scan QR Code di atas untuk melakukan pembayaran.</p>
        </div>
    @else
        <div class="alert alert-warning">
            QR Code tidak tersedia.
        </div>
    @endif
</div>
@endsection
