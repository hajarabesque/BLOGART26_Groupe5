<?php
require_once '../../config.php';
require_once '../../functions/ctrlSaisies.php';

// 1. Récupération et protection des champs (Gestion des apostrophes comme dans ton update)
$libTitrArt     = str_replace("'", "''", ctrlSaisies($_POST['libTitrArt'] ?? ''));
$libChapoArt    = str_replace("'", "''", ctrlSaisies($_POST['libChapoArt'] ?? ''));
$libAccrochArt  = str_replace("'", "''", ctrlSaisies($_POST['libAccrochArt'] ?? ''));
$parag1Art      = str_replace("'", "''", ctrlSaisies($_POST['parag1Art'] ?? ''));
$libSsTitr1Art  = str_replace("'", "''", ctrlSaisies($_POST['libSsTitr1Art'] ?? ''));
$parag2Art      = str_replace("'", "''", ctrlSaisies($_POST['parag2Art'] ?? ''));
$libSsTitr2Art  = str_replace("'", "''", ctrlSaisies($_POST['libSsTitr2Art'] ?? ''));
$parag3Art      = str_replace("'", "''", ctrlSaisies($_POST['parag3Art'] ?? ''));
$libConclArt    = str_replace("'", "''", ctrlSaisies($_POST['libConclArt'] ?? ''));
$numThem        = (int)($_POST['numThem'] ?? 0);

// 2. Gestion de la photo
$urlPhotArt = "";
if (isset($_FILES['urlPhotArt']) && $_FILES['urlPhotArt']['error'] == 0) {
    $extension = pathinfo($_FILES['urlPhotArt']['name'], PATHINFO_EXTENSION);
    $urlPhotArt = 'art_' . time() . '.' . $extension;
    $destination = "../../src/uploads/" . $urlPhotArt; 
    move_uploaded_file($_FILES['urlPhotArt']['tmp_name'], $destination);
}

// 3. Vérification des champs obligatoires
if (empty($libTitrArt) || empty($parag1Art) || $numThem <= 0) {
    header('Location: ../../views/backend/articles/create.php?error=empty_fields');
    exit();
}

// Insertion de l'article
sql_insert(
    'article',
    'dtCreaArt, libTitrArt, libChapoArt, libAccrochArt, parag1Art, libSsTitr1Art, parag2Art, libSsTitr2Art, parag3Art, libConclArt, urlPhotArt, numThem',
    "NOW(), '$libTitrArt', '$libChapoArt', '$libAccrochArt', '$parag1Art', '$libSsTitr1Art', '$parag2Art', '$libSsTitr2Art', '$parag3Art', '$libConclArt', '$urlPhotArt', '$numThem'"
);


global $DB;
$numArt = $DB->lastInsertId(); 

//Insertion des mots-clés 
if ($numArt > 0) {
    if (isset($_POST['motscles']) && is_array($_POST['motscles'])) {
        foreach ($_POST['motscles'] as $numMotCle) {
            $numMotCleSafe = intval($numMotCle);
            $values = "$numArt, $numMotCleSafe";
            
            sql_insert('motclearticle', 'numArt, numMotCle', $values);
        }
    }
} 


// 7. Redirection finale
header('Location: ../../views/backend/articles/list.php?success=1');
exit();
?>