
<?php
require_once '../../config.php';
require_once '../../functions/ctrlSaisies.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numArt'])) {
    $numArt = intval($_POST['numArt']);

    $imagePath = null;
    if (isset($_FILES['urlPhotArt']) && $_FILES['urlPhotArt']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/';
        $fileName = basename($_FILES['urlPhotArt']['name']);
        $filePath = $uploadDir . $fileName;

        // Bouge le fichier téléchargé vers le dossier
        if (move_uploaded_file($_FILES['urlPhotArt']['tmp_name'], $filePath)) {
            $imagePath = $fileName;
        }
    }

    // Construire la chaîne de mise à jour dynamiquement en fonction des champs présents
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

    // En cas de nouvelle image, on met à jour le champ urlPhotArt
    if ($imagePath) {
        $updateFields[] = "urlPhotArt = '" . str_replace("'", "''", ctrlSaisies($imagePath)) . "'";
    }

    $updateFields[] = "dtMajArt = NOW()";

    $updateString = implode(', ', $updateFields);

    // Mettre à jour l'article
    sql_update('ARTICLE', $updateString, "numArt = $numArt");

    // Supprimer les anciennes associations mots-clés
    sql_delete('MOTCLEARTICLE', "numArt = $numArt");

    // Ajouter les nouvelles associations mots-clés
    if (isset($_POST['motscles']) && is_array($_POST['motscles'])) {
        foreach ($_POST['motscles'] as $numMotCle) {
            $values = "$numArt, " . intval($numMotCle);
            sql_insert('MOTCLEARTICLE', 'numArt, numMotCle', $values);
        }
    }

    // Renvoyer vers la liste des articles après la mise à jour
    header('Location: ../../views/backend/articles/list.php');
    exit();
} else {
    // Si la requête n'est pas en POST ou si numArt n'est pas défini, rediriger vers la liste des articles
    header('Location: ../../views/backend/articles/list.php');
    exit();
}
?>
