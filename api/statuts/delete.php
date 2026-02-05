<?php
/**
 * ==========================================================
 * 1. PRÉPARATION ET RÉCUPÉRATION (BACKEND)
 * ==========================================================
 */
// Chargement de la configuration système (connexion BDD)
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

// Chargement des fonctions de nettoyage (sécurité)
require_once '../../functions/ctrlSaisies.php';

/**
 * RÉCUPÉRATION DE L'IDENTIFIANT :
 * On récupère l'ID unique du statut envoyé par le formulaire de confirmation.
 * Il est conseillé d'utiliser intval() pour s'assurer que c'est bien un nombre.
 */
$numStat = ($_POST['numStat']);

/**
 * ==========================================================
 * 2. ACTION DE SUPPRESSION SQL
 * ==========================================================
 * On appelle la fonction sql_delete() avec deux arguments :
 * 1. La table concernée : 'STATUT'
 * 2. La clause WHERE : "numStat = $numStat" (pour ne supprimer que CE statut)
 */

sql_delete('STATUT', "numStat = $numStat");

/**
 * ==========================================================
 * 3. REDIRECTION ET SORTIE
 * ==========================================================
 * Une fois la suppression effectuée, on redirige l'utilisateur vers
 * la liste des statuts pour confirmer visuellement le changement.
 */
header('Location: ../../views/backend/statuts/list.php');
exit(); // Interrompt le script pour valider la redirection