<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset( 'CSS/show.styles.css')}}">
</head>

<body>
    <div class="container mt-4">
        <h2 class="mb-4 text-center text-primary">Detalles de la Reserva</h2>

        <div class="card booking-card mx-auto">
            <div class="card-body">
                <div class="row g-3">
                    <div>
                        <strong>Nombre:</strong> {{ $reserva->customer_name }} {{ $reserva->customer_lastname }}
                    </div>
                    <div>
                        <strong>Teléfono:</strong> {{ $reserva->contact_phone }}
                    </div>
                    <div>
                        <strong>Email:</strong> {{ $reserva->contact_email }}
                    </div>
                    <div>
                        <strong>Fecha:</strong> {{ $reserva->booking_date }}
                    </div>
                    <div>
                        <strong>Hora:</strong> {{ $reserva->booking_time }}
                    </div>
                    <div>
                        <strong>Nº de personas:</strong> {{ $reserva->num_people }}
                    </div>
                    <div>
                        <strong>Tipo de mesa:</strong> {{ $reserva->table_type }}
                    </div>
                    <div>
                        <strong>Menú:</strong> {{ $reserva->menu }}
                    </div>
                    <div>
                        <strong>Comentarios:</strong>
                        <div class="border rounded p-2">{{ $reserva->comments ?? 'Sin comentarios' }}</div>
                    </div>
                    @if($reserva->allergies && strtolower($reserva->allergies) != 'no')
                    <div>
                        <strong>Alergias:</strong>
                        <div class="border rounded p-2">{{ $reserva->allergies }}</div>
                    </div>
                    @endif

                    @if($reserva->wheelchair && $reserva->wheelchair != 0)
                    <div>
                        <strong>Silla de ruedas:</strong> {{ $reserva->wheelchair }}
                    </div>
                    @endif
                    <div>
                        <strong>Llegada:</strong> {{ $reserva->arrival  ? 'Sí' : 'Pendiente por Llegar'}}
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>