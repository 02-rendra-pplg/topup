<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #1a1a2e;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #222845;
            border: none;
            border-radius: 12px;
            padding: 35px 28px;
            box-shadow: 0px 4px 18px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 15px;
        }

        .brand img {
            height: 45px;
        }

        .brand span {
            font-size: 1.3rem;
            font-weight: 600;
            color: #f1f1f1;
        }

        .login-card h3 {
            font-weight: 500;
            margin-bottom: 25px;
            color: #f1f1f1;
        }

        .form-control {
            background: #2e3657;
            border: 1px solid #3d4770;
            color: #fff;
        }

        .form-control::placeholder {
            color: #aaa;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 6px rgba(52, 152, 219, 0.4);
        }

        .btn-primary {
            background: #3498db;
            border: none;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        small.text-danger {
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="brand">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
        <span>Javapay</span>
    </div>

    <h3>Login Admin</h3>

    <form action="{{ route('admin.login.post') }}" method="POST">
        @csrf
        <div class="mb-3 text-start">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3 text-start">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>

</body>
</html>
