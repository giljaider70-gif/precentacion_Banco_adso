# Banco ADSO

Aplicación bancaria simulada con POO, Composer (autoload PSR-4), patrón MVC artesanal (sin frameworks) y PDO sobre MySQL. Proyecto integrador de la ruta PHP intermedio — SENA/ADSO.

## 1. Arquitectura

```
BancoADSO/
├── composer.json
├── config/basedatos.php        (credenciales de conexión — edítalo si es necesario)
├── sql/
│   ├── 01_creacion.sql         (5 tablas normalizadas a 3FN, InnoDB, utf8mb4)
│   └── 02_siembra.sql          (5 clientes/cuentas/usuarios de prueba)
├── public/
│   ├── index.php               (único punto de entrada / front controller)
│   └── css/estilo.css
├── src/
│   ├── Nucleo/                 (Router, Vista, ControladorBase, Conexion)
│   ├── Modelos/                (Cliente, Cuenta, Usuario, Retiro, Transferencia)
│   ├── Repositorios/           (acceso PDO por tabla)
│   ├── Servicios/               (reglas de negocio: login, retiro, transferencia atómica)
│   ├── Controladores/          (LoginControlador, PanelControlador, RetiroControlador, TransferenciaControlador)
│   ├── Excepciones/            (excepciones de dominio)
│   └── ayudantes.php           (función e() de escape, vía autoload "files")
├── vendor/                     (autoload de Composer, ya generado)
└── vistas/                     (login, panel, retiro, transferencia, historiales, layout, 404)
```

Capas: **Modelo** (solo datos) → **Repositorio** (SQL) → **Servicio** (reglas de negocio, transacciones) → **Controlador** (delgado, traduce HTTP) → **Vista** (solo presentación). El flujo de una petición: `public/index.php` → `Router` → `{Controlador}Controlador::{accion}Accion()`.

## 2. Puesta en marcha

### 2.1 Base de datos

1. Crea la base de datos y las tablas:
   ```
   mysql -u root -p < sql/01_creacion.sql
   ```
2. Siembra los 5 clientes/cuentas/usuarios de prueba:
   ```
   mysql -u root -p < sql/02_siembra.sql
   ```

### 2.2 Configuración de conexión

Edita `config/basedatos.php` si tu MySQL/XAMPP usa usuario, contraseña o puerto distintos a los valores por defecto (`root` / sin contraseña / `127.0.0.1:3306`).

### 2.3 Levantar el servidor

Con el servidor embebido de PHP (desde la carpeta del proyecto):

```
php -S localhost:8000 -t public
```

Luego abre `http://localhost:8000` en el navegador.

Si usas XAMPP, apunta el DocumentRoot (o crea un virtual host) a la carpeta `public/` del proyecto.

### 2.4 Composer

El autoload ya viene generado en `vendor/`. Si haces cambios a las clases y `vendor/` no refleja algo nuevo, regenera con:

```
composer dump-autoload
```

## 3. Credenciales de prueba (sembradas en `sql/02_siembra.sql`)

| Cliente | N° de cuenta | Contraseña | Saldo inicial |
|---|---|---|---|
| Luis Fernando Gómez | 1001000001 | Luis#2024 | $5.000.000,00 |
| Malquiel Tirado | 1001000002 | Malquiel#2024 | $8.500.000,00 |
| Magguier Torres | 1001000003 | Magguier#2024 | $3.200.000,00 |
| Jailer Rojas | 1001000004 | Jailer#2024 | $1.200.000,00 |
| Cristian Pérez | 1001000005 | Cristian#2024 | $4.700.000,00 |

En base de datos solo se guarda el hash (`password_hash()`); estas contraseñas en texto plano solo existen aquí para poder iniciar sesión durante la demostración/sustentación.

## 4. Verificación funcional ya realizada

Antes de entregarte el proyecto se levantó una instancia real (PHP 8.4 + MariaDB) y se probó de punta a punta con peticiones HTTP reales: login con credenciales correctas/incorrectas (mismo mensaje genérico en ambos casos), acceso bloqueado sin sesión, retiro válido y rechazado (saldo insuficiente, contraseña incorrecta, valor 0), transferencia válida y rechazada (cuenta destino inexistente, misma cuenta origen/destino), aislamiento de saldo por sesión entre dos cuentas distintas, historiales con conteos y totales correctos, ruta inexistente → 404 sin trazas de PHP, y logout invalidando la sesión. Los 24 casos pasaron.

## 5. Puntos clave para la sustentación (según el Taller Práctico)

- **Normalización a 3FN**: `clientes`, `cuentas` y `usuarios` están separadas porque el saldo depende del número de cuenta (no de la persona) y la `clave_hash` depende únicamente del registro de acceso — evita dependencias transitivas escondidas si todo viviera en una sola tabla.
- **Aislamiento de cuentas (RF2)**: el id de cuenta se lee siempre de `$_SESSION['cuenta_id']` (ver `ControladorBase::idCuentaSesion()`); ningún controlador lo toma de `$_GET`/`$_POST`.
- **Atomicidad de la transferencia (RF6)**: `ServicioCuenta::transferir()` abre la transacción con `beginTransaction()`, hace débito + crédito + inserción del registro, y hace `commit()`/`rollBack()` según el resultado — nunca vive en el controlador.

## 6. Fuera de alcance (según el enunciado)

Registro público, roles administrativos, API REST/JSON, frameworks de frontend/backend, diseño responsive avanzado y pruebas automatizadas quedan explícitamente fuera del alcance de este proyecto.
