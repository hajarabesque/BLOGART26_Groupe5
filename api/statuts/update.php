<?php

// ============================================================
// INCLUSION DES FICHIERS DE CONFIGURATION ET DES FONCTIONS
// ============================================================
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php'; 
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/query/update.php';
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ============================================================
    // RÉCUPÉRATION ET SÉCURISATION DES DONNÉES
    // ============================================================
    $numStat = intval($_POST['numStat']);
    // addslashes permet de gérer les apostrophes (ex: l'administrateur)
    $libStat = addslashes($_POST['libStat']); 
      
    // ============================================================
    // VALIDATION DES DONNÉES
    // ============================================================
    if (empty($numStat) || empty($libStat)) {
        echo "Erreur : Tous les champs doivent être remplis.";
        exit();
    }

    // ============================================================
    // EXÉCUTION DE LA REQUÊTE UPDATE
    // ============================================================
    try {
        // D'après votre image, la table semble s'appeler STATUT (sans S)
        // Si cela ne marche pas, essayez "STATUTS" avec un S.
        $table = "STATUT"; 
        
        // CORRECTION ICI : On envoie une CHAÎNE et non un tableau []
        // On prépare la partie "SET libStat = 'Valeur'"
        $champs = "libStat = '$libStat'";
        $where = "numStat = $numStat";

        // Appel de la fonction avec des chaînes de caractères
        $result = sql_update($table, $champs, $where);
        
        // Redirection après succès
        header('Location: ../../views/backend/statuts/list.php');
        exit();

    } catch(Exception $e) {
        echo "Erreur lors de la modification : " . $e->getMessage();
    }
} else {

    header('Location: ../../views/backend/statuts/list.php');
    exit();
}
?>