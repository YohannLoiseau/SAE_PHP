# SAE_PHP

## Membres du groupe

- Adam Daniel BIN ZULKORNAIN
- Yohann LOISEAU
- Dan COQUERANT

## Fonctionnalités Développées

- affichage des albums (avec image et titre)
- affichage des détails des albums
- détail d'un artiste avec ses albums
- recherche avancée dans les albums (par artiste, genre, année, mot clé)
- Création (CREATE), affichage (READ), mise à jour (UPDATE), et supprimer (DELETE) d'un album
- Création (CREATE), affichage (READ), mise à jour (UPDATE), et supprimer (DELETE) d'un artiste
- Création (CREATE), affichage (READ), mise à jour (UPDATE), et supprimer (DELETE) d'un utilisateur admin ou utilisateur normale
- inscription d'utilisateur
- connexion d'un utilisateur
- playlist par utilisateur avec l'ajout et supprimer album de playlist
- système de notation des albums par un utilisateur (même si pas dans playlist)

## Ligne de commande

- Créer une base de données

`php cli.php sqlite create-database`

- Créer des tables

`php cli.php sqlite create-table`

- Supprimer des tables

`php cli.php sqlite delete-table`

- Insertion des données

`php cli.php sqlite load-data`

## Prérequis

- information utilisateur (normale)

`nom: aboo  mdp: aboo`

- information utilisateur (admin)

`nom: toto  mdp: toto`

## Lancement

- Ligne de commande dans terminal

`php -S localhost:5000`

- adresse url

`http://localhost:5000/public/albums.php`