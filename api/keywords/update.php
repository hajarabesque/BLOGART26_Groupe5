<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/BLOGART26/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/BLOGART26/functions/ctrlSaisies.php';

$numMotCle = ($_POST['numMotCle']);

sql_update('MOTCLE', "libMotCle = '" . ctrlSaisies($_POST['libMotCle']) . "'", "numMotCle = " . intval($numMotCle));

header('Location: ../../views/backend/keywords/list.php');

?>
  