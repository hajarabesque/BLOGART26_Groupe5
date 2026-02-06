<?php
require_once '../../config.php';
require_once '../../functions/ctrlSaisies.php';

// 1. Récupération des données
$numArt = (int)($_POST['numArt'] ?? 0);
$libCom = ctrlSaisies($_POST['libCom'] ?? '');
$origin = $_POST['origin'] ?? 'front';

// Vérifications basiques
if ($numArt <= 0 || empty($libCom)) {
    header('Location: ' . ROOT_URL . '/views/frontend/articles/article1.php?id=' . $numArt . '&error=missing');
    exit();
}

// 2. Trouver l'ID du membre : si l'utilisateur est connecté on l'utilise,
// sinon on authentifie via pseudo + mot de passe fournis dans le formulaire.
session_start();
$numMemb = null;
if (!empty($_SESSION['user']['id'])) {
    $numMemb = (int)$_SESSION['user']['id'];
} else {
    $pseudo = ctrlSaisies($_POST['pseudoMemb'] ?? '');
    $passRaw = $_POST['passMemb'] ?? '';

    if (empty($pseudo) || empty($passRaw)) {
        header('Location: ' . ROOT_URL . '/views/frontend/articles/article1.php?id=' . $numArt . '&error=auth');
        exit();
    }

    // Rechercher le membre par pseudo
    $res = sql_select('MEMBRE', 'numMemb, passMemb', "pseudoMemb = '" . sql_escape($pseudo) . "'");
    if (empty($res)) {
        header('Location: ' . ROOT_URL . '/views/frontend/articles/article1.php?id=' . $numArt . '&error=auth');
        exit();
    }

    $row = $res[0];
    if (!password_verify($passRaw, $row['passMemb'])) {
        header('Location: ' . ROOT_URL . '/views/frontend/articles/article1.php?id=' . $numArt . '&error=auth');
        exit();
    }

    $numMemb = (int)$row['numMemb'];
}

// 3. Insertion du commentaire dans la table COMMENT
$attModOK = 0; // en attente de modération
$notifComKOAff = 'NULL';

sql_insert(
    'COMMENT',
    'dtCreaCom, libCom, attModOK, notifComKOAff, numArt, numMemb',
    "NOW(), '" . sql_escape($libCom) . "', $attModOK, $notifComKOAff, $numArt, $numMemb"
);

// 4. Redirection selon l'origine
if ($origin === 'front') {
    header('Location: ' . ROOT_URL . '/views/frontend/articles/article1.php?id=' . $numArt . '&success=1');
} else {
    header('Location: ' . ROOT_URL . '/views/backend/comments/list.php');
}
exit();
?>