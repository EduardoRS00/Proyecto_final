<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('CSS/admin.css') }}">

</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-header bg-success text-white text-center">Login Administrador</div>
                    <div class="card-body">
                        @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                        @endif
                        <form method="POST" action="{{ route('admin.login') }}">
                            @csrf
                            <div class="mb-3">
                                <label>Email </label>
                                <input type="email" name="email" class="form-control email" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label>Contraseña </label>
                                <input type="password" name="password" class="form-control contraseña" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Entrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>