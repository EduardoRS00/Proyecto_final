<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Confirmación de Reserva</title>
    <link rel="stylesheet" href="{{ asset('CSS/confirmacion.css') }}">
</head>

<body>
    <div class="confirmacion-container">
        <h1>¡Reserva confirmada!</h1>
        <h2>¡Gracias por tu reserva!</h2>
        <p><span>Restaurante:</span> {{ $reserva->restaurant->name }}</p>
        <p><span>Fecha:</span> {{ $reserva->booking_date }}</p>
        <p><span>Hora:</span> {{ $reserva->booking_time }}</p>
        <p><span>Personas:</span> {{ $reserva->num_people }}</p>
        <p><span>Nombre:</span> {{ $reserva->customer_name }} {{ $reserva->customer_lastname }}</p>
        <p><span>Email:</span> {{ $reserva->contact_email }}</p>
        <p><span>Teléfono:</span> {{ $reserva->contact_phone }}</p>
        <p><span>Mesa:</span> {{ $reserva->table_type }}</p>
        <p><span>Tipo de comida:</span> {{ $reserva->menu }}</p>
        @if (!empty($reserva->comments))
        <p><span>Comentarios:</span> {{ $reserva->comments }}</p>
        @endif


        <a href="{{ route('restaurant.view', $reserva->restaurant_id) }}" class="boton-volver">Volver al inicio</a>
    </div>
</body>

</html>