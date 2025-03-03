# Mangaverse

Mangaverse est une application de gestion de mangas qui permet aux utilisateurs de créer, consulter, commenter et noter des mangas. Une interface d'administration est également disponible pour valider les mangas soumis.

## Prérequis

- PHP >= 8.2
- Composer
- Node.js (pour Vite)
- MySQL (ou toute base compatible avec Laravel)

## Installation

### 1. Cloner le projet

```bash
git clone https://github.com/IlanCado/mangaverse2.0.git
cd mangaverse
```

### 2. Installer les dépendances

```bash
composer install
npm install
```

### 3. Configuration de l'environnement

Copier le fichier `.env.example` en `.env` :

```bash
cp .env.example .env
```

Configurer la base de données dans le fichier `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mangaverse
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Générer la clé d'application

```bash
php artisan key:generate
```

### 5. Effectuer les migrations et les seeders

```bash
php artisan migrate:fresh --seed
```

### 6. Lancer le serveur de développement

```bash
php artisan serve
```

Le projet sera accessible sur :

```
http://localhost:8000
```

### 7. Compiler les assets

```bash
npm run dev
```

---

## Fonctionnalités principales

- Consulter les mangas validés.
- Accéder aux détails d'un manga.
- Rechercher et filtrer les mangas.
- Ajouter des commentaires et des notes (utilisateurs connectés).
- Interface d'administration pour valider, modifier ou supprimer les mangas.
- Possibilité d'ajouter une image au manga lors de la validation administrateur
- Gestion du profil utilisateur.

---

## Accès administrateur

Un compte administrateur est automatiquement créé lors du `php artisan migrate:fresh --seed` :

- **Email** : admin@admin.com
- **Mot de passe** : adminadmin

---

## Accès utilisateur

Un compte utilisateur est automatiquement créé lors du `php artisan migrate:fresh --seed` :

- **Email** : utili@utili.com
- **Mot de passe** : utiliutili

---

## Commandes utiles

### Lancer les tests (si disponibles)

```bash
php artisan test
```

## Arborescence (simplifiée)

```text
app/
    Http/
        Controllers/
        Middleware/
        Requests/
    Models/
    Providers/
    View/
    Database/
        Factories/
        Seeders/
resources/
    views/
    css/
    js/
routes/
    web.php
```

---

