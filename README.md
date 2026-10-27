# 🚀 Guías de Prácticas Laravel - TaskBoard: Pasarela de Pagos

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Git](https://img.shields.io/badge/Git-F05032?style=for-the-badge&logo=git&logoColor=white)
![GitHub](https://img.shields.io/badge/GitHub-181717?style=for-the-badge&logo=github&logoColor=white)

---

<p align="center">
  <img src="https://res.cloudinary.com/bcwlyire/image/upload/v1785118882/NUEVO-LOGO-UPED-A-COLOR-scaled_szoqws.jpg" alt="Logo UPED" width="300"/>
</p>

## 🏛️ Información Académica

- **Institución:** Universidad Pedagógica de El Salvador *Luis Alonso Aparicio*
- **Facultad:** Ingeniería
- **Asignatura:** Integración de Sistemas
- **Ciclo:** 02-2026
- **Docente:** Ing. Oscar Contreras
- **Proyecto Integrador:** TaskBoard - Pasarela de Pagos

---

## 📌 Descripción del Proyecto

Este repositorio contiene el trabajo de la **Semana 13** del proyecto **TaskBoard**, una plataforma de **Pasarela de Pagos** desarrollada en el framework **Laravel 11**.

Hasta la Semana 12 TaskBoard atendía personas con navegador y respondía con vistas Blade. En esta semana se construye **una segunda puerta**: endpoints que responden **JSON** para que otros programas consuman los datos.

1. **Activación de la API:** `php artisan install:api` crea `routes/api.php`, cuyas rutas llevan el prefijo `/api`, no usan sesión ni cookies y no exigen token CSRF.
2. **API Resources:** `ComercioResource` y `TransaccionResource` deciden qué campos salen al exterior. Así la respuesta deja de ser un reflejo de la tabla: `user_id` y las fechas internas no se publican, y `whenLoaded()` incluye las transacciones solo en el detalle de un comercio.
3. **`Route::apiResource()`:** cinco rutas (index, store, show, update, destroy) sobre un controlador sin vistas. No hay `create` ni `edit` porque una API no tiene formularios.
4. **Códigos de estado como contrato:** 200 al leer, **201** al crear, **204** al eliminar, **404** si no existe y **422** con los errores de validación, reutilizando el mismo Form Request del formulario web.

La API todavía está abierta: cualquiera con la dirección puede leer y escribir. Protegerla con tokens (Laravel Sanctum, ya instalado) es el tema de la siguiente clase.

---

## 📁 Estructura del Proyecto

A continuación se detalla la estructura principal del repositorio y del proyecto Laravel `taskboard`:

```text
taskboard/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   └── TransaccionApiController.php      # Controlador de API, sin vistas
│   │   │   ├── AuthController.php                    # Registro, login y logout
│   │   │   ├── ComercioController.php                # Web: index filtrado y show protegido
│   │   │   └── TransaccionController.php             # Web: CRUD y moverEstado()
│   │   ├── Requests/
│   │   │   ├── RegistroRequest.php                   # Validación del registro
│   │   │   └── GuardarTransaccionRequest.php         # Compartido por el formulario y la API
│   │   └── Resources/
│   │       ├── ComercioResource.php                  # Campos públicos del comercio
│   │       └── TransaccionResource.php               # Campos públicos de la transacción
│   └── Models/
│       ├── User.php                                  # Cast 'hashed' y hasMany(Comercio)
│       ├── Comercio.php                              # user_id y belongsTo(User)
│       ├── Transaccion.php                           # belongsTo(Comercio)
│       └── EventoTransaccion.php                     # belongsTo(Transaccion)
├── bootstrap/
│   └── app.php                                       # install:api registró aquí routes/api.php
├── config/                                           # Archivos de configuración general del sistema
├── database/
│   ├── migrations/                                   # Incluye la tabla de tokens de Sanctum
│   └── seeders/
│       └── ComercioSeeder.php                        # Ana y Carlos, con sus propios comercios
├── public/                                           # Punto de entrada de la aplicación (index.php) y assets
├── resources/
│   └── views/                                        # Vistas Blade de la aplicación web
├── routes/
│   ├── api.php                                       # Endpoints JSON con prefijo /api
│   └── web.php                                       # Grupos guest y auth de la aplicación web
├── storage/                                          # Registros (logs), archivos cargados y caché de la app
├── vendor/                                           # Dependencias de PHP administradas por Composer
├── pruebas-api.http                                  # Colección de pruebas de la API
├── .env.example                                      # Variables de entorno de ejemplo
├── composer.json                                     # Configuración de Composer y paquetes
└── README.md                                         # Documentación principal del repositorio
```
