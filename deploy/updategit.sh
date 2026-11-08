#!/usr/bin/env bash
set -e

# CONFIGURACION DEL ALUMNO
# Modifica estas dos rutas para adaptarlas a tu cuenta de cPanel.
REPO_DIR="${HOME}/git_soporte1/repo"
PUBLIC_DIR="${HOME}/public_soporte1"

# Actualiza el repositorio con los ultimos cambios de GitHub.
git -C "${REPO_DIR}" pull

# Copia la web al directorio publico.
rsync -av --delete \
  --exclude='.git/' \
  --exclude='.gitattributes' \
  --exclude='.gitignore' \
  --exclude='README.md' \
  --exclude='config.example.php' \
  --exclude='config.local.php' \
  --exclude='database/' \
  --exclude='deploy/' \
  "${REPO_DIR}/" "${PUBLIC_DIR}/"

echo "Despliegue terminado en ${PUBLIC_DIR}"
