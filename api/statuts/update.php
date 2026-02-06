<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/BLOGART26/functions/query/update.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numStat = $_POST['numStat'];
    $libStat = $_POST['libStat'];

    sql_update("STATUTS", ["libStat" => $libStat], "numStat = '$numStat'");

    // Exécution sécurisée de la condition
    $stmt = $pdo->prepare("UPDATE STATUTS SET libStat = :libStat WHERE numStat = :numStat");
    $stmt->execute([
        ':libStat' => $libStat,
        ':numStat' => $numStat
    ]);

    header('Location: ../../views/backend/statuts/list.php');
    exit();
}
?>