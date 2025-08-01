@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="card shadow p-4">
        <h4 class="mb-3">Top-up {{ $namaGame }}</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('topup.store') }}">
            @csrf
            <input type="hidden" name="game" value="{{ $namaGame }}">
            <input type="hidden" name="harga" id="hargaInput">

            {{-- Input ID dan Server ID --}}
            @if ($type === '2id')
                <div class="mb-3">
                    <label for="user_id">User ID</label>
                    <input type="text" name="user_id" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="server_id">Server ID</label>
                    <input type="text" name="server_id" class="form-control" required>
                </div>
            @else
                <div class="mb-3">
                    <label for="user_id">User ID</label>
                    <input type="text" name="user_id" class="form-control" required>
                </div>
            @endif

            {{-- Pilih Paket Harga --}}
            <div class="mb-3">
                <label>Pilih Paket</label><br>
                @foreach ($list as $val)
                    @php
                        $harga_bersih = (int) preg_replace('/\D/', '', $val['hrg']);
                    @endphp
                    <button 
                        type="button" 
                        class="btn btn-outline-success mb-2 pilih-nominal" 
                        data-nominal="{{ $val['nama'] }}" 
                        data-harga="{{ $harga_bersih }}">
                        {{ $val['nama'] }} - Rp {{ number_format($harga_bersih, 0, ',', '.') }}
                    </button>
                @endforeach
            </div>

            <div class="mb-3">
                <label for="nominalInput">Nominal</label>
                <input type="text" name="nominal" id="nominalInput" class="form-control" readonly required>
            </div>

            <div class="mb-3">
                <label for="whatsapp">No. WhatsApp</label>
                <input type="text" name="whatsapp" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Kirim</button>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('.pilih-nominal').forEach(button => {
        button.addEventListener('click', function () {
            const nominal = this.getAttribute('data-nominal');
            const harga = this.getAttribute('data-harga');

            document.getElementById('nominalInput').value = nominal;
            document.getElementById('hargaInput').value = harga;

            // Ubah tampilan tombol aktif
            document.querySelectorAll('.pilih-nominal').forEach(btn => 
                btn.classList.remove('btn-success')
            );
            this.classList.add('btn-success');
        });
    });
</script>
@endsection
