<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="container mt-4">
        <h2 class="text-primary mb-4">Gestión de Usuarios</h2>

        <div class="table-responsive">
            <table>
                <thead class="table-primary text-center">
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Fecha de Pago</th>
                        <th>Estado</th>
                        <th>Aforo Máximo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->phone }}</td>
                        <td>{{ $usuario->payment_date ?? '—' }}</td>
                        <td>
                            @if($usuario->is_active_payment)
                            <span class="badge bg-success">Activo</span>
                            @else
                            <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>{{ $usuario->max_capacity ?? 'No definido' }}</td>
                        <td>
                            <a href="{{ route('admin.users.toggle', $usuario->id) }}" class="btn btn-sm btn-outline-success">
                                @if($usuario->is_active_payment)
                                Desactivar
                                @else
                                Activar
                                @endif
                            </a>
                            <a href="{{ route('admin.users.reservas', $usuario->id) }}" class="btn btn-sm btn-outline-primary">Reservas</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if (count($usuariosActualizados) > 0)
    <div class="alert alert-warning" style="background-color: #fff3cd; padding: 15px; margin-bottom: 20px; border: 1px solid #ffeeba; border-radius: 5px;">
        <strong>¡Atención!</strong> Se han desactivado automáticamente estos usuarios por pago caducado:
        <ul>
            @foreach ($usuariosActualizados as $nombre)
            <li>{{ $nombre }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const alert = document.querySelector(".alert-warning");
            if (alert) {
                setTimeout(() => {
                    alert.style.transition = "opacity 0.5s ease";
                    alert.style.opacity = 0;
                    setTimeout(() => alert.remove(), 500);
                }, 3000);
            }
        });
    </script>
</body>

</html>