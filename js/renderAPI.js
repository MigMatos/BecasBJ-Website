// Inicializadores
const estadoIcons = {
    'ACTIVA': 'check.svg',
    'EN REVISION': 'alert.svg',
    'CAMBIO DE TITULAR': 'alert.svg',
    'VERIFICACION RENAPO': 'alert.svg',
    'BAJA': 'baja.svg'
};

const colorStatusTXT = {
    'ACTIVA': 'green',
    'BAJA': 'red'
}

const programas = {
    'BASICA': 'Becas de Educación Básica para el Bienestar Benito Juárez',
    'BUEEMS': 'Beca Universal para el Bienestar Benito Juárez de Educación Media Superior (BUEEMS)',
    'JEF': 'Beca para el Bienestar Benito Juárez de Educación Superior (JEF)'
};

/*  
    la API del gobierno, sin dudas está hecho un desastre jaja
    hay cosas que de verdad no se comprenden o que valores incluso 
    puede devolver por ser tan drástico, así que el código aquí 
    está generalizado y faltan algunos cosillas, pero funciona
    para su próposito original y funciona súper bien hasta los datos
    del 2023, datos inferiores son muy variados y hasta erroneos.
*/

// Apartado de información en general
function renderInfo() {
    const d = api.datos;
    let SITUACION_TXT_FINAL = "SIN DATOS";
    document.getElementById('curp').textContent = d.CURP;
    document.getElementById('integrante').textContent = d.INTEGRANTE_ID;
    document.getElementById('iconbeca').setAttribute("src", `img/becaicons/${d.PROGRAMA || 'DEFAULT'}.jpg`);
    document.getElementById('programa').textContent = programas[d.PROGRAMA] || 'Beca Desconocida';
    document.getElementById('cct').textContent = d.CCT;
    document.getElementById('nacimiento').textContent = d.FECHA_NACIMIENTO_BECARIO;
    document.getElementById('periodo').textContent = d.PERIODO_INCORPORACION;
    document.getElementById('totalPagos').textContent = d.TOTAL_PAGOS;
    document.getElementById('maximoPagos').textContent = d.MAXIMO_PAGOS;
    document.getElementById('direccionads').textContent = d.DIRECCION_ADSCRIPCION;
    if (d.SITUACION_INSCRIPCION_ACTUAL === 'BAJA') {
        if (d.EXPLICACION_MOTIVO_BAJA) {
            document.getElementById('explicacionBaja').classList.remove('hidden');
            document.getElementById('motivoBaja').textContent = d.EXPLICACION_MOTIVO_BAJA;
            document.getElementById('motivoFundamentacion').textContent = d.FUNDAMENTACION;
            document.getElementById('etiquetaBaja').textContent = d.ETIQUETA_BAJA;
            document.getElementById('fechaBaja').textContent = d.EJERCICIO_FISCAL_BAJA;
        }
        SITUACION_TXT_FINAL = "BAJA"
    } else if (d.SITUACION_INSCRIPCION_ACTUAL == 'CAMBIO TITULAR') {
        SITUACION_TXT_FINAL = "CAMBIO DE TITULAR"
        document.getElementById('explicacionAny').classList.remove('hidden');
        document.getElementById('textoAlerta').textContent = "Está pendiente una revisión y actualización del representante de tu familiar o tutor.";
        document.getElementById('textoAlertaBUZON').classList.remove('hidden');
    } else if (d.SITUACION_INSCRIPCION_ACTUAL == 'VERIFICACION RENAPO') {
        SITUACION_TXT_FINAL = "VERIFICACION DE RENAPO"
        document.getElementById('explicacionAny').classList.remove('hidden');
        document.getElementById('textoAlerta').textContent = "Está pendiente una revisión y validación de tu CURP con RENAPO.";
        document.getElementById('textoAlertaBUZON').classList.remove('hidden');
    } else if (d.SITUACION_INSCRIPCION_ACTUAL == 'EN REVISION') {
        SITUACION_TXT_FINAL = "EN REVISION"
        document.getElementById('explicacionAny').classList.remove('hidden');
        document.getElementById('textoAlerta').textContent = "Está pendiente una revisión de tu información o solicitud.";
        document.getElementById('textoAlertaBUZON').classList.remove('hidden');
    } else if (d.SITUACION_INSCRIPCION_ACTUAL == 'ACTIVA') {
        SITUACION_TXT_FINAL = "ACTIVA"
    } else {
        SITUACION_TXT_FINAL = d.SITUACION_INSCRIPCION_ACTUAL;

        // Si se vuelve a consultar una CURP, esto actualiza los datos
        document.getElementById('textoAlertaBUZON').classList.add('hidden');
        document.getElementById('explicacionBaja').classList.add('hidden');
        document.getElementById('explicacionAny').classList.add('hidden');
    }

    // Mostrar al final la imagen de situación debido al nuevo render de situación
    document.getElementById('estado').innerHTML = `<img src="img/icons/${estadoIcons[d.SITUACION_INSCRIPCION_ACTUAL] || 'alert.svg'}" class="status-icon-becario"><span class="status-text-becario ${colorStatusTXT[d.SITUACION_INSCRIPCION_ACTUAL] || 'yellow'}">${SITUACION_TXT_FINAL}</span>`;
}

// Periodos que se pueden obtener por cada año, número de emisión y ID 
// (es decir los meses, ya que puede variar por estados) que devuelve la API
function obtenerPeriodoTexto(anio, emision, id) {

    let texto = "(SIN DATOS)"; // Por defecto
    id = String(id);
    emision = parseInt(emision);
    anio = parseInt(anio);

    const mesesPorEmision = {
        2023: {
            1: ["ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO"],
            2: ["MARZO", "ABRIL", "MAYO", "JUNIO"],
            3: ["SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"]
        },
        2024: {
            1: {
                "-1": "MAYO 2019 a JUNIO 2024",
                default: ["ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO"]
            },
            2: {
                default: ["SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"]
            }
        },
        2025: {
            1: {
                "-1": "MAYO 2019 a JUNIO 2024",
                default: ["ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO"]
            },
            2: {
                default: ["MARZO", "ABRIL", "MAYO", "JUNIO"]
            },
            3: {
                default: ["MAYO", "JUNIO", "JULIO", "AGOSTO"]
            }
        }
    };

    const datosAnio = mesesPorEmision[anio];
    if (!datosAnio || !datosAnio[emision]) return texto;

    const datosEmision = datosAnio[emision];

    if (typeof datosEmision === "object" && !Array.isArray(datosEmision)) {
        if (id === "-1" && datosEmision["-1"]) {
            return datosEmision["-1"];
        } else if (datosEmision.default) {
            let index = parseInt(id) - 1;
            if (index >= 0 && index < datosEmision.default.length) {
                texto = datosEmision.default.slice(0, index + 1).join(", ");
                return texto;
            }
        }
    } else if (Array.isArray(datosEmision)) {
        let index = parseInt(id) - 1;
        if (index >= 0 && index < datosEmision.length) {
            texto = datosEmision.slice(0, index + 1).join(", ");
            return texto;
        }
    }

    return texto;
}

// Apartado de becas emitidas
function renderEmisiones() {
    const emisionesCont = document.getElementById('contenedor-emisiones');
    emisionesCont.innerHTML = "";
    const datos = api.datos;
    const emisionesPorAnio = {};

    for (let key in datos) {
        // pequeñitoooo regex
        const match = key.match(/^(EMI(?:SION)?|FORMA_ENTREGA_APOYO|INSTITUCION_LIQUIDADORA|PAGADO|FECHA_PAGO|PERIODOS|ESTATUS_PAGO|EMISION_APOYO|FECHA_PROGRAMADA_SOT|DIR_PROGRAMADA_SOT)_?(\d{2})(?:EMI(?:SION)?)(\d)$/);
        console.log(key);
        if (match) {
            console.log(match);
            const anio = '20' + match[2];
            const numero = match[3];
            const tipo = match[1];
            if (!emisionesPorAnio[anio]) emisionesPorAnio[anio] = {};
            if (!emisionesPorAnio[anio][numero]) emisionesPorAnio[anio][numero] = {};
            emisionesPorAnio[anio][numero][tipo] = datos[key];
        }
    }

    // pequeña función para 2021 y 2022
    function convertBtoT(b){if(b == 0) return "NO"; else return "SI";}


    for (let anio in emisionesPorAnio) {
        let contenedorAnio = document.createElement('div');
        contenedorAnio.className = 'info sub';
        contenedorAnio.innerHTML = `<h3>${anio}</h3>`;
        const emisiones = emisionesPorAnio[anio];

        for (let num in emisiones) {
            const em = emisiones[num];
            console.log(anio);
            console.log(num);
            console.log(em);
            console.log("--------");
            const tieneDatos = Object.values(em).some(v => v !== null && v !== '' && v !== '...');
            if (!tieneDatos && parseInt(anio) === new Date().getFullYear() || tieneDatos && (em.EMISION_APOYO == null && parseInt(anio) > 2023)) {
                contenedorAnio.innerHTML += `<fieldset class="bloque"><legend><strong>Emisión #${num}</strong></legend>
                  <span>No tienes o aún no tienes asignado esta emisión con periodos de ${obtenerPeriodoTexto(anio, num, 2)}.</span>
              </fieldset>`;
                continue;
            } else if (!tieneDatos) { continue };

            if (em.PAGADO == "0") em.PAGADO = "NO PAGADO"; else em.PAGADO = "PAGADO";
            // if(em.EMISION_APOYO == null && parseInt(anio) > 2023) {
            //     continue;
            // }

            contenedorAnio.innerHTML += `
          <fieldset class="bloque"><legend><strong>Emisión #${num}</strong></legend>
            <span ${!em.FORMA_ENTREGA_APOYO || em.FORMA_ENTREGA_APOYO.trim() === '' ? 'class="hidden"' : 'class="span-object"'}><b>Forma de pago:</b> ${em.FORMA_ENTREGA_APOYO || 'N/A'}</span>
            <span ${!em.INSTITUCION_LIQUIDADORA || em.INSTITUCION_LIQUIDADORA.trim() === '' ? 'class="hidden"' : 'class="span-object"'}><b>Recibirás el pago en medio de:</b> ${em.INSTITUCION_LIQUIDADORA || 'N/A'}</span>
            <span ${!em.PAGADO || em.PAGADO.trim() === '' ? 'class="hidden"' : 'class="span-object"'}><b>Pago de la emisión efectuado:</b> ${em.PAGADO || 'N/A'}</span>
            <span ${!em.FECHA_PAGO || em.FECHA_PAGO.trim() === '' ? 'class="hidden"' : 'class="span-object"'}><b>Fecha de Pago:</b> ${em.FECHA_PAGO || 'N/A'}</span>
            <span ${!em.ESTATUS_PAGO || em.ESTATUS_PAGO.trim() === '' ? 'class="hidden"' : 'class="span-object"'}><b>Situación actual del pago:</b> ${em.ESTATUS_PAGO || 'N/A'}</span>
            <span ${!em.PERIODOS || em.PERIODOS.trim() === '' ? 'class="hidden"' : 'class="span-object"'}><b>Periodos a pagar:</b> ${obtenerPeriodoTexto(anio, num, em.PERIODOS)}</span>
            <span elem-type="sensibledata" ${!em.FECHA_PROGRAMADA_SOT || em.FECHA_PROGRAMADA_SOT.trim() === '' ? 'class="hidden"' : 'class="span-object"'}><b>Fecha Programada:</b> ${convertBtoT(em.FECHA_PROGRAMADA_SOT) || 'N/A'}</span>
            <span elem-type="sensibledata" ${!em.DIR_PROGRAMADA_SOT || em.DIR_PROGRAMADA_SOT.trim() === '' ? 'class="hidden"' : 'class="span-object"'}><b>Dirección Programada:</b> ${convertBtoT(em.DIR_PROGRAMADA_SOT) || 'N/A'}</span>
            
          </fieldset>
        `;
        }

        if (contenedorAnio.innerHTML !== `<h3>${anio}</h3>`) {
            emisionesCont.appendChild(contenedorAnio);
        }
    }
}


// Apartado de Bancarización
function renderFasesBancarizacion() {
    const d = api.datos;
    const fasesContainer = document.querySelector("#bancarizacionContainer .bancarizacionfases");
    fasesContainer.innerHTML = ""; // Limpiar contenido previo
    const fases = [];
    let mostrarFase1 = true;
    let mostrarFase2 = false;
    let mostrarFase3 = false;
    let alerta = "";
    // Mostrar alerta si aplica
    if (d.BANCARIZACION_RECHAZADA == 1 && d.PROGRAMA == "BUEEMS") {
        alerta = document.createElement("div");
        alerta.className = "alert alert-warning mb-3";
        alerta.innerHTML = `
              A partir de junio de 2024, revisa este apartado para saber cuándo y dónde recoger tu medio de pago.<br>
              Lleva tu documentación completa. Si eres menor de edad, ve acompañado/a de tu tutor.
          `;
        fasesContainer.appendChild(alerta);

    } else if (d.BANCARIZACION_RECHAZADA == 0 && d.BANCARIZACION.length == 0) {
        alerta = document.createElement("div");
        alerta.className = "alert alert-danger mb-3";
        alerta.innerHTML = `
              No se está llevando a cabo ninguna bancarización.
          `;
        fasesContainer.appendChild(alerta);
        mostrarFase1 = false;
    } else if (d.BANCARIZACION == "PENDIENTE") {
        alerta = document.createElement("div");
        alerta.className = "alert alert-primary mb-3";
        alerta.innerHTML = `
            <b>¡Revisa constantemente este apartado!</b><br>
            Aún no te hemos asignado una fecha de entrega y lugar de entrega, revisa constantemente este apartado.
          `;
        fasesContainer.appendChild(alerta);
    }

    if (mostrarFase1) {
        fases.push({
            nombre: "PENDIENTE",
            rutaIcono: "img/icons/relojalert.png",
            activa: true,
            i: 1
        });
    }

    if (Array.isArray(d.BANCARIZACION)) {
        for (const banco of d.BANCARIZACION) {
            const ac = banco.AC; // No tengo idea de que es pero está asignado para una función
            const medioPendiente = banco.DESC_EST_FORMZ_UPD;
            const estrategia = banco.TIPO_ESTRATEGIA_DGOVAC;
            const fechaHora = banco.FECHA_HORA;
            const fechaHoraProgramada = banco.FECHA_PROGRAMADA;
            const remesa = banco.NUMERO_REMESA;
            const sucursal = banco.SUCURSAL;
            const direccionSucursal = banco.DIRECCION_SUCURSAL;
            let inFecha = "";
            let longitudFechaBanco = "";
            let fecha = "";
            let hora = "";

            const tieneFecha = fechaHora && fechaHora.trim() !== "";
            if (tieneFecha) {
                inFecha = fechaHora.indexOf(",");
                lonFechaBanco = fechaHora.length;
                fecha = fechaHora.substring(0, inFecha);
                hora = fechaHora.substring(inFecha + 1, lonFechaBanco);
            }

            if (medioPendiente === "MEDIO PENDIENTE DE ENTREGAR" && estrategia != "" && tieneFecha) {
                mostrarFase2 = true;
                alerta = document.createElement("div");
                alerta.className = "alert alert-warning mb-3";
                alerta.innerHTML = `
                      Tienes una fecha asignada para recoger tu tarjeta el día <span elem-type="sensibledata">${fecha}</span> con horario de <span elem-type="sensibledata">${hora}</span> para recoger por medio de <span elem-type="sensibledata">${sucursal}</span> con dirección asignada en <span elem-type="sensibledata">${direccionSucursal}</span>.<br>
                      Remesas asignadas: <span elem-type="sensibledata">${remesa}</span><br>
                      <b>¡RECUERDA LLEVAR TU DOCUMENTACION COMPLETA!</b> 
                  `;
                fasesContainer.appendChild(alerta);

            } else if (medioPendiente === "MEDIO PENDIENTE DE ENTREGAR" && !tieneFecha) {
                alerta = document.createElement("div");
                alerta.className = "alert alert-info mb-3";
                alerta.innerHTML = `
                      ¡Tu tarjeta ya casi llega a tu localidad!
                      <b>¡REVISA CONSTANTEMENTE PARA VER UNA FECHA ASIGNADA A LA ENTREGA DE TU TARJETA!</b> 
                  `;
                fasesContainer.appendChild(alerta);
            }

            if (medioPendiente === "MEDIO ENTREGADO / FORMALIZADO") {
                if (ac == 2) {
                    alerta = document.createElement("div");
                    alerta.className = "alert alert-danger mb-3";
                    alerta.innerHTML = `
                      <b>¡NECESITAS CAMBIAR TU NIP DE TU TARJETA DEL BANCO BIENESTAR!</b><br>
                      ¡La dispersiones pendientes de tu beca no se activarán hasta realizar esta acción!<br>
                      Deberás acudir a una ventanilla de atención el día <span elem-type="sensibledata">${fechaHoraProgramada}</span> en la hora segerida de <span elem-type="sensibledata">${hora}</span> horas de algún Banco Bienestar con tu tarjeta, identificación oficial y tu NIP actual que está impreso en el sobre donde recibiste tu Tarjeta Bienestar.
                  `;
                } else {
                    alerta = document.createElement("div");
                    alerta.className = "alert alert-success mb-3";
                    alerta.innerHTML = `
                      <b>¡BANCARIZACIÓN COMPLETADA!</b><br>
                      Conoce las próximas fechas de las becas a emitir en la sección de "Becas Emitidas"<br>
                      Si necesitas consultar tu saldo o movimientos descarga la App Banco del Bienestar.
                  `;
                }
                fasesContainer.appendChild(alerta);
                mostrarFase2 = true;
                mostrarFase3 = true;
            }
        }
    }

    // Posible información erronea?
    if (d.PERIODO_INCORPORACION === "SEP-2023") {
        mostrarFase2 = true;
        mostrarFase3 = true;
    }

    if (mostrarFase2) {
        fases.push({
            nombre: "CITA PENDIENTE",
            rutaIcono: "img/icons/citaasignado.png",
            activa: true,
            i: 2
        });
    }

    if (mostrarFase3) {
        fases.push({
            nombre: "TARJETA ENTREGADA",
            rutaIcono: "img/icons/tarjeta_bienestar_2.png",
            activa: true,
            i: 3
        });
    }

    // Generar HTML de fases
    const faseHTML = fases.map(fase => `
          <div class="text-center mx-2" fase-bancarizacion="${fase.i}">
              <img src="${fase.rutaIcono}" class="status-icon-bancarizacion mb-1" alt="${fase.nombre}">
              <div class="${fase.activa ? 'fw-bold text-success' : 'text-muted'}">${fase.nombre}</div>
          </div>
      `).join("<h1 style='-webkit-text-stroke: thick;' fase-bancarizacion='0'>• • •</h1>");

    fasesContainer.innerHTML += `
          <div class="d-flex justify-content-center flex-wrap gap-3 mt-2" style="align-items: center;">
              ${faseHTML}
          </div>
      `;

    // Unicamente para telefonos
    // No muestra toda las fases de bancarización para no ocupar toda la pantalla y mal entender la información
    if (/Mobi|Android|iPhone|iPod|BlackBerry|Windows Phone/i.test(navigator.userAgent)) {
        const elementos = document.querySelectorAll('[fase-bancarizacion]');
        elementos.forEach((el, i) => {
            if (i !== elementos.length - 1) {
                el.classList.add('hidden');
            }
        });
    }
}