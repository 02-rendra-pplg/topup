<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Top Up Game')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1a1a2e;
            color: #fff;
            font-family: 'Poppins', sans-serif;
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
            background-color: #0f0f1a;
            color: #ccc;
            padding: 40px 20px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark" style="background-color: #0b0b2d;">
    <div class="container-fluid d-flex align-items-center">
        <!-- GROUP: Hamburger + Logo -->
        <div class="d-flex align-items-center gap-2">
            <!-- HAMBURGER BUTTON -->
            <button class="btn text-white p-0 me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12.5a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-11zm0-4a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-11zm0-4a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-11z"/>
                </svg>
            </button>

            <!-- LOGO -->
            <a class="navbar-brand fw-bold m-0" href="/">
                instinc<span style="color: #3498db">T</span>
            </a>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="sidebarLabel">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <li class="nav-item"><a href="/" class="nav-link text-white">🏠 Beranda</a></li>
            <li class="nav-item"><a href="/topup" class="nav-link text-white">💎 Top Up</a></li>
            <li class="nav-item"><a href="/login" class="nav-link text-white">🔐 Login</a></li>
            <li class="nav-item"><a href="/register" class="nav-link text-white">📝 Daftar</a></li>
        </ul>
    </div>
</div>


{{-- ISI HALAMAN --}}
<main>
    @yield('content')
</main>

{{-- FOOTER --}}
<div class="footer mt-5">
    <div class="container text-center">
        <h4 class="fw-bold">instinc<span style="color: #3498db">T</span></h4>
        <p class="text-light mb-1">Top-up game kesukaanmu dengan harga bersahabat dan layanan cepat!</p>
        <p class="text-light mb-3">Didukung berbagai metode pembayaran digital.</p>

        <div class="d-flex justify-content-center gap-3">
            <a href="#" class="btn btn-outline-light btn-sm">Instagram</a>
            <a href="#" class="btn btn-outline-light btn-sm">WhatsApp</a>
        </div>

        <hr class="bg-secondary my-3">
        <small class="text-ligt">© 2025 instincT. Semua Hak Cipta Dilindungi.</small>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
