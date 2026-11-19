# Mueblería Marquez — Gestión de pagos a proveedores

[![Laravel](https://img.shields.io/badge/Laravel_12-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP_8.2-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-4169E1?style=flat-square&logo=postgresql&logoColor=white)](https://www.postgresql.org)
[![Bootstrap](https://img.shields.io/badge/Bootstrap_5-7952B3?style=flat-square&logo=bootstrap&logoColor=white)](https://getbootstrap.com)

Sistema web para administrar las **cuentas a pagar de la mueblería**: proveedores, facturas de compra y cheques emitidos contra las cuentas bancarias del negocio, con proyección de saldos por fecha, exportación a PDF y un módulo de **sincronización de datos entre dos equipos** (PC de escritorio ↔ notebook) usando Google Drive.

Es mi copia de trabajo del proyecto grupal real que desarrollamos con Gabriel Krause y Juan Augusto Haser para la mueblería, y que se usa en el día a día del negocio.

## Módulos

| Módulo | Qué hace |
|---|---|
| **Bancos y cuentas** | CRUD de bancos y de sus cuentas bancarias (las cuentas se administran desde la vista del banco). |
| **Proveedores** | Alta, edición, búsqueda y **baja lógica** (`activo = false`); autocomplete AJAX desde el formulario de facturas. |
| **Facturas de compra** | CRUD con filtros por fecha, proveedor y número; al crear una factura te lleva directo a cargar sus cheques; cada factura muestra los cheques asociados en un desplegable por fila. |
| **Cheques** | CRUD contra una cuenta bancaria con **débito/haber**, fecha de emisión y fecha de entrada (cuando el cheque vence/entra); carga encadenada para dar de alta varios cheques seguidos de la misma factura. |
| **Proyección de cuentas** | Tabla filtrable por banco, cuenta y rango de fechas (opción de ver solo los que entraron) con **totales de débito, haber y neto**, y **exportación a PDF** con DomPDF. |
| **Usuarios** | Alta y baja de usuarios del sistema (con protección contra auto-eliminarse). |
| **Sincronización** | Exportar/importar la base entre dos equipos vía Google Drive (ver abajo). |

## El flujo del negocio

```
Banco 1─N Cuenta bancaria 1─N Cheque N─1 Factura N─1 Proveedor
```

Cada cheque pertenece a una factura de un proveedor y se cobra contra una cuenta bancaria, con importes en `debe`/`haber`. La proyección permite ver, para un banco y una cuenta, cuánto dinero entra y sale por fecha y con qué saldo neto contar.

## Sincronización entre equipos (la parte distintiva)

El negocio trabaja con los mismos datos en dos máquinas. En lugar de un servidor central, el módulo de sincronización:

1. **Exporta**: `pg_dump --data-only` de PostgreSQL → subida a Google Drive con `rclone` (carpeta `SincronizacionManual`).
2. **Importa**: descarga el dump y lo convierte a **UPSERT** (`INSERT ... ON CONFLICT DO UPDATE`): fusiona los datos **sin duplicar y sin borrar** nada de lo que ya está en la otra máquina, comenta los comandos destructivos (DROP/TRUNCATE/DELETE) y re-sincroniza las secuencias SERIAL al máximo id para evitar colisiones de claves.

Requisitos del módulo: PostgreSQL local con `pg_dump`/`psql` accesibles (se buscan en `C:\Program Files\PostgreSQL\<versión>\bin\`) y `rclone` configurado con el remote `muebleria-Marquez` apuntando a Google Drive. En `utils/` se incluye el binario de rclone para Windows (v1.72.1) junto con sus manuales, para que la sincronización funcione sin instalar nada extra. *Ese manual incluido es, por su volumen, el motivo por el que GitHub llega a mostrar «Roff» entre los lenguajes del repo: el sistema real es PHP.*

## Stack

| Capa | Tecnología |
|---|---|
| Backend | Laravel 12 · PHP 8.2 · Eloquent |
| Base de datos | **PostgreSQL** |
| Frontend | Blade + **Bootstrap 5.3.3, Bootstrap Icons y jQuery 3.6 por CDN** (el andamiaje Vite/Tailwind del skeleton quedó sin uso) |
| PDF | barryvdh/laravel-dompdf 3.1 |
| Sincronización | `pg_dump`/`psql` + rclone (Google Drive) |

## Puesta en marcha

Requisitos: PHP 8.2+, Composer y PostgreSQL.

```bash
composer install
cp .env.example .env
php artisan key:generate
# Crear la base "muebleria" en PostgreSQL y setear DB_* en .env con tus credenciales
php artisan migrate --seed
php artisan serve
# http://localhost:8000
```

El seeder inicial (`database/seeders/DatosInicialesSeeder.php`) crea el usuario inicial del sistema, los 3 bancos del negocio (Nación, ICBC, Macro), sus 4 cuentas y el padrón real de ~130 proveedores. Las credenciales iniciales están en ese archivo: cambialas tras el primer login.

La sincronización es opcional: sin rclone configurado, todo el resto del sistema funciona normal.

## Limitaciones conocidas

- No hay sistema de roles ni permisos: cualquier usuario autenticado accede a todos los módulos (incluida la administración de usuarios). El login es el único control de acceso.
- Los tests son los de ejemplo del skeleton de Laravel; no hay suite propia.
- Los workflows de `.github/` y el `CHANGELOG.md` también son los del skeleton (apuntan a una rama `master` que no se usa; el repo trabaja sobre `main`).
- Quedan algunas vistas huérfanas de etapas de prueba (`proyeccion_cuentas/show|create|edit`) y un middleware de admin duplicado sin uso, remanentes de la evolución del proyecto en equipo.

## Estructura

```
app/
├─ Console/Commands/        # TestRclone: prueba end-to-end de la sincronización
├─ Http/Controllers/        # Proveedores, Facturas, Cheques, Bancos, Cuentas,
│                            #   Proyecciones, Usuarios, Auth y Backup (~700 líneas)
└─ Models/                   # Proveedor, Factura, Cheque, CuentaBancaria, Banco, User
database/
├─ migrations/               # 11 migraciones del dominio
└─ seeders/DatosInicialesSeeder.php   # bancos, cuentas y padrón de proveedores
resources/views/            # 26 vistas Blade organizadas por módulo
routes/web.php               # todas las rutas
backups/                     # dumps de la sincronización
utils/                       # rclone + manuales (soporte del módulo de sincronización)
```

---

*Proyecto grupal real para la Mueblería Marquez. Esta es la copia de trabajo de [Kevin Otto](https://github.com/Ona96), participante del equipo de desarrollo.*
