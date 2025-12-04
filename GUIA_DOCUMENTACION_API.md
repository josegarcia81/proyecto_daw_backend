# Guía Completa: Implementación de Documentación API con Swagger/OpenAPI

**Proyecto:** Proyecto DAW Backend  
**Fecha:** 27 de Noviembre de 2025  
**Tecnología:** Laravel + L5 Swagger (OpenAPI 3.0)

---

## 📋 Tabla de Contenidos

1. [Introducción](#introducción)
2. [Requisitos Previos](#requisitos-previos)
3. [Paso 1: Instalación de L5 Swagger](#paso-1-instalación-de-l5-swagger)
4. [Paso 2: Publicación de Archivos de Configuración](#paso-2-publicación-de-archivos-de-configuración)
5. [Paso 3: Configuración Base en Controller.php](#paso-3-configuración-base-en-controllerphp)
6. [Paso 4: Documentación del UserController](#paso-4-documentación-del-usercontroller)
7. [Paso 5: Generación de Documentación](#paso-5-generación-de-documentación)
8. [Paso 6: Acceso a la Interfaz Swagger](#paso-6-acceso-a-la-interfaz-swagger)
9. [Ejemplos de Uso](#ejemplos-de-uso)
10. [Mantenimiento y Actualización](#mantenimiento-y-actualización)
11. [Solución de Problemas](#solución-de-problemas)
12. [Conclusiones](#conclusiones)

---

## Introducción

### ¿Qué es Swagger/OpenAPI?

**Swagger** (ahora conocido como OpenAPI) es un estándar internacional para documentar APIs REST. Permite:

- ✅ Documentación automática e interactiva
- ✅ Pruebas de endpoints desde el navegador
- ✅ Generación de especificaciones en formato JSON/YAML
- ✅ Compatibilidad con herramientas como Postman, Insomnia, etc.
- ✅ Mantenimiento sincronizado entre código y documentación

### ¿Por qué elegimos L5 Swagger?

- Es el paquete más popular para Laravel
- Compatible con OpenAPI 3.0 (estándar internacional)
- Interfaz Swagger UI integrada
- Fácil de usar con anotaciones PHP
- Ampliamente adoptado en la industria

---

## Requisitos Previos

Antes de comenzar, asegúrate de tener:

- ✅ Laravel instalado y funcionando
- ✅ Composer instalado
- ✅ XAMPP/Apache corriendo
- ✅ Acceso a la línea de comandos
- ✅ Base de datos configurada

---

## Paso 1: Instalación de L5 Swagger

### Comando de instalación

Abre tu terminal en la carpeta del proyecto Laravel (en nuestro caso: `api_rest`) y ejecuta:

```bash
composer require "darkaonline/l5-swagger"
```

### ¿Qué hace este comando?

Este comando descarga e instala las siguientes dependencias:

- `darkaonline/l5-swagger` - Paquete principal
- `zircote/swagger-php` - Librería para parsear anotaciones PHP
- `swagger-api/swagger-ui` - Interfaz web interactiva
- `doctrine/annotations` - Sistema de anotaciones PHP
- Otras dependencias necesarias

### Salida esperada

```
Using version ^9.0 for darkaonline/l5-swagger
Installing phpstan/phpdoc-parser (2.3.0)
Installing zircote/swagger-php (5.7.3)
Installing swagger-api/swagger-ui (v5.30.3)
Installing darkaonline/l5-swagger (9.0.1)
Generating optimized autoload files
```

⏱️ **Tiempo estimado:** 1-3 minutos dependiendo de tu conexión a internet.

---

## Paso 2: Publicación de Archivos de Configuración

### Comando de publicación

```bash
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
```

### ¿Qué hace este comando?

Este comando copia los archivos de configuración y vistas del paquete a tu proyecto:

**Archivos creados:**

1. **`config/l5-swagger.php`**
   - Archivo de configuración principal
   - Define rutas, seguridad, estilos, etc.

2. **`resources/views/vendor/l5-swagger/`**
   - Vistas personalizables de la interfaz Swagger UI

### Salida esperada

```
INFO  Publishing assets.

Copying file [vendor/.../l5-swagger.php] to [config/l5-swagger.php]  DONE
Copying directory [vendor/.../views] to [resources/views/vendor/l5-swagger]  DONE
```

---

## Paso 3: Configuración Base en Controller.php

### Ubicación del archivo

```
app/Http/Controllers/Controller.php
```

### Código agregado

```php
<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Proyecto DAW Backend API",
 *     version="1.0.0",
 *     description="Documentación de la API REST del Proyecto DAW",
 *     @OA\Contact(
 *         email="soporte@proyectodaw.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost/api",
 *     description="Servidor de desarrollo local"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Autenticación mediante token Laravel Sanctum"
 * )
 */
abstract class Controller
{
    //
}
```

### Explicación de las anotaciones

| Anotación | Propósito |
|-----------|-----------|
| `@OA\Info` | Información general de la API (título, versión, descripción) |
| `@OA\Contact` | Datos de contacto del equipo de desarrollo |
| `@OA\Server` | URL base del servidor de la API |
| `@OA\SecurityScheme` | Define el método de autenticación (en nuestro caso, Bearer Token) |

### ¿Por qué en Controller.php?

Este archivo es la clase base de todos los controladores. L5 Swagger escanea todos los controladores y sus comentarios, por lo que es el lugar ideal para definir la configuración global.

---

## Paso 4: Documentación del UserController

### Ubicación del archivo

```
app/Http/Controllers/UserController.php
```

### 4.1 Documentación de getAllUsers()

#### Código de anotación

```php
/**
 * @OA\Get(
 *     path="/users",
 *     summary="Obtener todos los usuarios",
 *     tags={"Usuarios"},
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de usuarios obtenida correctamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="code", type="integer", example=200),
 *             @OA\Property(property="time", type="string", format="date-time"),
 *             @OA\Property(property="message", type="string", example="Todos los usuarios obtenidos correctamente"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="usuario", type="string", example="admin")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error del servidor"
 *     )
 * )
 */
public function getAllUsers() { ... }
```

#### Elementos clave

- **`@OA\Get`** - Define un endpoint GET
- **`path="/users"`** - Ruta del endpoint
- **`tags={"Usuarios"}`** - Agrupa endpoints bajo "Usuarios"
- **`security={{"sanctum":{}}}`** - Requiere autenticación
- **`@OA\Response`** - Define respuestas posibles (200, 500)
- **`@OA\JsonContent`** - Estructura del JSON de respuesta
- **`@OA\Property`** - Cada campo del JSON con su tipo y ejemplo

---

### 4.2 Documentación de getUser($id)

#### Código de anotación

```php
/**
 * @OA\Get(
 *     path="/users/{id}",
 *     summary="Obtener un usuario específico",
 *     tags={"Usuarios"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID del usuario",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Usuario encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="code", type="integer", example=200),
 *             @OA\Property(property="time", type="string", format="date-time"),
 *             @OA\Property(property="message", type="string", example="Usuario encontrado"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="usuario", type="string", example="admin")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=406,
 *         description="Usuario no encontrado"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error del servidor"
 *     )
 * )
 */
public function getUser($id) { ... }
```

#### Novedad: Parámetros de ruta

```php
@OA\Parameter(
    name="id",
    in="path",           // El parámetro está en la URL
    description="ID del usuario",
    required=true,       // Es obligatorio
    @OA\Schema(type="integer")
)
```

---

### 4.3 Documentación de createUser()

#### Código de anotación

```php
/**
 * @OA\Post(
 *     path="/users",
 *     summary="Crear un nuevo usuario",
 *     tags={"Usuarios"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"usuario","password"},
 *             @OA\Property(property="usuario", type="string", example="nuevo_usuario"),
 *             @OA\Property(property="password", type="string", format="password", example="secreto123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Usuario creado correctamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="code", type="integer", example=201),
 *             @OA\Property(property="time", type="string", format="date-time"),
 *             @OA\Property(property="message", type="string", example="Usuario creado correctamente"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="usuario", type="string", example="nuevo_usuario")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error de validación"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error del servidor"
 *     )
 * )
 */
public function createUser(Request $request) { ... }
```

#### Novedad: RequestBody

```php
@OA\RequestBody(
    required=true,
    @OA\JsonContent(
        required={"usuario","password"},  // Campos obligatorios
        @OA\Property(property="usuario", type="string", example="nuevo_usuario"),
        @OA\Property(property="password", type="string", format="password", example="secreto123")
    )
)
```

---

### 4.4 Documentación de updateUser()

#### Código de anotación

```php
/**
 * @OA\Put(
 *     path="/users/{user}",
 *     summary="Actualizar un usuario existente",
 *     tags={"Usuarios"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="user",
 *         in="path",
 *         description="ID del usuario",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=false,
 *         @OA\JsonContent(
 *             @OA\Property(property="usuario", type="string", example="usuario_actualizado"),
 *             @OA\Property(property="password", type="string", format="password", example="nuevo_secreto123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Usuario actualizado correctamente"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error de validación"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error del servidor"
 *     )
 * )
 */
public function updateUser(Request $request, User $user) { ... }
```

---

### 4.5 Documentación de deleteUser()

#### Código de anotación

```php
/**
 * @OA\Delete(
 *     path="/users/{user}",
 *     summary="Eliminar un usuario",
 *     tags={"Usuarios"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="user",
 *         in="path",
 *         description="ID del usuario",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Usuario eliminado correctamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="code", type="integer", example=200),
 *             @OA\Property(property="time", type="string", format="date-time"),
 *             @OA\Property(property="message", type="string", example="Usuario eliminado correctamente"),
 *             @OA\Property(property="data", type="null")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error del servidor"
 *     )
 * )
 */
public function deleteUser(User $user) { ... }
```

---

## Paso 5: Generación de Documentación

### Comando de generación

```bash
php artisan l5-swagger:generate
```

### ¿Qué hace este comando?

L5 Swagger:

1. **Escanea** todos los archivos PHP en `app/Http/Controllers/`
2. **Parsea** las anotaciones `@OA\*`
3. **Valida** la sintaxis OpenAPI
4. **Genera** el archivo `storage/api-docs/api-docs.json`
5. **Crea** la especificación OpenAPI completa

### Salida esperada

```
Regenerating docs default
```

### Archivo generado

```
storage/api-docs/api-docs.json
```

Este archivo JSON contiene toda la especificación OpenAPI de tu API y es el que Swagger UI utiliza para mostrar la interfaz.

---

## Paso 6: Acceso a la Interfaz Swagger

### URL de acceso

```
http://localhost/api_rest/public/api/documentation
```

O si tienes configurado un virtual host:

```
http://tu-dominio.local/api/documentation
```

### ¿Qué verás?

Una interfaz interactiva dividida en secciones:

1. **Cabecera**
   - Título: "Proyecto DAW Backend API"
   - Versión: "1.0.0"
   - Descripción de la API

2. **Servers**
   - URL base: `http://localhost/api`

3. **Authorize (🔓)**
   - Botón para configurar el token de autenticación

4. **Endpoints agrupados por Tags**
   - **Usuarios** (5 endpoints)
     - GET /users
     - GET /users/{id}
     - POST /users
     - PUT /users/{user}
     - DELETE /users/{user}

### Funcionalidades de la interfaz

- **Ver detalles** - Click en un endpoint para expandir
- **Try it out** - Botón para probar el endpoint en vivo
- **Execute** - Envía la petición real
- **Responses** - Muestra la respuesta del servidor
- **Schemas** - Modelos de datos en formato JSON

---

## Ejemplos de Uso

### Ejemplo 1: Probar GET /users

1. Abre la interfaz Swagger
2. Haz login primero para obtener un token
3. Click en "Authorize" y pega tu token
4. Expande `GET /users`
5. Click en "Try it out"
6. Click en "Execute"
7. Verás la respuesta JSON en tiempo real

### Ejemplo 2: Crear un usuario (POST)

1. Expande `POST /users`
2. Click en "Try it out"
3. Modifica el JSON de ejemplo:
   ```json
   {
     "usuario": "mi_nuevo_usuario",
     "password": "password123"
   }
   ```
4. Click en "Execute"
5. Verás el código de respuesta (201 si fue exitoso)

---

## Mantenimiento y Actualización

### Cuando agregues nuevos endpoints

1. **Escribe el método** en tu controlador
2. **Agrega las anotaciones** `@OA\*` encima del método
3. **Regenera la documentación**:
   ```bash
   php artisan l5-swagger:generate
   ```
4. **Refresca** la página de Swagger UI

### Cuando modifiques endpoints existentes

1. **Actualiza** las anotaciones correspondientes
2. **Regenera** la documentación
3. **Verifica** en Swagger UI que los cambios se reflejen

### Buenas prácticas

- ✅ Documenta todos los endpoints
- ✅ Incluye ejemplos realistas
- ✅ Documenta todos los códigos de respuesta posibles
- ✅ Mantén sincronizada la documentación con el código
- ✅ Usa tags para organizar endpoints relacionados
- ✅ Especifica campos requeridos y opcionales claramente

---

## Solución de Problemas

### Problema 1: Error 404 en /api/documentation

**Causas posibles:**
- No se ejecutó `php artisan l5-swagger:generate`
- Configuración incorrecta de rutas en `l5-swagger.php`
- Servidor no está corriendo

**Solución:**
```bash
php artisan config:clear
php artisan l5-swagger:generate
```

### Problema 2: La documentación está vacía

**Causas posibles:**
- Sintaxis incorrecta en las anotaciones
- Archivos no están en el directorio escaneado

**Solución:**
- Verifica que las anotaciones usen `@OA\` (no `@SWG\`)
- Verifica la configuración `paths` en `config/l5-swagger.php`

### Problema 3: Cambios no se reflejan

**Causas posibles:**
- Caché no limpiada
- No se regeneró la documentación

**Solución:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan l5-swagger:generate
```

### Problema 4: Error de sintaxis en anotaciones

**Causas comunes:**
- Falta una coma
- Comilla incorrecta
- Paréntesis sin cerrar

**Solución:**
- Usa un editor con syntax highlighting para PHP
- Compara con ejemplos funcionantes
- Verifica los logs de Laravel: `storage/logs/laravel.log`

---

## Conclusiones

### ¿Qué hemos logrado?

✅ Instalado L5 Swagger (estándar internacional OpenAPI 3.0)  
✅ Configurado la documentación base de la API  
✅ Documentado completamente el CRUD de usuarios (5 endpoints)  
✅ Generado interfaz interactiva Swagger UI  
✅ Creado especificación exportable en JSON  

### Beneficios obtenidos

1. **Documentación automática** - Se actualiza con el código
2. **Pruebas rápidas** - Test de endpoints desde el navegador
3. **Estándar internacional** - Compatible con cualquier herramienta
4. **Mejor colaboración** - Equipo y clientes entienden la API
5. **Menos errores** - Validación de contratos API/Frontend

### Próximos pasos recomendados

1. **Documentar AuthController** - Endpoints de login, register, logout
2. **Agregar más detalles** - Descriptions, ejemplos adicionales
3. **Documentar modelos** - Schemas reutilizables con `@OA\Schema`
4. **Exportar documentación** - Compartir JSON con equipo frontend
5. **Integrar con CI/CD** - Regenerar docs automáticamente en despliegues

---

## Referencias

- [L5 Swagger - Documentación oficial](https://github.com/DarkaOnLine/L5-Swagger)
- [OpenAPI Specification 3.0](https://swagger.io/specification/)
- [Swagger Editor Online](https://editor.swagger.io/)
- [Laravel Sanctum - Autenticación](https://laravel.com/docs/sanctum)

---

**Documento creado el:** 27 de Noviembre de 2025  
**Autor:** Asistente AI  
**Proyecto:** Proyecto DAW Backend  
**Versión:** 1.0
