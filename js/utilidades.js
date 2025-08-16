const html = document.documentElement;

// Tema oscuro o claro, nada importante jajaja xd
const toggle = document.getElementById('themeToggle');
toggle.checked = localStorage.getItem('theme') === 'dark';
html.setAttribute('data-theme', toggle.checked ? 'dark' : 'light');
html.setAttribute('data-bs-theme', toggle.checked ? 'dark' : 'light');

toggle.addEventListener('change', () => {
    const mode = toggle.checked ? 'dark' : 'light';
    html.setAttribute('data-theme', mode);
    html.setAttribute('data-bs-theme', mode);
    localStorage.setItem('theme', mode);
});


// Para ocultar datos personales en caso que estés en público o por X situación, nunca se sabe.
const togglesensibleData = document.getElementById('sensibleData');
let sensitiveDataState = null;

function applySensitiveDataState() { //Primera aplicación, ejecutar por defecto
    const stored = localStorage.getItem('sensitivedata');
    const state = stored === null ? false : stored === 'true'; // false por defecto (oculto)

    const ids = "curpInput,curp,cct,nacimiento,periodo,integrante,direccionads,fechaBaja".split(",");
    const elements = [...ids.map(id => document.getElementById(id)), ...document.querySelectorAll('[elem-type="sensibledata"]')];
    elements.forEach(e => e?.classList.toggle("hiddensensibledata", state));

    if (typeof togglesensibleData !== "undefined") {
        togglesensibleData.checked = state;
    }

    if (stored === null) {
        localStorage.setItem('sensitivedata', state);
    }

    sensitiveDataState = state;
}

function toggleSensitiveData() { // Toggle para el eventListener
    sensitiveDataState = !sensitiveDataState;
    localStorage.setItem('sensitivedata', sensitiveDataState);

    const ids = "curpInput,curp,cct,nacimiento,periodo,integrante,direccionads,fechaBaja".split(",");
    const elements = [...ids.map(id => document.getElementById(id)), ...document.querySelectorAll('[elem-type="sensibledata"]')];
    elements.forEach(e => e?.classList.toggle("hiddensensibledata", sensitiveDataState));

    if (typeof togglesensibleData !== "undefined") {
        togglesensibleData.checked = sensitiveDataState;
    }

    console.log("Sensitivedata changed:", sensitiveDataState);
}

applySensitiveDataState();
togglesensibleData.addEventListener('change', () => { toggleSensitiveData(); });


window.addEventListener('load', () => {
    if (/Mobi|Android|iPhone|iPod|BlackBerry|Windows Phone/i.test(navigator.userAgent)) {
        // En caso de detectar algún mobile, no ejecutamos esto, por una extraña razón bugea el scroll por el hCaptcha al inicializar
        // Aún asi dejo esto así por si llego a incluir algo en un futuro para mobiles :D
    } else {
        const objetivo = document.getElementById("obj");
        if (objetivo) objetivo.scrollIntoView({ behavior: "smooth" });
    }
});