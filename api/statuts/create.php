<?php
<<<<<<< HEAD
/**
 * ==========================================================
 * 1. INITIALISATION ET SÉCURITÉ (BACKEND)
 * ==========================================================
 */
// Chargement de la configuration générale et de la connexion à la base de données
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
=======
require_once $_SERVER['DOCUMENT_ROOT'] . '/BLOGART26/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/BLOGART26/functions/ctrlSaisies.php';
>>>>>>> e8486efd7714ef339d3770a9c34db183bc2cacbc

// Chargement des fonctions de contrôle pour nettoyer les entrées utilisateur
require_once $_SERVER['DOCUMENT_ROOT'] . '/BLOGART26/functions/ctrlSaisies.php';

/**
 * RÉCUPÉRATION DE LA SAISIE :
 * On récupère le libellé du statut envoyé depuis le formulaire.
 * Note : Il est recommandé d'appliquer ctrlSaisies() ici pour 
 * prévenir les injections de scripts (XSS).
 */
$libStat = ($_POST['libStat']);

/**
 * ==========================================================
 * 2. ACTION SUR LA BASE DE DONNÉES
 * ==========================================================
 * Utilisation de la fonction SQL Helper pour insérer le nouveau statut.
 * - Table : 'STATUT'
 * - Colonne : 'libStat'
 * - Valeur : On entoure la variable de guillemets simples pour la syntaxe SQL.
 */
sql_insert('STATUT', 'libStat', "'$libStat'");

/**
 * ==========================================================
 * 3. FINALISATION ET REDIRECTION
 * ==========================================================
 * Après l'insertion, on redirige automatiquement l'administrateur
 * vers la page de liste pour qu'il puisse voir le nouveau statut ajouté.
 */
header('Location: ../../views/backend/statuts/list.php');
; // Bonne pratique : on stoppe l'exécution du script après une redirection
