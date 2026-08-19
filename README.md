# API REST – Gestion des notes d'une école

## 1. Présentation du projet

API REST développée avec **Laravel** permettant à une école de gérer ses étudiants, ses matières et
les notes obtenues par les étudiants. Trois rôles sont gérés :

- **admin** : accès complet (CRUD étudiants, matières, notes).
- **professeur** : consultation des étudiants/matières, gestion des notes de ses propres matières.
- **etudiant** : consultation de son propre profil et de ses propres notes/bulletin.

## 2. Technologies utilisées

- PHP 8.2+ / Laravel 11
- Laravel Sanctum (authentification par token)
- MySQL ou PostgreSQL
- Postman (tests des endpoints)

## 3. Installation

Ce dépôt contient uniquement le **code applicatif** (models, controllers, migrations, routes...).
Il doit être copié sur un squelette Laravel fraîchement installé.

```bash
# 1. Créer le squelette Laravel
composer create-project laravel/laravel notes-api
cd notes-api

# 2. Installer l'API + Sanctum (génère routes/api.php et publie la config Sanctum)
php artisan install:api

# 3. Copier les fichiers de ce dépôt dans le projet en écrasant :
#    - app/Models/*
#    - app/Http/Controllers/Api/*
#    - app/Http/Requests/*
#    - app/Http/Resources/*
#    - app/Http/Middleware/CheckRole.php
#    - database/migrations/2026_08_18_*.php
#    - database/seeders/DatabaseSeeder.php
#    - routes/api.php
#    - bootstrap/app.php (fusionner avec l'existant si modifié)

# 4. Installer les dépendances si besoin
composer install
```

## 4. Configuration de la base de données

Copier `.env.example.notes-api` en `.env` (ou reporter les valeurs dans le `.env` existant),
puis :

```bash
php artisan key:generate
```

Exemple pour MySQL (voir aussi le fichier fourni pour PostgreSQL) :

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=notes_ecole
DB_USERNAME=root
DB_PASSWORD=
```

Créer la base puis lancer les migrations et le seeder (comptes de test + données de démo) :

```bash
php artisan migrate --seed
```

## 5. Lancement du serveur

```bash
php artisan serve
```

L'API est disponible sur `http://localhost:8000/api`.

## 6. Authentification

Authentification par token via **Laravel Sanctum**.

1. `POST /api/register` ou `POST /api/login` → renvoie un `token`.
2. Envoyer ce token dans l'en-tête de chaque requête protégée :
   `Authorization: Bearer {token}`

## 7. Comptes de test (créés par le seeder, mot de passe : `password`)

| Rôle       | Email               | Mot de passe |
|------------|----------------------|--------------|
| admin      | admin@ecole.test     | password     |
| professeur | prof@ecole.test      | password     |
| etudiant   | etudiant@ecole.test  | password     |

## 8. Endpoints principaux

### Authentification
| Méthode | URL             | Accès    | Description              |
|---------|-----------------|----------|---------------------------|
| POST    | /api/register   | public   | Créer un compte           |
| POST    | /api/login      | public   | Se connecter (token)      |
| POST    | /api/logout     | connecté | Révoquer le token courant |
| GET     | /api/me         | connecté | Profil de l'utilisateur   |

### Étudiants
| Méthode | URL                        | Accès               | Description               |
|---------|-----------------------------|---------------------|---------------------------|
| GET     | /api/etudiants               | tous (connectés)    | Liste (filtrée si étudiant) |
| GET     | /api/etudiants/{id}           | tous (connectés)    | Détail                    |
| GET     | /api/etudiants/{id}/bulletin  | tous (connectés)    | Notes + moyenne générale  |
| POST    | /api/etudiants                | admin                | Créer                     |
| PUT     | /api/etudiants/{id}            | admin                | Modifier                  |
| DELETE  | /api/etudiants/{id}            | admin                | Supprimer                 |

### Matières
| Méthode | URL                | Accès             | Description |
|---------|--------------------|--------------------|-------------|
| GET     | /api/matieres        | tous (connectés)  | Liste       |
| GET     | /api/matieres/{id}     | tous (connectés)  | Détail      |
| POST    | /api/matieres         | admin              | Créer       |
| PUT     | /api/matieres/{id}      | admin              | Modifier    |
| DELETE  | /api/matieres/{id}      | admin              | Supprimer   |

### Notes
| Méthode | URL             | Accès                  | Description                          |
|---------|-----------------|-------------------------|----------------------------------------|
| GET     | /api/notes        | tous (connectés)        | Liste (scope selon rôle)               |
| GET     | /api/notes/{id}     | tous (connectés)        | Détail (scope selon rôle)              |
| POST    | /api/notes        | admin, professeur        | Créer                                  |
| PUT     | /api/notes/{id}     | admin, professeur        | Modifier                               |
| DELETE  | /api/notes/{id}     | admin, professeur        | Supprimer                              |

## 9. Codes HTTP utilisés

- `200` succès (lecture/modification), `201` création, `204` suppression
- `401` non authentifié, `403` non autorisé (mauvais rôle)
- `404` ressource introuvable, `422` erreur de validation

## 10. Tests

Une collection Postman est fournie dans `postman/Notes-API.postman_collection.json`.
Import dans Postman → renseigner la variable `token` après un login → exécuter les requêtes.

## 11. Structure de la base de données

- **users** (id, name, email, password, role)
- **etudiants** (id, user_id?, matricule, nom, prenom, classe, date_naissance)
- **matieres** (id, nom, code, coefficient, professeur_id?)
- **notes** (id, etudiant_id, matiere_id, valeur, type, date_evaluation, commentaire)
