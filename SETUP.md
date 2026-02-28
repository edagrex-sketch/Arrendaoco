# 🏠 ArrendaOco — Guía de Instalación para Colaboradores

Sigue estos pasos **en orden** para tener el proyecto corriendo en tu máquina local.

---

## ✅ Requisitos Previos

| Requisito | Versión recomendada | Verificar con |
|-----------|--------------------------|---------------|
| PHP       | 8.2 o superior           | `php -v` |
| Composer  | 2.x                      | `composer -V` |
| Node.js   | 18 o superior            | `node -v` |
| MySQL      | 8.x                     | Incluido en XAMPP/Laragon |
| Git        | Cualquier versión reciente | `git --version` |

> **Recomendación**: Usa [Laragon](https://laragon.org/) (Windows) — incluye PHP, MySQL y Apache ya configurados.

---

## 🚀 Pasos de Instalación

### 1. Clonar el repositorio

```bash
git clone <URL-del-repositorio>
cd Arrendaoco
```

### 2. Instalar dependencias PHP

```bash
composer install
```

### 3. Instalar dependencias JavaScript

```bash
npm install
```

### 4. Configurar el archivo de entorno

```bash
# Copia la plantilla de variables de entorno
cp .env.example .env

# Genera la clave de la aplicación
php artisan key:generate
```

### 5. Configurar la base de datos

1. Abre **phpMyAdmin** (o tu cliente MySQL) y crea la base de datos:
   ```sql
   CREATE DATABASE bd_arrendaoco CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
2. Edita tu `.env` con tus credenciales MySQL si son diferentes:
   ```env
   DB_USERNAME=root
   DB_PASSWORD=        ← tu contraseña (vacío si no tienes)
   ```

### 6. Ejecutar migraciones y seeders

```bash
php artisan migrate --seed
```

### 7. Configurar las API Keys (pídelas al líder del proyecto)

Abre tu `.env` y llena estas variables con los valores que te comparta el líder:

```env
GEMINI_API_KEY=         ← Para el asistente ROCO
YOUTUBE_API_KEY=        ← Para videos en propiedades
GOOGLE_CLIENT_ID=       ← Para login con Google
GOOGLE_CLIENT_SECRET=   ← Para login con Google
FACEBOOK_CLIENT_ID=     ← Para login con Facebook
FACEBOOK_CLIENT_SECRET= ← Para login con Facebook
```

> ⚠️ Las llaves de correo (MAIL_*) son opcionales para desarrollo. Por defecto, los correos se guardan en `/storage/logs/laravel.log`.

### 8. Crear el enlace de almacenamiento

```bash
php artisan storage:link
```

### 9. ¡Ejecutar el proyecto!

```bash
# Terminal 1: Servidor Laravel
php artisan serve

# Terminal 2: Compilar assets (CSS/JS)
npm run dev
```

Abre tu navegador en: **http://127.0.0.1:8000**

---

## 🔑 Login con Redes Sociales (Google / Facebook)

Para que funcione en tu máquina local, el líder del proyecto debe agregar tu URL de callback en el panel de cada red social. Las URLs locales son:

- **Google**: `http://127.0.0.1:8000/auth/google/callback`
- **Facebook**: `http://127.0.0.1:8000/auth/facebook/callback`

> ⚠️ **No uses ngrok** salvo que el líder te indique lo contrario. Cada quien trabaja con `http://127.0.0.1:8000` localmente.

---

## 🐶 Probar al Asistente ROCO

Consulta el archivo **`INSTRUCCIONES_ROCO.md`** para detalles sobre cómo probar el chatbot.

---

## ❓ Problemas Comunes

| Error | Solución |
|-------|----------|
| `php artisan key:generate` falla | Asegúrate de haber copiado `.env.example` a `.env` |
| Error de conexión a la BD | Verifica `DB_USERNAME` y `DB_PASSWORD` en tu `.env` |
| Página en blanco / error 500 | Revisa `storage/logs/laravel.log` |
| `npm run dev` falla | Ejecuta `npm install` primero |
| Imágenes no cargan | Ejecuta `php artisan storage:link` |
| Login social no funciona | Verifica que tu URL de callback sea `http://127.0.0.1:8000/auth/google/callback` |

---

*Última actualización: Febrero 2026*
