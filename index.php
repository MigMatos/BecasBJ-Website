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
    <title>Buscador de Becas Bienestar - NO OFICIAL</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <meta name="description" content="Consulta tu estatus en las becas Benito Juárez, Beca Rita Cetina, Becas Bienestar y más apoyos para jóvenes en México 2025.">

    <!-- Como siempre bootstrap god dandonos sus diseñitos simples pero bonitos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="./css/inicio.css?v=<?= $siteVersion ?>" rel="stylesheet">

    <!-- Open Graph (para Facebook, WhatsApp, Discord, etc) -->
    <meta property="og:title" content="Buscador de Becas Bienestar - NO OFICIAL">
    <meta property="og:description" content="Buscador NO OFICIAL de estatus o becas de Benito Juarez, Rita Cetina, Etc">
    <meta property="og:image" content="https://consultarbeca.x10.mx/img/icons/tarjeta_bienestar_2.png">
    <meta property="og:url" content="https://consultarbeca.x10.mx/">
    <meta property="og:type" content="website">

    <!-- Twitter Card (opcional, para Twitter/X) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Buscador de Becas Bienestar - NO OFICIAL">
    <meta name="twitter:description" content="Buscador NO OFICIAL de estatus o becas de Benito Juarez, Rita Cetina, Etc">
    <meta name="twitter:image" content="https://consultarbeca.x10.mx/img/icons/tarjeta_bienestar_2.png">
    <meta name="twitter:url" content="https://consultarbeca.x10.mx/">

</head>

<body>

        <!-- Mensaje de carga -->
        <div id="loadingMessage" class="loading text-center my-4" style="display: none;">
            <div class="spinner-border b-color-primary" role="status"></div>
            <p>Cargando tus datos...</p>
        </div>

    <div class="container my-4">

        <!-- Banner de imagen -->
        <header>
            <img src="img/banner2.jpg" aalt="Consulta estatus Beca Benito Juárez, Rita Cetina, Bienestar 2025">
        </header>

        <h1 id="obj" class="text-center mb-4">Consulta de Estatus <br><span id="main-title">Becas BIENESTAR</span></h1>

        <!-- Mensaje informativo superior -->
        <div class="alert alert-custom-info text-center info-b-text">
            <b>Este sitio web NO ES OFICIAL y NO SUPLANTA al <a href="https://buscador.becasbenitojuarez.gob.mx/consulta/">buscador de estatus oficial</a>.</b><br>
            <a href="#seccion1footer">Lea más acerca de mí y este sitio web aquí</a><br>
            <b> <!-- <a href="https://www.gob.mx/becasbenitojuarez/articulos/tutorial-para-hacer-tu-llave-mx?idiom=es" target="_blank">más información dando click aquí</a> --></b>
        </div>

        <!-- Toggle de tema -->
        <div class="text-end mb-3 utilities-div" >
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
                    <h2 id="curpTitle" for="curpInput" class="form-label">Escribe tu CURP</h2>
                    <div class="input-group" id="civ" sensibledata="">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="bi bi-person-vcard"></i>
                        </span>
                        <input type="text" name="CURP" id="curpInput" placeholder="EJEMPLO: ABCD012345HDFABC09" pattern="^[A-Za-z0-9]{18}$" class="form-control" minlength="18" maxlength="18" required style="text-transform: uppercase;" title="Ingresa una CURP válida de 18 caracteres (solo letras y números)" oninput="this.value = this.value.replace(/\s/g, '')">
                    </div>

                    
                    <span class="form-text-curp">¿No conoces tu CURP? <a href="https://www.gob.mx/curp/" target="_blank">Consulta tu CURP aquí.</a></span>
                </div>

                <div class="mb-3" style="display: flex;justify-content: center;">
                    <div class="h-captcha" data-sitekey="<?= $siteKey ?>"></div>
                </div>

                <!-- Mensaje de errores, ya que alert es feo XD -->
                <div class="alert alert-danger text-center hidden" id="errorAlertDiv"></div>

                <button id="submitBtn" type="button" class="btn btn-primary w-100" style="font-size: large;"><i class="bi bi-search"></i> Consultar</button>
            </form>
        </div>


        <!-- Contenedor de datos API -->
        <div id="apiDataContainer" style="display: none;" class="mt-4">
            <section class="info main-conteiner">
                <section class="icons">
                    <img id="iconbeca" data-load="sk" src="img/becaicons/DEFAULT.jpg" alt="Icono de beca">
                    <div class="estado" id="estado"></div>
                </section>
                <div><strong>Programa:</strong> <span id="programa"></span></div>
                <div><strong>CURP del beneficiario:</strong> <span id="curp"></span></div>
                <div><strong>NOTA: </strong> Tu información podría no estar actualizada, recuerda que tu información se actualiza de manera manual y por una persona real por la Coordinación de becas para el Bienestar.</div>
            </section>

            <section class="info main-conteiner">
                <h2 class="pre-title"><img class="icons-conteiner" src="img/icons/becario.svg"> Información del becario</h2>
                <div class="grid">
                    <!-- <div><strong>Programa:</strong> <span id="programa"></span></div> -->
                    <div><strong>CCT:</strong> <span id="cct"></span></div>
                    <div><strong>Fecha de Nacimiento:</strong> <span id="nacimiento"></span></div>
                    <div><strong>Periodo de Incorporación:</strong> <span id="periodo"></span></div>
                    <div><strong>Identificador del beneficiario:</strong> <span id="integrante"></span></div>
                    <div><strong>Estado:</strong> <span id="estadoMX"></span></div>
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
                
                <pop2><strong>Progreso de pagos:</strong>
                    <div class="progress" id="progress-bar-pagos">
                        <div id="myProgressBar" class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
                    </div>    
                    <strong><span id="totalPagos">0</span> pagos</strong> obtenidos de <strong><span id="maximoPagos">0</span> pagos</strong> totales.</pop2>
                <br>
                <h3 class="pre-off-title">Historial de Pagos</h3>

                <section class="emisiones" id="contenedor-emisiones">
                    <!-- Emisiones dinámicas -->
                    
                </section>
            </div>



        </div>

        <!-- Modal -->

        <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-alerts">
            <div class="modal-header">
                <h5 class="modal-title" id="alertModalLabel">🚨 ¡Evita las estafas! 🚨</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p class="fs-4">
                <span class="text-danger">📢 Nadie debe pedirte <span class="fw-bold text-uppercase">información bancaria,
                información personal como tu nombre, edad, INE; antes y después de entrar a esta página.</span>
                </span></p>
                <p>
                🕵🏻 Tus consultas siempre <span class="fw-bold text-uppercase">serán anónimas y nunca se almacenan</span>.
                </p>
                <p>
                <span class="fw-bold text-uppercase">⚠️ ¡Reporta los sitios webs clones con publicidad, falsos o engañosos en nuestro correo! ⚠️<br>
                🚫 ¡NO VENDAS TU INFORMACIÓN!</span>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary fw-bold" data-bs-dismiss="modal">¡Entendido!</button>
            </div>
            </div>
        </div>
        </div>


        <!-- Footer -->
        <footer id="seccion1footer">
            <b>¡HOLA! Soy un universitario (igual que posiblemente tú), así que nada de aquí es perfecto, posiblemente falte algo o alguna información sea erronea.</b><br>
            Si usted sabe programar y desea apoyar a mejorarlo sin recibir ningún apoyo monetario más que apoyar a los jóvenes estudiantes, puedes hacer hacerlo dando <a href="https://github.com/MigMatos/BecasBJ-Website">click aquí</a>. <i>(¡Viva el código libre!)</i><br>
            <b>Si deseas contactarme personalmente este es mi correo (o agradecerme igual es válido jaja): <a href="mailto:matitos2317@gmail.com">matitos2317@gmail.com</a></b><br>
            <b>Si necesitas ayuda sobre información de tu BECA o ESTATUS, deberás ir personalmente a una oficina de atención, encuentra un centro de atención en: <a href="https://buscador.becasbenitojuarez.gob.mx/sedes-atencion/">https://buscador.becasbenitojuarez.gob.mx/sedes-atencion/</a><br>
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
            <br><b>Actualización de Junio:</b><br> ¡Hola! tenía ganas de cerrar este sitio web por que el gobierno baneó este sitio web y además de estar fichados por gente ignorate cómo un sitio web de estafa cuando nosotros en ningún momento hemos pedido dinero a cambio o robado datos pero con la intención de seguir apoyando a quienes realmente lo necesitan, 
            estaremos dejando activo el sitio hasta que finalmente vuelva a funcionar el buscador de estatus,
            en serio que horrible lo que está haciendo nuestro gobierno y más por aquellos donde la sede de atención es demasiado lejos de los beneficiarios o saber si su beca ya cayó, ¡no todos tenemos un banco cercano ni tampoco los recursos, por algo necesitan este apoyo!
            <br>
            <br>
            <br>
            <p>Si eres beneficiario de las <strong>Becas Benito Juárez</strong> o buscas la 
            <strong>Beca Rita Cetina</strong>, aquí puedes usar nuestro <strong>buscador de estatus 2025</strong> 
            para verificar tus depósitos de becas.</p>

        </footer>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src='https://www.hCaptcha.com/1/api.js' async defer></script>
    <script src='./js/utilidades.js?v=<?= $siteVersion ?>'></script>
    <script src='./js/renderAPI.js?v=<?= $siteVersion ?>'></script>

    <!-- Script inicializador, básicamente aqui inicia todo, como la validación de datos y el retorno de datos de la API -->
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            if (!sessionStorage.getItem('modalShown')) {
            var myModal = new bootstrap.Modal(document.getElementById('alertModal'));
            myModal.show();
            sessionStorage.setItem('modalShown', 'true');
            }
        });

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
            // if (hcaptchaVal === "") {
            //     errorBox.classList.remove("hidden");
            //     errorBox.innerHTML = "<b>Porfavor, resuelve el Captcha</b>";
            //     // alert("Porfavor completa el Captcha");
            //     return;
            // }

            loading.style.display = '';

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
                        if (api.message == null || api.message == undefined || api.message == ""){
                            errorBox.innerHTML = "<b>Ocurrió un error al consultar tus datos, posiblemente hay algún problema con la página de becabenitojuarez.gob.mx o mantenimiento.</b><br><b>¡Intentalo nuevamente en una hora o más tarde!</b>";
                        } else {
                            errorBox.innerHTML = `<b>${api.message}</b>`;
                        }
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
            } catch (err) {
                errorBox.classList.remove("hidden");
                errorBox.innerHTML = "<b>Ocurrió un error al consultar tus datos, posiblemente hay algún problema con la página de becabenitojuarez.gob.mx o mantenimiento.</b><br><b>¡Intentalo nuevamente en una hora o más tarde!</b>";
                // alert("Error al consultar datos. Intenta nuevamente.");
                container.style.display = 'none';
                console.error(err)
            } finally {
                loading.style.display = 'none';
                setTimeout(() => {
                    const objetivo = document.getElementById("iconbeca");
                    if (objetivo) objetivo.scrollIntoView({
                        behavior: "smooth"
                    });
                }, 100);
            }
        });
    </script>
    <!-- Ya no es necesario el script borrado, la index para otras páginas esta en el HEAD, así hay más control sobre los meta-tags -->

</body>

</html>