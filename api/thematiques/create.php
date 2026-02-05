<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sécurisation de la saisie
    $libThem = htmlspecialchars($_POST['libThem']);

    // Utilisation de la fonction avec des paramètres sécurisés
    // Note : sql_insert attend souvent des valeurs nettoyées
    sql_insert('THEMATIQUE', 'libThem', "'$libThem'");

   
header('Location: ../../views/backend/thematiques/list.php');
    exit();
}
?>