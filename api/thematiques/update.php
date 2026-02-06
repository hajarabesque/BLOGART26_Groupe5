<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Récupération et protection des données
    $numThem = intval($_POST['numThem']);
    $libThem = htmlspecialchars($_POST['libThem']);

    // 2. Mise à jour
    // On s'assure que les apostrophes sont bien gérées pour le texte
    sql_update("THEMATIQUE", "libThem = '$libThem'", "numThem = $numThem");

    // 3. Redirection vers la liste
   header('Location: ../../views/backend/thematiques/list.php');
    exit();
}
?>