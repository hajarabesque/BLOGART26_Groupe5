<?php
require_once '../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numArt'])) {
    $numArt = intval($_POST['numArt']);

    $articles = sql_select('ARTICLE', 'urlPhotArt', "numArt = $numArt");
    $imagePath = null;
    if (!empty($articles)) {
        $imagePath = $articles[0]['urlPhotArt'];
    }

    sql_delete('MOTCLEARTICLE', "numArt = $numArt");

    sql_delete('ARTICLE', "numArt = $numArt");

    if ($imagePath) {
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $imagePath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    header('Location: ../../views/backend/articles/list.php');
    exit();
}
?>
