#!/usr/bin/env bash
# Builds the 5 service images and deploys the whole stack to whatever
# Kubernetes cluster your current kubectl context points at.
#
# Works with either:
#   - Docker Desktop's built-in Kubernetes (enable it in Docker Desktop
#     settings) — simplest on Windows/Mac, shares the same image store as
#     `docker compose build`, so no image-loading step is needed at all.
#   - kind (https://kind.sigs.k8s.io) — if a cluster named "gaming-store"
#     doesn't exist yet, this script creates one and loads the images into
#     it (kind runs its own separate image store, unlike Docker Desktop k8s).
set -euo pipefail
cd "$(dirname "$0")/.."

NAMESPACE=gaming-store
OVERLAY=${1:-dev}
SERVICES="catalog-service orders-service users-service web-bff notifications-service"

# A unique tag per run, not "latest" — Docker Desktop Kubernetes has been
# observed to keep serving a stale image after a same-tag rebuild (its
# containerd-side cache doesn't always notice the Docker daemon's "latest"
# pointer moved), so a rebuild would silently NOT reach the running pods.
# A fresh tag forces a real (fast, local) pull every time. Learned this the
# hard way: two real bug fixes rebuilt under :latest never actually made it
# into the running cluster until this was added.
BUILD_TAG=$(date +%Y%m%d%H%M%S)

echo "==> Building service images (tag: ${BUILD_TAG})"
docker compose build $SERVICES
for service in $SERVICES; do
    docker tag "gaming-store-${service}:latest" "gaming-store-${service}:${BUILD_TAG}"
done

if command -v kind >/dev/null 2>&1; then
    if ! kind get clusters 2>/dev/null | grep -q "^gaming-store$"; then
        echo "==> Creating kind cluster 'gaming-store'"
        kind create cluster --name gaming-store
    fi
    kubectl config use-context kind-gaming-store
    echo "==> Loading images into kind (it doesn't share the host's image store)"
    for service in $SERVICES; do
        kind load docker-image "gaming-store-${service}:${BUILD_TAG}" --name gaming-store
    done
else
    echo "==> 'kind' not found — assuming the current kubectl context (Docker Desktop"
    echo "    Kubernetes, or similar) shares the local Docker image store directly."
    kubectl cluster-info >/dev/null # fails loudly here if no cluster is reachable
fi

echo "==> Installing ingress-nginx (skipped if already present)"
if ! kubectl get ns ingress-nginx >/dev/null 2>&1; then
    kubectl apply -f https://raw.githubusercontent.com/kubernetes/ingress-nginx/controller-v1.11.3/deploy/static/provider/cloud/deploy.yaml
    echo "    Waiting for the ingress controller to become ready..."
    kubectl wait --namespace ingress-nginx \
        --for=condition=ready pod \
        --selector=app.kubernetes.io/component=controller \
        --timeout=180s
fi

echo "==> Applying secrets (APP_KEY, JWT_SECRET, SMTP — from services/*/.env)"
./scripts/k8s-secrets.sh

echo "==> Applying k8s/overlays/${OVERLAY}"
kubectl apply -k "k8s/overlays/${OVERLAY}"

echo "==> Pointing deployments at the freshly built images (tag: ${BUILD_TAG})"
for service in $SERVICES; do
    kubectl set image "deployment/${service}" "${service}=gaming-store-${service}:${BUILD_TAG}" -n "$NAMESPACE"
done

echo "==> Waiting for rollouts"
for deploy in catalog-service orders-service users-service web-bff notifications-service api-gateway; do
    kubectl rollout status deployment "$deploy" -n "$NAMESPACE" --timeout=180s
done
for sts in catalog-db orders-db users-db rabbitmq; do
    kubectl rollout status statefulset "$sts" -n "$NAMESPACE" --timeout=180s
done

echo "==> Running database migrations"
kubectl exec -n "$NAMESPACE" deploy/catalog-service -- php artisan migrate --force
kubectl exec -n "$NAMESPACE" deploy/orders-service -- php artisan migrate --force
kubectl exec -n "$NAMESPACE" deploy/users-service -- php artisan migrate --force

echo
echo "Done. Reach the site with:"
echo "  kubectl port-forward -n ingress-nginx svc/ingress-nginx-controller 8080:80"
echo "then open http://localhost:8080"
