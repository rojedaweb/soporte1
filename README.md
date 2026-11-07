# Soporte de incidencias

Pequeño proyecto educativo de integración realizado con PHP, MariaDB/MySQL y
PHPMailer. El formulario registra una incidencia y envía una notificación por
correo electrónico.

## Requisitos

- PHP 8.1 o superior con las extensiones `mysqli` y `fileinfo`.
- MariaDB o MySQL.
- Composer.

## Instalación

1. Clona el repositorio.
2. Ejecuta `composer install --no-dev`.
3. Crea una base de datos e importa `database/schema.sql`.
4. Copia `config.example.php` como `config.local.php`.
5. Completa en `config.local.php` las credenciales de la base de datos y del correo.

El archivo `config.local.php` está excluido de Git y nunca debe publicarse.

