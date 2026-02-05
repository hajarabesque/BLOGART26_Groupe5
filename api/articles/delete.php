<?php
/**
 * ==========================================================
 * 1. INITIALISATION ET SÉCURISATION
 * ==========================================================
 */
require_once '../../config.php';

// On vérifie que la requête arrive bien en POST (sécurité contre les suppressions accidentelles via URL)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numArt'])) {
    $numArt = intval($_POST['numArt']);

    /**
     * ==========================================================
     * 2. RÉCUPÉRATION DU NOM DE L'IMAGE
     * ==========================================================
     * Avant de supprimer l'article de la BDD, on doit savoir quelle 
     * image lui était associée pour pouvoir l'effacer du disque dur.
     */
    $articles = sql_select('ARTICLE', 'urlPhotArt', "numArt = $numArt");
    $imagePath = null;
    if (!empty($articles)) {
        $imagePath = $articles[0]['urlPhotArt'];
    }

    /**
     * ==========================================================
     * 3. SUPPRESSION DES LIAISONS (TABLE PIVOT)
     * ==========================================================
     * À cause des contraintes d'intégrité (Clés Étrangères), on doit 
     * d'abord supprimer les liens dans la table pivot MOTCLEARTICLE 
     * avant de pouvoir supprimer l'article parent.
     */
    
    sql_delete('MOTCLEARTICLE', "numArt = $numArt");

    /**
     * ==========================================================
     * 4. SUPPRESSION DE L'ARTICLE
     * ==========================================================
     */
    sql_delete('ARTICLE', "numArt = $numArt");

    /**
     * ==========================================================
     * 5. NETTOYAGE DU SERVEUR (UNLINK)
     * ==========================================================
     * Si l'article avait une photo, on utilise unlink() pour supprimer 
     * le fichier du dossier /uploads/. C'est crucial pour ne pas 
     * saturer l'espace disque du serveur avec des images "orphelines".
     */
    if ($imagePath) {
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $imagePath;
        if (file_exists($fullPath)) {
            unlink($fullPath); // Supprime physiquement le fichier
        }
    }

    // Redirection vers la liste
    header('Location: ../../views/backend/articles/list.php');
    exit();
}
?>