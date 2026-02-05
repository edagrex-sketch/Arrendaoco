# 🐶 Guía para Probar a ROCO (Asistente de ArrendaOco)

¡Hola equipo! Este documento contiene las instrucciones necesarias para activar y probar a **ROCO**, nuestro asistente virtual (un Beagle muy inteligente) dentro de la plataforma ArrendaOco.

## 🛠 1. Configuración de API (Indispensable)

ROCO utiliza el modelo **Gemini 2.0 Flash** para procesar las conversaciones. Para que funcione en tu máquina local:

1. Abre tu archivo `.env` en la raíz del proyecto.
2. Asegúrate de tener la siguiente variable con una API Key válida (puedes pedirle la llave actual al líder del proyecto o generar una gratis en [Google AI Studio](https://aistudio.google.com/)):

```env
GEMINI_API_KEY=tu_llave_aqui
```

## 🚀 2. Ejecutar el Proyecto

Asegúrate de tener el entorno encendido:

```bash
# Iniciar el servidor de Laravel
php artisan serve

# (Opcional) Si estás trabajando en el diseño
npm run dev
```

## 📍 3. ¿Dónde encontrar a ROCO?

ROCO está integrado en las vistas principales. Una vez que entres a `http://127.0.0.1:8000`, lo verás como un botón flotante con un icono de perro o un chat en las siguientes secciones:

- **Inicio (Landing page)**: Disponible para visitantes.
- **Dashboard (Inicio)**: Disponible después de loguearte.
- **Detalles de Propiedad**: ROCO puede ayudarte con dudas específicas de la casa que estás viendo.

## 🦴 4. ¿Qué puedes preguntarle?

ROCO no es solo un chat genérico, tiene "olfato" para los inmuebles de Ocosingo y conoce la zona. Prueba con estas preguntas:

- *"¿Qué departamentos tienes cerca de la UTS?"*
- *"Busco algo de menos de $2000 pesos."*
- *"¿Cómo puedo contactar al dueño?"* (Te dará el correo de soporte).
- *"¿Dónde queda el inmueble de la zona centro?"*

## ⚠️ 5. Solución de Problemas

- **"¡Guau! 🐾 Mi conexión falló"**: Revisa que tu `GEMINI_API_KEY` sea correcta y que tengas conexión a internet.
- **No aparece el icono**: Verifica que la vista que estás viendo incluya el componente `<x-arrendito />`.
- **Error 419 o CSRF**: Recarga la página: esto sucede si la sesión de Laravel expiró mientras el chat estaba abierto.

---
*Recuerda: ROCO es un asistente en desarrollo. Si notas que responde algo extraño o se equivoca con las ubicaciones, por favor repórtalo para ajustar su "entrenamiento".* 🐾
