# Pokémon Project (Laravel)

Application Laravel permettant de consulter un Pokédex et de gérer des decks personnels (6 Pokémon max) avec authentification.

## Prérequis
- PHP 8.2+, Composer
- Node.js 18+, npm
- Base de données (SQLite recommandé en dev)

## Installation
1. Cloner le dépôt puis installer les dépendances:
   - `composer install`
   - `npm install`
2. Copier l’environnement et générer la clé:
   - `cp .env.example .env`
   - `php artisan key:generate`
3. Configurer la base:
   - SQLite (rapide) :
     - Créer `database/database.sqlite`
     - Dans `.env`: `DB_CONNECTION=sqlite` et commenter les variables MySQL
   - Ou configurer MySQL/PostgreSQL selon vos besoins.
4. Migrations et données:
   - `php artisan migrate --seed`
   - Pour repartir de zéro: `php artisan migrate:fresh --seed`

## Lancer le projet
- Backend: `php artisan serve`
- Front (Vite): `npm run dev`

## Fonctionnalités
- Pokédex
  - Liste paginée, recherche par nom, filtre par type
  - Page détail avec stats et image (fallback en ligne si absence locale)
- Decks (par utilisateur)
  - Créer/renommer/supprimer un deck
  - Depuis la page d’un Pokémon, ajouter au deck choisi via un sélecteur
  - Limite stricte à 6 Pokémon par deck (bouton “Deck full” si plein)
  - Page /deck: liste des decks et aperçu des Pokémon de chaque deck

## Routes principales
- `GET /pokemon` — liste (filtre/recherche/pagination)
- `GET /pokemon/{pokedex_number}` — détail d’un Pokémon
- `GET /deck` — gestion des decks (création, aperçu, actions)
- `POST /deck/store` — créer un deck
- `POST /deck/{deck}/rename` — renommer
- `POST /deck/{deck}/delete` — supprimer
- `POST /deck/add-pokemon` — ajouter un Pokémon au deck choisi

## Données
- Seed initial via `database/seeders/PokemonSeeder.php` lisant `database/seeders/data/pokemon.json`.

## Styles
- CSS extrait dans `resources/css`:
  - `pokemon-index.css` (liste)
  - `pokemon-show.css` (détail)
  - `decks.css` (page decks)

## Astuces
- Pour réinitialiser rapidement: `php artisan migrate:fresh --seed`
- Pour publier le front en prod: `npm run build`
