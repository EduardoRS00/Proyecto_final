<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Calendario Inline - Full Width</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('CSS/styles.booking.css') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
  <div class="language-buttons">
    <form action="/set-locale/es" method="GET">
      <button type="submit">ES</button>
    </form>
    <form action="/set-locale/en" method="GET">
      <button type="submit">EN</button>
    </form>
  </div>


  <div class="inicio-restaurante">
    <h1 class="name">{{ $restaurante->name }}</h1>
    <h2 class="name">{{ $restaurante->slogan }}</h2>
    <h2><i class="bi bi-calendar"></i> {{ __('general.calendar_title') }}</h2>
  </div>

  <div class="calendarios">
    <div id="inline-calendar"></div>
  </div>

  <div class="formulario-reserva" id="formulario-reserva" style="display: none; margin-top: 30px;">
    <form method="POST" action="{{ route('reservar') }}">
      @csrf
      <input type="hidden" name="booking_date" id="fecha_reserva" />
      <input type="hidden" name="restaurant_id" value="{{ $restaurante->id }}" />

      <div class="formulario-datoss">
        <!-- COMENSALES -->
        <div class="seccion-formulario">
          <div class="etiqueta-icono">
            <i class="bi bi-people-fill"></i>
            <span>{{ __('general.people') }}</span>
          </div>
          <div class="grupo-botones" id="grupo-personas">
            @for ($i = 1; $i <= 15; $i++)
              <button type="button" class="boton-opcion" data-valor="{{ $i }}">{{ $i }}</button>
              @endfor
          </div>
          <input type="hidden" name="num_people" id="input-comensales" required>
        </div>

        <!-- HORA -->
        <div class="seccion-formulario">
          <div class="etiqueta-icono">
            <i class="bi bi-clock"></i>
            <span>{{ __('general.select_time') }}</span>
          </div>
          <div class="grupo-botones" id="grupo-horas">
            <div>
              <p>{{ __('general.lunch') }}</p>
              @php $horasComida = ["12:00","12:15","12:30","12:45","13:00","13:15","13:30","13:45","14:00","14:15","14:30","14:45","15:00","15:15","15:30","15:45","16:00"]; @endphp
              @foreach ($horasComida as $hora)
              <button type="button" class="boton-opcion" data-hora="{{ $hora }}">{{ $hora }}</button>
              @endforeach
            </div>
            <div>
              <p>{{ __('general.dinner') }}</p>
              @php $horasCena = ["18:30","18:45","19:00","19:15","19:30","19:45","20:00","20:15","20:30","20:45","21:00","21:15","21:30","21:45","22:00","22:15","22:30"]; @endphp
              @foreach ($horasCena as $hora)
              <button type="button" class="boton-opcion" data-hora="{{ $hora }}">{{ $hora }}</button>
              @endforeach
            </div>
          </div>
          <input type="hidden" name="booking_time" id="input-hora" required>
        </div>

        <!-- MESA Y TIPO DE COMIDA -->
        <div class="seccion-formulario">
          <div class="etiqueta-icono">
            <i class="bi bi-table"></i>
            <span>{{ __('general.select_table') }}</span>
          </div>
          <select name="mesa" class="form-select" required>
            <option value="estandar">{{ __('general.table_standard') }}</option>
            <option value="altas">{{ __('general.table_high') }}</option>
            <option value="banco" disabled>{{ __('general.table_bank') }}</option>
          </select>
        </div>

        <div class="seccion-formulario">
          <label for="tipo"><i class="bi bi-book"></i> {{ __('general.food_type') }}</label>
          <select name="tipo" class="form-select" required>
            <option value="menu">{{ __('general.menu') }}</option>
            <option value="carta">{{ __('general.a_la_carte') }}</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="comentarios" class="form-label">{{ __('general.comments') }}</label>
          <textarea class="form-control" name="comments" rows="4" placeholder="{{ __('general.write_comment') }}"></textarea>
        </div>

        <p id="mensaje-error" style="color:red; font-weight:bold; display:none;">
          {{ __('general.required_fields') }}
        </p>

        <button type="button" id="boton-validar-parte-uno" class="reservar">{{ __('general.reserve') }}</button>

        <div id="formulario-datos-personales" class="mt-4" style="display: none;">
          <hr>
          <div class="mb-3">
            <label class="form-label">{{ __('general.firstname') }}</label>
            <input type="text" name="customer_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">{{ __('general.lastname') }}</label>
            <input type="text" name="customer_lastname" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">{{ __('general.email') }}</label>
            <input type="email" name="contact_email" class="form-control" required>
          </div>

          <p>{{ __('general.phone') }}</p>
          <div class="row g-2 mb-3">
            <div class="col-md-4">
              <select name="prefix" class="form-select">
                <option value="+34" selected>(+34) {{ __('general.spain') }}</option>
                <option value="+1">(+1) {{ __('general.usa') }}</option>
                <option value="+44">(+44) {{ __('general.uk') }}</option>
                <option value="+33">(+33) {{ __('general.france') }}</option>
                <option value="+49">(+49) {{ __('general.germany') }}</option>
                <option value="+39">(+39) {{ __('general.italy') }}</option>
                <option value="+351">(+351) {{ __('general.portugal') }}</option>
                <option value="+52">(+52) {{ __('general.mexico') }}</option>
                <option value="+55">(+55) {{ __('general.brazil') }}</option>
                <option value="+81">(+81) {{ __('general.japan') }}</option>
              </select>

            </div>
            <div class="col-md-8">
              <input type="tel" name="contact_phone" class="form-control" required>
            </div>
          </div>

          <hr>
          <h5 class="mb-3">{{ __('general.special_conditions') }}</h5>
          <div class="row g-3 mb-3">
            <div class="col-12 col-md-3">
              <label class="form-label">{{ __('general.wheelchair') }}</label>
              <select name="wheelchair" class="form-select">
                @for ($i = 0; $i <= 5; $i++)
                  <option value="{{ $i }}">{{ $i }}</option>
                  @endfor
              </select>
            </div>
            <div class="col-12 col-md-3">
              <label class="form-label">{{ __('general.allergy') }}</label>
              <select name="allergies" class="form-select">
                <option value="no">{{ __('general.no') }}</option>
                <option value="si">{{ __('general.yes') }}</option>
              </select>
            </div>
            <div class="col-12 col-md-3">
              <label class="form-label">{{ __('general.stroller') }}</label>
              <select name="baby_stroller" class="form-select">
                @for ($i = 0; $i <= 5; $i++)
                  <option value="{{ $i }}">{{ $i }}</option>
                  @endfor
              </select>
            </div>
            <div class="col-12 col-md-3">
              <label class="form-label">{{ __('general.high_chair') }}</label>
              <select name="high_chair" class="form-select">
                @for ($i = 0; $i <= 5; $i++)
                  <option value="{{ $i }}">{{ $i }}</option>
                  @endfor
              </select>
            </div>
          </div>

          <hr>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="terms_accepted" required>
            <label class="form-check-label">{{ __('general.terms') }}</label>
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="promo_opt_in">
            <label class="form-check-label">{{ __('general.promos') }}</label>
          </div>

          <button type="submit" id="boton-enviar-final" class="reservar mt-3" style="display: none;">{{ __('general.submit') }}</button>
        </div>
      </div>
    </form>
  </div>

  <div class="contacto">
    <p class="contact">{{ __('general.contact') }}</p>
    <p><i class="bi bi-telephone"></i> <strong>{{ __('general.phone') }}: </strong>{{ $restaurante->phone }}</p>
    <p><i class="bi bi-envelope"></i> <strong>{{ __('general.email') }}: </strong>{{ $restaurante->email }}</p>
    <p><i class="bi bi-geo-fill"></i> <strong>{{ __('general.address') }}:</strong> {{ $restaurante->city }} {{ $restaurante->street }} {{ $restaurante->street_number }}</p>
  </div>

  <div id="data-restaurante" data-restaurante-id="{{ $restaurante->id }}" data-max-capacity="{{ $restaurante->max_capacity }}"></div>
  <script>
    const currentLang = "{{ app()->getLocale() }}";
    const mensajes = {
      required_fields: {
        es: "Todos los campos requeridos deben estar completos.",
        en: "All required fields must be filled out before continuing."
      },
      invalid_email: {
        es: "Introduce un email válido.",
        en: "Enter a valid email."
      },
      invalid_phone: {
        es: "Introduce un teléfono válido (solo números, mínimo 6 dígitos).",
        en: "Enter a valid phone number (only digits, at least 6)."
      }
    };
  </script>
  <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/l10n/es.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/l10n/en-us.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>
  <script src="{{ asset('JS/index.js') }}"></script>


  <div id="chatbot" class="chatbot-container">
    <div class="chatbot-header" onclick="toggleChatbot()">
      <span>{{ __('general.welcome') }}</span>
      <button class="chatbot-toggle" id="toggle-chat">+</button>
    </div>
    <div id="chatbot-body" class="chatbot-body" style="display: none;">
      <div class="chatbot-messages" id="chatbot-messages"></div>
    </div>
    <hr>
    <div class="chatbot-input-container">
      <input type="text" id="chatbot-input" placeholder="{{ __('general.write_here') }}" />
      <button onclick="enviarMensaje()">{{ __('general.send') }}</button>
    </div>
  </div>
</body>

</html>