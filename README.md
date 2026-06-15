# Système de Gestion des Étudiants

## Présentation

Le Système de Gestion des Étudiants est une application web développée en PHP, MySQL, HTML, CSS et JavaScript. Elle permet de gérer les étudiants, les enseignants et les classes au sein d’un établissement scolaire à travers une interface simple et intuitive.

## Fonctionnalités

### Authentification
- Connexion sécurisée
- Gestion des sessions
- Déconnexion

### Gestion des étudiants
- Ajouter un étudiant
- Modifier les informations d’un étudiant
- Supprimer un étudiant
- Consulter la liste des étudiants
- Rechercher un étudiant

### Gestion des enseignants
- Ajouter un enseignant
- Modifier les informations d’un enseignant
- Supprimer un enseignant
- Consulter la liste des enseignants

### Gestion des classes
- Ajouter une classe
- Modifier une classe
- Supprimer une classe
- Consulter la liste des classes

### Tableau de bord
- Nombre total d’étudiants
- Nombre total d’enseignants
- Nombre total de classes
- Vue d’ensemble du système

## Technologies Utilisées

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript

## Structure du Projet

/config → Configuration de la base de données  
/includes → Fichiers communs (header, footer, navbar)  
/auth → Authentification (login/logout)  
/students → Gestion des étudiants  
/teachers → Gestion des enseignants  
/classes → Gestion des classes  
/dashboard → Tableau de bord  
/assets → CSS, JS, images  
/database → Script SQL

## Base de Données

Le système repose sur les tables suivantes :

- users
- students
- teachers
- classes

## Objectif

Développer une application web permettant de simplifier la gestion administrative des étudiants, des enseignants et des classes en utilisant PHP et MySQL.

## Évolutions possibles

Le projet est modulaire et peut être amélioré avec de nouvelles fonctionnalités (absences, notes, statistiques avancées...).

## Auteur

Projet réalisé dans le cadre d’un stage en développement web (PHP / MySQL).
