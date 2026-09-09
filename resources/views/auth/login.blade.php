<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem STTP Ditreskrimsus Polda Sumsel</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top right, #1e293b, #0f172a, #020617);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            margin: 0;
            overflow: hidden;
            position: relative;
        }

        .bg-glow {
            position: absolute;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.25) 0%, rgba(0, 0, 0, 0) 70%);
            top: -100px;
            right: -100px;
            border-radius: 50%;
            z-index: 0;
        }

        .bg-glow-2 {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(147, 51, 234, 0.15) 0%, rgba(0, 0, 0, 0) 70%);
            bottom: -150px;
            left: -150px;
            border-radius: 50%;
            z-index: 0;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 2.5rem 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
            position: relative;
            z-index: 1;
        }

        .brand-logo-wrapper {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem auto;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
            font-size: 2.5rem;
            color: #ffffff;
        }

        .form-control {
            background-color: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #f8fafc;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            transition: all 0.25s ease;
        }

        .form-control:focus {
            background-color: rgba(15, 23, 42, 0.85);
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
            color: #ffffff;
        }

        .btn-submit {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            color: white;
            font-weight: 700;
            padding: 0.85rem;
            border-radius: 12px;
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.5);
            transition: all 0.25s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(37, 99, 235, 0.6);
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }
    </style>
</head>
<body>
    <div class="bg-glow"></div>
    <div class="bg-glow-2"></div>

    <div class="login-card">
        <div class="text-center mb-3">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="Logo Polda Sumsel" style="max-height: 90px; width: auto;" class="img-fluid drop-shadow">
            @elseif(file_exists(public_path('images/logo1.png')))
                <img src="{{ asset('images/logo1.png') }}" alt="Logo Polda Sumsel" style="max-height: 90px; width: auto;" class="img-fluid drop-shadow">
            @else
                <div class="brand-logo-wrapper">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
            @endif
        </div>

        <div class="text-center mb-4">
            <h4 class="fw-extrabold text-white mb-1">STTP CYBER CRIME</h4>
            <p class="text-secondary small">Ditreskrimsus Polda Sumatera Selatan</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-3 text-sm p-3 mb-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success border-0 rounded-3 text-sm p-3 mb-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill"></i>
                    <div>{{ session('success') }}</div>
                </div>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label text-secondary small fw-medium">Email Terdaftar</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-secondary-subtle text-secondary">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email" class="form-control border-start-0" id="email" name="email" value="{{ old('email') }}" placeholder="nama@polri.go.id" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label text-secondary small fw-medium">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-secondary-subtle text-secondary">
                        <i class="bi bi-key"></i>
                    </span>
                    <input type="password" class="form-control border-start-0" id="password" name="password" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-submit w-100 mb-3">
                Masuk ke Sistem <i class="bi bi-arrow-right-short ms-1 fs-5"></i>
            </button>
        </form>

        <div class="text-center mt-4">
            <span class="text-secondary small">Lupa akses masuk? Hubungi Administrator SIM.</span>
        </div>
    </div>
</body>
</html>
