<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/BLOGART26/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/BLOGART26/functions/ctrlSaisies.php';

$numMotCle = $_POST['numMotCle'];

if (!empty($numMotCle)) {
    // 1. Supprimer les liens (Correction de la syntaxe : le WHERE est un seul argument)
    sql_delete('motclearticle', "numMotCle = $numMotCle");

    // 2. Supprimer le mot-clé lui-même
    sql_delete('motcle', "numMotCle = $numMotCle");
}

header('Location: ../../views/backend/keywords/list.php');
exit();
?>