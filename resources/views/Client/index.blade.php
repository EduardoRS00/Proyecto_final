<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de reservas</title>
    <link rel="stylesheet" href="{{ asset('css/styles.index-client.css') }}">
</head>

<body>
    <div class="container my-4">
        <!-- Saludo con el nombre del restaurante -->
        <h2 class="mb-4">¡Hola, {{ $restaurantName }}!</h2>

        <div class="d-flex flex-wrap gap-3 mb-4 summary-buttons">
            <a href="#listado-reservas" class="btn btn-outline-primary flex-fill">
                {{ $totalPax }} Pax
            </a>
            <a href="#listado-reservas" class="btn btn-outline-success flex-fill">
                {{ $totalMesas }} Mesas
            </a>
        </div>

    </div>

    <br>

    @php
    $restaurantId = Auth::id();
    @endphp

    @if ($restaurantId)
    <form method="GET" action="{{ route('reservas.filtrar', ['id' => $restaurantId]) }}" class="filter-bar mb-4">


        <div class="filter-group">
            <label for="filter-date">📅 Fecha</label>
            <input type="date" id="filter-date" name="date" value="{{ $selectedDate ?? '' }}">
        </div>

        <div class="filter-group">
            <label for="filter-time">⏰ Hora</label>
            <input type="time" id="filter-time" name="time" value="{{ $timeFilter ?? '' }}">
        </div>

        <div class="filter-group">
            <label for="filter-search">👤 Nombre</label>
            <input type="text" id="filter-search" name="search" placeholder="Nombre del cliente" value="{{ $nameFilter ?? '' }}">
        </div>

        <button type="submit" class="btn-filtrar">Filtrar</button>

        @if($timeFilter || $nameFilter || (!empty($selectedDate) && $selectedDate != date('Y-m-d')))
        <a href="{{ route('reservas.index', ['id' => Auth::id()]) }}" class="btn-limpiar">Limpiar</a>
        @endif

        {{-- filtros --}}
    </form>
    @else
    <div class="alert alert-danger">No se pudo obtener el ID del restaurante.</div>
    @endif
    </form>

    <!-- Listado de reservas -->
    <h4 id="listado-reservas" class="mb-3">Listado de Reservas</h4>

    @forelse ($bookings as $booking)
    @php
    $now = \Carbon\Carbon::now();
    $bookingDateTime = \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->booking_time);
    $hasArrived = $booking->arrival;

    // Lógica para clase CSS de la tarjeta
    $cardClass = '';
    if ($hasArrived) {
    $cardClass = 'bg-confirmed';
    } elseif ($bookingDateTime->addMinutes(15)->lt($now)) {
    $cardClass = 'bg-delayed'; // más de 20 minutos tarde
    } elseif ($booking->status === 'pending') {
    $cardClass = 'bg-pending';
    } elseif ($booking->status === 'no_show') {
    $cardClass = 'bg-no-show';
    } else {
    $cardClass = 'bg-arrived'; // Confirmada pero en hora
    }
    @endphp

    <a href="{{ route('reservas.edit', $booking->id) }}" class="card-link-wrapper">
        <div class="card {{ $cardClass }}">
            <div class="card-body">
                <div class="reservation-row">
                    <div><strong>{{ $booking->customer_name }} {{ $booking->customer_lastname }}</strong></div>
                    <div>{{ $booking->booking_date }}</div>
                    <div>{{ $booking->booking_time }}</div>
                    <div>{{ $booking->num_people }} - Pax</div>
                    <div>{{ $booking->contact_phone }}</div>
                    <div class="status-icon">
                        @if ($hasArrived)
                        ✅ Llegó
                        @else
                        ⏱️ Pendiente
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </a>


    {{-- Botón para marcar como llegada --}}
    @if (!$hasArrived)
    <form action="{{ route('reservas.arrival', $booking->id) }}" method="POST" style="margin-top: 10px;">
        @csrf
        <button type="submit" class="btn btn-sm btn-success">Marcar como llegada</button>
    </form>
    @endif
    </div>
    </div>
    @empty
    <p>No hay reservas para los criterios seleccionados.</p>
    @endforelse
    </div>
</body>

</html>