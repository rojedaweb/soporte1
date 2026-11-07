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

## Despliegue automático en cPanel

El script `deploy/deploy-cpanel.sh` mantiene una copia del repositorio en
`~/repositories/soporte1` y sincroniza la aplicación con
`~/public_soporte1`. Solo ejecuta Composer y `rsync` cuando detecta un commit
nuevo en la rama `main`.

Desde la terminal de cPanel, copia el script fuera de `public_soporte1` y dale
permiso de ejecución:

```bash
mkdir -p ~/scripts ~/logs ~/repositories
git clone https://github.com/rojedaweb/soporte1.git ~/repositories/soporte1
cp ~/repositories/soporte1/deploy/deploy-cpanel.sh ~/scripts/deploy-soporte1.sh
chmod 700 ~/scripts/deploy-soporte1.sh
~/scripts/deploy-soporte1.sh
```

Después del primer despliegue, crea la configuración privada del servidor:

```bash
cp ~/repositories/soporte1/config.example.php ~/public_soporte1/config.local.php
chmod 600 ~/public_soporte1/config.local.php
```

Edita `~/public_soporte1/config.local.php` con los datos reales de la base de
datos y del correo. Las siguientes sincronizaciones conservarán este archivo.

En **cPanel > Trabajos de cron**, programa esta orden cada cinco minutos,
sustituyendo `USUARIO_CPANEL` por el usuario real de la cuenta:

```cron
*/5 * * * * /bin/bash /home/USUARIO_CPANEL/scripts/deploy-soporte1.sh >> /home/USUARIO_CPANEL/logs/deploy-soporte1.log 2>&1
```

El script usa `flock` para que dos ejecuciones no se solapen y `git pull
--ff-only` para no sobrescribir cambios locales inesperados.

