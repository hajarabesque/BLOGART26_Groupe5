<?php
/**
 * ==========================================================
 * 1. INCLUSIONS ET INITIALISATION
 * ==========================================================
 */
require_once '../../config.php';
require_once '../../functions/ctrlSaisies.php';

/**
 * VÉRIFICATION DE SÉCURITÉ :
 * On s'assure que la requête arrive en POST et qu'un ID d'article est présent.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numArt'])) {
    // Cast en entier pour éviter toute injection SQL via l'ID
    $numArt = intval($_POST['numArt']);

    /**
     * ==========================================================
     * 2. GESTION DE L'IMAGE (UPLOAD)
     * ==========================================================
     * On ne traite l'image que si l'utilisateur en a sélectionné une nouvelle.
     * UPLOAD_ERR_OK (0) signifie qu'il n'y a eu aucune erreur lors du transfert.
     */
    $imagePath = null;
    if (isset($_FILES['urlPhotArt']) && $_FILES['urlPhotArt']['error'] === UPLOAD_ERR_OK) {
        // Extraction de l'extension du fichier original
        $extension = pathinfo($_FILES['urlPhotArt']['name'], PATHINFO_EXTENSION);
        
        // Création d'un nom unique basé sur le temps pour éviter les doublons
        $imagePath = 'art_' . time() . '.' . $extension;
        
        // Définition du chemin physique sur le serveur
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $imagePath;
        
        // Déplacement du fichier temporaire vers sa destination finale
        move_uploaded_file($_FILES['urlPhotArt']['tmp_name'], $filePath);
    }

    /**
     * ==========================================================
     * 3. PRÉPARATION DE LA MISE À JOUR SQL
     * ==========================================================
     * On construit dynamiquement la liste des champs à mettre à jour.
     * Le str_replace("'", "''", ...) permet d'échapper les apostrophes pour SQL.
     */
    $updateFields = array();
    $updateFields[] = "libTitrArt = '" . str_replace("'", "''", ctrlSaisies($_POST['libTitrArt'])) . "'";
    $updateFields[] = "libChapoArt = '" . str_replace("'", "''", ctrlSaisies($_POST['libChapoArt'])) . "'";
    $updateFields[] = "libAccrochArt = '" . str_replace("'", "''", ctrlSaisies($_POST['libAccrochArt'])) . "'";
    $updateFields[] = "parag1Art = '" . str_replace("'", "''", ctrlSaisies($_POST['parag1Art'])) . "'";
    $updateFields[] = "libSsTitr1Art = '" . str_replace("'", "''", ctrlSaisies($_POST['libSsTitr1Art'])) . "'";
    $updateFields[] = "parag2Art = '" . str_replace("'", "''", ctrlSaisies($_POST['parag2Art'])) . "'";
    $updateFields[] = "libSsTitr2Art = '" . str_replace("'", "''", ctrlSaisies($_POST['libSsTitr2Art'])) . "'";
    $updateFields[] = "parag3Art = '" . str_replace("'", "''", ctrlSaisies($_POST['parag3Art'])) . "'";
    $updateFields[] = "libConclArt = '" . str_replace("'", "''", ctrlSaisies($_POST['libConclArt'])) . "'";
    $updateFields[] = "numThem = " . intval($_POST['numThem']);

    /**
     * MISE À JOUR DE LA PHOTO :
     * Si une nouvelle photo a été uploadée, on ajoute son nouveau nom à la requête.
     */
    if ($imagePath) {
        $updateFields[] = "urlPhotArt = '" . str_replace("'", "''", ctrlSaisies($imagePath)) . "'";
    }

    // On horodate la modification pour le suivi (Date Mise à Jour)
    $updateFields[] = "dtMajArt = NOW()";

    // On transforme le tableau en une chaîne de caractères séparée par des virgules
    $updateString = implode(', ', $updateFields);

    /**
     * EXÉCUTION DE L'UPDATE :
     * Modifie l'article cible identifié par son numArt.
     */
    sql_update('ARTICLE', $updateString, "numArt = $numArt");

    /**
     * ==========================================================
     * 4. GESTION DES RELATIONS (MOTS-CLÉS)
     * ==========================================================
     * Logique "Nettoyer et Réécrire" :
     * 1. On supprime toutes les anciennes liaisons dans la table pivot.
     */
    
    sql_delete('MOTCLEARTICLE', "numArt = $numArt");

    /**
     * 2. On parcourt le tableau de mots-clés sélectionnés dans le formulaire.
     * Pour chaque ID reçu, on crée une nouvelle ligne dans la table de liaison.
     */
    if (isset($_POST['motscles']) && is_array($_POST['motscles'])) {
        foreach ($_POST['motscles'] as $numMotCle) {
            $values = "$numArt, " . intval($numMotCle);
            sql_insert('MOTCLEARTICLE', 'numArt, numMotCle', $values);
        }
    }

    // Redirection vers le listing en cas de succès
    header('Location: ../../views/backend/articles/list.php');
    exit();

} else {
    /**
     * SÉCURITÉ :
     * Si quelqu'un tente d'accéder au script sans passer par le formulaire (GET),
     * on le renvoie immédiatement vers la liste.
     */
    header('Location: ../../views/backend/articles/list.php');
    exit();
}
?>