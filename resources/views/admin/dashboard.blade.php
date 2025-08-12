@extends('admin.admin')

@section('content')

<div class="container text-center mt-5">
    <h3 class="mb-4 fw-bold">instinc<span style="color:#3498db">T</span></h3>
    <h2>Selamat Datang, {{ auth('admin')->user()->name }}</h2>
    <div class="row mt-5">
        <div class="col-md-4">
            <a href="{{ route('games.index') }}" class="btn btn-primary btn-lg w-100 mb-3">Setting Games</a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('pembayaran.index') }}" class="btn btn-success btn-lg w-100 mb-3">Metode Pembayaran</a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('flashsale.index') }}" class="btn btn-success btn-lg w-100 mb-3">FlashSale</a>
        </div>
    </div>
</div>
@endsection
