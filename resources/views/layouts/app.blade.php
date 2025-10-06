<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Top Up Game')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #1a1a2e;
            color: #e0e0e0;
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
        }

        .navbar {
            height: 60px;
            background-color: #0b0b2d;
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
        }

        .offcanvas-body .nav-link {
            font-size: 1.1rem;
            font-weight: 400;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.2s;
        }
        .offcanvas-body .nav-link:hover {
            background-color: #3498db;
            color: #fff;
        }

        .game-card img {
            border-radius: 12px;
        }
        .game-card:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }

        .promo-box {
            background: #3498db;
            color: white;
            border-radius: 10px;
            padding: 10px;
            font-weight: 400;
        }

        .footer {
            background-color: #0b0b2d;
            padding: 50px 20px;
            font-size: 14px;
        }
        .footer h4, .footer h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            color: #ffffff;
            margin-bottom: 15px;
        }
        .footer p, .footer a {
            color: #bdbdbd;
            font-weight: 300;
            font-size: 14px;
            text-decoration: none;
        }
        /* .footer a:hover {
            color: #3498db;
        } */

        .section-title {
            font-family: 'Poppins', sans-serif;
            font-size: 1.6rem;
            font-weight: 500;
            color: #ffffff;
            margin-bottom: 20px;
            border-left: 4px solid #3498db;
            padding-left: 10px;
        }

        .btn-outline-light {
            border-radius: 30px;
            padding: 5px 15px;
            font-weight: 400;
            transition: 0.3s;
        }
        /* .btn-outline-light:hover {
            background-color: #3498db;
            color: #fff;
            border-color: #3498db;
        } */
    </style>
</head>
<body>

<nav class="navbar navbar-dark">
    <div class="container-fluid d-flex align-items-center">
        <div class="d-flex align-items-center gap-2">

            <button class="btn text-white p-0 me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
                <i class="bi bi-list fs-4"></i>
            </button>

           <a class="navbar-brand d-flex align-items-center m-0" href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="30" class="me-2">
                <span class="fw-bold">Javapay</span>
            </a>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarLabel">
    <div class="offcanvas-header">
         <a class="navbar-brand d-flex align-items-center m-0" href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="30" class="me-2">
                <span class="fw-bold">Javapay</span>
            </a>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="/" class="nav-link text-white d-flex align-items-center gap-2">
                    <i class="bi bi-house-door-fill"></i> Beranda
                </a>
            </li>
            <li class="nav-item">
                <a href="/topup" class="nav-link text-white d-flex align-items-center gap-2">
                    <i class="bi bi-controller"></i> Semua Game
                </a>
            </li>
            {{-- <li class="nav-item">
                <a href="#" class="nav-link text-white d-flex align-items-center gap-2">
                    <i class="bi bi-search"></i> Riwayat Transaksi
                </a>
            </li> --}}
        </ul>
    </div>
</div>

<main>
    <div class="container">
        @yield('content')
    </div>
</main>

<div class="footer mt-5 text-white">
    <div class="container">
        <div class="row gy-4">
            <div class="col-md-4">
            <a class="navbar-brand d-flex align-items-center m-0" href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="30" class="me-2">
                <span class="fw-bold">Javapay</span>
            </a>
             <p>Top-Up Game Favorit Kamu Di instincT Agar Main Game Semakin Seru.<br>Pengiriman Cepat Dan Berbagai Methode Pembayaran Yang Lengkap.</p>
            </div>
            <div class="col-md-2">
                <h6>PETA SITUS</h6>
                <ul class="list-unstyled">
                    <li><a href="/" class="d-block my-1">Beranda</a></li>
                    <li><a href="/topup" class="d-block my-1">Semua Game</a></li>
                </ul>
            </div>
            <div class="col-md-2">
                <h6>TOP UP LAINNYA</h6>
                <ul class="list-unstyled">
                    <li><a href="#" class="d-block my-1">Mobile Legends</a></li>
                    <li><a href="#" class="d-block my-1">Free Fire</a></li>
                    <li><a href="#" class="d-block my-1">Pubg Mobile</a></li>
                    <li><a href="#" class="d-block my-1">Undawn</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6>IKUTI KAMI</h6>
                <div class="d-flex gap-2 mb-3">
                    <a href="#" class="btn btn-outline-light rounded-circle p-2" style="width: 40px; height: 40px;"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="btn btn-outline-light rounded-circle p-2" style="width: 40px; height: 40px;"><i class="bi bi-whatsapp"></i></a>
                </div>
                <h6 class="mt-3">BANTUAN PELANGGAN</h6>
                <a href="#" class="btn btn-outline-light btn-sm d-inline-flex align-items-center px-3">
                    <i class="bi bi-headset me-2"></i> Hubungi Kami
                </a>
            </div>
        </div>
        <hr class="mt-5" style="border-color: #444;">
        <div class="d-flex flex-wrap justify-content-between text-white-50 small">
            <div>© 2025 JavaPay. Semua Hak Cipta</div>
            <div><a href="#" class="text-decoration-none" style="color: #0099ff;">Syarat & Ketentuan Pengguna</a></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
