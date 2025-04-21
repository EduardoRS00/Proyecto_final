<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Alta de Usuario</title>
    <link rel="stylesheet" href="{{ asset('CSS/admin-user-create.css') }}">
</head>

<body>
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Ups!</strong> Corrige los errores para continuar:
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    <div class="formulario-alta">
        <h3>Dar de alta un nuevo restaurante</h3>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="form-row">
                <label for="name">Nombre</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div class="form-row">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="form-row">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div class="form-row">
                <label for="city">Ciudad</label>
                <input type="text" name="city" id="city" required>
            </div>

            <div class="form-row">
                <label for="street">Calle</label>
                <input type="text" name="street" id="street" required>
            </div>

            <div class="form-row">
                <label for="street_number">Número</label>
                <input type="text" name="street_number" id="street_number" required>
            </div>

            <div class="form-row">
                <label for="phone">Teléfono</label>
                <input type="text" name="phone" id="phone" required>
            </div>

            <div class="form-row">
                <label for="max_capacity">Aforo máximo</label>
                <input type="number" name="max_capacity" id="max_capacity" min="1" required>
            </div>
            
            <div class="form-row">
                <label for="payment_date">Fecha de pago</label>
                <input type="date" name="payment_date" id="payment_date">
            </div>


            <div class="form-submit">
                <button type="submit">Guardar usuario</button>
            </div>
        </form>
    </div>

</body>

</html>