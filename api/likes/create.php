<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/BLOGART26/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/BLOGART26/functions/ctrlSaisies.php';

// 1. Récupération des données via $_POST (venant du formulaire)
$numArt  = $_POST['numArt'] ?? null;
$numMemb = $_POST['numMemb'] ?? null;
$LikeA   = $_POST['LikeA'] ?? 1; // On met 1 (Liké) par défaut

// 2. Vérification que les IDs sont présents
if (!empty($numArt) && !empty($numMemb)) {
    
    // 3. Insertion avec la BONNE syntaxe :
    // Arg 1 : La table ('likeart')
    // Arg 2 : Les colonnes séparées par des virgules
    // Arg 3 : Les valeurs entourées de guillemets simples
    sql_insert(
        'likeart', 
        'numArt, numMemb, LikeA', 
        "'$numArt', '$numMemb', '$LikeA'"
    );
}

// 4. Redirection vers la liste
header('Location: ../../views/backend/likes/list.php?success=1');
exit();
?>
?>