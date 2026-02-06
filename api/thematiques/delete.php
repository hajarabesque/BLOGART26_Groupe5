<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

// On récupère l'ID soit par POST (formulaire) soit par GET (lien)
$numThem = isset($_POST['numThem']) ? $_POST['numThem'] : $_GET['numThem'];

// Vérifier que numThem est fourni
if (!isset($numThem) || empty($numThem)) {
    header('Location: ../../views/backend/thematiques/list.php?error=missing_id');
    exit();
}

// Récupérer la connexion PDO
try {
    $db = new PDO('mysql:host=localhost;dbname=blogart26;charset=utf8', 'root', 'root');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

// Vérifier si la thématique est utilisée dans des articles
$checkQuery = $db->prepare("SELECT COUNT(*) as count FROM ARTICLE WHERE numThem = ?");
$checkQuery->execute([$numThem]);
$result = $checkQuery->fetch();

if ($result['count'] > 0) {
    // La thématique est liée à des articles
    header('Location: ../../views/backend/thematiques/list.php?error=is_used');
    exit();
}

// Supprimer la thématique
$deleteQuery = $db->prepare("DELETE FROM THEMATIQUE WHERE numThem = ?");
$deleteResult = $deleteQuery->execute([$numThem]);

if ($deleteResult) {
    header('Location: ../../views/backend/thematiques/list.php?success=deleted');
} else {
    header('Location: ../../views/backend/thematiques/list.php?error=delete_failed');
}
exit();
?>