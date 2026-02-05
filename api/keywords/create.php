<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/BLOGART26/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/BLOGART26/functions/ctrlSaisies.php';

$libMotCle = ($_POST['libMotCle']);

sql_insert('MOTCLE', 'libMotCle', "'$libMotCle'");

header('Location: ../../views/backend/keywords/list.php');