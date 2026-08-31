# GitOps (Argo CD)

Git is the single source of truth for what's running in the cluster.
`k8s/base/kustomization.yaml`'s `images:` block holds the one tag actually
deployed; a release is a commit that changes that tag, nothing else. Argo CD
watches this repo and reconciles the cluster to match it automatically —
including reverting anyone who runs `kubectl edit`/`kubectl set image`
directly against the cluster (`selfHeal: true`), which is exactly the
imperative pattern Phase 2/3 used before this phase and that this phase
retires for anything meant to persist (see `scripts/k8s-deploy.sh`'s header
comment — it still exists for quick local iteration, but its own tag edit is
`git checkout`-ed away at the end of the script so it can never linger as
real cluster state).

## Architecture decision: one Application, not five

The plan's original phrasing suggested a per-service Argo CD `Application`
(or an `ApplicationSet` generating one per service). This repo ships **one**
`Application` (`argocd/application.yaml`) covering the whole stack instead —
documented here as a deliberate deviation, same as the kind→Docker Desktop
Kubernetes swap in Phase 2.

Why: `k8s/base/kustomization.yaml` already models the 5 services as a single
Kustomize unit sharing one `images:` block, and every release
(`scripts/gitops-release.sh`) builds and tags all 5 together — there's no
independent release cadence per service to reflect in git yet. Five
Applications syncing the same commit at the same time would be UI noise, not
real independence. If that ever changes (a service gets its own release
pipeline), split `argocd/application.yaml` into an `ApplicationSet`
generator over `services/*/` — the Kustomize structure under
`k8s/base/<service>/` already supports pointing at one service at a time.

## Installing Argo CD

Any Kubernetes cluster works — this project uses Docker Desktop's built-in
Kubernetes (see Phase 2's note on why, in `plan.md`), but a free `kind`
cluster locally, or a free-forever Oracle Cloud Always Free k3s instance,
work identically for everything below.

```bash
kubectl create namespace argocd
# --server-side is required here: Argo CD's CRDs (ApplicationSet especially)
# exceed the 262144-byte annotation limit that a plain client-side
# `kubectl apply` enforces via last-applied-configuration.
kubectl apply -n argocd --server-side --force-conflicts \
  -f https://raw.githubusercontent.com/argoproj/argo-cd/stable/manifests/install.yaml

kubectl wait --for=condition=available --timeout=300s \
  -n argocd deployment --all
```

Get the auto-generated admin password (first login only — rotate or switch
to SSO for anything beyond a local demo):

```bash
kubectl -n argocd get secret argocd-initial-admin-secret \
  -o jsonpath='{.data.password}' | base64 -d
```

Access the UI:

```bash
kubectl port-forward -n argocd svc/argocd-server 8081:443
# open https://localhost:8081, user "admin"
```

## Registering this repo

```bash
kubectl apply -f argocd/application.yaml
```

That's the entire step — `argocd/application.yaml` is itself a tracked file
in this repo (Argo CD's own config is git-managed too, "app of apps" style
minus the extra indirection since there's only one app). It points at
`k8s/overlays/dev` (what this project's cluster actually runs);
`k8s/overlays/prod` (2 replicas on the stateless API/gateway services) is
the production-target overlay, switchable with a one-line `path:` change in
that same file once a real second cluster exists.

Verify:

```bash
kubectl get application gaming-store -n argocd
# NAME           SYNC STATUS   HEALTH STATUS
# gaming-store   Synced        Healthy
```

## Releasing

```bash
./scripts/gitops-release.sh
```

Builds the 5 images tagged with the current commit's short SHA, writes that
tag into `k8s/base/kustomization.yaml`, commits, and pushes to `main`. Argo
CD picks the new commit up on its next poll (3 minutes by default) and
rolls it out — or force it immediately:

```bash
argocd app sync gaming-store
# or, without the CLI:
kubectl patch application gaming-store -n argocd --type merge \
  -p '{"operation":{"sync":{}}}'
```

## What `selfHeal` actually buys here

Before this phase, a rebuild landed on the cluster only if someone
remembered to run `scripts/k8s-deploy.sh` (or manually `kubectl set image`)
straight after `docker compose build` — nothing enforced that the two ever
stayed in sync, and the Phase 2/3 "stale image" bug (`plan.md`) was a direct
symptom of that gap. With Argo CD, the cluster's state is continuously
compared against the `images:` tag in git; drift (a stale pod, an accidental
manual edit, a half-finished imperative rollout) gets corrected within
Argo CD's next reconcile loop instead of silently persisting until someone
notices.

This was verified for real, not just configured: with `syncPolicy.automated.selfHeal: true`
already applied, running

```bash
kubectl set image deployment/catalog-service \
  catalog-service=gaming-store-catalog-service:latest -n gaming-store
```

(a manual edit that bypasses git entirely) took effect on the cluster for a
few seconds — a new ReplicaSet on `:latest` scaled up — and was then
reverted automatically, with no further `kubectl`/`argocd` command run: Argo
CD scaled the drifted ReplicaSet back to 0 and restored the one running the
tag actually declared in `k8s/base/kustomization.yaml`, in under 10 seconds,
confirmed via `kubectl get events` and the final pod's image.
