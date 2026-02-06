<?php
require_once '../../config.php';
require_once '../../functions/query/delete.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numArt'])) {
    $numArt = intval($_POST['numArt']);

    // 1. On récupère l'image pour pouvoir la supprimer du disque plus tard
    $articles = sql_select('ARTICLE', 'urlPhotArt', "numArt = $numArt");
    $imagePath = null;
    if (!empty($articles)) {
        $imagePath = $articles[0]['urlPhotArt'];
    }

    // ============================================================
    // SUPPRESSION DE TOUTES LES DÉPENDANCES (LES ENFANTS)
    // On doit le faire AVANT de supprimer l'article
    // ============================================================

    // A. Supprimer les Likes (C'est ce qui bloque maintenant)
    sql_delete('LIKEART', "numArt = $numArt");

    // B. Supprimer les Commentaires
    sql_delete('COMMENT', "numArt = $numArt");

    // C. Supprimer les liens avec les Mots-clés
    sql_delete('MOTCLEARTICLE', "numArt = $numArt");

    // ============================================================
    // SUPPRESSION DU PARENT (L'ARTICLE)
    // ============================================================
    sql_delete('ARTICLE', "numArt = $numArt");

    // ============================================================
    // SUPPRESSION PHYSIQUE DE L'IMAGE SUR LE SERVEUR
    // ============================================================
    if ($imagePath) {
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $imagePath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    // Redirection vers la liste des articles
    header('Location: ../../views/backend/articles/list.php');
    exit();
}
?>