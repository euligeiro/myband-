# 🎸 Projet "My Band" – Gestion de groupe de musique

## 📌 Description

Ce projet est une application web PHP permettant à un groupe de musique de gérer sa **setlist** (liste de chansons), d’**uploader des paroles** au format PDF, d’**afficher un logo et un nom aléatoires** à chaque session, et de contacter l’administrateur.

L’application dispose de trois niveaux d’utilisateurs :
- **Invité** (non connecté)
- **Membre** (connecté)
- **Administrateur** (accès à la gestion complète)

---

## 🧱 Architecture technique

- **Langage** : PHP (procédural)
- **Base de données** : MySQL / MariaDB
- **Serveur** : Localhost (Apache recommandé)
- **CSS** : personnalisé + Font Awesome
- **JavaScript** : natif (modales, recherche dynamique)

---

## 🗂️ Structure des fichiers

| Fichier | Rôle |
|--------|------|
| `index.php` | Page d’accueil avec contenu généré aléatoirement (loripsum) |
| `header.php` | Gestion de session, authentification, génération bandname/logo |
| `footer.php` | Pied de page avec copyright et date |
| `setlist.php` | Affichage + gestion CRUD de la setlist et des paroles |
| `upload.php` | Upload et suppression des fichiers PDF (paroles) |
| `download_lyrics.php` | Téléchargement des fichiers PDF |
| `logo.php` | Affichage dynamique du logo (sans vérification) |
| `contact.php` | Formulaire de contact (non fonctionnel) |
| `dbconnect.php` | Connexion à la base de données |
| `band_generators.php` | Générateur aléatoire de nom de groupe |
| `myband.css` | Feuille de style principale |
| `php.ini` | Configuration upload (max 10 Mo) |

---

## 🚀 Installation

1. **Base de données MySQL**  
   Créez une base nommée `myband` et une table `setlist` :
   ```sql
   CREATE TABLE setlist (
       id INT AUTO_INCREMENT PRIMARY KEY,
       title VARCHAR(255) NOT NULL,
       artist VARCHAR(255) NOT NULL,
       style VARCHAR(100) NOT NULL,
       lyrics VARCHAR(255) NULL
   );