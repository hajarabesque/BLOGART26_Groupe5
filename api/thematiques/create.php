<?php
/**
 * ==========================================================
 * 1. PRÉPARATION ET SÉCURITÉ (BACKEND)
 * ==========================================================
 */
// Chargement de la configuration (connexion BDD) et des fonctions de nettoyage
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

/**
 * VÉRIFICATION DU MODE D'ENVOI :
 * On s'assure que les données arrivent via la méthode POST (formulaire).
 * Cela évite que quelqu'un ne crée une thématique via une simple URL.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /**
     * SÉCURISATION DES DONNÉES (Anti-XSS) :
     * htmlspecialchars() convertit les caractères spéciaux (comme < ou >)
     * en entités HTML. Cela empêche l'exécution de code malveillant 
     * si un utilisateur saisit une balise <script>.
     */
    $libThem = htmlspecialchars($_POST['libThem']);

    /**
     * ==========================================================
     * 2. ACTION SUR LA BASE DE DONNÉES
     * ==========================================================
     * sql_insert() prend 3 arguments :
     * 1. Le nom de la table : 'THEMATIQUE'
     * 2. Le nom de la colonne : 'libThem'
     * 3. La valeur à insérer : '$libThem' (entourée de guillemets simples pour SQL)
     */
    sql_insert('THEMATIQUE', 'libThem', "'$libThem'");

    /**
     * ==========================================================
     * 3. FINALISATION ET REDIRECTION
     * ==========================================================
     * Une fois l'insertion réussie, on ne laisse pas l'utilisateur sur une page blanche.
     * On le redirige immédiatement vers la liste des thématiques.
     * exit() est crucial pour stopper l'exécution du script après la redirection.
     */
    header('Location: ../../views/backend/thematiques/list.php');
    exit();
}
?>