#!/usr/bin/env bash
# The GitOps release flow: build, tag with the current commit, write that
# tag into k8s/base/kustomization.yaml, commit, push. Argo CD (watching this
# repo) picks up the new commit and rolls the new image out on its own —
# no kubectl command touches the cluster here. Compare with
# scripts/k8s-deploy.sh, which deploys imperatively (kubectl apply -k) for
# local iteration without Argo CD in the loop; this script is what you run
# once GitOps is the source of truth (see docs/gitops.md).
set -euo pipefail
cd "$(dirname "$0")/.."

if [ -n "$(git status --porcelain)" ]; then
    echo "Working tree isn't clean — commit or stash first (a release commit should contain only the tag bump)." >&2
    exit 1
fi

SERVICES="catalog-service orders-service users-service web-bff notifications-service"
TAG=$(git rev-parse --short HEAD)

echo "==> Building service images (tag: ${TAG})"
docker compose build $SERVICES
for service in $SERVICES; do
    docker tag "gaming-store-${service}:latest" "gaming-store-${service}:${TAG}"
done

echo "==> Writing tag into k8s/base/kustomization.yaml"
sed -i "s/newTag: .*/newTag: ${TAG}/" k8s/base/kustomization.yaml
git diff k8s/base/kustomization.yaml

echo "==> Committing and pushing"
git add k8s/base/kustomization.yaml
git commit -m "release: deploy ${TAG}"
git push origin main

echo
echo "Done. Argo CD will detect this commit and sync within its poll interval"
echo "(3 minutes by default), or force it now with:"
echo "  argocd app sync gaming-store"
