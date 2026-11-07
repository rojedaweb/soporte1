# Soporte de incidencias

Pequeño proyecto educativo de integración realizado con PHP y MariaDB/MySQL.
El formulario registra una incidencia y envía una notificación mediante la
función `mail()` de PHP.

## Requisitos

- PHP 8.1 o superior con la extensión `mysqli` y la función `mail()` habilitada.
- MariaDB o MySQL.

## Instalación

1. Clona el repositorio.
2. Crea una base de datos e importa `database/schema.sql`.
3. Copia `config.example.php` como `config.local.php`.
4. Completa en `config.local.php` las credenciales de la base de datos y el remitente del correo.

El archivo `config.local.php` está excluido de Git y nunca debe publicarse.

## Despliegue automático en cPanel

El script `deploy/deploy-cpanel.sh` mantiene una copia del repositorio en
`~/git_soporte1/repo` y sincroniza la aplicación con
`~/public_soporte1`. Solo ejecuta `rsync` cuando detecta un commit
nuevo en la rama `main`.

Desde la terminal de cPanel, copia el script fuera de `public_soporte1` y dale
permiso de ejecución:

```bash
mkdir -p ~/git_soporte1
git clone https://github.com/rojedaweb/soporte1.git ~/git_soporte1/repo
cp ~/git_soporte1/repo/deploy/deploy-cpanel.sh ~/git_soporte1/updategit.sh
chmod 700 ~/git_soporte1/updategit.sh
~/git_soporte1/updategit.sh
```

Después del primer despliegue, crea la configuración privada del servidor:

```bash
cp ~/git_soporte1/repo/config.example.php ~/public_soporte1/config.local.php
chmod 600 ~/public_soporte1/config.local.php
```

Edita `~/public_soporte1/config.local.php` con los datos reales de la base de
datos y del correo. Las siguientes sincronizaciones conservarán este archivo.

En **cPanel > Trabajos de cron**, programa esta orden cada cinco minutos,
sustituyendo `USUARIO_CPANEL` por el usuario real de la cuenta:

```cron
*/5 * * * * /bin/bash /home/USUARIO_CPANEL/git_soporte1/updategit.sh >> /home/USUARIO_CPANEL/git_soporte1/deploy.log 2>&1
```

El script usa `flock` para que dos ejecuciones no se solapen y `git pull
--ff-only` para no sobrescribir cambios locales inesperados.

