# Support-ticket: Plateforme de gestion des tickets de support

## Présentation
SupportDesk est une plateforme web permettant aux employés d'une entreprise de signaler
des problèmes informatiques (ticket de support) et de suivre leur résolution, et permettant
à un technicien/administrateur de consulter, filtrer, traiter et gérer ces tickets.

Ce projet a été réalisé dans le cadre d'un TP de stage, en PHP natif (sans framework),
avec Bootstrap 5 pour l'interface et MySQL/PDO pour la persistance des données.

### Fonctionnalités principales
**Partie publique**
- Page d'accueil présentant la plateforme
- Création d'un ticket via formulaire (avec validation côté serveur)
- Consultation d'un ticket via son numéro (ex.`TK-00001`)

**Partie administration**
- Tableau de bord (statistiques : tickets ouverts, résolus, urgents, du jour)
- Liste des tickets avec recherche (numéro, sujet, nom, e-mail) et filtres (statut, priorité)
- Consultation détaillée d'un ticket
- Modification (catégorie, priorité, statut, description)
- Suppression (avec confirmation)

## Prérequis
- PHP 8.0 ou supérieur
- MySQL / MariaDB
- Un serveur local type XAMPP, WAMP ou équivalent (Apache + PHP + MySQL)
- Un navigateur récent

## Installation
1. Clone ou copie ce projet dans le dossier `htdocs` (XAMPP) ou `www` (WAMP) :
   ```
   htdocs/support-ticket/
   ```

2. Démarre Apache et MySQL depuis le panneau de contrôle de ton serveur local.

## Configuration de la base de données
1. Ouvre phpMyAdmin (`http://localhost/phpmyadmin`).
2. Crée une base de données nommée exactement **`support_ticket`** (underscore, pas de tiret).
3. Importe le fichier `database/support_ticket.sql` fourni dans le projet (onglet **Importer**).
4. Vérifie que la table `tickets` a bien été créée avec la colonne `id` en clé primaire
   AUTO_INCREMENT (visible dans l'onglet **Structure**).

Si besoin, les identifiants de connexion à la base sont définis dans `config/database.php` :

```php
$host = "localhost";
$dbname = "support-ticket";
$username = "root";
$password = "";
```

Adapte ces valeurs si ta configuration MySQL diffère (utilisateur/mot de passe personnalisés).

## Lancement du projet
Une fois l'installation et la base de données configurées, ouvre dans ton navigateur :

```
http://localhost/support-ticket/
```

## Identifiants administrateur
Aucune authentification n'est implémentée dans cette version : la partie `admin/` est
accessible librement, sans connexion préalable (fonctionnalité bonus non réalisée).

## Structure du projet
```
support-ticket/
├── index.php                 Page d'accueil
├── create-ticket.php         Formulaire de création d'un ticket
├── traitement-ticket.php     Traitement et validation du formulaire
├── confirmation.php          Page de confirmation après création
├── ticket.php                Consultation publique d'un ticket
│
├── admin/
│   ├── index.php             Tableau de bord
│   ├── tickets.php           Liste des tickets (recherche, filtres, pagination)
│   ├── ticket-details.php    Détail d'un ticket
│   ├── update-ticket.php     Modification d'un ticket
│   └── delete-ticket.php     Suppression d'un ticket
│
├── config/
│   └── database.php          Connexion PDO à MySQL
│
├── includes/
│   ├── header.php
│   ├── navbar.php
│   └── footer.php
│
├── assets/
│   ├── css/style.css
│   ├── js/script.js
│   └── images/
│
├── database/
│   └── support_ticket.sql    Structure de la base de données
│
└── .htaccess                 Désactive le listing de répertoire
```

## Technologies utilisées
- HTML5 / CSS3
- Bootstrap 5
- PHP 8 (sans framework)
- MySQL / PDO
- JavaScript (interactions simples : confirmation de suppression, champ catégorie
  personnalisée)

## Auteurs
- FOTOHOUN Mahukèdè Samuel
- NOUMOU  Juste