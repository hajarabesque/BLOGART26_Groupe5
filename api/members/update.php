<?php
// On démarre la session pour gérer les messages d'erreurs
session_start();

// Inclusion des fichiers de configuration et des fonctions de sécurité
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/ctrlSaisies.php';

// On vérifie que le formulaire a été envoyé en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // On récupère l'identifiant unique du membre à modifier (caché dans le formulaire)
    $numMemb = $_POST['numMemb'];
    $errors = [];

    // --- 1. RÉCUPÉRATION ET NETTOYAGE DES DONNÉES ---
    // ctrlSaisies : nettoie les balises HTML
    // addslashes : ajoute des anti-slashs pour protéger les apostrophes dans les noms (ex: D'Orazio)
    $prenom = addslashes(ctrlSaisies($_POST['prenomMemb']));
    $nom    = addslashes(ctrlSaisies($_POST['nomMemb']));
    $email1 = ctrlSaisies($_POST['eMailMemb']);
    $email2 = ctrlSaisies($_POST['eMailMembConf']);
    $pass1  = $_POST['passMemb'];
    $pass2  = $_POST['passMembConf'];
    $numStat = $_POST['numStat']; // Nouveau statut (admin, membre...)
    
    // On génère la date et l'heure actuelle pour dire que le profil a été modifié maintenant
    $dtMaj = date("Y-m-d H:i:s");

    // --- 2. VÉRIFICATION DES EMAILS ---
    // On vérifie si le format est bon ET si les deux emails saisis sont identiques
    if (!filter_var($email1, FILTER_VALIDATE_EMAIL) || $email1 !== $email2) {
        $errors['email'] = "Les emails ne sont pas valides ou ne correspondent pas.";
    }

    // --- 3. VÉRIFICATION DU MOT DE PASSE (Optionnel) ---
    // Logique : Si les cases password sont vides, on ne change pas le mot de passe actuel.
    $passUpdateQuery = ""; // On prépare un morceau de requête SQL vide
    
    if (!empty($pass1)) {
        // Si l'utilisateur a écrit quelque chose, on vérifie la sécurité (Regex)
        $regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,15}$/";
        
        if (!preg_match($regex, $pass1)) {
            $errors['pass'] = "Le mot de passe ne respecte pas les critères (8-15 car, Maj, min, chiffre, spécial).";
        } elseif ($pass1 !== $pass2) {
            $errors['pass'] = "Les mots de passe ne correspondent pas.";
        } else {
            // Si c'est bon, on hache le nouveau mot de passe
            $hash = password_hash($pass1, PASSWORD_DEFAULT);
            // On prépare le bout de code SQL pour mettre à jour le champ passMemb
            $passUpdateQuery = ", passMemb = '$hash'";
        }
    }

    // --- GESTION DES ERREURS ---
    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        // On renvoie vers la page d'édition en précisant quel membre on modifiait
        header("Location: /views/backend/members/edit.php?numMemb=$numMemb");
        exit();
    }

    // --- 4. EXÉCUTION DE LA MISE À JOUR (UPDATE) ---
    
    // On construit la liste des colonnes à modifier
    // Note : $passUpdateQuery restera vide si le mot de passe n'a pas été changé
    $queryValues = "prenomMemb = '$prenom', 
                    nomMemb = '$nom', 
                    eMailMemb = '$email1', 
                    numStat = $numStat, 
                    dtMajMemb = '$dtMaj' 
                    $passUpdateQuery";

    // On appelle la fonction SQL pour mettre à jour la table MEMBRE
    // WHERE numMemb = X permet de ne modifier QUE ce membre là
    sql_update("MEMBRE", $queryValues, "numMemb = $numMemb");

    // Une fois terminé, on redirige vers la liste des membres
    header('Location: /views/backend/members/list.php');
    exit();
}