# 🎮 Gaming Store

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Composer](https://img.shields.io/badge/Composer-885630?style=for-the-badge&logo=composer&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

> A **Laravel-based** project for a modern and dynamic video game store.  
> This guide will help you **install and run** the project locally.  

---

## 🚀 Requirements

Before starting, make sure you have installed:

- **PHP** (compatible version with Laravel, e.g., PHP 8.1+)
- **Composer** (Dependency Manager for PHP)
- A web server: **Apache**, **Nginx**, or Laravel’s built-in server (`php artisan serve`)
- **Database**: MySQL, PostgreSQL, or SQLite

---

## ⚙️ Installation Guide

```bash
# 1️⃣ Copy the environment configuration file
cp .env.example .env

# 2️⃣ Open the .env file and configure your database credentials:
# DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 3️⃣ Install PHP dependencies
composer install

# 4️⃣ Generate the application key
php artisan key:generate

# 5️⃣ Run the database migrations
php artisan migrate

# 6️⃣ (Optional) Seed the database with default data
php artisan db:seed

# 7️⃣ Start the local development server
php artisan serve
