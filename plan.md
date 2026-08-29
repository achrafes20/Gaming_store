# Transformer Gaming Store en projet vitrine DevOps + microservices

## Contexte

Le projet est actuellement un monolithe Laravel déployé par FTP (InfinityFree, `.github/workflows/deploy.yml`), sans Docker, sans tests en CI, sans scan de sécurité, sans monitoring, avec une seule base de données partagée. L'utilisateur a suivi une formation IBM DevOps (dossier `IBM DevOps/` : Cloud, CI/CD, Application Security, Monitoring) et veut en faire une pièce forte de CV — un recruteur doit pouvoir ouvrir le repo et voir une vraie architecture microservices avec un pipeline DevOps complet, pas une todo-list.

Décisions validées avec l'utilisateur :
- **Architecture** : découpage par domaine métier, base de données dédiée par service, communication REST synchrone + événements asynchrones.
- **Contraintes DevOps** : 100% gratuit, Docker + Kubernetes, niveau "showcase maximal" (CI/CD avancé, sécurité, monitoring, GitOps, IaC).

Comme je n'ai pas accès à des comptes cloud/CI réels de l'utilisateur, tout ce plan produit des **artefacts fonctionnels dans le repo**, exécutables localement (Docker Desktop + kind, 100% gratuits) et sur GitHub Actions (gratuit sur repo public). Les étapes nécessitant un compte externe (cluster cloud permanent, secrets GitHub) sont documentées précisément mais réalisées par l'utilisateur.

C'est un projet volumineux : il sera livré **phase par phase**, chaque phase testée et validée avant de passer à la suivante — pas tout d'un bloc.

## Architecture cible

```
                        ┌─────────────────┐
                        │   API Gateway    │  (Nginx, routage par préfixe)
                        │  /catalog /orders │
                        │  /users  /web      │
                        └───┬────┬────┬────┬─┘
              ┌─────────────┘    │    │    └──────────────┐
              ▼                  ▼    ▼                    ▼
     ┌────────────────┐ ┌──────────────┐ ┌──────────────┐ ┌───────────────────┐
     │ catalog-service │ │ orders-service│ │ users-service│ │  web-bff (Blade)  │
     │ (Laravel API)   │ │ (Laravel API) │ │(Laravel API) │ │  UI + orchestration│
     │ produits, cat.,  │ │ panier, cmd,  │ │ auth (JWT),  │ │  appelle les autres│
     │ photos, reviews  │ │ paiement,     │ │ favoris,     │ │  services en REST  │
     │ produit          │ │ coupons       │ │ avis, subs   │ │                    │
     └───────┬─────────┘ └──────┬───────┘ └──────┬───────┘ └────────────────────┘
             │ DB dédiée         │ DB dédiée       │ DB dédiée
             ▼                   ▼                 ▼
        catalog_db          orders_db          users_db

     orders-service ──(événement "order.created")──▶ RabbitMQ ──▶ notifications-service ──▶ email
```

- **catalog-service** : `Product`, `Categories`, `ProductPhoto`, `Review_Product` — API REST, DB `catalog_db`
- **orders-service** : `Cart`, `Order`, `OrderDetails`, `Payments`, `Coupon`, `Coupon_User` — API REST, DB `orders_db`, publie `order.created` sur RabbitMQ, appelle `catalog-service` en REST pour prix/stock au moment du checkout
- **users-service** : `User`, `Favorite`, `Review`, `Sub` — API REST, DB `users_db`, émet les JWT (auth centralisée), consomme aussi les événements pour mettre à jour l'historique si besoin
- **notifications-service** : petit worker Laravel sans DB propre, consomme RabbitMQ (`order.created`, `user.registered`) et envoie les emails (reprend `OrderConfirmedMail`, `CustomVerifyEmail`)
- **web-bff** : garde les vues Blade actuelles, mais chaque contrôleur appelle les services via un client HTTP (Guzzle) au lieu d'Eloquent local ; gère la session utilisateur et transmet le JWT
- **api-gateway** : Nginx, un seul point d'entrée public, route par préfixe vers chaque service

**Authentification inter-services** : `users-service` émet des JWT signés (package `tymon/jwt-auth`, secret partagé via variable d'env identique sur tous les services) — chaque service vérifie le JWT localement sans appel réseau supplémentaire (évite le couplage synchrone excessif).

## Phase 0 — Extraction des microservices (la plus grosse phase)

**Statut : ✅ Terminé (9/9 étapes) — validé avec de vrais conteneurs Docker**

But : passer d'un monolithe à 5 codebases Laravel indépendantes dans un monorepo, chacune avec son propre `composer.json`, ses migrations, son `.env`.

1. ✅ Arborescence `services/` créée avec les 5 services : `catalog-service`, `orders-service`, `users-service`, `notifications-service`, `web-bff` (Laravel 12).
2. ✅ **catalog-service** : `Product`, `Categories`, `ProductPhoto`, `ReviewProduct`. API REST, `JwtAuth`, endpoint interne de décrément de stock, vérification d'achat inter-services pour les avis.
3. ✅ **orders-service** : `Cart`, `Order`, `OrderDetails`, `Payments`, `Coupon`. Panier, checkout transactionnel, aperçu de coupon stateless, vue admin toutes commandes. Publie `order.created` sur RabbitMQ.
4. ✅ **users-service** : `User`, `Favorite`, `Review`, `Sub`. Auth JWT stateless, favoris, rôles admin. Publie `user.registered` sur RabbitMQ.
5. ✅ **notifications-service** : worker `events:consume` consommant RabbitMQ, envoie `OrderConfirmedMail`/`WelcomeMail`.
6. ✅ **web-bff** : toutes les vues Blade reprises, 3 clients HTTP, guard d'authentification maison (`SessionJwtGuard`/`SessionUser`) pour que `Auth::check()`/`@guest` fonctionnent sans base locale.
7. ✅ **api-gateway** : `gateway/nginx.conf`, routage par préfixe vers les 3 API + `web-bff` en catch-all.
8. ✅ **docker-compose.yml** complet : 5 services applicatifs + 3 MySQL + RabbitMQ + api-gateway (10 conteneurs), `Dockerfile` par service (nginx + php-fpm + supervisord dans un seul conteneur), `.dockerignore` racine, script `scripts/setup-env.sh` (génère `APP_KEY` et un `JWT_SECRET` partagé pour les 3 services qui vérifient les tokens).
9. ⬜ Ancien monolithe non touché volontairement — `deploy.yml` le déploie encore en prod (FTP InfinityFree) ; à retirer seulement quand vous déciderez de basculer la prod vers la stack microservices.

**Bugs corrigés pendant l'implémentation** (tous trouvés en testant réellement, pas en relisant le code) :

*Découverts en local (SQLite, 4 serveurs `php artisan serve`)* :
- `EventPublisher` catchait `\Exception` mais pas `\Error` — sur Windows, `php-amqplib` lève une `Error` fatale (`Undefined constant SOCKET_EAGAIN`). Corrigé en catchant `\Throwable`.
- `catalog-service` appelait `/internal/has-purchased` au lieu de `/api/internal/has-purchased` → tout avis produit refusé à tort. Corrigé.
- **Bug architectural transverse** : un `stdClass` de `json_decode()` lève une erreur sur propriété absente (contrairement à Eloquent qui retourne `null`) — aurait cassé la quasi-totalité des vues Blade reprises du monolithe. Corrigé une fois pour toutes via un wrapper `App\Support\ApiObject`.
- `FirstController::Product_page()` : ordre des paramètres empêchant l'injection Laravel de `CatalogClient`. Corrigé.
- Vue `product.blade.php` appelait une relation Eloquent (`Auth::user()->favorites()`) inexistante sur le `SessionUser` maison. Résolu par un cache des IDs favoris en session.

*Découverts en construisant/lançant la stack Docker (donc invisibles sans un vrai `docker compose up`)* :
- Extension PHP `sockets` (requise par `php-amqplib`) ne compile pas sous Alpine sans le paquet `linux-headers` (header noyau manquant). Ajouté aux 5 Dockerfiles.
- `bootstrap/cache/packages.php`, généré localement avec les dépendances dev, était copié dans l'image et référençait `laravel/pail` absent en `--no-dev` → `composer dump-autoload` cassait le build. Cause racine plus profonde : avec `context: .` (racine du repo, nécessaire pour que les Dockerfiles accèdent à `docker/nginx/*.conf` partagé), Docker ne lit **que** le `.dockerignore` à la racine — les `.dockerignore` par service étaient silencieusement ignorés, donc `vendor/` de tous les services partait dans le contexte de build (80 Mo × 5, builds très lents). Remplacé par un `.dockerignore` unique à la racine.
- `.env` local (basculé en `sqlite` pour les tests) réutilisé tel quel par `docker-compose` → écriture impossible en "base sqlite" nommée `catalog_db`. Corrigé en forçant `DB_CONNECTION`/`DB_PASSWORD` dans `docker-compose.yml`, indépendamment de l'état local du `.env`.
- `php-amqplib` 2.8 (version réellement résolue par Composer) n'a **ni** `AMQPChannel::is_consuming()` **ni** `AMQPMessage::ack()/nack()` — ce sont des méthodes ajoutées en v3. Trois corrections successives : boucle de consommation réécrite en `count($channel->callbacks)`, timeout de lecture du consommateur allongé (3600s + heartbeat, car un consommateur bloque en attente contrairement à l'éditeur "fail-fast"), et ack/nack réécrits via l'API bas niveau `$message->delivery_info['channel']->basic_ack(...)`.
- `public/uploads` n'existait pas dans l'image `catalog-service` (jamais créé explicitement, contrairement à l'environnement de dev local) → upload d'image en échec. Corrigé (dossier créé + `chown` + volume nommé pour la persistance entre redéploiements).
- **Gotcha nginx classique** : `proxy_pass http://backend:80;` **sans slash final** ignore silencieusement tout `rewrite` précédent et transmet l'URI originale du client telle quelle — cassait les 3 routes de l'API Gateway (`/api/catalog/*`, `/api/orders/*`, `/api/users/*`), qui répondaient 404 malgré une configuration en apparence correcte. Corrigé en ajoutant le `/` final sur les trois `proxy_pass`.

**Testé en conditions réelles avec de vrais conteneurs Docker (`docker compose up`, 10 conteneurs, MySQL réel ×3, vrai RabbitMQ)** :
- Migrations exécutées sur MySQL réel dans les 3 services
- Parcours client complet via l'API Gateway (`http://localhost:8080`, un seul point d'entrée) : inscription → catalogue → panier → checkout → confirmation → **stock décrémenté inter-services (15 → 14)**
- Les 3 routes API du gateway validées individuellement (`/api/catalog/*` → catalog-service, `/api/orders/*` → orders-service avec 401 correct sur endpoint protégé, `/api/users/*` → users-service)
- **Pipeline d'événements RabbitMQ validé de bout en bout avec un vrai broker** : `order.created` et `user.registered` publiés, routés, consommés par `notifications-service`, tentative d'envoi d'email échouant proprement sur l'authentification SMTP (attendu — pas d'identifiants Gmail réels en test) sans jamais planter le worker
- Création de catégorie/produit avec upload d'image réel via l'API Gateway (admin JWT)

**Non exercé individuellement** (même schéma que le parcours validé, mais pas testé un par un faute de temps) : écrans admin de `web-bff` (CRUD produits/catégories/coupons/utilisateurs).

## Phase 1 — Conteneurisation par service

**Statut : ✅ Terminé** (réalisé en même temps que la Phase 0, étape 8 — les deux étaient trop liées pour les séparer proprement)

- ✅ Un `Dockerfile` par service (`services/*/Dockerfile`) : build multi-stage (Composer + npm pour `web-bff`), image finale `php:8.2-fpm-alpine` + nginx + supervisord dans le même conteneur (pattern "un conteneur = un service complet", plus simple à orchestrer qu'un sidecar nginx séparé)
- ✅ `docker-compose.yml` racine orchestrant 10 conteneurs : 5 services applicatifs, 3 MySQL (une par service, avec healthcheck), RabbitMQ (management UI sur :15672), api-gateway
- ✅ `.env.example` par service, `.dockerignore` unique à la racine, `scripts/setup-env.sh` pour amorcer tous les `.env` + secret JWT partagé

**Vérification** : `docker compose up --build` → tous les conteneurs `healthy`/`Up`, parcours utilisateur complet (inscription → catalogue → panier → checkout) fonctionnel via l'API Gateway sur `http://localhost:8080`, RabbitMQ management UI accessible sur `http://localhost:15672` (guest/guest). **Réellement exécuté et validé**, pas seulement écrit.

## Phase 2 — Kubernetes (manifests + Kustomize)

- `k8s/base/<service>/` (Deployment, Service, ConfigMap, Secret template) pour chacun des 5 services + RabbitMQ + 3 MySQL (StatefulSet) + Ingress pour l'API Gateway
- `k8s/overlays/dev` et `k8s/overlays/prod` (Kustomize)
- `scripts/kind-demo.sh` : cluster `kind` local, build+charge les 5 images, déploie tout, un seul point d'entrée testable

**Vérification** : `kind create cluster`, `kubectl apply -k k8s/overlays/dev`, tous les pods `Running`, parcours utilisateur via `kubectl port-forward` sur l'Ingress.

## Phase 3 — CI/CD (GitHub Actions)

- `.github/workflows/ci.yml` avec une **matrix strategy** sur les 5 services : lint (Pint) → tests (PHPUnit par service, DB SQLite en mémoire pour les tests) → sécurité (composer/npm audit, gitleaks, Semgrep) → build & push image GHCR par service → scan Trivy par image → validation Kustomize
- Le pipeline ne rebuild/déploie que les services dont le code a changé (path filters) — bonne pratique CI microservices
- `.github/workflows/deploy.yml` existant conservé, adapté ou remplacé selon ce qui reste pertinent une fois `web-bff` en place (à revoir ensemble à ce moment)

- **Pre-commit hook local** (`.githooks/pre-commit` ou package `captainhook/captainhook`, installé via `composer install` dans chaque service) : lance `pint --test` avant d'accepter le commit sur la machine du développeur — bloque un commit mal formaté avant même le push. Le pipeline CI serveur ci-dessus rejoue les mêmes vérifications indépendamment (au cas où le hook est absent/contourné avec `--no-verify` sur une autre machine), garantissant le contrôle à deux niveaux : local (rapide, avant commit) et serveur (fiable, avant merge/déploiement).

**Vérification** : push sur une branche modifiant un seul service → seul le job de ce service se déclenche dans Actions ; un commit local avec du code mal formaté est rejeté par le hook avant même d'atteindre GitHub.

## Phase 4 — GitOps (Argo CD)

- Une `Application` Argo CD par service (ou une `ApplicationSet` générant les 5 automatiquement) pointant vers `k8s/overlays/prod`
- `docs/gitops.md` : instructions d'installation sur un cluster (kind local ou Oracle Cloud Always Free k3s, gratuit à vie)

## Phase 5 — Sécurité applicative

- Middleware `SecurityHeaders` répliqué sur chaque service API + `web-bff`
- Rate limiting sur les endpoints sensibles (`/login`, `/register`, `/StoreOrder`) de chaque service concerné
- Validation JWT centralisée dans un middleware partagé (package interne ou trait dupliqué documenté)
- `SECURITY.md` mappant chaque contrôle à l'OWASP Top 10

## Phase 6 — Monitoring & Observabilité (le plus pertinent une fois multi-services)

- Chaque service expose `/metrics` (Golden Signals : latence, trafic, erreurs, saturation) via `promphp/prometheus_client_php`
- Prometheus scrape les 5 services + RabbitMQ (exporter officiel)
- Grafana : dashboard global multi-services + dashboard par service
- Tracing distribué basique (trace ID propagé dans les headers HTTP entre `web-bff` → services, et dans les messages RabbitMQ) exporté vers Jaeger — **c'est ici que le passage en microservices rend le tracing distribué réellement pertinent**, contrairement au monolithe où il n'aurait eu aucun sens
- Logs JSON structurés par service, agrégés via Loki (docker-compose)

## Phase 7 — Documentation "vitrine CV"

- `README.md` : schéma d'architecture (Mermaid, celui ci-dessus formalisé), badges CI par service, section "DevOps & microservices practices applied"
- `ARCHITECTURE.md` détaillé : découpage des domaines, choix techniques (pourquoi JWT plutôt qu'appel synchrone, pourquoi RabbitMQ, pourquoi DB par service)

## Ordre d'exécution

Phase 0 d'abord et seule dans un premier temps (c'est le changement fondamental, tout le reste en dépend) — je la découperai moi-même en sous-étapes livrées une par une (catalog-service d'abord, puis orders-service, etc.) avec test à chaque étape plutôt qu'un big-bang. Une fois Phase 0 validée et le parcours utilisateur complet fonctionnel en microservices, on enchaîne 1 → 3 → 5 → 2/4 → 6 → 7 comme dans le plan DevOps initial.
