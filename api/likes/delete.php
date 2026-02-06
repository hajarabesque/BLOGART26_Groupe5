<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/BLOGART26/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/BLOGART26/functions/ctrlSaisies.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numArt  = $_POST['numArt'] ?? null;
    $numMemb = $_POST['numMemb'] ?? null;

    if (!empty($numArt) && !empty($numMemb)) {
        // Suppression du like
        sql_delete('likeart', "numArt = '$numArt' AND numMemb = '$numMemb'");
        // Redirection vers la liste avec un message de succès
        header('Location: ../../views/backend/likes/list.php?success=1');
        exit();
    }
}
