<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Calendario Inline - Full Width</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('CSS/styles.booking.css') }}"> <!-- Siempre al final -->

</head>

<body>

  <div class="inicio-restaurante">
    <h1 class="name">{{ $restaurante->name }}</h1>
    <h2><i class="bi bi-calendar"></i> Selecciona una fecha</h2>
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
            <span>Nº de personas (niños incluidos)</span>
          </div>
          <div class="grupo-botones" id="grupo-personas">
            <button type="button" class="boton-opcion" data-valor="1">1</button>
            <button type="button" class="boton-opcion" data-valor="2">2</button>
            <button type="button" class="boton-opcion" data-valor="3">3</button>
            <button type="button" class="boton-opcion" data-valor="4">4</button>
            <button type="button" class="boton-opcion" data-valor="5">5</button>
            <button type="button" class="boton-opcion" data-valor="6">6</button>
            <button type="button" class="boton-opcion" id="boton-7">7</button>
            <select id="select-mas" class="mas">
              <option value=""> + </option>
              @for ($i = 8; $i <= 50; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
          </div>
          <input type="hidden" name="num_people" id="input-comensales" required>
        </div>

        <!-- HORA -->
        <div class="seccion-formulario">
          <div class="etiqueta-icono">
            <i class="bi bi-clock"></i>
            <span>Selecciona una hora</span>
          </div>
          <div class="grupo-botones" id="grupo-horas">
            <div>
              <p>Comidas</p>
              <button type="button" class="boton-opcion" data-hora="12:00">12:00</button>
              <button type="button" class="boton-opcion" data-hora="12:15">12:15</button>
              <button type="button" class="boton-opcion" data-hora="12:30">12:30</button>
              <button type="button" class="boton-opcion" data-hora="12:45">12:45</button>
              <button type="button" class="boton-opcion" data-hora="13:00">13:00</button>
              <button type="button" class="boton-opcion" data-hora="13:15">13:15</button>
              <button type="button" class="boton-opcion" data-hora="13:30">13:30</button>
              <button type="button" class="boton-opcion" data-hora="13:45">13:45</button>
              <button type="button" class="boton-opcion" data-hora="14:00">14:00</button>
              <button type="button" class="boton-opcion" data-hora="14:15">14:15</button>
              <button type="button" class="boton-opcion" data-hora="14:30">14:30</button>
              <button type="button" class="boton-opcion" data-hora="14:45">14:45</button>
              <button type="button" class="boton-opcion" data-hora="15:00">15:00</button>
              <button type="button" class="boton-opcion" data-hora="15:15">15:15</button>
              <button type="button" class="boton-opcion" data-hora="15:30">15:30</button>
              <button type="button" class="boton-opcion" data-hora="15:45">15:45</button>
              <button type="button" class="boton-opcion" data-hora="16:00">16:00</button>
            </div>
            <div>
              <p>Cenas</p>
              <button type="button" class="boton-opcion" data-hora="18:30">18:30</button>
              <button type="button" class="boton-opcion" data-hora="18:45">18:45</button>
              <button type="button" class="boton-opcion" data-hora="19:00">19:00</button>
              <button type="button" class="boton-opcion" data-hora="19:15">19:15</button>
              <button type="button" class="boton-opcion" data-hora="19:30">19:30</button>
              <button type="button" class="boton-opcion" data-hora="19:45">19:45</button>
              <button type="button" class="boton-opcion" data-hora="20:00">20:00</button>
              <button type="button" class="boton-opcion" data-hora="20:15">20:15</button>
              <button type="button" class="boton-opcion" data-hora="20:30">20:30</button>
              <button type="button" class="boton-opcion" data-hora="20:45">20:45</button>
              <button type="button" class="boton-opcion" data-hora="21:00">21:00</button>
              <button type="button" class="boton-opcion" data-hora="21:15">21:15</button>
              <button type="button" class="boton-opcion" data-hora="21:30">21:30</button>
              <button type="button" class="boton-opcion" data-hora="21:45">21:45</button>
              <button type="button" class="boton-opcion" data-hora="22:00">22:00</button>
              <button type="button" class="boton-opcion" data-hora="22:15">22:15</button>
              <button type="button" class="boton-opcion" data-hora="22:30">22:30</button>
            </div>
          </div>
          <input type="hidden" name="booking_time" id="input-hora" required>
        </div>

        <!-- MESA Y TIPO DE COMIDA -->
        <div class="seccion-formulario">
          <div class="etiqueta-icono">
            <i class="bi bi-table"></i>
            <span>Selecciona el tipo de mesa</span>
          </div>
          <select name="mesa" class="form-select" required>
            <option value="estandar">Mesa estándar</option>
            <option value="altas">Mesas Altas</option>
            <option value="banco" disabled>Banco (No disponible)</option>
          </select>
        </div>

        <div class="seccion-formulario">
          <label for="tipo"><i class="bi bi-book"></i> Tipo de comida:</label>
          <select name="tipo" name="menu" class="form-select" required>
            <option value="menu">Menú</option>
            <option value="carta">Carta</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="comentarios" class="form-label">Comentarios (Opcional)</label>
          <textarea class="form-control" name="comments" rows="4" placeholder="Escribe tu comentario"></textarea>
        </div>

        <!-- MENSAJE DE ERROR -->
        <p id="mensaje-error" style="color:red; font-weight:bold; display:none;">
          Todos los campos deben estar rellenos antes de continuar.
        </p>

        <!-- BOTÓN INICIAL -->
        <button type="button" id="boton-validar-parte-uno" class="reservar">Reservar</button>

        <!-- SEGUNDO FORMULARIO -->
        <div id="formulario-datos-personales" class="mt-4" style="display: none;">
          <hr>
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="customer_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Apellidos</label>
            <input type="text" name="customer_lastname" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="contact_email" class="form-control" required>
          </div>
          <p>Telefono</p>
          <div class="row g-2 mb-3">
            <div class="col-md-4">
              <select name="prefix" class="form-select">
                <option value="+1">(+1) Estados Unidos y Canadá</option>
                <option value="+7">(+7) Rusia y Kazajistán</option>
                <option value="+20">(+20) Egipto</option>
                <option value="+27">(+27) Sudáfrica</option>
                <option value="+30">(+30) Grecia</option>
                <option value="+31">(+31) Países Bajos</option>
                <option value="+32">(+32) Bélgica</option>
                <option value="+33">(+33) Francia</option>
                <option value="+34" selected>(+34) España</option>
                <option value="+36">(+36) Hungría</option>
                <option value="+39">(+39) Italia</option>
                <option value="+40">(+40) Rumania</option>
                <option value="+41">(+41) Suiza</option>
                <option value="+43">(+43) Austria</option>
                <option value="+44">(+44) Reino Unido</option>
                <option value="+45">(+45) Dinamarca</option>
                <option value="+46">(+46) Suecia</option>
                <option value="+47">(+47) Noruega</option>
                <option value="+48">(+48) Polonia</option>
                <option value="+49">(+49) Alemania</option>
                <option value="+51">(+51) Perú</option>
                <option value="+52">(+52) México</option>
                <option value="+53">(+53) Cuba</option>
                <option value="+54">(+54) Argentina</option>
                <option value="+55">(+55) Brasil</option>
                <option value="+56">(+56) Chile</option>
                <option value="+57">(+57) Colombia</option>
                <option value="+58">(+58) Venezuela</option>
                <option value="+60">(+60) Malasia</option>
                <option value="+61">(+61) Australia</option>
                <option value="+62">(+62) Indonesia</option>
                <option value="+63">(+63) Filipinas</option>
                <option value="+64">(+64) Nueva Zelanda</option>
                <option value="+65">(+65) Singapur</option>
                <option value="+66">(+66) Tailandia</option>
                <option value="+81">(+81) Japón</option>
                <option value="+82">(+82) Corea del Sur</option>
                <option value="+84">(+84) Vietnam</option>
                <option value="+86">(+86) China</option>
                <option value="+90">(+90) Turquía</option>
                <option value="+91">(+91) India</option>
                <option value="+92">(+92) Pakistán</option>
                <option value="+93">(+93) Afganistán</option>
                <option value="+94">(+94) Sri Lanka</option>
                <option value="+95">(+95) Birmania</option>
                <option value="+98">(+98) Irán</option>
                <option value="+211">(+211) Sudán del Sur</option>
                <option value="+212">(+212) Marruecos</option>
                <option value="+213">(+213) Argelia</option>
                <option value="+216">(+216) Túnez</option>
                <option value="+218">(+218) Libia</option>
                <option value="+220">(+220) Gambia</option>
                <option value="+221">(+221) Senegal</option>
                <option value="+222">(+222) Mauritania</option>
                <option value="+223">(+223) Malí</option>
                <option value="+224">(+224) Guinea</option>
                <option value="+225">(+225) Costa de Marfil</option>
                <option value="+226">(+226) Burkina Faso</option>
                <option value="+227">(+227) Níger</option>
                <option value="+228">(+228) Togo</option>
                <option value="+229">(+229) Benín</option>
                <option value="+230">(+230) Mauricio</option>
                <option value="+231">(+231) Liberia</option>
                <option value="+232">(+232) Sierra Leona</option>
                <option value="+233">(+233) Ghana</option>
                <option value="+234">(+234) Nigeria</option>
                <option value="+235">(+235) Chad</option>
                <option value="+236">(+236) República Centroafricana</option>
                <option value="+237">(+237) Camerún</option>
                <option value="+238">(+238) Cabo Verde</option>
                <option value="+239">(+239) Santo Tomé y Príncipe</option>
                <option value="+240">(+240) Guinea Ecuatorial</option>
                <option value="+241">(+241) Gabón</option>
                <option value="+242">(+242) República del Congo</option>
                <option value="+243">(+243) República Democrática del Congo</option>
                <option value="+244">(+244) Angola</option>
                <option value="+245">(+245) Guinea-Bisáu</option>
                <option value="+246">(+246) Territorio Británico del Océano Índico</option>
                <option value="+248">(+248) Seychelles</option>
                <option value="+249">(+249) Sudán</option>
                <option value="+250">(+250) Ruanda</option>
                <option value="+251">(+251) Etiopía</option>
                <option value="+252">(+252) Somalia</option>
                <option value="+253">(+253) Yibuti</option>
                <option value="+254">(+254) Kenia</option>
                <option value="+255">(+255) Tanzania</option>
                <option value="+256">(+256) Uganda</option>
                <option value="+257">(+257) Burundi</option>
                <option value="+258">(+258) Mozambique</option>
                <option value="+260">(+260) Zambia</option>
                <option value="+261">(+261) Madagascar</option>
                <option value="+262">(+262) Reunión</option>
                <option value="+263">(+263) Zimbabue</option>
                <option value="+264">(+264) Namibia</option>
              </select>
            </div>
            <div class="col-md-8">
              <input type="tel" name="contact_phone" class="form-control" required>
            </div>
          </div>
          <hr>
          <h5 class="mb-3">¿Alguna circunstancia especial?</h5>
          <div class="row g-2 mb-3">
            <div class="col-md-6"><label class="form-label">Silla de Ruedas</label>
              <select name="wheelchair" class="form-select">@for ($i = 0; $i <= 5; $i++)<option value="{{ $i }}">{{ $i }}</option>@endfor</select>
            </div>
            <div class="col-md-6"><label class="form-label">Alergia</label>
              <select name="allergies" class="form-select">
                <option value="no">No</option>
                <option value="si">Sí</option>
              </select>
            </div>
          </div>
          <hr>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="terms_accepted" required>
            <label class="form-check-label">Acepto condiciones y privacidad.</label>
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="promo_opt_in">
            <label class="form-check-label">Deseo recibir promociones.</label>
          </div>
        </div>

        <!-- BOTÓN FINAL -->
        <button type="submit" id="boton-enviar-final" class="reservar mt-3" style="display: none;">Enviar Reserva</button>

      </div>
    </form>
  </div>

  <div class="contacto">
    <p class="contact">Contacto</p>
    <p><i class="bi bi-telephone"></i> <strong>Teléfono: </strong>{{ $restaurante->phone }}</p>
    <p><i class="bi bi-envelope"></i> <strong>Email: </strong>{{ $restaurante->email }}</p>
    <p><i class="bi bi-geo-fill"></i> <strong>Dirección:</strong> {{ $restaurante->city }} {{ $restaurante->street }} {{ $restaurante->street_number }}</p>
  </div>



  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>
  <script src="{{ asset('JS/index.js') }}"></script>

  <script>

  </script>

  <div id="data-restaurante"
    data-restaurante-id="{{ $restaurante->id }}"
    data-max-capacity="{{ $restaurante->max_capacity }}">
  </div>


</body>

</html>