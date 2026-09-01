#!/usr/bin/env bash
# Creates/updates the K8s Secrets that base/kustomization.yaml deliberately
# does NOT list (APP_KEY, JWT_SECRET, SMTP credentials — real values,
# nothing to commit). Reads them straight from the already-populated
# services/*/.env files (see scripts/setup-env.sh) so there is exactly one
# source of truth, whether you run this project via docker-compose or k8s.
set -euo pipefail
cd "$(dirname "$0")/.."

NAMESPACE=gaming-store
kubectl get namespace "$NAMESPACE" >/dev/null 2>&1 || kubectl create namespace "$NAMESPACE"

env_value() { grep -m1 "^$2=" "services/$1/.env" | cut -d= -f2- | tr -d '"'; }

apply_secret() {
    local name=$1; shift
    kubectl create secret generic "$name" -n "$NAMESPACE" "$@" \
        --dry-run=client -o yaml | kubectl apply -f -
}

# Shared across catalog/orders/users-service — they all verify JWTs minted by
# users-service, so this MUST be identical everywhere (see docs/architecture).
# INTERNAL_SERVICE_SECRET rides along in the same Secret object: it's only
# shared between catalog-service and orders-service (not users-service), but
# reusing this Secret avoids introducing a second K8s object just for one
# more key — see SECURITY.md for what it protects.
apply_secret jwt-secret \
    --from-literal=JWT_SECRET="$(env_value catalog-service JWT_SECRET)" \
    --from-literal=INTERNAL_SERVICE_SECRET="$(env_value catalog-service INTERNAL_SERVICE_SECRET)"

for service in catalog-service orders-service users-service web-bff; do
    apply_secret "${service}-secret" --from-literal=APP_KEY="$(env_value "$service" APP_KEY)"
done

apply_secret notifications-service-secret \
    --from-literal=APP_KEY="$(env_value notifications-service APP_KEY)" \
    --from-literal=MAIL_USERNAME="$(env_value notifications-service MAIL_USERNAME)" \
    --from-literal=MAIL_PASSWORD="$(env_value notifications-service MAIL_PASSWORD)"

# GEMINI_API_KEY can't be auto-generated (it's a real Google AI Studio key
# you paste in yourself, see services/chatbot-service/.env.example) — this
# just carries whatever's in services/chatbot-service/.env, blank or not.
apply_secret chatbot-service-secret \
    --from-literal=APP_KEY="$(env_value chatbot-service APP_KEY)" \
    --from-literal=GEMINI_API_KEY="$(env_value chatbot-service GEMINI_API_KEY)"

echo "Secrets applied in namespace $NAMESPACE."
