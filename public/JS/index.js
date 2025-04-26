// index.js
let disponibilidadMes = {}; // Objeto donde guardaremos TODA la disponibilidad del año completo
const hoy = new Date();
const fechaLimite = new Date();
fechaLimite.setFullYear(hoy.getFullYear() + 1);

const picker = new Litepicker({
    element: document.getElementById("inline-calendar"),
    inlineMode: true,
    singleMode: true,
    format: "YYYY-MM-DD",
    lang: "es-ES",
    autoApply: true,
    minDate: "2025-01-01",
    maxDate:fechaLimite,
    setup: (picker) => {
        picker.on("render", () => {
            pintarDiasPasados();
            actualizarDisponibilidadHoras();
        });

        picker.on("selected", (date) => {
            document.getElementById("fecha_reserva").value = date.format("YYYY-MM-DD");
            document.getElementById("formulario-reserva").style.display = "block";
            actualizarDisponibilidadHoras();
        });
    },
});

// Cargar disponibilidad del mes actual y los 12 siguientes
async function precargarDisponibilidad() {
    const restauranteId = document.getElementById("data-restaurante").dataset.restauranteId;
    const hoy = new Date();
    const yearActual = hoy.getFullYear();
    let mesActual = hoy.getMonth() + 1;

    for (let i = 0; i <= 12; i++) {
        const date = new Date(yearActual, mesActual - 1 + i);
        const year = date.getFullYear();
        const month = date.getMonth() + 1;

        try {
            const res = await fetch(`/booking/availability/${restauranteId}/${year}/${month}`);
            const data = await res.json();

            disponibilidadMes = {
                ...disponibilidadMes,
                ...data,
            };

            console.log(`✅ Aforo cargado para ${year}-${month}`, data);
        } catch (err) {
            console.error("❌ Error al precargar disponibilidad:", err);
        }
    }

    actualizarDisponibilidadHoras();
}

precargarDisponibilidad(); // Lanzamos la carga desde el principio

function pintarDiasPasados() {
    const limite = new Date();
    limite.setFullYear(hoy.getFullYear() + 1);
    limite.setHours(0, 0, 0, 0);

    setTimeout(() => {
        document.querySelectorAll(".day-item").forEach((dia) => {
            const time = parseInt(dia.dataset.time);
            const fecha = new Date(time);

            // Si la fecha es anterior a hoy o posterior al límite de un año, la marcamos como pasada
            if (fecha < hoy || fecha > limite) {
                dia.classList.add("pasado");
            }
        });
    }, 5);
}

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

    if (
        !selectedDate ||
        !numPersonas ||
        !maxCapacity ||
        Object.keys(disponibilidadMes).length === 0
    )
        return;

    const botonesHora = document.querySelectorAll(".boton-opcion[data-hora]");

    botonesHora.forEach((boton) => {
        const hora = boton.dataset.hora;
        const franjas = obtenerFranjasDesdeHora(hora);

        const disponible = franjas.every((franja) => {
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


document.getElementById("fecha_reserva").addEventListener("change", actualizarDisponibilidadHoras);
document.getElementById("input-comensales").addEventListener("change", actualizarDisponibilidadHoras);
document.querySelectorAll("#grupo-personas .boton-opcion").forEach((btn) => {
    btn.addEventListener("click", actualizarDisponibilidadHoras);
});

document.getElementById("grupo-horas").addEventListener("click", (e) => {
    if (e.target.classList.contains("boton-opcion") && !e.target.disabled) {
        document
            .querySelectorAll("#grupo-horas .boton-opcion")
            .forEach((btn) => btn.classList.remove("activo"));

        e.target.classList.add("activo");
        document.getElementById("input-hora").value = e.target.dataset.hora;
    }
});


const grupoPersonas = document.getElementById("grupo-personas");
const inputPersonas = document.getElementById("input-comensales");

grupoPersonas.addEventListener("click", (e) => {
    if (e.target.classList.contains("boton-opcion")) {
        // Limpiar selección previa
        document
            .querySelectorAll("#grupo-personas .boton-opcion")
            .forEach((btn) => {
                btn.classList.remove("activo");
            });

        // Marcar el botón clicado
        e.target.classList.add("activo");

        // Guardar el valor de comensales
        inputPersonas.value = e.target.dataset.valor;

        // Actualizar disponibilidad
        actualizarDisponibilidadHoras();
    }
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

        // Validamos que todos los campos estén rellenos
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
            const campos = {
                fecha: document.getElementById("fecha_reserva").value,
                comensales: document.getElementById("input-comensales").value,
                hora: document.getElementById("input-hora").value,
                mesa: form.querySelector('select[name="mesa"]').value,
                tipo: form.querySelector('select[name="tipo"]').value,
                nombre: form.querySelector('input[name="customer_name"]').value,
                apellidos: form.querySelector('input[name="customer_lastname"]')
                    .value,
                email: form.querySelector('input[name="contact_email"]').value,
                telefono: form.querySelector('input[name="contact_phone"]')
                    .value,
                condiciones: form.querySelector('input[name="terms_accepted"]')
                    .checked,
            };

            const mensajeError = document.getElementById("mensaje-error");

            // Validación global
            if (
                Object.values(campos).some((val) => val === "" || val === false)
            ) {
                e.preventDefault();
                mensajeError.innerText =
                    "Todos los campos requeridos deben estar completos.";
                mensajeError.style.display = "block";
                return;
            }

            // Validar email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(campos.email)) {
                e.preventDefault();
                alert("Introduce un email válido.");
                return;
            }

            // Validar teléfono
            const telRegex = /^[0-9]{6,15}$/;
            if (!telRegex.test(campos.telefono)) {
                e.preventDefault();
                alert(
                    "Introduce un teléfono válido (solo números, mínimo 6 dígitos)."
                );
                return;
            }

        });
