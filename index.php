<?php
include("./php/class/envloader.php");
loadEnv(__DIR__ . '/.env'); 
$siteKey = $_ENV['HCAPTCHA_KEY'];

?>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consulta de Becas</title>
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    :root {
      --background-color: #f9f9f9;
      --text-color: #000;
      --card-bg: #fff;
      --border: #0000007a;
    }

    [data-theme="dark"] {
      --background-color: #121212;
      --text-color: #e0e0e0;
      --card-bg: #1e1e1e;
      --border: #ffffff7a;
    }

    body {
      background-color: var(--background-color);
      color: var(--text-color);
    }

    .info,
    .emisiones {
      background-color: var(--card-bg);
      border-radius: 8px;
      padding: 3.5%;
      margin-bottom: 1em;
      box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
      display: grid;
    }

    .info > .icons{
      display: flex;
      align-items: center;
      flex-direction: column;
      gap: 1vh;
      margin-bottom: 3vh;
    }

    #iconbeca {
      width: 30vh;
      /* filter: brightness(2); */
      /* mix-blend-mode: multiply; */
      border-radius: 2vh;
    }

    .info h2,
    .emisiones h3 {
      margin-top: 0;
    }

    .info.sub {
      border: 0.2vh solid var(--border);
      border-radius: 2.2vh;
    }

    .estado {
      display: inline-flex;
      gap: 2vh;
      align-items: flex-end;
    }

    .status-icon-becario {
      width: 65px;
      height: auto;
    }

    .status-icon-bancarizacion {
      width: 65px;
      height: auto;
    }

    .status-text-becario {
      text-size-adjust: auto;
      font-size: xxx-large;
    }

    .status-text-becario.green {
      color: #27ae60;
    }

    .status-icon-becario.yellow{
      color: #FFC700;
    }

    .status-icon-becario.red{
      color: #EB5757;
    }

    .estado svg {
      width: 20px;
      height: 20px;
      fill: currentColor;
    }

    .grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
    }

    .form-container {
      max-width: 500px;
      margin: auto;
    }

    .loading {
      display: none;
    }

    .hidden {
      display: none !important;
    }

    .bloque {
      margin-top: 20px;
      display: grid;
    }

    .bloque > .span-object {
      display: list-item;
      list-style: circle;
      margin-left: 3vh;
    }

    header img {
      max-width: 100%;
      border-radius: 1em;
      margin-bottom: 1em;
      margin-top: -8%;
      overflow: hidden;
    }

    footer {
      margin-top: 2em;
    }

    fieldset {
      padding: 2vh;
      margin: 1vh;
      border: 0.2vh solid var(--border);
      border-radius: 2.2vh;
    }

    .main-conteiner {
      filter: drop-shadow(0.8vh 0.8vh 1vh rgba(0, 0, 0, 0.3));
    }

    .main-form {
      margin-top: 5vh;
      margin-bottom: 15vh;
    }
  </style>
</head>

<body>

  <div class="container my-4">

    <!-- Banner de imagen -->
    <header>
      <img src="img/banner1.jpg" alt="Banner de Beca Benito Juárez">
    </header>

    <h1 id="obj" class="text-center mb-4">Consulta de Becas Benito Juárez</h1>

    <!-- Mensaje informativo superior -->
    <div class="alert alert-info text-center">
      Consulta el calendario de pagos dando <a href="https://www.gob.mx/becasbenitojuarez/articulos/calendario-de-pago-segundo-bimestre-2025-becas-para-el-bienestar" target="_blank">click aquí</a>
    </div>

    <!-- Toggle de tema -->
    <div class="text-end mb-3">
      <div class="form-check form-switch"  style="display: flex; flex-direction: row;">
        <input class="form-check-input" type="checkbox" id="themeToggle" style="margin-right: 1vh;">
        <label class="form-check-label" for="themeToggle">Modo oscuro</label>
      </div>
    </div>

    <!-- FORM -->
    <div class="form-container info main-conteiner main-form">
      <form id="curpForm" onsubmit="return false;">
        <div class="mb-3">
          <h3 for="curpInput" class="form-label"><b>Escribe tu CURP</b></h3>
          <span class="form-text-curp">¿No conoces tu CURP? <a href="https://www.gob.mx/curp/" target="_blank">Consulta tu CURP aquí.</a></span>
          <input type="text" name="CURP" id="curpInput" class="form-control" maxlength="18" required
            style="text-transform: uppercase;" pattern="^[A-Z0-9]{18}$"
            title="Ingresa una CURP válida de 18 caracteres (solo letras y números)">
        </div>

        <div class="mb-3" style="display: flex;justify-content: center;">
          <div class="h-captcha" data-sitekey="<?= $siteKey ?>"></div>
        </div>

        <button id="submitBtn" type="button" class="btn btn-primary w-100">Buscar</button>
      </form>
    </div>

    <!-- Mensaje de carga -->
    <div id="loadingMessage" class="loading text-center my-4">
      <div class="spinner-border text-primary" role="status"></div>
      <p>Cargando tus datos...</p>
    </div>

    <!-- Contenedor de datos API -->
    <div id="apiDataContainer" style="display: none;" class="mt-4">
      <section class="info main-conteiner">
        <section class="icons">
          <img id="iconbeca" src="" alt="Icono de beca">
          <div class="estado" id="estado"></div>
        </section>
        <div><strong>Programa:</strong> <span id="programa"></span></div>
        <div><strong>CURP del beneficiario:</strong> <span id="curp"></span></div>
      </section>

      <section class="info main-conteiner">
        <h2>Información General</h2><br>
        <div class="grid">
          <!-- <div><strong>Programa:</strong> <span id="programa"></span></div> -->
          <div><strong>CCT:</strong> <span id="cct"></span></div>
          <div><strong>Fecha de Nacimiento:</strong> <span id="nacimiento"></span></div>
          <div><strong>Periodo de Incorporación:</strong> <span id="periodo"></span></div>
          <div><strong>Identificador del beneficiario:</strong> <span id="integrante"></span></div>
          <div><strong>Total Pagos:</strong> <span id="totalPagos"></span></div>
          <div><strong>Máximo de Pagos:</strong> <span id="maximoPagos"></span></div>
          <div><strong>Dirección de Adscripción:</strong> <span id="direccionads"></span></div>
        </div>
        <div class="bloque hidden" id="explicacionBaja">
          <strong>Motivo de baja:</strong> <span id="motivoBaja"></span>
        </div>
      </section>

      <div id="bancarizacionContainer" class="mt-4">
        <section class="info main-conteiner">
          <h1 class="mb-3">Bancarización</h1>
          <div class="bancarizacionfases p-3 border rounded">
              <!-- Aquí se insertarán dinámicamente las fases -->
          </div>
        </section>
      </div>

      <section class="emisiones main-conteiner" id="contenedor-emisiones">
        <!-- Emisiones dinámicas -->
        <h1>Emisiones</h1>
      </section>
    </div>

    <!-- Footer -->
    <footer>
          <b>Ningún dato de este sitio es recopilado, ¡darle un mal uso podría bloquearte de seguir usandolo!</b>
          <br>
          Este sitio web fue creado desde que irresponsablemente por la coordinación de Becas Benito Juarez dejó en mantenimiento el sitio web, dejando sin información a los nuevos integrantes/reintegrantes becarios sin la información acerca de sus tarjetas, citas, etc. por más de un MES, eso es demasiado tiempo.<br>
          Soy un universitario (igual que posiblemente tú), así que nada de aquí es perfecto, posiblemente falte algo o alguna información sea erronea.<br>
          Si usted sabe programar y desea apoyar a mejorarlo sin recibir ningún apoyo monetario más que apoyar a los jóvenes estudiantes, puedes hacer hacerlo dando <a href="https://github.com/MigMatos/BecasBJ-Website">click aquí</a>. <i>(¡Viva el código libre!)</i>
          <br>
          <b>Si deseas contactarme personalmente este es mi correo: <a href="mailto:matitos2317@gmail.com">matitos2317@gmail.com</a></b>
    </footer>

  </div>
  
  <script src='https://www.hCaptcha.com/1/api.js' async defer></script>
  <script src='js/jquery-3.7.1.min.js' async defer></script>

  <script>
    // Modo oscuro persistente
    const toggle = document.getElementById('themeToggle');
    const html = document.documentElement;
    let api = {};
    toggle.checked = localStorage.getItem('theme') === 'dark';
    html.setAttribute('data-theme', toggle.checked ? 'dark' : 'light');

    toggle.addEventListener('change', () => {
      const mode = toggle.checked ? 'dark' : 'light';
      html.setAttribute('data-theme', mode);
      localStorage.setItem('theme', mode);
    });

    window.addEventListener('load', () => {
        if (/Mobi|Android|iPhone|iPod|BlackBerry|Windows Phone/i.test(navigator.userAgent)) {
            // Pues vácio jaja
        } else {
            const objetivo = document.getElementById("obj"); 
            if (objetivo) objetivo.scrollIntoView({ behavior: "smooth" });
        }
    });

    // Validación y envío
    const form = document.getElementById('curpForm');
    const submitBtn = document.getElementById("submitBtn");
    const loading = document.getElementById('loadingMessage');
    const container = document.getElementById('apiDataContainer');
    let hcaptchaVal = "";

    submitBtn.addEventListener('click', async e => {

      try {hcaptchaVal = document.querySelector('[name="h-captcha-response"]').value;}
      catch(e){console.log(e);}

      if (!form.checkValidity()) {
          form.reportValidity();
          return;
      }
      if (hcaptchaVal === "") {

          alert("Porfavor completa el Captcha");
          return;
      }

      loading.style.display = 'block';

      const formData = new FormData(form);
      const data = Object.fromEntries(formData.entries());
      data.CURP = data.CURP.toUpperCase();

      try {
          const response = await fetch('php/usuario.php', {
              method: 'POST',
              body: new URLSearchParams(data),
          });
          api = await response.text();
          console.log(api);
          api = JSON.parse(api);
          if(api.status == "400"){
            alert(api.error);
            container.style.display = 'none';
            return;
          }
          renderInfo();
          renderEmisiones();
          container.style.display = 'block';
          const objetivo = document.getElementById("loadingMessage"); if (objetivo) objetivo.scrollIntoView({ behavior: "smooth" });
      } catch (err) {
          alert("Error al consultar datos. Intenta nuevamente.");
          container.style.display = 'none';
          console.error(err)
      } finally {
          loading.style.display = 'none';
      }
    });

  // JSON DATA


    const estadoIcons = {
      'ACTIVA': 'check.svg',
      'EN REVISION': 'alert.svg',
      'CAMBIO DE TITULAR': 'alert.svg',
      'VERIFICACION RENAPO': 'alert.svg',
      'BAJA': 'x-status-baja.svg'
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

    function renderInfo() {
      const d = api.datos;
      document.getElementById('curp').textContent = d.CURP;
      document.getElementById('integrante').textContent = d.INTEGRANTE_ID;
      document.getElementById('iconbeca').setAttribute("src",`img/becaicons/${d.PROGRAMA || ''}.jpg`);
      document.getElementById('estado').innerHTML = `<img src="img/icons/${estadoIcons[d.SITUACION_INSCRIPCION_ACTUAL] || 'alert.svg'}" class="status-icon-becario"><span class="status-text-becario ${colorStatusTXT[d.SITUACION_INSCRIPCION_ACTUAL] || 'yellow'}">${d.SITUACION_INSCRIPCION_ACTUAL}</span>`;
      document.getElementById('programa').textContent = programas[d.PROGRAMA] || 'Beca Desconocida';
      document.getElementById('cct').textContent = d.CCT;
      document.getElementById('nacimiento').textContent = d.FECHA_NACIMIENTO_BECARIO;
      document.getElementById('periodo').textContent = d.PERIODO_INCORPORACION;
      document.getElementById('totalPagos').textContent = d.TOTAL_PAGOS;
      document.getElementById('maximoPagos').textContent = d.MAXIMO_PAGOS;
      document.getElementById('direccionads').textContent = d.DIRECCION_ADSCRIPCION;
      if (d.SITUACION_INSCRIPCION_ACTUAL === 'BAJA' && d.EXPLICACION_MOTIVO_BAJA) {
        document.getElementById('explicacionBaja').classList.remove('hidden');
        document.getElementById('motivoBaja').textContent = d.EXPLICACION_MOTIVO_BAJA;
      }

      mostrarFasesBancarizacion(d);
    }

    function renderEmisiones() {
      const emisionesCont = document.getElementById('contenedor-emisiones');
      const datos = api.datos;
      const emisionesPorAnio = {};

      for (let key in datos) {

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

      for (let anio in emisionesPorAnio) {
        let contenedorAnio = document.createElement('div');
        contenedorAnio.className = 'info sub';
        contenedorAnio.innerHTML = `<h3>${anio}</h3>`;
        const emisiones = emisionesPorAnio[anio];

        for (let num in emisiones) {
          const em = emisiones[num];
          const tieneDatos = Object.values(em).some(v => v !== null && v !== '' && v !== '...');
          if (!tieneDatos) continue;
          console.log(em);
          if(em.PAGADO == "0") em.PAGADO = "NO PAGADO"; else em.PAGADO = "PAGADO";
          if(em.EMISION_APOYO == null) continue

          contenedorAnio.innerHTML += `
            <fieldset class="bloque"><legend><strong>Emisión #${num}</strong></legend>
              <span ${!em.FORMA_ENTREGA_APOYO || em.FORMA_ENTREGA_APOYO.trim() === '' ? 'class="hidden"' : 'class="span-object"'}><b>Forma Entrega:</b> ${em.FORMA_ENTREGA_APOYO || 'N/A'}</span>
              <span ${!em.INSTITUCION_LIQUIDADORA || em.INSTITUCION_LIQUIDADORA.trim() === '' ? 'class="hidden"' : 'class="span-object"'}><b>Institución:</b> ${em.INSTITUCION_LIQUIDADORA || 'N/A'}</span>
              <span ${!em.PAGADO || em.PAGADO.trim() === '' ? 'class="hidden"' : 'class="span-object"'}><b>Pagado:</b> ${em.PAGADO || 'N/A'}</span>
              <span ${!em.FECHA_PAGO || em.FECHA_PAGO.trim() === '' ? 'class="hidden"' : 'class="span-object"'}><b>Fecha de Pago:</b> ${em.FECHA_PAGO || 'N/A'}</span>
              <span ${!em.PERIODOS || em.PERIODOS.trim() === '' ? 'class="hidden"' : 'class="span-object"'}><b>Periodos:</b> ${em.PERIODOS || 'N/A'}</span>
              <span ${!em.ESTATUS_PAGO || em.ESTATUS_PAGO.trim() === '' ? 'class="hidden"' : 'class="span-object"'}><b>Estatus:</b> ${em.ESTATUS_PAGO || 'N/A'}</span>
              <span ${!em.FECHA_PROGRAMADA_SOT || em.FECHA_PROGRAMADA_SOT.trim() === '' ? 'class="hidden"' : 'class="span-object"'}><b>Fecha Programada:</b> ${em.FECHA_PROGRAMADA_SOT || 'N/A'}</span>
              <span ${!em.DIR_PROGRAMADA_SOT || em.DIR_PROGRAMADA_SOT.trim() === '' ? 'class="hidden"' : 'class="span-object"'}><b>Dirección Programada:</b> ${em.DIR_PROGRAMADA_SOT || 'N/A'}
            </fieldset>
          `;
        }

        if (contenedorAnio.innerHTML !== `<h3>${anio}</h3>`) {
          emisionesCont.appendChild(contenedorAnio);
        }
      }
    }

    function mostrarFasesBancarizacion(d) {
        const fasesContainer = document.querySelector("#bancarizacionContainer .bancarizacionfases");
        fasesContainer.innerHTML = ""; // Limpiar contenido previo

        // Mostrar alerta si aplica
        if (d.BANCARIZACION_RECHAZADA == 1 && d.PROGRAMA == "BUEEMS") {
            const alerta = document.createElement("div");
            alerta.className = "alert alert-warning mb-3";
            alerta.innerHTML = `
                A partir de junio de 2024, revisa este apartado para saber cuándo y dónde recoger tu medio de pago.<br>
                Lleva tu documentación completa. Si eres menor de edad, ve acompañado/a de tu tutor.
            `;
            fasesContainer.appendChild(alerta);
        }

        const fases = [];
        let mostrarFase2 = false;
        let mostrarFase3 = false;

        // FASE 1 (siempre que haya BANCARIZACION)
        fases.push({
            nombre: "PENDIENTE",
            rutaIcono: "img/icons/relojalert.png",
            activa: true,
            i: 1
        });

        if (Array.isArray(d.BANCARIZACION)) {
            for (const banco of d.BANCARIZACION) {
                const medioPendiente = banco.DESC_EST_FORMZ_UPD;
                const estrategia = banco.TIPO_ESTRATEGIA_DGOVAC;
                const fechaHora = banco.FECHA_HORA;

                const tieneFecha = fechaHora && fechaHora.trim() !== "";

                if (medioPendiente === "MEDIO PENDIENTE DE ENTREGAR" && estrategia === "SUCURSAL" && tieneFecha) {
                    mostrarFase2 = true;
                }

                if (medioPendiente === "MEDIO ENTREGADO / FORMALIZADO") {
                    mostrarFase3 = true;
                }
            }
        }

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
          
        // MOBILE DESING
        if (/Mobi|Android|iPhone|iPod|BlackBerry|Windows Phone/i.test(navigator.userAgent)) {
            const elementos = document.querySelectorAll('[fase-bancarizacion]');
            elementos.forEach((el, i) => {
                if (i !== elementos.length - 1) {
                    el.classList.add('hidden');
                }
            });
        }
    }


  </script>

</body>

</html>