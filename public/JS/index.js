// index.js
let disponibilidadMes = {}; // Objeto donde guardaremos TODA la disponibilidad del año completo
const hoy = new Date();
hoy.setHours(0, 0, 0, 0);
const fechaLimite = new Date();
fechaLimite.setFullYear(hoy.getFullYear() + 1);

const picker = new Litepicker({
    element: document.getElementById("inline-calendar"),
    inlineMode: true,
    singleMode: true,
    format: "YYYY-MM-DD",
    lang: currentLang === "en" ? "en-US" : "es-ES",
    autoApply: true,
    minDate: "2025-01-01",
    maxDate: fechaLimite,
    setup: (picker) => {
        picker.on("render", () => {
            pintarDiasPasados();
            actualizarDisponibilidadHoras();
        });

        picker.on("selected", (date) => {
            document.getElementById("fecha_reserva").value =
                date.format("YYYY-MM-DD");
            document.getElementById("formulario-reserva").style.display =
                "block";
            actualizarDisponibilidadHoras();
        });
    },
});

// Cargar disponibilidad del mes actual y los 12 siguientes
async function precargarDisponibilidad() {
    const restauranteId = document.getElementById("data-restaurante").dataset.restauranteId;
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);
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

            console.log(`Aforo cargado para ${year}-${month}`, data);
        } catch (err) {
            console.error("Error al precargar disponibilidad:", err);
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
    const hoyFecha = new Date();
    const fechaSeleccionada = new Date(selectedDate);
    const esHoy = hoyFecha.toDateString() === fechaSeleccionada.toDateString();

    botonesHora.forEach((boton) => {
        const hora = boton.dataset.hora;
        const franjas = obtenerFranjasDesdeHora(hora);

        let disponible = franjas.every((franja) => {
            const ocupadas = disponibilidadMes[selectedDate]?.[franja] ?? 0;
            return ocupadas + numPersonas <= maxCapacity;
        });

        if (esHoy) {
            const ahora = new Date();
            const [h, m] = hora.split(":").map(Number);
            const horaReserva = new Date();
            horaReserva.setHours(h, m, 0, 0);

            if (horaReserva <= ahora) {
                disponible = false;
            }
        }

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
               mensajeError.innerText = mensajes.required_fields[currentLang];
                mensajeError.style.display = "block";
                return;
            }

            // Validar email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(campos.email)) {
                e.preventDefault();
                alert(mensajes.invalid_email[currentLang]);
                return;
            }

            // Validar teléfono
            const telRegex = /^[0-9]{7,12}$/;
            if (!telRegex.test(campos.telefono)) {
                e.preventDefault();
                alert(mensajes.invalid_phone[currentLang]);
                return;
            }

        });


function toggleChatbot() {
    const body = document.getElementById("chatbot-body");
    const toggle = document.getElementById("toggle-chat");
    const isVisible = body.style.display === "block";
    body.style.display = isVisible ? "none" : "block";
    toggle.textContent = isVisible ? "+" : "−";
}

async function enviarMensaje() {
    console.log(" Idioma detectado:", navigator.language);

    // 💡 Asegurarse de que el chatbot esté desplegado
    const body = document.getElementById("chatbot-body");
    if (body.style.display === "none" || body.style.display === "") {
        toggleChatbot(); // Despliega el chatbot si estaba cerrado
    }

    const input = document.getElementById("chatbot-input");
    const mensaje = input.value.trim();
    if (!mensaje) return;

    const messages = document.getElementById("chatbot-messages");
    const msgUser = document.createElement("div");
    msgUser.className = "message user";
    msgUser.textContent = mensaje;
    messages.appendChild(msgUser);

    input.value = "";

    const msgBot = document.createElement("div");
    msgBot.className = "message bot";
    msgBot.textContent = "Pensando...";
    messages.appendChild(msgBot);

    // Scroll automático hacia abajo
    messages.scrollTop = messages.scrollHeight;

    // Detectar idioma automáticamente
    const lang = currentLang; // usa el idioma seleccionado en Laravel

    try {
        const response = await fetch("/chatbot/enviar", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({ message: mensaje, lang: lang }),
        });

        const data = await response.json();

        if (data.error) {
            msgBot.textContent =
                "Error al procesar la respuesta: " + data.error;
        } else if (data.choices && data.choices[0]?.message?.content) {
            msgBot.textContent = data.choices[0].message.content;
        } else {
            msgBot.textContent = "Respuesta no válida del chatbot.";
        }
    } catch (error) {
        msgBot.textContent = "Error al conectar con el servidor.";
        console.error(error);
    }

    // Scroll automático después de la respuesta
    messages.scrollTop = messages.scrollHeight;
}
