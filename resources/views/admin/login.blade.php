<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — SIVISIT CareVisit Monitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/sivisit.css') }}" rel="stylesheet">
</head>
<body class="login-body">

<div class="login-wrap">
    <div class="login-card">
        <div class="login-header">
            <div class="login-logo">SV</div>
            <h1>SIVISIT</h1>
            <p>CareVisit Monitor — Masuk Akun Petugas</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mb-4" role="alert">
                ⚠️ {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="contoh: test@example.com" value="{{ old('email', 'test@example.com') }}" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="••••••••" value="password" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Masuk Aplikasi</button>
            </div>
        </form>
        <div class="text-center mt-3" style="font-size:11px;color:var(--sv-text-muted);">
            Gunakan akun demo: <strong>test@example.com</strong> / <strong>password</strong>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>