
<?php
require_once '../../config.php';
require_once '../../functions/ctrlSaisies.php';

// 1. Récupération des infos d'identité et du commentaire
$pseudo = ctrlSaisies($_POST['pseudoMemb'] ?? '');
$prenom = ctrlSaisies($_POST['prenomMemb'] ?? '');
$nom    = ctrlSaisies($_POST['nomMemb'] ?? '');
$numArt = (int)($_POST['numArt'] ?? 0);
$libCom = ctrlSaisies($_POST['libCom'] ?? '');

// 2. Trouver l'ID du membre (numMemb) à partir de son identité
// On utilise ta table 'membre' (en minuscules)
$sql = "SELECT numMemb FROM membre WHERE pseudoMemb = '$pseudo' AND prenomMemb = '$prenom' AND nomMemb = '$nom'";
$res = sql_select('membre', 'numMemb', "pseudoMemb = '$pseudo' AND prenomMemb = '$prenom' AND nomMemb = '$nom'");

if (empty($res)) {
    // Si on ne trouve pas le membre, on affiche l'alerte
    echo "<script>alert('Identité inconnue. Veuillez vérifier votre pseudo, nom et prénom.'); window.location.href='../../views/backend/comments/create.php';</script>";
    exit();
}

$numMemb = $res[0]['numMemb'];
Z
// 3. Insertion du commentaire Table = comment
$attModOK = 0;
$notifComKOAff = 0;

sql_insert(
    'comment',
    'dtCreaCom, libCom, attModOK, notifComKOAff, numArt, numMemb',
    "NOW(), '$libCom', '$attModOK', '$notifComKOAff', '$numArt', '$numMemb'"
);

if ($origin === 'front') {
    // On le renvoie vers la page de l'article à la racine
    header("Location: ../../article.php?numArt=$numArt&success=1");
} else {
    // Si c'est un admin dans le backend, on le renvoie vers la liste
    header('Location: ../../views/backend/comments/list.php');
}
exit();
?>
>>>>>>> e8486efd7714ef339d3770a9c34db183bc2cacbc
