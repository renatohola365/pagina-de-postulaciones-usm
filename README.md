# Página de Postulaciones CT-USM

Aplicación web en PHP para crear, consultar y gestionar postulaciones a iniciativas universitarias. Incluye acceso por roles para administradores, evaluadores y responsables académicos.

## Tecnologías

- PHP con las extensiones `mysqli` y `mbstring`
- MariaDB o MySQL
- HTML y CSS

## Requisitos

- PHP 8.0 o posterior
- MariaDB 10.4 o posterior, o una versión compatible de MySQL
- Extensión PHP `mysqli` habilitada

En Ubuntu o Debian se pueden instalar con:

```bash
sudo apt update
sudo apt install php php-mysql php-mbstring mariadb-server
```

## Instalación de la base de datos

Inicia MariaDB:

```bash
sudo systemctl enable --now mariadb
```

Crea la base de datos y un usuario para la aplicación. Reemplaza `CAMBIA_ESTA_CLAVE` por una contraseña local:

```bash
sudo mariadb
```

En la consola de MariaDB:

```sql
CREATE DATABASE ct_usm_postulaciones CHARACTER SET utf8mb4;
CREATE USER 'postulaciones'@'localhost' IDENTIFIED BY 'CAMBIA_ESTA_CLAVE';
GRANT ALL PRIVILEGES ON ct_usm_postulaciones.* TO 'postulaciones'@'localhost';
EXIT;
```

Importa la estructura y los datos de ejemplo:

```bash
sudo mariadb ct_usm_postulaciones < ct_usm_postulaciones.sql
```

Importa el archivo una sola vez en una base vacía; volver a importarlo puede producir errores por tablas o datos ya existentes.

## Configuración

Edita `bdconex.php` y configura el usuario y la contraseña que creaste:

```php
$servidor   = "localhost";
$usuario    = "postulaciones";
$password   = "CAMBIA_ESTA_CLAVE";
$base_datos = "ct_usm_postulaciones";
```

No publiques contraseñas reales en repositorios compartidos.

## Ejecución local

Desde la carpeta del proyecto, inicia el servidor de desarrollo de PHP:

```bash
php -S 127.0.0.1:8000
```

Abre <http://127.0.0.1:8000> en el navegador. Mantén abierta la terminal mientras uses la aplicación y asegúrate de que MariaDB esté activo.

## Accesos de prueba

El volcado SQL incluye estas cuentas para uso local:

| Rol | RUT | Contraseña |
|---|---|---|
| Administrador | `11.111.111-1` | `1234` |
| Evaluador | `22.222.222-2` | `eval123` |
| Responsable académico | `33.333.333-3` | `123` |

Las contraseñas de ejemplo están almacenadas en texto plano en los datos de prueba. No uses esas cuentas ni ese esquema de contraseñas en producción.

## Funcionalidades principales

- Inicio de sesión y redirección según el rol.
- Panel de administración para revisar postulaciones y asignar evaluadores o responsables.
- Panel de evaluación para revisar postulaciones asignadas y actualizar sus estados.
- Gestión de postulaciones para responsables académicos, incluyendo sus etapas e integrantes.
- Consulta y búsqueda de postulaciones.