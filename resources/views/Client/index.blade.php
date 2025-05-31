<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de reservas</title>
    <link rel="stylesheet" href="{{ asset('css/styles.index-client.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>
    <div class="container my-4">
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
    <div class="contenido-reservas">
        @php
        $restaurantId = Auth::id();
        @endphp

        @if ($restaurantId)
        <form method="GET" action="{{ route('reservas.filtrar', ['id' => $restaurantId]) }}" class="filter-bar mb-4">

            <div class="filter-group">
                <label for="filter-date"><i class="bi bi-calendar-event"></i> Fecha</label>
                <input type="date" id="filter-date" name="date" value="{{ $selectedDate ?? date('Y-m-d') }}">
            </div>

            <div class="filter-group">
                <label for="filter-time"><i class="bi bi-clock"></i> Hora</label>
                <input type="time" id="filter-time" name="time" value="{{ $timeFilter ?? '' }}">
            </div>

            <div class="filter-group">
                <label for="filter-search"><i class="bi bi-person"></i> Nombre</label>
                <input type="text" id="filter-search" name="search" placeholder="Nombre del cliente" value="{{ $nameFilter ?? '' }}">
            </div>

            <button type="submit" class=" CREATE DATABASE IF NOT EXISTS probook;
 USE probook;
 CREATE TABLE users (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(150),
    password VARCHAR(255),
    city VARCHAR(100),
    street VARCHAR(100),
    street_number VARCHAR(50),
    phone VARCHAR(20),
    payment_date DATE,
    is_active_payment TINYINT(1),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CU
RRENT_TIMESTAMP,
    max_capacity INT,
    slogan VARCHAR(255)
 );
 CREATE TABLE bookings (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    restaurant_id BIGINT,
    customer_name VARCHAR(255),
    customer_lastname VARCHAR(255),
    contact_phone VARCHAR(20),
    contact_email VARCHAR(255),
    booking_date DATE,
    arrival TINYINT(1),
    booking_time TIME,
    num_people INT,
    table_type VARCHAR(255),
    menu VARCHAR(255),
    comments TEXT,
    allergies TEXT,
    baby_stroller TINYINT(1),
    high_chair TINYINT(1),
    wheelchair TINYINT(1),
    promo_opt_in TINYINT(1),
    terms_accepted TINYINT(1),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CU
RRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES users(id) ON DELETE CASCADE
 );
 CREATE TABLE admins (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(150),
 password VARCHAR(255),
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CU
RRENT_TIMESTAMP
 );r">Filtrar</button>

            @if($timeFilter || $nameFilter || (!empty($selectedDate) && $selectedDate != date('Y-m-d')))
            <a href="{{ route('reservas.index', ['id' => Auth::guard('admin')->id()]) }}" class="btn-limpiar">Limpiar</a>
            @endif

            {{-- filtros --}}
        </form>
        @else
        <div class="alert alert-danger">No se pudo obtener el ID del restaurante.</div>
        @endif
        </form>



        <!-- Listado de reservas -->
        <h4 id="listado-reservas" class="mb-3">Listado de Reservas</h4>
        <div style="margin-bottom: 25px;">
            <label for="toggle-llegados"><strong>Ocultar reservas que ya llegaron</strong></label>
            <input type="checkbox" id="toggle-llegados">
        </div>


        <div class="reservation-header">
            <div>Nombre</div>
            <div>Fecha</div>
            <div>Hora</div>
            <div>Pax</div>
            <div>Teléfono</div>
            <div class="estado">Estado</div>
        </div>


        @forelse ($bookings as $booking)
        @php
        $now = \Carbon\Carbon::now();
        $bookingDateTime = \Carbon\Carbon::parse($booking->booking_date . ' ' . $booking->booking_time);
        $hasArrived = $booking->arrival;

        $cardClass = '';
        if ($hasArrived) {
        $cardClass = 'bg-confirmed';
        } elseif ($bookingDateTime->addMinutes(20)->lt($now)) {
        $cardClass = 'bg-delayed';
        } else {
        $cardClass = 'bg-arrived';
        }
        @endphp

        <a href="{{ route('reservas.edit', $booking->id) }}" class="card-link-wrapper">
            <div class="card {{ $cardClass }}" data-reserva-id="{{ $booking->id }}">
                <div class="card-inner">
                    <div class="reservation-row">
                        <div>
                            <span class="mobile-label"><i class="bi bi-person me-1"></i> Nombre: -------></span>
                            {{ $booking->customer_name }} {{ $booking->customer_lastname }}
                        </div>
                        <div>
                            <span class="mobile-label"><i class="bi bi-calendar-event me-1"></i> Fecha: ----------></span>
                            {{ $booking->booking_date }}
                        </div>
                        <div>
                            <span class="mobile-label"><i class="bi bi-clock me-1"></i> Hora: -----------></span>
                            {{ $booking->booking_time }}
                        </div>
                        <div>
                            <span class="mobile-label"><i class="bi bi-people me-1"></i> Comensales: ---></span>
                            {{ $booking->num_people }} Pax
                        </div>
                        <div>
                            <span class="mobile-label"><i class="bi bi-telephone me-1"></i> Teléfono: -------></span>
                            {{ $booking->contact_phone }}
                        </div>
                        <div class="status-icon">
                            @if ($hasArrived)
                            <p class="llegar">Llegó</p>
                            @else
                            <button class="estado-pendiente-btn marcar-llegada" data-id="{{ $booking->id }}">
                                <i class="bi bi-hourglass-split"></i> Pendiente
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </a>


        @empty
        <p>No hay reservas para los criterios seleccionados.</p>
        @endforelse
    </div>
    </div>
    </div>

    </div>

    <script>
        document.getElementById('toggle-llegados').addEventListener('change', function() {
            const ocultar = this.checked;
            document.querySelectorAll('.card.bg-confirmed').forEach(card => {
                card.style.display = ocultar ? 'none' : '';
            });
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleLlegadas = document.getElementById('toggle-llegados');

            toggleLlegadas.addEventListener('change', function() {
                const ocultar = this.checked;
                document.querySelectorAll('.card.bg-confirmed').forEach(card => {
                    card.style.display = ocultar ? 'none' : '';
                });
            });

            const botonesLlegada = document.querySelectorAll(".marcar-llegada");

            botonesLlegada.forEach(boton => {
                boton.addEventListener("click", function(e) {
                    e.preventDefault();

                    const idReserva = this.dataset.id;
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch(`/reservas/${idReserva}/llegada`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify({})
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                window.location.href = window.location.href;
                            } else {
                                alert("Error al marcar la llegada.");
                            }
                        })

                        .catch(() => console.log("Error de red al intentar marcar como llegada."));
                });
            });
        });
    </script>

</body>

</html>