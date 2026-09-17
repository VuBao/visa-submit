#!/usr/bin/env bash
set -euo pipefail

project_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
env_file="${project_dir}/.env"
template_file="${project_dir}/.env.example"

if [[ -e "${env_file}" ]]; then
  echo ".env already exists; refusing to overwrite it."
  exit 1
fi

db_password="$(openssl rand -hex 32)"
root_password="$(openssl rand -hex 32)"

sed \
  -e "s/REPLACE_WITH_RANDOM_PASSWORD/${db_password}/" \
  -e "s/REPLACE_WITH_SAME_RANDOM_PASSWORD/${db_password}/" \
  -e "s/REPLACE_WITH_ANOTHER_RANDOM_PASSWORD/${root_password}/" \
  "${template_file}" > "${env_file}"

chmod 600 "${env_file}"
mkdir -p "${project_dir}/data/bitwarden" "${project_dir}/data/mariadb" "${project_dir}/backups"

echo "Created ${env_file} with random database passwords."
echo "Next: add BW_INSTALLATION_ID and BW_INSTALLATION_KEY, then run: docker compose up -d"

