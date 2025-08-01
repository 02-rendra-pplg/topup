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

{{-- NAVBAR --}}
<nav class="navbar navbar-dark px-4" style="background-color: #0b0b2d;">
    <a class="navbar-brand fw-bold" href="/">instinc<span style="color: #3498db">T</span></a>
</nav>

{{-- ISI HALAMAN --}}
<main>
    @yield('content')
</main>

{{-- FOOTER --}}
<div class="footer mt-5">
    <div class="container text-center">
        <h4 class="fw-bold">instinc<span style="color: #3498db">T</span></h4>
        <p class="text-muted mb-1">Top-up game kesukaanmu dengan harga bersahabat dan layanan cepat!</p>
        <p class="text-muted mb-3">Didukung berbagai metode pembayaran digital.</p>

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
