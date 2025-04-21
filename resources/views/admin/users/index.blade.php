<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Usuarios Registrados</title>
    <link rel="stylesheet" href="{{ asset('CSS/admin-index.css') }}">
</head>

<body style="background-color: #f0f8f4; font-family: 'Segoe UI', sans-serif;">

    <div class="container" style="max-width: 90%; margin: 40px auto;">
        <h2>Usuarios registrados</h2>
        <form method="GET" action="{{ route('admin.users.create') }}" class="mb-4 text-end">
            <button type="submit" class="btn">+ Añadir nuevo usuario</button>
        </form>


        <table class="tabla-usuarios">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Ciudad</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Aforo Máx.</th>
                    <th>Pago Activo</th>
                    <th>Total Reservas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->city }}</td>
                    <td>{{ $user->street }} {{ $user->street_number }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->max_capacity }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                            @csrf
                            <button class="{{ $user->is_active_payment ? 'btn-activo' : 'btn-inactivo' }}">
                                {{ $user->is_active_payment ? 'Sí' : 'No' }}
                            </button>
                        </form>
                    </td>
                    <td>{{ $user->bookings_count }}</td>
                    <td>
                        <a href="{{ route('admin.users.reservas', $user) }}" class="btn-ver">Ver Reservas</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if (session('success'))
        <div id="alert-success" class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
        @endif


    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const alert = document.getElementById("alert-success");
            if (alert) {
                setTimeout(() => {
                    alert.style.transition = "opacity 0.5s ease";
                    alert.style.opacity = "0";
                    setTimeout(() => alert.remove(), 500);
                }, 2000);
            }
        });
    </script>


</body>

</html>