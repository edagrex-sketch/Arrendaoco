# 🐶 Guía para Probar a ROCO (Asistente de ArrendaOco)

¡Hola equipo! Este documento contiene las instrucciones necesarias para activar y probar a **ROCO**, nuestro asistente virtual (un Beagle muy inteligente) dentro de la plataforma ArrendaOco.

## 🛠 1. Configuración de API (Indispensable)

ROCO utiliza el modelo **Gemini 2.5 Flash** (GA - Google AI) para procesar las conversaciones. Para que funcione en tu máquina local:

1. Abre tu archivo `.env` en la raíz del proyecto.
2. Asegúrate de tener la siguiente variable con una API Key válida (puedes pedirle la llave actual al líder del proyecto o generar una gratis en [Google AI Studio](https://aistudio.google.com/)):

```env
GEMINI_API_KEY=tu_llave_aqui
```

> **Nota técnica**: La API Key se lee desde `config/services.php` → `services.gemini.api_key`. Si necesitas cambiar el modelo, puedes agregar `GEMINI_MODEL=gemini-2.5-pro` en el `.env`.

### Modelos disponibles (Febrero 2026):
| Modelo | Descripción | Uso recomendado |
|--------|-------------|-----------------|
| `gemini-2.5-flash` | Rápido, económico, adaptativo | ✅ **Actual (por defecto)** |
| `gemini-2.5-pro` | Mayor razonamiento | Para tareas complejas |
| `gemini-3-flash-preview` | Preview, última generación | Experimental |

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
- *"¿Hay casas baratas disponibles?"*
- *"¿Cómo puedo contactar al dueño?"* (Te dará el correo de soporte).
- *"¿Dónde queda el inmueble de la zona centro?"*
- *"Busco un cuarto económico para estudiante"*

### 🧠 Inteligencia de Búsqueda
ROCO ahora detecta automáticamente:
- **Zonas**: UTS, Centro, Zona Norte, Zona Sur
- **Precios**: "menos de $2000", "algo barato", "económico"
- **Tipos**: departamento, casa, cuarto, local, terreno
- **Combinaciones**: "depa barato cerca de la UTS"

## 🏗️ 5. Arquitectura Técnica

```
Usuario escribe mensaje
        ↓
[Frontend] scripts.blade.php → fetch POST /arrendito/chat
        ↓
[Backend] ArrenditoChatController::chat()
        ↓
    ┌───────────────────┐
    │ Búsqueda          │ → buscarInmueblesInteligente()
    │ Inteligente        │   (zona, precio, tipo)
    └───────────────────┘
        ↓
    ┌───────────────────┐
    │ Gemini API        │ → systemInstruction (personalidad)
    │ v1beta            │ → contents (mensaje + contexto)
    │ gemini-2.5-flash  │ → generationConfig (temp, tokens)
    └───────────────────┘
        ↓
    Respuesta HTML → Chat del usuario
```

### Archivos clave:
| Archivo | Función |
|---------|---------|
| `app/Http/Controllers/ArrenditoChatController.php` | Controlador principal (API + búsqueda) |
| `resources/views/components/arrendito.blade.php` | Componente raíz |
| `resources/views/components/arrendito/view.blade.php` | HTML del chat |
| `resources/views/components/arrendito/scripts.blade.php` | Lógica JavaScript |
| `resources/views/components/arrendito/styles.blade.php` | Estilos CSS |
| `config/services.php` | Configuración de API Key y modelo |

## ⚠️ 6. Solución de Problemas

| Problema | Solución |
|----------|----------|
| "¡Guau! 🐾 Mi conexión falló" | Revisa que `GEMINI_API_KEY` sea correcta y tengas internet |
| "Mi cerebro perruno necesita una llave API" | Agrega `GEMINI_API_KEY=...` en `.env` |
| "Estoy cansado de tantas preguntas" (429) | Espera unos segundos, hay rate limiting |
| "Mi llave de acceso tiene un problema" (403) | La API Key expiró o fue revocada. Genera una nueva en [AI Studio](https://aistudio.google.com/) |
| No aparece el icono | Verifica que la vista incluya `<x-arrendito />` |
| Error 419 o CSRF | Recarga la página: la sesión de Laravel expiró |

### Test rápido de la API:
```bash
php artisan tinker --execute="include 'test_gemini.php'"
```

---
*Última actualización: Febrero 2026 - Modelo actualizado a Gemini 2.5 Flash con systemInstruction, búsqueda inteligente mejorada, y mejor manejo de errores.* 🐾
