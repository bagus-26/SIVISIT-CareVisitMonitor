<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIVISIT</title>
    <link href="https://cdn.jsdelivr.net/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .login-container {
            margin-top: 10%;
        }
    </style>
</head>

<body>

    <div class="container login-container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 px-3 py-4">
                    <div class="card-body">
                        <h3 class="text-center fw-bold text-primary mb-2">SIVISIT</h3>
                        <p class="text-muted text-center small mb-4">Silakan masuk ke akun perawat Anda</p>

                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label small fw-medium">Email</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Masukkan email" value="{{ old('email') }}" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label small fw-medium">Password</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="••••••••" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary fw-medium">Masuk Aplikasi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>