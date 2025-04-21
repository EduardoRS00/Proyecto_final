let disponibilidadMes = {};

const picker = new Litepicker({
    element: document.getElementById("inline-calendar"),
    inlineMode: true,
    singleMode: true,
    format: "DD-MM-YYYY",
    lang: "es-ES",
    autoApply: true,
    setup: (picker) => {
        picker.on("render", () => {
            const currentDate = picker.getDate(); // esto te da la fecha seleccionada
  if (!currentDate) return; // aún no se ha seleccionado nada

  const year = currentDate.getFullYear();
  const month = currentDate.getMonth() + 1;

  const restauranteId = document.getElementById("data-restaurante").dataset.restauranteId;

            // 👇 Aquí se hace la petición al backend
            fetch(`/booking/availability/${restauranteId}/${year}/${month}`)
                .then((res) => res.json())
                .then((data) => {
                    disponibilidadMes = data;
                    console.log("Disponibilidad del mes:", disponibilidadMes);
                    actualizarDisponibilidadHoras(); // Vuelve a validar horas
                })
                .catch((err) => {
                    console.error("Error al cargar disponibilidad:", err);
                });

            // Pintar días pasados en rojo
            const hoy = new Date();
            hoy.setHours(0, 0, 0, 0);
            document.querySelectorAll(".day-item").forEach((dia) => {
                const time = parseInt(dia.dataset.time);
                if (time < hoy.getTime()) dia.classList.add("pasado");
            });
        });

        // Evento: al seleccionar una fecha
        picker.on("selected", (date) => {
            document.getElementById("fecha_reserva").value =
                date.format("YYYY-MM-DD");
            document.getElementById("formulario-reserva").style.display =
                "block";
            actualizarDisponibilidadHoras();
        });
    },
});

document
    .getElementById("boton-validar-parte-uno")
    .addEventListener("click", function () {
        const fecha = document.getElementById("fecha_reserva").value;
        const comensales = document.getElementById("input-comensales").value;
        const hora = document.getElementById("input-hora").value;
        const mesa = document.querySelector('select[name="mesa"]').value;
        const tipo = document.querySelector('select[name="tipo"]').value;
        const mensajeError = document.getElementById("mensaje-error");

        if (fecha && comensales && hora && mesa && tipo) {
            mensajeError.style.display = "none";
            document.getElementById(
                "formulario-datos-personales"
            ).style.display = "block";
            this.style.display = "none";
            document.getElementById("boton-enviar-final").style.display =
                "inline-block";
        } else {
            mensajeError.style.display = "block";
        }
    });

document
    .getElementById("boton-enviar-final")
    .addEventListener("click", function (e) {
        const form = this.closest("form");
        const camposRequeridos = form.querySelectorAll("[required]");
        let todosRellenos = true;

        camposRequeridos.forEach((campo) => {
            if (!campo.value.trim()) todosRellenos = false;
        });

        if (!todosRellenos) {
            e.preventDefault();
            alert(
                "Por favor, completa todos los campos antes de enviar la reserva."
            );
        }
    });

const grupoPersonas = document.getElementById("grupo-personas");
const inputPersonas = document.getElementById("input-comensales");
const boton7 = document.getElementById("boton-7");
const selectMas = document.getElementById("select-mas");

grupoPersonas.addEventListener("click", (e) => {
    if (e.target.classList.contains("boton-opcion")) {
        document
            .querySelectorAll("#grupo-personas .boton-opcion")
            .forEach((btn) => btn.classList.remove("activo"));
        if (e.target === boton7) {
            selectMas.classList.remove("oculto");
            boton7.classList.add("activo");
            inputPersonas.value = "7";
        } else {
            inputPersonas.value = e.target.dataset.valor;
            e.target.classList.add("activo");
        }
    }
});

selectMas.addEventListener("change", () => {
    inputPersonas.value = selectMas.value;
});

const grupoHoras = document.getElementById("grupo-horas");
const inputHora = document.getElementById("input-hora");

grupoHoras.addEventListener("click", (e) => {
    if (e.target.classList.contains("boton-opcion")) {
        document
            .querySelectorAll("#grupo-horas .boton-opcion")
            .forEach((btn) => btn.classList.remove("activo"));
        e.target.classList.add("activo");
        inputHora.value = e.target.dataset.hora;
    }
});

// Click en botón de comensales
grupoPersonas.addEventListener("click", (e) => {
    if (e.target.classList.contains("boton-opcion")) {
        document
            .querySelectorAll("#grupo-personas .boton-opcion")
            .forEach((btn) => btn.classList.remove("activo"));
        selectMas.value = ""; // Reinicia select
        e.target.classList.add("activo");
        inputPersonas.value = e.target.dataset.valor;
    }
});

// Selección de más comensales desde el select
selectMas.addEventListener("change", () => {
    document
        .querySelectorAll("#grupo-personas .boton-opcion")
        .forEach((btn) => btn.classList.remove("activo"));
    inputPersonas.value = selectMas.value;
});

grupoHoras.addEventListener("click", (e) => {
    if (e.target.classList.contains("boton-opcion") && !e.target.disabled) {
        document
            .querySelectorAll("#grupo-horas .boton-opcion")
            .forEach((btn) => btn.classList.remove("activo"));
        e.target.classList.add("activo");
        inputHora.value = e.target.dataset.hora;
    }
});

function obtenerFranjasDesdeHora(horaInicial) {
    const franjas = [];
    const [h, m] = horaInicial.split(":").map(Number);
    let momento = new Date(0, 0, 0, h, m);

    for (let i = 0; i < 8; i++) {
        const horas = momento.getHours().toString().padStart(2, "0");
        const minutos = momento.getMinutes().toString().padStart(2, "0");
        franjas.push(`${horas}:${minutos}`);
        momento.setMinutes(momento.getMinutes() + 15);
    }

    return franjas;
}

function actualizarDisponibilidadHoras() {
    const selectedDate = document.getElementById("fecha_reserva").value;
    const numPersonas = parseInt(
        document.getElementById("input-comensales").value || 0
    );
    const maxCapacity = parseInt(
        document.getElementById("data-restaurante").dataset.maxCapacity
    );

    // Si falta algún dato o aún no se ha cargado disponibilidad, salimos
    if (
        !selectedDate ||
        !numPersonas ||
        !maxCapacity ||
        Object.keys(disponibilidadMes).length === 0
    )
        return;

    const botonesHora = document.querySelectorAll(".boton-opcion[data-hora]");

    console.log("Fecha seleccionada:", selectedDate);
    console.log("DisponibilidadMes:", disponibilidadMes);

    botonesHora.forEach((boton) => {
        const hora = boton.dataset.hora;
        const franjas = obtenerFranjasDesdeHora(hora);

        const disponible = franjas.every((franja) => {
            console.log("Franja horaria:", franja);
            const ocupadas = disponibilidadMes[selectedDate]?.[franja] ?? 0;
            return ocupadas + numPersonas <= maxCapacity;
        });

        if (!disponible) {
            boton.disabled = true;
            boton.classList.add("no-disponible");
            boton.classList.remove("activo");
        } else {
            boton.disabled = false;
            boton.classList.remove("no-disponible");
        }
    });
}

document
    .getElementById("fecha_reserva")
    .addEventListener("change", actualizarDisponibilidadHoras);
document
    .getElementById("input-comensales")
    .addEventListener("change", actualizarDisponibilidadHoras);

document.querySelectorAll("#grupo-personas .boton-opcion").forEach((btn) => {
    btn.addEventListener("click", actualizarDisponibilidadHoras);
});
