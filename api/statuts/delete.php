<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/BLOGART26/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/BLOGART26/functions/ctrlSaisies.php';

$numStat = ($_POST['numStat']);

sql_delete('STATUT', "numStat = $numStat");


header('Location: ../../views/backend/statuts/list.php');