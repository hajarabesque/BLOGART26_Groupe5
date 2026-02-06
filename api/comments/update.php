
<?php
require_once '../../config.php';
require_once '../../functions/ctrlSaisies.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numCom = ctrlSaisies($_POST['numCom']);
    $action = $_POST['action'] ?? '';

    if ($action === 'approve') {
        // Approuve le commentaire
        sql_update("COMMENT", "attModOK = 1", "numCom = $numCom");
    } elseif ($action === 'edit') {
        // Met a jour le commentaire avec le nouveau texte
        $newText = ctrlSaisies($_POST['comment_text']);
        sql_update("COMMENT", "libCom = '$newText'", "numCom = $numCom");
    } elseif ($action === 'reject') {
        // Rejette le commentaire et enregistre la raison de rejet
        $reason = ctrlSaisies($_POST['reason']);
        sql_update("COMMENT", "delLogiq = 1, notifComKOAff = '$reason'", "numCom = $numCom");
    }

    header('Location: ../../views/backend/comments/list.php');
    exit();
}
?>


