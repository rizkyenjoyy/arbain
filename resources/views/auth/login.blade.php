<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Unique class names for CSS */
        .login-bg {
            background-image: url('/img/mos.jpg');
            background-size: cover;
            background-position: center;
            filter: blur(8px);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent white */
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .login-card .card-header {
            border-radius: 10px 10px 0 0;
            padding: 1rem;
            text-align: center;
            border-bottom: 2px solid #DDB02C; /* Subtle bottom border */
        }

        .login-card .form-label {
            color: #333;
        }

        .login-card .btn-primary {
            background-color: #DDB02C;
            border: none;
        }

        .login-card .btn-primary:hover {
            background-color: #c59b25;
        }
    </style>
</head>
<body>

    <div class="login-bg"></div>

    <div class="container login-container">
        <div class="col-md-6">
            <div class="card login-card">
                <div class="card-header">
                    <h4>Login</h4>
                </div>
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" id="username" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
