# Soporte de incidencias

Pequeño proyecto educativo de integración realizado con PHP y MariaDB/MySQL.
El formulario registra una incidencia y envía una notificación mediante la
función `mail()` de PHP.

## Requisitos

- PHP 8.1 o superior con la extensión `mysqli` y la función `mail()` habilitada.
- MariaDB o MySQL.

## Instalación

1. Clona el repositorio.
2. Crea una base de datos e importa el archivo `bdincidencias.gz`.
3. Copia `config.example.php` como `config.local.php`.
4. Completa en `config.local.php` las credenciales de la base de datos y el remitente del correo.

El archivo `config.local.php` está excluido de Git y nunca debe publicarse.

## Despliegue manual en cPanel

El archivo `deploy/updategit.sh` contiene un script sencillo que ejecuta
`git pull` para actualizar el repositorio y `rsync` para copiar la aplicación
al directorio público.

### Primera instalación

Desde la terminal de cPanel, clona el repositorio y copia el script fuera del
directorio público:

```bash
mkdir -p ~/git_soporte1
git clone https://github.com/rojedaweb/soporte1.git ~/git_soporte1/repo
cp ~/git_soporte1/repo/deploy/updategit.sh ~/git_soporte1/updategit.sh
chmod 700 ~/git_soporte1/updategit.sh
```

Antes de ejecutarlo, abre `~/git_soporte1/updategit.sh` y adapta estas dos
variables a las rutas de la cuenta de cada alumno:

```bash
REPO_DIR="${HOME}/git_soporte1/repo"
PUBLIC_DIR="${HOME}/public_soporte1"
```

Ejecuta el despliegue:

```bash
~/git_soporte1/updategit.sh
```

Después, crea la configuración privada del servidor:

```bash
cp ~/git_soporte1/repo/config.example.php ~/public_soporte1/config.local.php
chmod 600 ~/public_soporte1/config.local.php
```

Edita `~/public_soporte1/config.local.php` con los datos reales de la base de
datos y del correo. Las siguientes sincronizaciones conservarán este archivo.

### Actualizaciones posteriores

Cada vez que se quiera actualizar la web, ejecuta manualmente:

```bash
~/git_soporte1/updategit.sh
```

La carpeta `deploy` no aparece dentro de `public_soporte1` porque el propio
`rsync` la excluye. El script que debe ejecutarse queda guardado fuera del
directorio público, en `~/git_soporte1/updategit.sh`.

