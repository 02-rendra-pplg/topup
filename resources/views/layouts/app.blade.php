<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Top Up Game')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #1a1a2e;
            color: #fff;
            font-family: 'Poppins', sans-serif;
        }

        .navbar {
            height: 60px;
            background-color: #0b0b2d;
        }
        .navbar-brand {
            font-size: 1.6rem;
        }

        .offcanvas-body .nav-link {
            font-size: 1.4rem;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.2s;
        }
        .offcanvas-body .nav-link:hover {
            background-color: #00b0e6;
            color: #00bfff;
        }
        .offcanvas-header a {
            font-size: 2.2rem;
        }

        .game-card img {
            border-radius: 15px;
        }
        .game-card:hover {
            transform: scale(1.05);
        }
        .promo-box {
            background: #3498db;
            color: white;
            border-radius: 10px;
            padding: 10px;
        }
        .footer {
            background-color: #0b0b2d;
            padding: 40px 20px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark">
    <div class="container-fluid d-flex align-items-center">
        <div class="d-flex align-items-center gap-2">
            <button class="btn text-white p-0 me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
                <i class="bi bi-list fs-4"></i>
            </button>
            <a class="navbar-brand fw-bold m-0" href="/">
                instinc<span style="color: #3498db">T</span>
            </a>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarLabel">
    <div class="offcanvas-header">
        <a href="/" class="navbar-brand fw-bold text-white m-0">
            instinc<span style="color: #3498db">T</span>
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
            <li class="nav-item">
                <a href="/login" class="nav-link text-white d-flex align-items-center gap-2">
                    <i class="bi bi-search"></i> Lacak Pesanan
                </a>
            </li>
        </ul>
    </div>
</div>

<main>
    @yield('content')
</main>

<div class="footer mt-5 text-white" style= "padding-top: 50px;">
    <div class="container">
        <div class="row gy-4">
            <div class="col-md-4">
                <h4 class="fw-bold mb-3">instinc<span style="color: #3498db">T</span></h4>
                <p style="color: #ccc;">
                    Top-Up Game Favorit Kamu Di instincT Agar Main Game Semakin Seru.<br>
                    Pengiriman Cepat Dan Berbagai Methode Pembayaran Yang Lengkap.
                </p>
            </div>

            <div class="col-md-2">
                <h6 class="fw-bold text-white">PETA SITUS</h6>
                <ul class="list-unstyled">
                    <li><a href="/" class="text-white text-decoration-none d-block my-1">Beranda</a></li>
                    <li><a href="/topup" class="text-white text-decoration-none d-block my-1">Semua Game</a></li>
                </ul>
            </div>

            <div class="col-md-2">
                <h6 class="fw-bold text-white">TOP UP LAINNYA</h6>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white text-decoration-none d-block my-1">Mobile Legends</a></li>
                    <li><a href="#" class="text-white text-decoration-none d-block my-1">Free Fire</a></li>
                    <li><a href="#" class="text-white text-decoration-none d-block my-1">Pubg Mobile</a></li>
                    <li><a href="#" class="text-white text-decoration-none d-block my-1">Undawn</a></li>
                </ul>
            </div>

            <div class="col-md-4">
                <h6 class="fw-bold text-white">IKUTI KAMI</h6>
                <div class="d-flex gap-2 mb-3">
                    <a href="#" class="btn btn-outline-light rounded-circle p-2" style="width: 40px; height: 40px;"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="btn btn-outline-light rounded-circle p-2" style="width: 40px; height: 40px;"><i class="bi bi-whatsapp"></i></a>
                </div>

                <h6 class="fw-bold text-white mt-3">BANTUAN PELANGGAN</h6>
                <a href="#" class="btn btn-outline-light btn-sm d-inline-flex align-items-center px-3">
                    <i class="bi bi-headset me-2"></i> Hubungi Kami
                </a>
            </div>
        </div>

        <hr class="mt-5" style="border-color: #444;">

        <div class="d-flex flex-wrap justify-content-between text-white-50 small">
            <div>© 2025 instincT. Semua Hak Cipta</div>
            <div><a href="#" class="text-decoration-none" style="color: #0099ff;">Syarat & Ketentuan Pengguna</a></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
