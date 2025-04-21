<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('CSS/styles.css') }}">
</head>

<body>
    <div class="login-container">
        <h1>Bienvenido a ProBook</h1>
        <h2>Iniciar sesión</h2>

        @if ($errors->any())
        <p class="error">{{ $errors->first() }}</p>
        @endif

        <form method="POST" action="{{ route('index') }}">
            @csrf
            <label for="email">Correo electrónico</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>

            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Entrar</button>
        </form>
    </div>
</body>

</html>