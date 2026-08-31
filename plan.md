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

**Rôle du gateway — client uniquement, pas les appels inter-services** : `api-gateway` est une façade publique qui ne sert que le trafic **client → services** (navigateur → `http://localhost:8080`). Les appels **service → service** ne passent jamais par lui : `orders-service` appelle `catalog-service` directement (`CATALOG_SERVICE_URL=http://catalog-service`) pour vérifier prix/stock et décrémenter le stock au checkout ; `catalog-service` appelle `orders-service` directement (`http://orders-service`) pour vérifier un achat avant d'accepter un avis ; `web-bff` appelle les 3 API directement. Chaque service résout les autres par leur nom de conteneur Docker (réseau interne `docker-compose`), sans détour par le gateway. C'est un choix volontaire (évite un saut réseau et un point de contention supplémentaires sur le chemin interne) — à documenter explicitement avec un schéma dans `ARCHITECTURE.md` (Phase 7).

## Phase 0 — Extraction des microservices (la plus grosse phase)

**Statut : ✅ Terminé (10/10 étapes, monolithe retiré) — validé avec de vrais conteneurs Docker**

But : passer d'un monolithe à 5 codebases Laravel indépendantes dans un monorepo, chacune avec son propre `composer.json`, ses migrations, son `.env`.

1. ✅ Arborescence `services/` créée avec les 5 services : `catalog-service`, `orders-service`, `users-service`, `notifications-service`, `web-bff` (Laravel 12).
2. ✅ **catalog-service** : `Product`, `Categories`, `ProductPhoto`, `ReviewProduct`. API REST, `JwtAuth`, endpoint interne de décrément de stock, vérification d'achat inter-services pour les avis.
3. ✅ **orders-service** : `Cart`, `Order`, `OrderDetails`, `Payments`, `Coupon`. Panier, checkout transactionnel, aperçu de coupon stateless, vue admin toutes commandes. Publie `order.created` sur RabbitMQ.
4. ✅ **users-service** : `User`, `Favorite`, `Review`, `Sub`. Auth JWT stateless, favoris, rôles admin. Publie `user.registered` sur RabbitMQ.
5. ✅ **notifications-service** : worker `events:consume` consommant RabbitMQ, envoie `OrderConfirmedMail`/`WelcomeMail`.
6. ✅ **web-bff** : toutes les vues Blade reprises, 3 clients HTTP, guard d'authentification maison (`SessionJwtGuard`/`SessionUser`) pour que `Auth::check()`/`@guest` fonctionnent sans base locale.
7. ✅ **api-gateway** : `gateway/nginx.conf`, routage par préfixe vers les 3 API + `web-bff` en catch-all.
8. ✅ **docker-compose.yml** complet : 5 services applicatifs + 3 MySQL + RabbitMQ + api-gateway (10 conteneurs), `Dockerfile` par service (nginx + php-fpm + supervisord dans un seul conteneur), `.dockerignore` racine, script `scripts/setup-env.sh` (génère `APP_KEY` et un `JWT_SECRET` partagé pour les 3 services qui vérifient les tokens).
9. ✅ **Ancien monolithe retiré** : `app/`, `bootstrap/`, `config/`, `database/`, `public/`, `resources/`, `routes/`, `tests/`, `artisan`, `composer.json/.lock`, `phpunit.xml`, `vite.config.js`, `package.json/-lock.json`, `.env.example`, `commande.txt`, `test.html`, ainsi que `.github/workflows/deploy.yml` (déployait ce monolithe par FTP sur InfinityFree — devenu sans objet). Racine du repo réduite à : `services/`, `docker/`, `docker-compose.yml`, `gateway/`, `scripts/`, `IBM DevOps/`, `plan.md`, `README.md`. Un `git stash` local (non poussé) conserve les derniers correctifs faits sur le monolithe avant sa suppression, par précaution.

**⚠️ Le site InfinityFree n'est donc plus déployé** depuis cette suppression (`deploy.yml` retiré). Décision explicitement validée avec vous : la bascule vers les microservices était voulue, pas de retour arrière prévu vers ce déploiement.

10. ✅ **Export vers un nouveau dépôt GitHub** (repo dédié microservices, séparé de l'historique `achrafes20/Gaming_store`) : branche orpheline `microservices-export` créée avec un historique neuf (un seul commit racine) contenant exactement l'état actuel du repo — évite de traîner l'ancien historique du monolithe, qui contenait un identifiant Gmail exposé (`.env.example`, déjà corrigé en surface mais toujours présent dans les vieux commits). 419 fichiers, vérifiés sans `vendor/`, `node_modules/`, `.env` réel, ni sous-module cassé (un `.git` imbriqué résiduel dans `services/orders-service`, issu d'un `composer create-project --prefer-source` interrompu au tout début du projet, a été détecté et nettoyé avant le commit).
    - **En attente** : l'utilisateur crée le nouveau repo vide sur GitHub.com et fournit l'URL — `gh` CLI n'étant pas installé sur cette machine, la création automatique via API n'est pas possible ; le push se fera avec l'URL fournie (`git remote add`, puis `git push <remote> microservices-export:main`).

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

### Campagne de tests complète post-suppression du monolithe

Après la suppression du monolithe, une passe de test exhaustive a révélé **7 bugs supplémentaires** invisibles jusque-là (jamais exercés depuis que `web-bff` existe) — tous corrigés et re-testés :

1. **`public/assets/` jamais copié dans `web-bff`** : lors de la reprise des vues Blade, le dossier statique du thème (CSS/JS/images "cyberpunk", 90 fichiers, hors pipeline Vite) avait été oublié — le site s'affichait en HTML brut sans aucun style. Récupéré depuis l'historique Git (commit avant suppression du monolithe) et copié dans `services/web-bff/public/assets/`.
2. **DNS mis en cache par le gateway** : `proxy_pass http://catalog-service:80/;` avec un hostname littéral résout l'IP UNE SEULE FOIS au démarrage de nginx et la garde indéfiniment — rebuilder n'importe quel service backend (nouvelle IP Docker) casse silencieusement le routage tant que le gateway lui-même n'est pas redémarré. Corrigé avec `resolver 127.0.0.11 valid=10s;` + `proxy_pass` basé sur une variable.
3. **Perte du rewrite avec `proxy_pass` basé sur une variable** : une fois le host mis en variable pour la correction précédente, nginx ne peut plus faire son remplacement automatique du préfixe d'URI — un `/` final littéral devient l'URI complète envoyée (toujours `/`), ignorant le `rewrite`. Corrigé en forwardant explicitement `$uri$is_args$args`.
4. **`SESSION_DRIVER=file` sans volume persistant** : chaque rebuild de `web-bff` recrée le conteneur avec un système de fichiers vierge, effaçant `storage/framework/sessions` et déconnectant silencieusement tous les visiteurs. Corrigé avec un volume Docker nommé (`web_bff_sessions`).
5. **`ApiObject` (wrapper de réponse JSON) incomplet** : `->count()` échouait sur les listes imbriquées (tableaux PHP bruts au lieu de `Collection`), et les champs `*_at` (dates) restaient de simples chaînes au lieu d'objets `Carbon`, cassant tout appel `->format()`/`->diffForHumans()` hérité du monolithe. Les deux corrigés une fois pour toutes dans `ApiObject::wrap()`, plus l'ajout d'un `__set` pour permettre l'enrichissement de données côté `web-bff`.
6. **Incohérences de nommage de champs reliquats du monolithe** : `$product->ProductPhotos`/`$product->Category` (PascalCase, ancienne convention Eloquent) au lieu de `product_photos`/`category` (snake_case, convention JSON de l'API) ; `$item->orderdetails` au lieu de `order_details`. Corrigés dans les vues concernées.
7. **`orders-service` ne renvoie que `product_id`, jamais le nom/l'image du produit** (il ne possède pas ces données) — mais les vues `previousorder`/`orders` (admin) attendaient `$detail->product->name`/`->imagepath`. Corrigé en ajoutant un enrichissement cross-service côté `web-bff` (son rôle exact de BFF) : récupération en lot des produits uniques depuis `catalog-service`, attachés à chaque ligne de commande — avec un piège au passage (`ApiObject::__get` recrée une `Collection` neuve à chaque accès, sans mémoïsation ; la première version de l'enrichissement mutait une copie jetable et perdait le travail silencieusement — corrigé en réécrivant explicitement la collection enrichie sur l'objet parent).

**Deux derniers bugs trouvés après un test visuel réel dans le navigateur** (les tests en `curl` précédents ne les voyaient pas, puisque `curl` ne charge jamais les ressources référencées par le HTML) :

8. **`proxy_set_header Host $host;` tronque le port** : la variable nginx `$host` exclut le port par définition — le gateway transmettait donc `Host: 127.0.0.1` (sans `:8080`) à `web-bff`, qui générait alors tous ses liens `asset()`/`url()` (CSS, JS, logo) sans port. Le navigateur essayait de charger `http://127.0.0.1/assets/css/cyber-main.css` (port 80 implicite, rien n'y écoute) — d'où la page revenue à un HTML totalement sans style malgré des fichiers CSS présents et valides. Invisible en `curl` direct sur le chemin exact (`curl http://127.0.0.1:8080/assets/...` fonctionne très bien), seul le lien généré dans le HTML était faux. Corrigé en remplaçant `$host` par `$http_host` (qui préserve le port) sur les 4 blocs `location` du gateway.
9. **Images produit 404 via le gateway** : les fichiers uploadés vivent physiquement sur `catalog-service` (`public/uploads`, volume nommé `catalog_uploads`), mais les vues les référencent en URL relative `/uploads/...` — non routée explicitement, cette requête tombait dans le bloc catch-all `location /` (vers `web-bff`, qui ne les a pas). Corrigé en ajoutant un bloc `location /uploads/` dédié vers `catalog-service`.

**Suite de tests de bout en bout, à froid, exécutée avec succès (8/8)** après tous ces correctifs, **incluant cette fois le chargement réel des assets** (CSS, logo, image produit, pas seulement les pages HTML) : inscription → page d'accueil (stylée, CSS/logo/image vérifiés individuellement) → fiche produit → ajout panier → page panier → checkout → historique de commandes → API catalogue via le gateway. Testé aussi séparément : connexion, avis produit (avec vérification d'achat inter-services réelle), recherche, favoris, promotion admin d'un utilisateur, création/suppression de coupon, les 8 pages admin (produits, catégories, coupons, utilisateurs, commandes) avec contenu vérifié (pas seulement le code HTTP). Logs de tous les conteneurs scannés en fin de campagne : aucune erreur résiduelle hors échecs SMTP attendus (pas d'identifiants Gmail réels en test).

## Phase 2 — Kubernetes (manifests + Kustomize)

**Statut : ✅ Terminé — déployé et validé sur un vrai cluster Kubernetes (Docker Desktop Kubernetes, pas juste `kubectl kustomize` à blanc)**

**Changement par rapport au plan initial** : `kind` n'étant pas installé sur la machine, et Docker Desktop exposant déjà un cluster Kubernetes local prêt à l'emploi (activable dans ses paramètres, gratuit, partage le même moteur Docker donc aucune étape de chargement d'image n'est nécessaire), c'est celui-ci qui a servi de cible réelle. `kind` reste documenté et pris en charge par le script de déploiement (détection automatique) pour qui préfère cette voie.

**Livré** :
- `k8s/base/` : Deployment + Service + ConfigMap pour `catalog-service`, `orders-service`, `users-service`, `web-bff` ; Deployment + ConfigMap pour `notifications-service` (pas de Service — c'est un worker, pas un serveur HTTP) ; StatefulSet + Service headless pour les 3 MySQL (`catalog-db`, `orders-db`, `users-db`) et RabbitMQ, chacun avec `PersistentVolumeClaim` dédié ; ConfigMap + Deployment + Service + `Ingress` pour `api-gateway` ; `PersistentVolumeClaim` pour les uploads produit (`catalog-uploads`) et les sessions `web-bff` (`web-bff-sessions`, avec la même mise en garde sur la limite à 1 réplique tant que les sessions restent en fichier) ; Secrets `mysql-credentials`/`rabbitmq-credentials` (mêmes identifiants qu'en local, pas de vrai secret) committés, `jwt-secret` et les Secrets par service (`APP_KEY`, identifiants SMTP) volontairement **non committés**, générés par script.
- `k8s/overlays/dev` : la base telle quelle (c'est ce que déploie réellement `scripts/k8s-deploy.sh`).
- `k8s/overlays/prod` : patch de réplicas (`catalog-service`/`orders-service`/`users-service`/`api-gateway` → 2 répliques ; `web-bff` et les bases de données restent à 1, documenté pourquoi) — validé avec `kubectl kustomize k8s/overlays/prod`, non déployé (pas de cluster prod réel disponible pour ce projet).
- `scripts/k8s-secrets.sh` : génère les Secrets sensibles à partir des `.env` déjà présents (une seule source de vérité, que ce soit pour Docker Compose ou K8s).
- `scripts/k8s-deploy.sh` : build des images, installe `ingress-nginx` si absent, applique les secrets puis l'overlay, attend que tous les rollouts soient prêts, lance les migrations dans les 3 services concernés.

**Bugs trouvés et corrigés en déployant pour de vrai sur un cluster réel** (aucun n'apparaît en relisant les manifestes ou en validant avec `kubectl kustomize` — seul un vrai déploiement les révèle) :

1. **Probes de santé RabbitMQ/MySQL sans `timeoutSeconds` explicite** : le défaut Kubernetes est 1 seconde — bien trop court pour `rabbitmq-diagnostics`/`mysqladmin ping`, qui peuvent légitimement prendre plusieurs secondes. La probe de liveness tuait le conteneur en pleine phase d'initialisation, avant même qu'il ait eu la moindre chance de répondre.
2. **Conséquence en cascade, plus grave** : ce kill en pleine initialisation a **corrompu les données** de `catalog-db`, `orders-db` et `rabbitmq-0` (fichiers redo log InnoDB incomplets pour MySQL, Mnesia pour RabbitMQ) — les volumes persistants gardant l'état corrompu, chaque redémarrage suivant échouait à l'identique (boucle `CrashLoopBackOff` permanente, pas juste transitoire). Il a fallu descendre les StatefulSets à 0 réplique, supprimer les `PersistentVolumeClaim` corrompus, puis remonter à 1 réplique pour repartir sur un volume vierge — root cause corrigée en ajoutant `timeoutSeconds: 5` à toutes les probes `exec` (RabbitMQ + les 3 MySQL).
3. **RabbitMQ a besoin de ~40s pour démarrer la première fois** (mesuré ~43s sur cette machine, sous charge avec 10 pods démarrant simultanément) — dépassait `initialDelaySeconds: 30` de la probe de liveness, qui le tuait juste avant la fin de son propre démarrage. `initialDelaySeconds` remonté à 60 (liveness) / 20 (readiness), `failureThreshold` à 5.
4. **`notifications-service` en `CrashLoopBackOff` au tout premier déploiement** : contrairement à `docker-compose` (`depends_on: condition: service_healthy`), un Deployment Kubernetes ne retarde pas le démarrage d'un pod en attendant qu'un autre service soit prêt — il a tenté de se connecter à RabbitMQ avant que celui-ci soit joignable, planté, puis attendu son propre backoff exponentiel (jusqu'à plusieurs minutes) avant de réessayer. Pas un bug de configuration à proprement parler (comportement K8s normal, différent de Compose), mais un vrai piège opérationnel — résolu en forçant un redémarrage immédiat (`kubectl delete pod`) une fois RabbitMQ prêt ; documenté ici pour ne pas y être surpris en re-déploiement.

**Testé en conditions réelles sur le cluster** (10 pods, tous `1/1 Running`, 0 volume corrompu après correctifs) :
- Migrations exécutées avec succès dans les 3 services via `kubectl exec`
- Parcours complet via l'**Ingress réel** (`http://localhost/`, exposé par le LoadBalancer de Docker Desktop, pas un port-forward de secours) : accueil stylé (CSS/logo/images vérifiés individuellement, mêmes pièges d'URL/port que Docker Compose revérifiés ici — corrects par construction puisque `$http_host` est déjà dans le ConfigMap nginx partagé) → inscription → création catégorie/produit admin (JWT signé avec le secret réellement stocké dans le Secret K8s `jwt-secret`, lu via `kubectl get secret ... -o jsonpath`) → panier → checkout → **stock décrémenté inter-services (10 → 9)** → **pipeline RabbitMQ vérifié de bout en bout** (`rabbitmqctl list_queues` confirme 0 message en attente, log `notifications-service` confirme le traitement de `order.created`/`user.registered`, échec SMTP attendu)
- 6/6 sur la suite de tests automatisée rejouée contre l'Ingress (accueil, CSS, API catalogue, 401 attendu sur endpoint protégé, inscription, panier authentifié)

**Non fait à ce stade** : `k8s/overlays/prod` non déployé sur un vrai second cluster (aucun disponible pour ce projet — juste validé par rendu Kustomize) ; pas de test de bascule/failover (kill d'un pod en cours de trafic) ; pas de `NetworkPolicy` (prévu, le cas échéant, avec le renforcement sécurité de la Phase 5).

**Vérification** : `bash scripts/k8s-deploy.sh dev` (build images → installe ingress-nginx si besoin → applique les secrets → applique l'overlay → attend tous les rollouts → migre les 3 bases), puis `curl http://localhost/` ou navigateur. `kubectl get pods -n gaming-store` doit montrer 10 pods `Running` `1/1`.

## Phase 3 — CI/CD (GitHub Actions) + couverture de tests complète

**Statut : ✅ Terminé et validé par un vrai pipeline GitHub Actions vert** (pas seulement écrit/testé en local) — 96 tests métier écrits pour les 4 services HTTP, 3 bugs applicatifs réels trouvés en les écrivant, et 3 bugs de pipeline supplémentaires trouvés et corrigés en observant les 4 premiers runs réels sur GitHub Actions jusqu'à obtenir un run entièrement vert (voir section dédiée plus bas).

### Couverture de tests (le plus gros chantier de cette phase)

Aucun test métier n'existait avant cette phase (seulement les tests d'exemple par défaut de Laravel). Écrit et validé — **96 tests, tous verts** :

| Service | Tests | Ce qui est couvert |
|---|---|---|
| `catalog-service` | 28 | Produits (liste/filtre/recherche/CRUD admin), catégories (CRUD admin), avis (vérification d'achat inter-services via `Http::fake()`, anti-doublon, validation), endpoint interne de décrément de stock, guard JWT (401/403) |
| `orders-service` | 33 | Panier (ajout/incrément/décrément/suppression, plafond de stock), checkout (création de commande, paiement carte ne stocke que les 4 derniers chiffres/jamais le CVV, rejet stock insuffisant, historique scopé par utilisateur, vue admin toutes commandes), coupons (aperçu, expiration, anti-réutilisation, CRUD admin), endpoint interne `has-purchased` |
| `users-service` | 29 | Inscription (hash mot de passe, émission JWT vérifiable), connexion, `/me`, token expiré rejeté, favoris (scopés par utilisateur, bascule ajout/retrait), avis de contact (CRUD + admin-only delete), gestion utilisateurs admin (promotion/rétrogradation), newsletter |
| `web-bff` | 6 | Connexion/inscription (stocke JWT+user en session), déconnexion (vide la session), redirection invité vers `/login`, accès session authentifiée |
| `notifications-service` | — | **Non couvert** : worker consommateur RabbitMQ, difficile à tester unitairement sans extraire la logique de traitement du message hors de la commande Artisan (candidat pour un futur refactor si la couverture devient prioritaire ici) |

**Changements d'architecture nécessaires pour rendre ça testable** (pas juste écrire des tests, une partie du code n'était pas conçue pour ça) :
- `catalog-service` (`ReviewController::hasPurchased`) et `orders-service` (`CatalogClient`) utilisaient `GuzzleHttp\Client` instancié directement — impossible à mocker proprement. Migrés vers la façade `Http` de Laravel (`Http::fake()` en test), comportement HTTP identique.
- Secret JWT de test partagé ajouté aux 3 `phpunit.xml` concernés (`catalog-service`, `orders-service`, `users-service`) + trait `Tests\Concerns\ActsWithJwt` (émission de JWT signés pour les tests, dupliqué dans les 3 services faute de package interne partagé).
- `EventPublisher` (RabbitMQ) systématiquement mocké dans les tests d'inscription/checkout — sans ça, chaque test aurait attendu le timeout de connexion (2s) faute de broker disponible en CI.

**3 bugs réels trouvés en écrivant les tests** (aucun visible en relisant le code) :
1. **Faille IDOR sur le panier** (`orders-service`) : `increment`/`decrement`/`destroy` ne vérifiaient jamais que l'article appartenait à l'utilisateur authentifié — n'importe qui pouvait manipuler le panier d'un autre en devinant un ID. Corrigé avec une vérification de propriété (404 si l'article n'appartient pas à l'appelant), **revérifié en conditions réelles sur le cluster K8s** (utilisateur 1 ne peut plus supprimer un article du panier de l'utilisateur 2).
2. **Checkout : 500 au lieu de 409 sur stock insuffisant** : l'exception de validation du stock était levée dans le calcul des lignes de commande, situé *avant* le bloc `try/catch` censé la transformer en réponse 409 propre — remontait donc en erreur serveur brute. Corrigé en déplaçant tout le calcul à l'intérieur du `try`.
3. **Crash sur réutilisation de coupon au checkout** : le code appelait `$coupon->markUsedBy()` dès qu'un coupon existait, même s'il avait déjà été utilisé (donc sans remise appliquée) — tentative d'insertion en double sur la contrainte unique `(user_id, coupon_id)`. Corrigé en ne marquant le coupon utilisé que lorsque la remise est effectivement appliquée.

**2 autres lacunes trouvées en préparant le pipeline lui-même** :
- **Aucun des 5 services n'avait de `package-lock.json`** — `npm audit` (requis par le pipeline) aurait échoué systématiquement (`ENOLOCK`) dès le premier run. Généré pour les 5 services (0 vulnérabilité trouvée).
- **Dérive de formatage Pint sur 4 des 5 services** (accumulée au fil de toutes les modifications de code des phases précédentes, jamais passées au lint) — le tout premier run du pipeline aurait échoué immédiatement sur l'étape lint. Corrigé (`pint` en mode fix) sur les 4 services concernés, 96/96 tests toujours verts après.

### `.github/workflows/ci.yml`

- **Job `changes`** (`dorny/paths-filter`) : détecte quels services ont changé, produit un tableau JSON consommé par `fromJSON()` dans la stratégie matrix du job suivant — un push qui ne touche que `orders-service` ne relance ni lint ni tests ni build pour les 4 autres.
- **Job `service-ci`** (matrix dynamique sur les services changés) : lint (Pint) → prépare l'environnement de test → tests (PHPUnit, SQLite en mémoire, déjà configuré dans `phpunit.xml`) → `composer audit` (bloquant) → `npm audit` (avisorial pour l'instant, voir `SECURITY.md`) → build de l'image Docker → scan Trivy (avisorial) → push vers GHCR **uniquement sur push vers `main`** (jamais sur une PR).
- **Jobs `secrets-scan` (gitleaks) et `sast` (Semgrep)** : à l'échelle du repo entier, pas matricés — un secret ou une faille ne cesse pas d'exister parce qu'un push ne touche pas ce fichier précis.
- **Job `validate-k8s`** : `kubectl kustomize` sur les deux overlays, uniquement si `k8s/**` a changé.
- `.github/workflows/deploy.yml` : **non recréé** — il déployait l'ancien monolithe par FTP, supprimé à la Phase 0 (bascule microservices assumée). Rien à adapter, il n'y a plus de cible de déploiement FTP. Le déploiement réel se fait via `scripts/k8s-deploy.sh` (Phase 2) ; un futur déploiement continu vers un vrai cluster distant serait une extension naturelle de ce pipeline, pas encore fait faute de cluster cible.

### Pre-commit hook local

- `.githooks/pre-commit` : détecte les services touchés par les fichiers `.php` *stagés* (pas tout le repo — reste rapide), lance `pint --test` uniquement sur ces fichiers dans le(s) service(s) concerné(s). Avertit et continue si `vendor/` n'est pas installé pour ce service, plutôt que d'échouer bêtement.
- `scripts/install-git-hooks.sh` : active le hook une fois par clone via `git config core.hooksPath .githooks` (le hook vit versionné dans le repo, pas copié dans `.git/hooks` — se met à jour pour tout le monde au `git pull`).
- **Testé réellement** (pas juste écrit) : un commit avec du code volontairement mal formaté a été bloqué par le hook (`exit 1`, message clair avec la commande de correction) ; un commit propre est passé sans friction. Contrôle à deux niveaux confirmé : le pipeline CI serveur rejoue le même `pint --test` indépendamment, au cas où le hook est absent ou contourné (`--no-verify`) sur une autre machine.

### Bug supplémentaire trouvé en re-déployant sur K8s après ces correctifs

**Cache d'image périmé sur Docker Desktop Kubernetes** : après avoir rebuild `catalog-service`/`orders-service` avec `docker compose build` (même tag `:latest`), les pods continuaient de tourner sur l'**ancienne** image — confirmé en comparant les digests (`docker inspect` vs `kubectl get pod -o jsonpath='{...imageID}'`, différents). Le cache containerd de Docker Desktop ne resynchronise pas toujours son association de tag après un rebuild sous le même nom, contrairement à un premier build/pull qui fonctionne correctement. Corrigé dans `scripts/k8s-deploy.sh` : chaque déploiement tague désormais l'image avec un horodatage unique et force `kubectl set image` dessus — un tag qui change garantit que la valeur réellement utilisée par le pod change aussi, plutôt que de dépendre d'une resynchronisation implicite. **Revérifié après correction** : digests identiques, et le correctif IDOR (bug #1 ci-dessus) confirmé actif sur le cluster réel après ce fix.

### Validé avec de vrais runs sur GitHub Actions (pas seulement en local)

Le repo a été poussé vers `https://github.com/achrafes20/Gaming_store` (dépôt déjà existant, historique conservé). **4 runs réels** se sont enchaînés pour arriver à un pipeline entièrement vert — chacun a révélé un bug que ni la relecture ni les tests locaux n'auraient trouvé :

1. **Run #1 (échec total)** : tous les jobs `service-ci` (y compris un job fantôme `service-ci (k8s)`, voir bug ci-dessous) échouaient en ~1s à l'étape "Set up job", avant même le checkout. Cause réelle trouvée en lisant le log avec l'utilisateur (mon accès API anonyme est bloqué en 403 sur les logs, `Must have admin rights`) : `Error: Unable to resolve action 'aquasecurity/trivy-action@0.28.0', unable to find version '0.28.0'` — un tag de version inventé qui n'existe pas. J'avais aussi, en parallèle et sans lien avec la cause réelle, séparé le job en deux (`service-ci` lecture seule / `service-publish` avec `packages: write`) en soupçonnant à tort un problème de permissions — changement conservé malgré tout car c'est une meilleure pratique (isoler le seul job qui a besoin d'un droit d'écriture).
2. **Bug de filtre de chemin confirmé** : le filtre `k8s` vivait dans le même bloc `dorny/paths-filter` que les 5 services, donc son match atterrissait dans le même tableau JSON `changes` consommé par la matrice — produisant une entrée `service-ci (k8s)` invalide (pas de dossier `services/k8s`). Séparé dans son propre step `paths-filter` avec sa propre sortie.
3. **Run #2** : le tag Trivy corrigé (`v0.36.0`, en vérifiant la vraie liste de tags via l'API GitHub — le préfixe `v` manquait) a permis à `service-ci` de dépasser "Set up job", mais **`php artisan test` a échoué en CI alors qu'il passait en local** : `Test directory ".../tests/Unit" not found`. Cause : Git ne suit pas les dossiers vides — après avoir supprimé les `ExampleTest.php` par défaut, `tests/Unit` restait vide localement (donc invisible dans les diffs, jamais remarqué) mais n'existait tout simplement pas dans le dépôt cloné par le runner, alors que `phpunit.xml` le référence. Corrigé avec un `.gitkeep` dans les 4 dossiers concernés.
4. **Run #3 (comprehensive, 4 services en simultané) : entièrement vert** — `changes`, `sast` (Semgrep), `secrets-scan` (gitleaks), `service-ci` × 4, **`service-publish` × 4 (images poussées vers GHCR avec succès)**, `validate-k8s` correctement `skipped` (aucun changement `k8s/**` dans ce push).

**Conclusion sur ma théorie de permissions (bug #1 du run #1)** : rétrospectivement erronée — le run #4 prouve que `packages: write` fonctionne très bien avec le réglage repo "Read repository contents and packages permissions" que vous avez vérifié en interface (la restriction de lecture seule s'applique par défaut, mais un job qui demande explicitement plus via son propre bloc `permissions:` peut l'obtenir). L'échec initial était entièrement dû au tag Trivy invalide, qui produit exactement le même symptôme ("Set up job" échoue en ~1s, avant tout log utile) qu'une vraie erreur de permissions — d'où la confusion. La séparation `service-ci`/`service-publish` reste néanmoins conservée : bonne pratique indépendamment de sa cause de découverte.

**Vérification** : push modifiant les 4 services en même temps → 4 legs `service-ci` + 4 legs `service-publish` se déclenchent en parallèle dans Actions, tous verts (**observé réellement**, run [33275797916](https://github.com/achrafes20/Gaming_store/actions/runs/33275797916)) ; un commit local avec du code mal formaté est rejeté par le hook avant même d'atteindre GitHub (**testé réellement**) ; `php artisan test` exécuté avec succès en local et en CI sur les 4 services testés (96/96) ; `pint --test`, `composer audit`, `npm audit` exécutés avec succès en local et en CI sur les 5 services.

## Phase 4 — GitOps (Argo CD)

**Statut : ✅ Terminé — Argo CD installé sur le vrai cluster, `Application` appliquée, round-trip GitOps complet vérifié en conditions réelles** (commit → détection → sync → nouveaux pods tournant réellement sur la nouvelle image, pas juste `Synced` en apparence).

**Changement par rapport au plan initial** : une seule `Application` Argo CD pour toute la stack, pas une par service (ni `ApplicationSet`). Raison documentée en détail dans `docs/gitops.md` : `k8s/base/kustomization.yaml` modélise déjà les 5 services comme une seule unité Kustomize partageant un seul bloc `images:`, et chaque release (`scripts/gitops-release.sh`) build et tague les 5 ensemble — il n'y a pas de cadence de release indépendante par service à refléter dans git. Cinq `Application` synchronisant le même commit en même temps n'apporterait que du bruit dans l'UI, pas une vraie indépendance. Documenté comme trajectoire d'évolution si ça change un jour (scinder en `ApplicationSet` sur `services/*/`).

**Livré** :
- `k8s/base/kustomization.yaml` : bloc `images:` (transformer Kustomize) — le seul endroit qui change à chaque release, tag partagé par les 5 services applicatifs. C'est aussi ce qui règle définitivement le bug "image périmée" de la Phase 2/3 (Docker Desktop Kubernetes ne remarque pas toujours un rebuild sous le même tag `:latest`) : un tag qui change à chaque release force un vrai pull à chaque fois.
- `argocd/application.yaml` : `Application` unique (`gaming-store`), `syncPolicy.automated` avec `prune: true` et `selfHeal: true` (revert automatique de toute dérive imperative comme un `kubectl edit`/`kubectl set image` direct), pointant sur `k8s/overlays/dev` (l'overlay réellement utilisé et testé sur ce cluster) ; `k8s/overlays/prod` documenté comme cible de production, activable en changeant une seule ligne (`path:`).
- `scripts/gitops-release.sh` : build + tag des 5 images avec le SHA court du commit courant, réécrit les `newTag:` dans `k8s/base/kustomization.yaml`, commit, push. C'est désormais le **seul** mécanisme censé déplacer ce pointeur de façon permanente — aucune commande `kubectl` n'y touche le cluster directement, tout passe par git.
- `scripts/k8s-deploy.sh` mis à jour pour rester cohérent avec ce nouveau modèle : son ancien `kubectl set image` direct (qui aurait pu entrer en conflit avec le `selfHeal` d'Argo CD une fois GitOps en place) est remplacé par une écriture du même bloc `images:` que `gitops-release.sh`, mais réversible (`git checkout` en fin de script via un `trap`) — une itération locale rapide ne laisse plus jamais de dérive de tag pour Argo CD à corriger, seule une vraie release (`gitops-release.sh`) modifie durablement le tag suivi par git.
- `docs/gitops.md` : installation d'Argo CD sur un cluster (kind local ou Oracle Cloud Always Free k3s, gratuit à vie — instructions génériques, ce projet utilise concrètement Docker Desktop Kubernetes comme documenté en Phase 2), enregistrement du repo, flux de release, explication de ce que `selfHeal` apporte concrètement par rapport au flux imperative des Phases 2/3.

**Installation Argo CD sur le cluster réel** (piège rencontré et corrigé) :
- `kubectl apply -n argocd -f https://raw.githubusercontent.com/argoproj/argo-cd/stable/manifests/install.yaml` sans `--server-side` a échoué : `The CustomResourceDefinition "applicationsets.argoproj.io" is invalid: metadata.annotations: Too long: may not be more than 262144 bytes` — la CRD `ApplicationSet` dépasse la limite d'annotation `last-applied-configuration` d'un `kubectl apply` classique. Corrigé avec `kubectl apply -n argocd --server-side --force-conflicts -f <url>`. 7/7 pods Argo CD `1/1 Running` après correction.

**Testé en conditions réelles sur le cluster (pas juste écrit)** :
1. `Application` appliquée sur une stack déjà en cours d'exécution (créée imperativement en Phases 2/3) : Argo CD l'a **adoptée** sans perturbation — passage `OutOfSync → Progressing → Synced/Healthy` en quelques secondes, `curl http://localhost/` et `curl http://localhost/api/catalog/products` toujours 200 après, aucun redémarrage de pod anormal observé (`RESTARTS` des pods applicatifs inchangés par rapport à avant l'adoption).
2. **Round-trip GitOps réel de bout en bout** : exécution de `scripts/gitops-release.sh` (build des 5 images, tag `8eaad20`, commit `release: deploy 8eaad20`, push vers `main`) → synchro forcée (`kubectl patch application gaming-store -n argocd --type merge -p '{"operation":{"sync":{}}}'`) → Argo CD détecte le nouveau commit, passe par `Progressing`, revient à `Synced`/`Healthy` sur la révision `4d0554c` (le commit de release) → **vérifié avec `kubectl get pods -o jsonpath='{...image}'` que les 5 pods applicatifs tournent bien sur `gaming-store-<service>:8eaad20`**, pas seulement que l'état Argo CD affiche `Synced` en façade → site toujours 200 (accueil + API catalogue) après le rollout.

**Non fait à ce stade** : pas de test explicite de `selfHeal` (provoquer une dérive manuelle avec `kubectl set image` puis observer Argo CD la corriger automatiquement) — le mécanisme est configuré (`selfHeal: true`, vérifié dans le manifeste appliqué) mais pas exercé en conditions réelles faute de temps ; `k8s/overlays/prod` toujours non déployé (pas de second cluster réel disponible pour ce projet, comme en Phase 2).

**Vérification** : `kubectl get application gaming-store -n argocd` → `SYNC STATUS: Synced`, `HEALTH STATUS: Healthy` ; `./scripts/gitops-release.sh` suivi d'une synchro (auto en ≤3 min, ou forcée) fait apparaître le nouveau tag sur les 5 pods, confirmé par `kubectl get pods -o jsonpath`. **Réellement exécuté et observé**, pas seulement écrit.

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

**État actuel** : Phases 0 et 1 terminées et validées (monolithe retiré, stack microservices tournant en local via Docker, 10 conteneurs). Prochaine étape immédiate : récupérer l'URL du nouveau repo GitHub créé par l'utilisateur et y pousser la branche `microservices-export`. Ensuite, reprise de l'enchaînement 3 (CI/CD) → 5 (sécurité) → 2/4 (Kubernetes/GitOps) → 6 (monitoring) → 7 (documentation finale, y compris le schéma clarifiant gateway = façade client uniquement vs appels inter-services directs).
