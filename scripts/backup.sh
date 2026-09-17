#!/usr/bin/env bash
set -euo pipefail

project_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
backup_dir="${project_dir}/backups/$(date -u +%Y%m%dT%H%M%SZ)"

cd "${project_dir}"
if [[ ! -f .env ]]; then
  echo "Missing .env. Run ./scripts/init.sh first."
  exit 1
fi

mkdir -p "${backup_dir}"
docker compose exec -T database sh -c \
  'mariadb-dump --single-transaction -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' \
  > "${backup_dir}/database.sql"
tar -C "${project_dir}/data" -czf "${backup_dir}/bitwarden-data.tar.gz" bitwarden
chmod -R go-rwx "${backup_dir}"

echo "Backup created at ${backup_dir}"
echo "The backup contains sensitive encrypted-vault metadata; store it securely."

