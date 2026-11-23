<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - Kosvia Owner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9; /* Warna background abu muda, khas dashboard */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .card-header {
            background-color: #1a9bfc; /* Warna biru khas Flexy/Admin */
            color: white;
            text-align: center;
            padding: 20px;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .btn-primary {
            background-color: #1a9bfc;
            border-color: #1a9bfc;
        }
        .btn-primary:hover {
            background-color: #1380d1;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card login-card">
                    <div class="card-header">
                        Login Owner Kosvia
                    </div>
                    <div class="card-body p-4">
                        
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Ups!</strong> {{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ url('/login') }}" method="POST">
                            @csrf <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" 
                                       name="email" 
                                       class="form-control" 
                                       id="email" 
                                       placeholder="Masukan email owner..." 
                                       value="{{ old('email') }}" 
                                       required>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" 
                                       name="password" 
                                       class="form-control" 
                                       id="password" 
                                       placeholder="Masukan password..." 
                                       required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Masuk Dashboard</button>
                            </div>
                        </form>
                        </div>
                    <div class="card-footer text-center bg-white py-3">
                        <small class="text-muted">Kembali ke <a href="/" class="text-decoration-none">Halaman Utama</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>