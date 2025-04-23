### Sitio Web para Consulta de Becas Benito Juarez

#### No es un sitio web oficial del gobierno, es un sitio alternativo

Este repositorio es para un sitio web fue creado desde que irresponsablemente por la coordinación de Becas Benito Juarez dejó en mantenimiento el sitio web, dejando sin información a los nuevos integrantes/reintegrantes becarios la información acerca de sus tarjetas, citas, etc.


## 🔍 Funcionalidades por agregar o comprender

### 🧑‍🤝‍🧑 Familiares
- **[ ] Agregar apartado de familiares.**
  - Aunque actualmente no hay información visible, existía en la versión oficial del sitio. Debe investigarse su estructura e lógica que tenía.

### 📅 Emisiones
- **[ ] Agregar soporte para emisiones de los años 2021 y 2022.**
  - Se debe consultar la API o el historial del servidor para obtener los datos de años anteriores.

### 📖 Historial de citas
- **[ ] Agregar historial de citas.**
  - No se muestra en el frontend actual, pero era parte del sitio original. Requiere descubrir su endpoint o estructura.

### 🚨 Alertas personalizadas por estado
- **[ ] Agregar alertas específicas por entidad federativa.**
  - Algunos estados adelantan pagos, otros no. El sistema debe ser capaz de reconocer y alertar según corresponda.

---

## 🧠 Temas por investigar

### 🌌 UNIVERSOS
- **[ ] Comprender el uso de la variable `UNIVERSOS`.**
  - Se detecta su uso en algunas funciones, pero su propósito y valores no están claros.

### 💳 Bancarización
- **[ ] Entender cuándo una cuenta es o no bancarizable.**
  - Determinar las reglas internas que definen si una cuenta puede recibir depósito en banco, no puede tener una tarjeta, se les envia un comprobante de pago o solo por tarjeta (como se muestra en el sitio web actualmente).

### 🧩 Variables sueltas por estado
- **[ ] Identificar y entender variables específicas por cada estado.**
  - Estas pueden cambiar la lógica o comportamiento del sitio dependiendo de la entidad federativa.

---

## Nota

> **Para visualizar la API debes consultar tus datos con una CURP en tu propio ENTORNO clonando este repositorio, una vez hecho esto podrás visualizar la API en la consola de tu navegador y examinar su estructura.**

Por ética y responsabilidad con los datos personales, utiliza tu propia CURP o una CURP con el consentimiento de la persona.

---

Si deseas contribuir, puedes abrir un _issue_ o hacer un _pull request_. Toda colaboración es bienvenida mientras se respete la privacidad, no guarde NINGUN TIPO de DATOS PERSONALES, sea útil, mejore el rendimiento y el código, agregue o comprende alguna funcionalidad anteriormente mencionada.