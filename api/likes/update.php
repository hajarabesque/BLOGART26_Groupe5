<?php
require_once '../../config.php';
require_once '../../functions/ctrlSaisies.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numArt  = $_POST['numArt'] ?? null;
    $numMemb = $_POST['numMemb'] ?? null;
    $LikeA   = $_POST['LikeA'] ?? null; // On récupère si c'est Like (1) ou Unlike (0)

    if ($numArt && $numMemb && $LikeA !== null) {
        
        // CORRECTION : On passe bien les 3 arguments
        // 1. Table : 'likeart'
        // 2. Ce qu'on modifie : "LikeA = '$LikeA'"
        // 3. La condition (le WHERE) : "numArt = $numArt AND numMemb = $numMemb"
        sql_update(
            'likeart', 
            "LikeA = '$LikeA'", 
            "numArt = $numArt AND numMemb = $numMemb"
        );

        // Redirection vers la liste
        header('Location: ../../views/backend/likes/list.php?success=1');
        exit();
    }
}
?>