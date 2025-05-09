<?php
    include("./php/class/envloader.php");
    loadEnv(__DIR__ . '/.env');
    $siteKey = strval($_ENV['HCAPTCHA_KEY']);
    $siteVersion = strval($_ENV['VERSION']); // No olvidar cambiar esto en git xD
?>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- HEAD -->
    <title>Buscador de Becas Benito Juárez - NO OFICIAL</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <meta name="description" content="Buscador NO OFICIAL de estatus o becas de Benito Juarez, Rita Cetina, Etc">

    <!-- Como siempre bootstrap god dandonos sus diseñitos simples pero bonitos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="./css/inicio.css?v=<?= $siteVersion ?>" rel="stylesheet">

    <!-- Open Graph (para Facebook, WhatsApp, Discord, etc) -->
    <meta property="og:title" content="Buscador de Becas Benito Juárez - NO OFICIAL">
    <meta property="og:description" content="Buscador NO OFICIAL de estatus o becas de Benito Juarez, Rita Cetina, Etc">
    <meta property="og:image" content="https://consultarbeca.x10.mx/img/icons/tarjeta_bienestar_2.png">
    <meta property="og:url" content="https://consultarbeca.x10.mx/">
    <meta property="og:type" content="website">

    <!-- Twitter Card (opcional, para Twitter/X) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Buscador de Becas Benito Juárez - NO OFICIAL">
    <meta name="twitter:description" content="Buscador NO OFICIAL de estatus o becas de Benito Juarez, Rita Cetina, Etc">
    <meta name="twitter:image" content="https://consultarbeca.x10.mx/img/icons/tarjeta_bienestar_2.png">
    <meta name="twitter:url" content="https://consultarbeca.x10.mx/">

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
            <b>Este sitio web NO ES OFICIAL y NO SUPLANTA al buscador de estatus oficial.</b><br>
            <a href="#seccion1footer">Lea más acerca de mí y este sitio web aquí</a><br>
            <b>Consulta el calendario de pagos dando <a href="https://www.gob.mx/becasbenitojuarez/articulos/calendario-de-pago-segundo-bimestre-2025-becas-para-el-bienestar" target="_blank">click aquí</a></b>
        </div>

        <!-- Toggle de tema -->
        <div class="text-end mb-3" style="display: flex;gap: 5%;flex-wrap: nowrap;justify-content: center;">
            <div class="form-check form-switch" style="display: flex; flex-direction: row;">
                <input class="form-check-input" type="checkbox" id="themeToggle" style="margin-right: 1vh;">
                <label class="form-check-label" for="themeToggle">Modo oscuro</label>
            </div>
            <div class="form-check form-switch" style="display: flex; flex-direction: row;">
                <input class="form-check-input" type="checkbox" id="sensibleData" style="margin-right: 1vh;">
                <label class="form-check-label" for="sensibleData">Ocultar datos sensibles</label>
            </div>
        </div>

        <!-- FORM -->
        <div class="form-container info main-conteiner main-form">
            <form id="curpForm" onsubmit="return false;">
                <div class="mb-3">
                    <h3 for="curpInput" class="form-label"><b>Escribe tu CURP</b></h3>
                    <span class="form-text-curp">¿No conoces tu CURP? <a href="https://www.gob.mx/curp/" target="_blank">Consulta tu CURP aquí.</a></span>
                    <input type="text" name="CURP" id="curpInput" pattern="^[A-Za-z0-9]{18}$" class="form-control" minlength="18" maxlength="18" required style="text-transform: uppercase;" title="Ingresa una CURP válida de 18 caracteres (solo letras y números)" oninput="this.value = this.value.replace(/\s/g, '')">
                </div>

                <div class="mb-3" style="display: flex;justify-content: center;">
                    <div class="h-captcha" data-sitekey="<?= $siteKey ?>"></div>
                </div>

                <!-- Mensaje de errores, ya que alert es feo XD -->
                <div class="alert alert-danger text-center hidden" id="errorAlertDiv"></div>

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
                    <img id="iconbeca" src="img/becaicons/DEFAULT.jpg" alt="Icono de beca">
                    <div class="estado" id="estado"></div>
                </section>
                <div><strong>Programa:</strong> <span id="programa"></span></div>
                <div><strong>CURP del beneficiario:</strong> <span id="curp"></span></div>
                <div><strong>NOTA: </strong> Tu información podría no estar actualizada (esto quiere decir que no todos los beneficiarios tienen este problema), esto puede tardar días, semanas o meses en actualizarse por la Coordinación de Becas Benito Juárez.</div>
            </section>

            <section class="info main-conteiner">
                <h2 class="pre-title"><img class="icons-conteiner" src="img/icons/becario.svg"> Información del becario</h2><br>
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
                    <h3 class="status-text-becario red" style="font-size: xx-large;"><span id="etiquetaBaja"></span></h3>
                    <strong>Fecha de baja:</strong> <span id="fechaBaja"></span>
                    <strong>Motivo de baja:</strong> <span id="motivoBaja"></span>
                    <strong>Fundamentación:</strong> <span id="motivoFundamentacion"></span>
                </div>
                <div class="bloque hidden" id="explicacionAny">
                    <h3 class="status-text-becario yellow" style="font-size: xx-large;"><span id="etiquetaAlerta"></span></h3>
                    <strong><span id="textoAlerta"></span></strong> <br>
                    <strong class="hidden" id="textoAlertaBUZON">¡Revisa tu buzón de mensajes, ahi recibirás seguimiento a tu caso!</strong>
                </div>
            </section>

            <div id="bancarizacionContainer" class="mt-4">
                <section class="info main-conteiner">
                    <h2 class="pre-title"><img class="icons-conteiner" src="img/icons/bancarizacion.svg"> Bancarización</h2>
                    <div class="bancarizacionfases p-3 border rounded">
                        <!-- Aquí se insertarán dinámicamente las fases -->
                    </div>
                </section>
            </div>

            <div class="info main-conteiner mt-4">
                <h2 class="pre-title"><img class="icons-conteiner" src="img/icons/becasemitidas.svg"> Becas emitidas</h2>
                <section class="emisiones" id="contenedor-emisiones">
                    <!-- Emisiones dinámicas -->
                </section>
            </div>



        </div>


        <!-- Footer -->
        <footer id="seccion1footer">
            <b>¡HOLA! Soy un universitario (igual que posiblemente tú), así que nada de aquí es perfecto, posiblemente falte algo o alguna información sea erronea.</b><br>
            Si usted sabe programar y desea apoyar a mejorarlo sin recibir ningún apoyo monetario más que apoyar a los jóvenes estudiantes, puedes hacer hacerlo dando <a href="https://github.com/MigMatos/BecasBJ-Website">click aquí</a>. <i>(¡Viva el código libre!)</i><br>
            <b>Si deseas contactarme personalmente este es mi correo (o agradecerme igual es válido jaja): <a href="mailto:matitos2317@gmail.com">matitos2317@gmail.com</a></b><br>
            <b>Si necesitas ayuda sobre información de tu BECA o ESTATUS: Llama al número 55 1162 0300 o envía un correo a <a href="mailto:atencion@becasbenitojuarez.gob.mx">atencion@becasbenitojuarez.gob.mx</a></b><br>
            <br>
            <h3>TODO ACERCA DEL SITIO WEB</h3></b><br>
            Este sitio web está creado por situación INFORMATIVA ajeno a cualquier partido político o empresa.<br>
            <b>Por obvias razones, este sitio web RECOPILA TU INFORMACIÓN para OBTENER TU INFORMACIÓN DE ESTATUS pero NO LO GUARDAMOS, TODO ES PRIVADO Y CONFIDENCIAL.<br>
                Está prohibido recopilar datos ajenos a tu persona sin autorización, si alguien reporta un mal uso, ¡esto podría bloquearte de este sitio web!</b>
            Esto no quiere decir que estés dando tu INFORMACIÓN a cualquier SITIO WEB, ten cuidado y protegete de quienes quieran lucrar con tus datos.<br>
            <b>Si alguien te mandó este enlace por DINERO (o por un acortador con publicidad) o pidió TU INFORMACION BANCARIA, ¡lamento decirte que te han estafado!. Nosotros no pedimos NINGÚN PESO así que reportalo inmediatamente.</b><br>
            <br>
            <br>
            <h3>HISTORIA DEL SITIO WEB</h3></b><br>
            Este sitio web fue creado desde que irresponsablemente por la coordinación de Becas Benito Juarez dejó en mantenimiento el sitio web, dejando sin información a los nuevos integrantes/reintegrantes becarios sin la información acerca de sus tarjetas, citas, etc. por más de un MES, eso es demasiado tiempo.<br>

            <br>

        </footer>

    </div>

    <script src='https://www.hCaptcha.com/1/api.js' async defer></script>
    <script src='./js/utilidades.js?v=<?= $siteVersion ?>'></script>
    <script src='./js/renderAPI.js?v=<?= $siteVersion ?>'></script>

    <!-- Script inicializador, básicamente aqui inicia todo, como la validación de datos y el retorno de datos de la API -->
    <script>
        let api = {};
        // Validación y envío
        const form = document.getElementById('curpForm');
        const submitBtn = document.getElementById("submitBtn");
        const loading = document.getElementById('loadingMessage'); // Posiblemente haga otro loader, pero nah, a quien le importa la estética ahorita jaja
        const container = document.getElementById('apiDataContainer');
        const errorBox = document.getElementById('errorAlertDiv');

        let hcaptchaVal = "";

        submitBtn.addEventListener('click', async e => {

            try {
                hcaptchaVal = document.querySelector('[name="h-captcha-response"]').value;
            } catch (e) {
                console.log(e);
            }

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            if (hcaptchaVal === "") {
                errorBox.classList.remove("hidden");
                errorBox.innerHTML = "<b>Porfavor, resuelve el Captcha</b>";
                // alert("Porfavor completa el Captcha");
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
                if (api.status != "200") {
                    if (api.error == null || api.error == undefined || api.error == "") {
                        errorBox.classList.remove("hidden");
                        errorBox.innerHTML = `<b>${api.message}</b>`;
                    } else {
                        errorBox.classList.remove("hidden");
                        errorBox.innerHTML = `<b>${api.error}</b>`;
                    }
                    container.style.display = 'none';
                    return;
                }
                renderInfo();
                renderFasesBancarizacion();
                renderEmisiones();
                container.style.display = 'block';
                errorBox.classList.add("hidden");
                const objetivo = document.getElementById("loadingMessage");
                if (objetivo) objetivo.scrollIntoView({
                    behavior: "smooth"
                });
            } catch (err) {
                errorBox.classList.remove("hidden");
                errorBox.innerHTML = "<b>Ocurrió un error al consultar tus datos, posiblemente hay algún problema con la página de becabenitojuarez.gob.mx o mantenimiento.</b>";
                // alert("Error al consultar datos. Intenta nuevamente.");
                container.style.display = 'none';
                console.error(err)
            } finally {
                loading.style.display = 'none';
            }
        });
    </script>
    <!-- Ya no es necesario el script borrado, la index para otras páginas esta en el HEAD, así hay más control sobre los meta-tags -->

</body>

</html>