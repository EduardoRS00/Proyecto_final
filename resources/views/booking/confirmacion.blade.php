<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <title>{{ __('general.confirmation_title') }}</title>
    <link rel="stylesheet" href="{{ asset('CSS/confirmacion.css') }}">
</head>

<body>
    <div class="confirmacion-container">
        <h1>{{ __('general.confirmed') }}</h1>
        <h2>{{ __('general.thank_you') }}</h2>
        <p><span>{{ __('general.restaurant') }}:</span> {{ $reserva->restaurant->name }}</p>
        <p><span>{{ __('general.date') }}:</span> {{ $reserva->booking_date }}</p>
        <p><span>{{ __('general.time') }}:</span> {{ $reserva->booking_time }}</p>
        <p><span>{{ __('general.people') }}:</span> {{ $reserva->num_people }}</p>
        <p><span>{{ __('general.firstname') }}:</span> {{ $reserva->customer_name }} {{ $reserva->customer_lastname }}</p>
        <p><span>{{ __('general.email') }}:</span> {{ $reserva->contact_email }}</p>
        <p><span>{{ __('general.phone') }}:</span> {{ $reserva->contact_phone }}</p>
        <p><span>{{ __('general.table') }}:</span> {{ $reserva->table_type }}</p>
        <p><span>{{ __('general.food_type') }}:</span> {{ $reserva->menu }}</p>
        @if (!empty($reserva->comments))
        <p><span>{{ __('general.comments') }}:</span> {{ $reserva->comments }}</p>
        @endif

        <a href="{{ route('restaurant.view', $reserva->restaurant_id) }}" class="boton-volver">{{ __('general.back_home') }}</a>
    </div>
</body>

</html>