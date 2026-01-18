<p align="center">
  <h1 align="center">🔄 Proyecto DAW Backend - API REST</h1>
  <p align="center">Plataforma de Intercambio de Servicios - Backend API</p>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker">
  <img src="https://img.shields.io/badge/OpenAPI-3.0-6BA539?style=for-the-badge&logo=openapiinitiative&logoColor=white" alt="OpenAPI">
</p>

---

## 📋 Descripción

**Proyecto DAW Backend** es una API REST desarrollada en Laravel que permite a los usuarios intercambiar servicios mediante un sistema de banco de tiempo. La plataforma facilita la conexión entre personas que ofrecen y demandan servicios, gestionando transacciones, valoraciones y comunicación entre usuarios.

> [!NOTE]
> Este proyecto es **exclusivamente backend**. El frontend está desarrollado por otro equipo utilizando **Angular** y consume esta API.
> La documentación está enfocada en la API REST y su integración.

### 🎯 Objetivo del Proyecto

Crear una economía colaborativa donde los usuarios puedan:
- **Publicar servicios** que ofrecen o necesitan
- **Realizar transacciones** basadas en horas de trabajo
- **Valorar** la calidad de los servicios recibidos
- **Comunicarse** mediante un sistema de mensajería integrado
- **Geolocalizar** servicios por provincia y población

---

## ✨ Características Principales

### 🔐 Autenticación y Usuarios
- Registro y login de usuarios
- Autenticación mediante Laravel Sanctum (Bearer tokens)
- Gestión de perfiles de usuario
- Sistema de cambio de contraseña
- Roles de usuario (mediante tabla de roles)

### 🛠️ Gestión de Servicios
- CRUD completo de servicios
- Categorización de servicios
- Geolocalización por provincia y población
- Estado de servicios (activo/inactivo)
- Subida de imágenes mediante Cloudinary
- Estimación de horas por servicio

### 💱 Sistema de Transacciones
- Creación de transacciones entre usuarios
- Roles de solicitante y ofertante
- Estados de transacción (pendiente, confirmada, completada)
- Registro de horas intercambiadas
- Fecha de confirmación

### ⭐ Valoraciones
- Sistema de reviews entre usuarios
- Vinculación con transacciones completadas
- Historial de valoraciones por usuario

### 💬 Mensajería
- Sistema de mensajes entre usuarios
- CRUD completo de mensajes
- Comunicación directa sobre servicios

### 📍 Datos Comunes
- Catálogo de provincias de España
- Catálogo de poblaciones
- Categorías de servicios
- Sistema de roles

### 📚 Documentación API
- Documentación interactiva con Swagger/OpenAPI 3.0
- Interfaz Swagger UI integrada
- Exportación de especificaciones en JSON

---

## 🛠️ Stack Tecnológico

### Backend
- **Framework**: Laravel 12
- **Lenguaje**: PHP 8.2
- **Autenticación**: Laravel Sanctum
- **Documentación API**: L5-Swagger (OpenAPI 3.0)
- **ORM**: Eloquent
- **Validación**: Form Requests

### Base de Datos
- **Motor**: MySQL (gestionada externamente por equipo de persistencia)
- **ORM**: Eloquent (consultas y relaciones)

### Integración Frontend
- **Framework**: Angular (proyecto separado, gestionado por equipo frontend)
- **Comunicación**: API REST con JSON
- **Autenticación**: Bearer tokens (Laravel Sanctum)

### DevOps
- **Containerización**: Docker
- **Servidor Web**: Apache
- **Deployment**: Render (configurado)

### Almacenamiento
- **Imágenes**: Cloudinary (CDN)

---

## 📋 Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

- **PHP**: >= 8.2
- **Composer**: >= 2.0
- **MySQL**: >= 8.0 o MariaDB >= 10.3 (acceso a la BD existente)
- **Docker** (opcional, para deployment)
- **Git**: Para clonar el repositorio

---

## 🚀 Instalación y Configuración

### 1. Clonar el repositorio

```bash
git clone https://github.com/josegarcia81/proyecto_daw_backend.git
cd proyecto_daw_backend/api_rest
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

### 3. Configurar variables de entorno

```bash
# Copiar el archivo de ejemplo
copy .env.example .env

# Generar la clave de aplicación
php artisan key:generate
```

### 4. Configurar la base de datos

Edita el archivo `.env` y configura tu conexión a la base de datos MySQL existente:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api_rest
DB_USERNAME=root
DB_PASSWORD=tu_password
```

> [!NOTE]
> La base de datos está gestionada por el equipo de persistencia. Este proyecto se conecta a una base de datos MySQL ya existente y configurada.

### 5. Configurar Cloudinary (opcional)

Si vas a usar subida de imágenes, configura tus credenciales de Cloudinary en `.env`:

```env
CLOUDINARY_CLOUD_NAME=tu_cloud_name
CLOUDINARY_API_KEY=tu_api_key
CLOUDINARY_API_SECRET=tu_api_secret
```

### 6. Generar documentación de API

```bash
php artisan l5-swagger:generate
```

---

## 🎮 Ejecución del Proyecto

### Modo Desarrollo

```bash
php artisan serve
```

La API REST estará disponible en: `http://localhost:8000`

Para ejecutar el servidor con queue listener (trabajos en segundo plano):

```bash
composer run dev
```

### Modo Producción

```bash
# Optimizar autoloader
composer install --optimize-autoloader --no-dev

# Optimizar configuración
php artisan config:cache
php artisan route:cache
```

### Docker

```bash
# Construir la imagen
docker build -t proyecto-daw-backend .

# Ejecutar el contenedor
docker run -p 10000:10000 -e PORT=10000 proyecto-daw-backend
```

### Deployment en Render

El proyecto está preparado para desplegarse en [Render](https://render.com). Las variables de entorno se configuran directamente en el panel de Render:

**Variables requeridas en Render:**
- `APP_KEY` - Clave de aplicación Laravel
- `DB_HOST` - Host de la base de datos
- `DB_PORT` - Puerto de la base de datos (3306)
- `DB_DATABASE` - Nombre de la base de datos
- `DB_USERNAME` - Usuario de la base de datos
- `DB_PASSWORD` - Contraseña de la base de datos
- `CLOUDINARY_CLOUD_NAME` - (opcional) Cloud name de Cloudinary
- `CLOUDINARY_API_KEY` - (opcional) API key de Cloudinary  
- `CLOUDINARY_API_SECRET` - (opcional) API secret de Cloudinary

> [!TIP]
> En producción (Render), todas las variables de entorno se gestionan desde el panel web de Render. No se utiliza ningún archivo `.env` en producción. En local, se usa el archivo `.env` estándar.

---

## 📚 Documentación de la API

### Acceso a Swagger UI

Una vez iniciado el servidor, accede a la documentación interactiva en:

```
http://localhost:8000/api/documentation
```

### Autenticación

La API utiliza **Laravel Sanctum** con tokens Bearer. Para autenticarte:

1. **Registrarte** o **hacer login** mediante los endpoints:
   - `POST /api/register`
   - `POST /api/login`

2. Recibirás un **token** en la respuesta

3. En Swagger UI, haz clic en el botón **"Authorize"** 🔓

4. Introduce el token en el formato:
   ```
   Bearer tu_token_aqui
   ```

### Principales Endpoints

#### Autenticación
- `POST /api/register` - Registrar nuevo usuario
- `POST /api/login` - Iniciar sesión
- `POST /api/logout` - Cerrar sesión

#### Usuarios
- `GET /api/users` - Obtener todos los usuarios
- `GET /api/users/{id}` - Obtener usuario específico
- `POST /api/users` - Crear usuario
- `PUT /api/users/{user}` - Actualizar usuario
- `DELETE /api/users/{user}` - Eliminar usuario
- `POST /api/users/{user}/change-password` - Cambiar contraseña

#### Servicios
- `GET /api/servicios` - Listar todos los servicios
- `GET /api/servicios/{user_id}` - Servicios de un usuario
- `GET /api/servicio/{id}` - Obtener servicio específico
- `POST /api/servicio` - Crear servicio
- `PUT /api/servicio/{servicio}` - Actualizar servicio
- `DELETE /api/servicio/{servicio}` - Eliminar servicio

#### Transacciones
- `GET /api/transacciones` - Listar todas las transacciones
- `GET /api/transacciones/{usuario_id}` - Transacciones de un usuario
- `POST /api/transaccion` - Crear transacción
- `PUT /api/transaccion/{transaccion}` - Actualizar transacción
- `DELETE /api/transaccion/{transaccion}` - Eliminar transacción

#### Valoraciones
- `GET /api/valoraciones` - Listar todas las valoraciones
- `GET /api/valoraciones/{usuario_id}` - Valoraciones de un usuario
- `POST /api/valoracion` - Crear valoración
- `PUT /api/valoracion/{valoracion}` - Actualizar valoración
- `DELETE /api/valoracion/{valoracion}` - Eliminar valoración

#### Mensajes
- `GET /api/mensajes` - Listar todos los mensajes
- `GET /api/mensajes/{usuario_id}` - Mensajes de un usuario
- `GET /api/mensaje/{mensaje_id}` - Obtener mensaje específico
- `POST /api/mensaje` - Crear mensaje
- `PUT /api/mensaje/{mensaje}` - Actualizar mensaje
- `DELETE /api/mensaje/{mensaje}` - Eliminar mensaje

#### Datos Comunes (públicos)
- `GET /api/getProvincias` - Listado de provincias
- `GET /api/getPoblaciones` - Listado de poblaciones
- `GET /api/getCategorias` - Listado de categorías
- `GET /api/getRoles` - Listado de roles

#### Utilidad
- `GET /api/alive` - Health check del servicio

---

## 📁 Estructura del Proyecto

```
api_rest/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Controladores de la API
│   │   │   ├── AuthController.php
│   │   │   ├── UserController.php
│   │   │   ├── ServicioController.php
│   │   │   ├── TransaccionController.php
│   │   │   ├── ValoracionController.php
│   │   │   ├── MensajeController.php
│   │   │   └── CommonController.php
│   │   └── Middleware/
│   └── Models/                # Modelos Eloquent
│       ├── User.php
│       ├── Servicio.php
│       ├── Transaccion.php
│       ├── Valoracion.php
│       ├── Mensaje.php
│       ├── Categoria.php
│       ├── Provincia.php
│       ├── Poblacion.php
│       └── Rol.php
├── config/
│   └── l5-swagger.php         # Configuración de Swagger
├── database/
│   └── migrations/            # (No utilizado - BD externa)
├── doc/
│   └── GUIA_DOCUMENTACION_API.md  # Guía completa de Swagger
├── routes/
│   └── api.php                # Definición de rutas API
├── storage/
│   └── api-docs/              # Documentación generada por Swagger
├── Dockerfile                 # Configuración Docker
├── composer.json              # Dependencias PHP
└── package.json               # Dependencias mínimas (concurrently)
```

### Modelos Principales

#### User
Gestión de usuarios del sistema con autenticación Sanctum.

#### Servicio
Servicios publicados por usuarios (oferta o demanda).
- Relaciones: Usuario, Categoría, Provincia, Población

#### Transacción
Intercambios entre usuarios (solicitante-ofertante).
- Relaciones: Servicio, UsuarioSolicitante, UsuarioOfertante

#### Valoración
Reviews y puntuaciones entre usuarios.
- Relaciones: UsuarioValorado, UsuarioValorador

#### Mensaje
Sistema de mensajería entre usuarios.
- Relaciones: Emisor, Receptor

---


## 🐳 Deployment con Docker

El proyecto incluye un `Dockerfile` optimizado para producción:

### Características del contenedor:
- **Base**: PHP 8.2 con Apache
- **Extensiones**: PDO MySQL, Zip
- **Composer**: Instalado y optimizado
- **Apache mod_rewrite**: Activado
- **Document Root**: Configurado en `/public`
- **L5-Swagger**: Assets publicados automáticamente
- **Permisos**: Configurados para `storage` y `bootstrap/cache`
- **Puerto**: Configurable mediante variable `PORT`

### Build y ejecución:
- **Configuración**: Se indica en el dashboard de Render la localización del archivo Dockerfile y las variables de entorno necesarias.

---

## 📖 Documentación Adicional

- **[Guía de Documentación API](doc/GUIA_DOCUMENTACION_API.md)** - Tutorial completo sobre Swagger/OpenAPI
- **[CHANGELOG.md](CHANGELOG.md)** - Historial de cambios del proyecto

---

## 🤝 Contribución

Las contribuciones son bienvenidas. Para contribuir:

1. Haz un fork del proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

---

## 📜 Licencia

Este proyecto está bajo la licencia **MIT**. Ver el archivo [LICENSE](LICENSE) para más detalles.

---

## 👤 Autor

**Jose Garcia**
- GitHub: [@josegarcia81](https://github.com/josegarcia81)
- Email: soporte@proyectodaw.com

---

## 🙏 Agradecimientos

- [Laravel](https://laravel.com) - El framework PHP
- [L5-Swagger](https://github.com/DarkaOnLine/L5-Swagger) - Integración de Swagger
- [Cloudinary](https://cloudinary.com) - Almacenamiento de imágenes
- [TailwindCSS](https://tailwindcss.com) - Framework CSS

---

<p align="center">Hecho con ❤️ para el Proyecto DAW</p>
