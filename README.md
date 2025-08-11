# 🎮 Gaming Store

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Composer](https://img.shields.io/badge/Composer-885630?style=for-the-badge&logo=composer&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

Un projet **Laravel** pour une boutique de jeux vidéo.  
Voici comment l’installer et l’utiliser sur votre machine locale.

---

## 🚀 Prérequis

- **PHP** (version compatible avec Laravel)
- **Composer**
- Serveur web : **Apache**, **Nginx** ou via PHP intégré (`php artisan serve`)
- **Base de données** : MySQL, PostgreSQL, SQLite…

---

## ⚙️ Installation

```bash
# 1️⃣ Copier le fichier d'environnement
cp .env.example .env

# 2️⃣ Modifier le fichier .env
# (Configurer DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD, etc.)

# 3️⃣ Installer les dépendances PHP
composer install

# 4️⃣ Générer la clé d'application
php artisan key:generate

# 5️⃣ Lancer les migrations
php artisan migrate

# 6️⃣ (Optionnel) Lancer le seeder pour insérer des données par défaut
php artisan db:seed

# 7️⃣ Démarrer le serveur local
php artisan serve



