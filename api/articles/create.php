<?php
require_once '../../config.php';
require_once '../../functions/ctrlSaisies.php';

// 1. Récupération des champs
$libTitrArt     = ctrlSaisies($_POST['libTitrArt'] ?? '');
$libChapoArt    = ctrlSaisies($_POST['libChapoArt'] ?? '');
$libAccrochArt  = ctrlSaisies($_POST['libAccrochArt'] ?? '');
$parag1Art      = ctrlSaisies($_POST['parag1Art'] ?? '');
$libSsTitr1Art  = ctrlSaisies($_POST['libSsTitr1Art'] ?? '');
$parag2Art      = ctrlSaisies($_POST['parag2Art'] ?? '');
$libSsTitr2Art  = ctrlSaisies($_POST['libSsTitr2Art'] ?? '');
$parag3Art      = ctrlSaisies($_POST['parag3Art'] ?? '');
$libConclArt    = ctrlSaisies($_POST['libConclArt'] ?? '');
$numThem        = (int)($_POST['numThem'] ?? 0);

// 2. Gestion de la photo (Renommage automatique pour éviter les bugs de noms)
$urlPhotArt = "";
if (isset($_FILES['urlPhotArt']) && $_FILES['urlPhotArt']['error'] == 0) {
    $extension = pathinfo($_FILES['urlPhotArt']['name'], PATHINFO_EXTENSION);
    // On génère un nom unique : art_timestamp.extension
    $urlPhotArt = 'art_' . time() . '.' . $extension;
    $destination = "../../../src/uploads/" . $urlPhotArt;

    move_uploaded_file($_FILES['urlPhotArt']['tmp_name'], $destination);
}

// 3. Vérification des champs obligatoires
if (empty($libTitrArt) || empty($parag1Art) || $numThem <= 0) {
    header('Location: ../../views/backend/articles/create.php?error=empty_fields');
    exit();
}

// 4. Insertion SQL avec le nouveau nom de photo
sql_insert(
    'article',
    'dtCreaArt, libTitrArt, libChapoArt, libAccrochArt, parag1Art, libSsTitr1Art, parag2Art, libSsTitr2Art, parag3Art, libConclArt, urlPhotArt, numThem',
    "NOW(), '$libTitrArt', '$libChapoArt', '$libAccrochArt', '$parag1Art', '$libSsTitr1Art', '$parag2Art', '$libSsTitr2Art', '$parag3Art', '$libConclArt', '$urlPhotArt', '$numThem'"
);

// 5. Redirection
header('Location: ../../views/backend/articles/list.php?success=1');
exit();
?>