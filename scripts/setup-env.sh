#!/usr/bin/env bash
# Prepares .env for every microservice before the first `docker compose up`.
# Safe to re-run: never overwrites an .env that already exists.
set -euo pipefail
cd "$(dirname "$0")/.."

SERVICES=(catalog-service orders-service users-service notifications-service web-bff chatbot-service)
JWT_SECRET=$(php -r 'echo bin2hex(random_bytes(32));')
INTERNAL_SERVICE_SECRET=$(php -r 'echo bin2hex(random_bytes(32));')

for service in "${SERVICES[@]}"; do
    dir="services/$service"
    if [ ! -f "$dir/.env" ]; then
        cp "$dir/.env.example" "$dir/.env"
        echo "Created $dir/.env"
    fi

    # APP_KEY: generate one if missing (requires vendor/ — run `composer install`
    # in that service first if this fails).
    if grep -q '^APP_KEY=$' "$dir/.env" 2>/dev/null; then
        (cd "$dir" && php artisan key:generate --no-interaction) || \
            echo "WARN: could not generate APP_KEY for $service — run 'composer install' there first."
    fi
done

# Shared secret: catalog/orders/users (and chatbot-service, which also
# verifies caller JWTs — see docs/chatbot.md) all verify JWTs signed by
# users-service, so this value MUST be identical across every one of them.
for service in catalog-service orders-service users-service chatbot-service; do
    dir="services/$service"
    if grep -q '^JWT_SECRET=$' "$dir/.env" 2>/dev/null; then
        sed -i "s/^JWT_SECRET=$/JWT_SECRET=$JWT_SECRET/" "$dir/.env"
    fi
done

# Internal-service secret: only catalog-service and orders-service call each
# other's /api/internal/* endpoints, so only those two need to match.
for service in catalog-service orders-service; do
    dir="services/$service"
    if grep -q '^INTERNAL_SERVICE_SECRET=$' "$dir/.env" 2>/dev/null; then
        sed -i "s/^INTERNAL_SERVICE_SECRET=$/INTERNAL_SERVICE_SECRET=$INTERNAL_SERVICE_SECRET/" "$dir/.env"
    fi
done

if grep -q '^GEMINI_API_KEY=$' services/chatbot-service/.env 2>/dev/null; then
    echo
    echo "NOTE: services/chatbot-service/.env still has an empty GEMINI_API_KEY."
    echo "Get a free key at https://aistudio.google.com/apikey and paste it in,"
    echo "or the chatbot will fail every request (everything else works fine"
    echo "without it — see docs/chatbot.md)."
fi

echo "Done. Run: docker compose up --build"
