<?php
/**
 * ==========================================================
 * 1. SÉCURISATION ET RÉCUPÉRATION
 * ==========================================================
 */
require_once '../../config.php';
require_once '../../functions/ctrlSaisies.php';

// ctrlSaisies est crucial : il nettoie les données pour éviter les failles XSS
$libTitrArt     = ctrlSaisies($_POST['libTitrArt'] ?? '');
$libChapoArt    = ctrlSaisies($_POST['libChapoArt'] ?? '');
$libAccrochArt  = ctrlSaisies($_POST['libAccrochArt'] ?? '');
$parag1Art      = ctrlSaisies($_POST['parag1Art'] ?? '');
$libSsTitr1Art  = ctrlSaisies($_POST['libSsTitr1Art'] ?? '');
$parag2Art      = ctrlSaisies($_POST['parag2Art'] ?? '');
$libSsTitr2Art  = ctrlSaisies($_POST['libSsTitr2Art'] ?? '');
$parag3Art      = ctrlSaisies($_POST['parag3Art'] ?? '');
$libConclArt    = ctrlSaisies($_POST['libConclArt'] ?? '');
$numThem        = (int)($_POST['numThem'] ?? 0); // Cast en INT pour la sécurité SQL

/**
 * ==========================================================
 * 2. GESTION DE L'UPLOAD D'IMAGE
 * ==========================================================
 */


$urlPhotArt = "";
if (isset($_FILES['urlPhotArt']) && $_FILES['urlPhotArt']['error'] == 0) {
    // On récupère l'extension (jpg, png, etc.)
    $extension = pathinfo($_FILES['urlPhotArt']['name'], PATHINFO_EXTENSION);
    
    // NOMMAGE UNIQUE : 'art_' + timestamp (ex: art_1707150000.jpg)
    // Cela évite que deux images avec le même nom (ex: "image.jpg") s'écrasent.
    $urlPhotArt = 'art_' . time() . '.' . $extension;
    
    // CHEMIN ABSOLU : On définit où stocker le fichier sur le disque dur du serveur
    $destination = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $urlPhotArt;
    
    // Déplacement du fichier du dossier temporaire vers ton dossier final
    move_uploaded_file($_FILES['urlPhotArt']['tmp_name'], $destination);
}

/**
 * ==========================================================
 * 3. VALIDATION ET INSERTION SQL
 * ==========================================================
 */
// Vérification de sécurité côté serveur (si le JS a été contourné)
if (empty($libTitrArt) || empty($parag1Art) || $numThem <= 0) {
    header('Location: ../../views/backend/articles/create.php?error=empty_fields');
    exit();
}

/**
 * INSERTION : 
 * Note l'utilisation de NOW() pour la date de création automatique.
 */
sql_insert(
    'article',
    'dtCreaArt, libTitrArt, libChapoArt, libAccrochArt, parag1Art, libSsTitr1Art, parag2Art, libSsTitr2Art, parag3Art, libConclArt, urlPhotArt, numThem',
    "NOW(), '$libTitrArt', '$libChapoArt', '$libAccrochArt', '$parag1Art', '$libSsTitr1Art', '$parag2Art', '$libSsTitr2Art', '$parag3Art', '$libConclArt', '$urlPhotArt', '$numThem'"
);

// Redirection vers la liste avec un message de succès
header('Location: ../../views/backend/articles/list.php?success=1');
exit();
?>