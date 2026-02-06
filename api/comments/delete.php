
<?php
require_once '../../config.php';
require_once '../../functions/ctrlSaisies.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numCom = ctrlSaisies($_POST['numCom']);

    // Soft delete: mark as logically deleted instead of actually deleting
    sql_update("COMMENT", "delLogiq = 1", "numCom = $numCom");

    header('Location: ../../views/backend/comments/list.php');
    exit();
}
?>


