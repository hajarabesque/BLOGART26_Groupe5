<?php
/**
 * ==========================================================
 * 1. INITIALISATION ET SÉCURISATION (BACKEND)
 * ==========================================================
 */
// Chargement de la configuration et des fonctions de nettoyage
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

/**
 * VÉRIFICATION DE LA MÉTHODE :
 * On ne traite les données que si elles sont envoyées via un formulaire (POST).
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    /**
     * RÉCUPÉRATION ET PROTECTION :
     * - intval() : Force l'ID en nombre entier pour éviter les injections SQL.
     * - htmlspecialchars() : Neutralise les balises HTML pour éviter les failles XSS.
     */
    $numThem = intval($_POST['numThem']);
    $libThem = htmlspecialchars($_POST['libThem']);

    /**
     * ==========================================================
     * 2. MISE À JOUR DANS LA BASE DE DONNÉES
     * ==========================================================
     * La fonction sql_update() prend 3 arguments :
     * 1. La table cible : "THEMATIQUE"
     * 2. La modification : "libThem = '$libThem'" (le nouveau nom)
     * 3. La condition (WHERE) : "numThem = $numThem" (quelle ligne modifier)
     */
    sql_update("THEMATIQUE", "libThem = '$libThem'", "numThem = $numThem");

    /**
     * ==========================================================
     * 3. FINALISATION ET NAVIGATION
     * ==========================================================
     * Après la modification, on redirige vers le tableau de bord des thématiques.
     * exit() garantit qu'aucune ligne de code supplémentaire n'est exécutée.
     */
   header('Location: ../../views/backend/thematiques/list.php');
    exit();
}
?>