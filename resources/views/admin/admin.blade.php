<!DOCTYPE html>
<html>
<head>
    <title>instincT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
   <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fs-2 fw-bold " href="{{ route('admin.dashboard') }}">instinc<span style="color:#3498db">T</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
            {{-- <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link fs-5" href="{{ route('products.index') }}">Produk</a></li>
                <li class="nav-item"><a class="nav-link fs-5" href="{{ route('categories.index') }}">Kategori</a></li>
                <li class="nav-item"><a class="nav-link fs-5" href="{{ route('transactions.index') }}">Transaksi</a></li>
                <li class="nav-item"><a class="nav-link fs-5" href="{{ route('transactions.history') }}">Riwayat Transaksi</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fs-5" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Laporan
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="{{ route('report.pendapatan')}}">Laporan Pendapatan</a></li>
                        <li><a class="dropdown-item" href="{{ route('laporan.produk') }}">Laporan Produk</a></li>
                        <li><a class="dropdown-item" href="{{ route('stock_report.index') }}">Laporan Pergerakan Stock</a></li>
                        <li><a class="dropdown-item" href="{{ route('stock_opname.report') }}">Laporan SO</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link fs-5" href="{{ route('stock_opname.index') }}">Stock Opname</a></li>
            </ul> --}}
        </div>
     @auth('admin')
    <form action="{{ route('admin.logout') }}" method="POST" class="d-flex ms-3">
        @csrf
        <button class="btn btn-outline-light btn-sm" type="submit">Logout</button>
    </form>
    @endauth
    </div>
</nav>
    <div class="container mt-4">
        @yield('content')
    </div>
</body>
</html>
