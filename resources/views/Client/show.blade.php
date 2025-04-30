<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Reserva</title>
    <link rel="stylesheet" href="{{ asset('CSS/show.styles.css') }}">
</head>

<body>
    <div class="container mt-4">
        <h1>Detalles de la Reserva</h1>

        <div class="card booking-card mx-auto">
            <div class="card-body">

                <!-- DATOS NORMALES -->
                <div id="datos-reserva">
                    <div><strong>Nombre:</strong> {{ $reserva->customer_name }} {{ $reserva->customer_lastname }}</div>
                    <hr>
                    <div><strong>Teléfono:</strong> {{ $reserva->contact_phone }}</div>
                    <hr>
                    <div><strong>Email:</strong> {{ $reserva->contact_email }}</div>
                    <hr>
                    <div><strong>Fecha:</strong> {{ $reserva->booking_date }}</div>
                    <hr>
                    <div><strong>Hora:</strong> {{ $reserva->booking_time }}</div>
                    <hr>
                    <div><strong>Nº de personas:</strong> {{ $reserva->num_people }}</div>
                    <hr>
                    <div><strong>Tipo de mesa:</strong> {{ $reserva->table_type }}</div>
                    <hr>
                    <div><strong>Menú:</strong> {{ $reserva->menu }}</div>
                    <hr>
                    <div><strong>Comentarios:</strong> {{ $reserva->comments ?? 'Sin comentarios' }}</div>
                    <hr>

                    @if($reserva->allergies && strtolower($reserva->allergies) != 'no')
                    <div><strong>Alergias:</strong> {{ $reserva->allergies }}</div>
                    <hr>
                    @endif

                    @if($reserva->wheelchair && $reserva->wheelchair != 0)
                    <div><strong>Silla de ruedas:</strong> {{ $reserva->wheelchair }}</div>
                    <hr>
                    @endif

                    @if($reserva->baby_stroller && $reserva->baby_stroller != 0)
                    <div><strong>Carrito de bebé:</strong> {{ $reserva->baby_stroller }}</div>
                    <hr>
                    @endif

                    @if($reserva->high_chair && $reserva->high_chair != 0)
                    <div><strong>Trona para bebé:</strong> {{ $reserva->high_chair }}</div>
                    <hr>
                    @endif

                    <div><strong>Llegada:</strong> {{ $reserva->arrival  ? 'Sí' : 'Pendiente por llegar' }}</div>
                    <hr>

                    <!-- BOTONES de Modificar y Eliminar -->
                    <div class="text-center mt-4">
                        <a href="{{ route(name: 'reservas.index') }}" class="volver">Volver al Listado</a>
                        <button class="btn btn-warning" onclick="mostrarFormulario()">Modificar Reserva</button>
                        <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST"
                            style="display:inline-block;"
                            onsubmit="return confirm('¿Está seguro de eliminar esta reserva?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar Reserva</button>
                        </form>
                    </div>
                </div>

                <!-- FORMULARIO EDITABLE (OCULTO AL PRINCIPIO) -->
                <div id="formulario-edicion" style="display: none;">
                    <form action="{{ route('reservas.update', $reserva->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form">
                            <div>
                                <label class="form-label"><strong>Nombre:</strong></label>
                                <input type="text" name="customer_name" class="form-control" value="{{ $reserva->customer_name }}" required>
                            </div>

                            <div>
                                <label class="form-label"><strong>Apellidos:</strong></label>
                                <input type="text" name="customer_lastname" class="form-control" value="{{ $reserva->customer_lastname }}" required>
                            </div>

                            <div>
                                <label class="form-label"><strong>Teléfono:</strong></label>
                                <input type="text" name="contact_phone" class="form-control" value="{{ $reserva->contact_phone }}" required>
                            </div>

                            <div>
                                <label class="form-label"><strong>Email:</strong></label>
                                <input type="email" name="contact_email" class="form-control" value="{{ $reserva->contact_email }}">
                            </div>

                            <div>
                                <label class="form-label"><strong>Fecha:</strong></label>
                                <input type="date" name="booking_date" class="form-control" value="{{ $reserva->booking_date }}" required>
                            </div>

                            <div>
                                <label class="form-label"><strong>Hora:</strong></label>
                                <input type="time" name="booking_time" class="form-control" value="{{ $reserva->booking_time }}" required>
                            </div>

                            <div>
                                <label class="form-label"><strong>Nº de personas:</strong></label>
                                <input type="number" name="num_people" class="form-control" value="{{ $reserva->num_people }}" required>
                            </div>

                            <div>
                                <label class="form-label"><strong>Tipo de mesa:</strong></label>
                                <input type="text" name="table_type" class="form-control" value="{{ $reserva->table_type }}">
                            </div>

                            <div>
                                <label class="form-label"><strong>Menú:</strong></label>
                                <input type="text" name="menu" class="form-control" value="{{ $reserva->menu }}">
                            </div>

                            <div>
                                <label class="form-label"><strong>Comentarios:</strong></label>
                                <textarea name="comments" class="form-control" rows="2">{{ $reserva->comments }}</textarea>
                            </div>

                            <div>
                                <label class="form-label"><strong>Alergias:</strong></label>
                                <select name="allergies" class="form-control" required>
                                    <option value="sí" {{ strtolower($reserva->allergies) == 'sí' ? 'selected' : '' }}>Sí</option>
                                    <option value="no" {{ strtolower($reserva->allergies) == 'no' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>


                            <div>
                                <label class="form-label"><strong>Silla de ruedas:</strong></label>
                                <select name="wheelchair" class="form-control">
                                    @for($i = 0; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ $reserva->wheelchair == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                        </option>
                                        @endfor
                                </select>
                            </div>

                            <div>
                                <label class="form-label"><strong>Carrito de bebé:</strong></label>
                                <select name="baby_stroller" class="form-control">
                                    @for($i = 0; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ $reserva->baby_stroller == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                        </option>
                                        @endfor
                                </select>
                            </div>

                            <div>
                                <label class="form-label"><strong>Trona para bebé:</strong></label>
                                <select name="high_chair" class="form-control">
                                    @for($i = 0; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ $reserva->high_chair == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                        </option>
                                        @endfor
                                </select>
                            </div>

                        </div>

                        <div class="botones">


                            <button type="submit" class="btn btn-success">Guardar cambios</button>


                            <!-- Botón de cancelar -->


                    </form>
                    @if ($reserva->arrival)
                    <form method="POST" action="{{ route('reservas.marcarNoLlegado', $reserva->id) }}" style="display:inline-block;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="boton-medio">Marcar como No Llegado</button>
                    </form>
                    @endif

                    <button type="button" class="btn btn-secondary ms-2" onclick="cancelarEdicion()">Cancelar cambios</button>

                </div>
            </div>




        </div>
    </div>

    </div>


    <!-- Script para mostrar formulario -->
    <script>
        function mostrarFormulario() {
            document.getElementById('datos-reserva').style.display = 'none';
            document.getElementById('formulario-edicion').style.display = 'block';
        }

        function cancelarEdicion() {
            document.getElementById('formulario-edicion').style.display = 'none';
            document.getElementById('datos-reserva').style.display = 'block';
        }
    </script>


</body>

</html>