#!/usr/bin/env bash
set -Eeuo pipefail

# Despliegue automatico de soporte1 en cPanel.
# El repositorio se guarda fuera del directorio publico.

umask 077

readonly REPO_URL="https://github.com/rojedaweb/soporte1.git"
readonly BRANCH="main"
readonly REPO_DIR="${HOME}/repositories/soporte1"
readonly PUBLIC_DIR="${HOME}/public_soporte1"
readonly STATE_DIR="${HOME}/.local/state/soporte1"
readonly STATE_FILE="${STATE_DIR}/deployed-commit"
readonly LOCK_FILE="${STATE_DIR}/deploy.lock"

if [[ -z "${HOME}" || "${HOME}" == "/" || "${PUBLIC_DIR}" != "${HOME}/public_soporte1" ]]; then
  echo "ERROR: ruta de publicacion no valida: ${PUBLIC_DIR}" >&2
  exit 1
fi

mkdir -p "${REPO_DIR%/*}" "${PUBLIC_DIR}" "${STATE_DIR}"

# Evita que dos ejecuciones del cron desplieguen simultaneamente.
exec 9>"${LOCK_FILE}"
if ! flock -n 9; then
  exit 0
fi

if [[ ! -d "${REPO_DIR}/.git" ]]; then
  git clone --branch "${BRANCH}" --single-branch "${REPO_URL}" "${REPO_DIR}"
else
  git -C "${REPO_DIR}" pull --ff-only origin "${BRANCH}"
fi

current_commit="$(git -C "${REPO_DIR}" rev-parse HEAD)"
deployed_commit="$(cat "${STATE_FILE}" 2>/dev/null || true)"

if [[ "${current_commit}" == "${deployed_commit}" ]]; then
  exit 0
fi

if command -v composer >/dev/null 2>&1; then
  composer_bin="$(command -v composer)"
elif [[ -x /opt/cpanel/composer/bin/composer ]]; then
  composer_bin="/opt/cpanel/composer/bin/composer"
else
  echo "ERROR: Composer no esta disponible en cPanel." >&2
  exit 1
fi

"${composer_bin}" install \
  --working-dir="${REPO_DIR}" \
  --no-dev \
  --prefer-dist \
  --optimize-autoloader \
  --no-interaction

# Los archivos excluidos no se publican. config.local.php queda protegido y
# no se elimina del destino aunque se utilice --delete.
rsync -a --delete --delay-updates \
  --exclude='.git/' \
  --exclude='.gitattributes' \
  --exclude='.gitignore' \
  --exclude='README.md' \
  --exclude='composer.json' \
  --exclude='composer.lock' \
  --exclude='config.example.php' \
  --exclude='config.local.php' \
  --exclude='database/' \
  --exclude='deploy/' \
  "${REPO_DIR}/" "${PUBLIC_DIR}/"

printf '%s\n' "${current_commit}" > "${STATE_FILE}.tmp"
mv "${STATE_FILE}.tmp" "${STATE_FILE}"

echo "$(date '+%Y-%m-%d %H:%M:%S') - Desplegado ${current_commit} en ${PUBLIC_DIR}"

