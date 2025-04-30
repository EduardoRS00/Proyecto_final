<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reservas de {{ $user->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4fdf4;
        }

        h2 {
            color: #2c7a7b;
        }

        .table thead {
            background-color: #38a169;
            color: white;
        }

        .btn-volver {
            background-color: #2b6cb0;
            color: white;
        }

        .btn-volver:hover {
            background-color: #2c5282;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h2 class="mb-4">Reservas de {{ $user->name }} - {{ $user->email }}</h2>

        @if ($reservas->count())
        <table class="table table-bordered table-hover shadow-sm bg-white">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Comensales</th>
                    <th>Comentarios</th>
                    <th>Tipo mesa</th>
                    <th>Tipo comida</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservas as $reserva)
                <tr>
                    <td>{{ $reserva->booking_date }}</td>
                    <td>{{ $reserva->booking_time }}</td>
                    <td>{{ $reserva->num_people }}</td>
                    <td>{{ $reserva->comments }}</td>
                    <td>{{ ucfirst($reserva->table_type) }}</td>
                    <td>{{ ucfirst($reserva->tipo) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="alert alert-info">Este usuario no tiene reservas aún.</div>
        @endif

        <a href="{{ route('admin.users.index') }}" class="btn btn-volver mt-3">← Volver al listado</a>
    </div>
</body>

</html>