<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .sidebar {
            width: 240px; min-height: 100vh;
            background: #1e1e2d; color: #fff;
            position: fixed; top: 0; left: 0; padding-top: 60px;
        }
        .sidebar .nav-link {
            color: #ccc; padding: 12px 20px;
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #3498db; color: #fff;
        }
        .content {
            margin-left: 240px;
            padding: 80px 20px 20px;
            min-height: 100vh;
            background: #f8f9fa;
        }
        .navbar { background: #0b0b2d; height: 60px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark fixed-top d-flex justify-content-between px-3">
        <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('storage/logo.png') }}" alt="Logo" height="30" class="me-2">
                <span class="fw-bold">Javapay</span>        </a>
        <div><i class="bi bi-person-circle fs-4 text-white"></i></div>
    </nav>

    <div class="sidebar">
        <ul class="nav flex-column">
            <li><a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li><a href="{{ route('games.index') }}" class="nav-link"><i class="bi bi-controller"></i> Games</a></li>
            <li><a href="{{ route('pembayaran.index') }}" class="nav-link"><i class="bi bi-wallet2"></i> Metode Pembayaran</a></li>
            <li><a href="{{ route('flashsale.index') }}" class="nav-link"><i class="bi bi-lightning-fill"></i> Flash Sale</a></li>
            <li><a href="{{ route('banner.index') }}" class="nav-link"><i class="bi bi-image"></i> Banner</a></li>
            {{-- <li><a href="{{ route('logout') }}" class="nav-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a></li> --}}
        </ul>
    </div>

    <div class="content">
        @yield('content')
    </div>
</body>
</html>
