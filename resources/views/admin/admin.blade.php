<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Sidebar */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: linear-gradient(180deg, #1e1e2d 0%, #27293d 100%);
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 60px;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.2);
        }

        /* Links */
        .sidebar .nav-link {
            color: #bfc4d0;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .sidebar .nav-link i {
            font-size: 18px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(52, 152, 219, 0.15);
            color: #fff;
            border-left: 4px solid #3498db;
            text-decoration: none;
            box-shadow: inset 4px 0 0 #3498db;
        }

        /* Content area */
        .content {
            margin-left: 240px;
            padding: 80px 20px 20px;
            min-height: 100vh;
            background: #f8f9fa;
        }

        /* Navbar */
        .navbar {
            background: #0b0b2d;
            height: 60px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark fixed-top d-flex justify-content-between px-3">
        <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" height="30" class="me-2">
            <span class="fw-bold">Javapay</span> </a>
        <div><i class="bi bi-person-circle fs-4 text-white"></i></div>
    </nav>

    <div class="sidebar">
        <ul class="nav flex-column">
            <li><a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i>
                    Dashboard</a></li>
            <li><a href="{{ route('games.index') }}" class="nav-link"><i class="bi bi-controller"></i> Games</a></li>
            <li><a href="{{ route('pembayaran.index') }}" class="nav-link"><i class="bi bi-wallet2"></i> Metode
                    Pembayaran</a></li>
            <li><a href="{{ route('flashsale.index') }}" class="nav-link"><i class="bi bi-lightning-fill"></i> Flash
                    Sale</a></li>
            <li><a href="{{ route('banner.index') }}" class="nav-link"><i class="bi bi-image"></i> Banner</a></li>
        </ul>
    </div>

    <div class="content">
        @yield('content')
    </div>
</body>

</html>
