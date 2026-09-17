# Personal Vault

A minimal, self-hosted personal password vault based on the official Bitwarden Lite image.

## Requirements

- Docker Engine 26 or newer
- Docker Compose
- At least 1 GB free storage and 200 MB RAM
- An installation ID and key from <https://bitwarden.com/host/>

## Start locally

```bash
./scripts/init.sh
```

Edit `.env` and replace:

- `BW_INSTALLATION_ID`
- `BW_INSTALLATION_KEY`
- optionally `adminSettings__admins` with your email

Then start the vault:

```bash
docker compose config --quiet
docker compose up -d
docker compose ps
```

Open <http://localhost:8080>, register your account, then change this setting in `.env`:

```dotenv
globalSettings__disableUserRegistration=true
```

Apply the change:

```bash
docker compose up -d --force-recreate bitwarden
```

## Security notes

- The service binds only to `127.0.0.1` by default.
- Do not expose plain HTTP directly to the internet. Use HTTPS through a reverse proxy, Cloudflare Tunnel, or a private VPN such as Tailscale.
- Use a long, unique master password and enable two-step login immediately.
- Keep `.env`, `data/`, and `backups/` out of Git.
- Do not depend on a single server copy. Keep an encrypted, tested off-site backup.
- Update regularly with `docker compose pull && docker compose up -d`.

## Operations

```bash
# Logs
docker compose logs -f bitwarden

# Stop without deleting data
docker compose down

# Create a timestamped backup
./scripts/backup.sh
```

The admin portal manages server settings; it does not replace your vault account. For a personal setup, create the first account and then disable public registration.

# visa-submit
Platform submit visa for K-Anh Company
