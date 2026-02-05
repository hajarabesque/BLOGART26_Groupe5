<?php
// On démarre la session pour pouvoir passer des messages d'erreurs ou 
// des données d'une page à l'autre (ex: garder les champs remplis en cas d'erreur)
session_start();

// Inclusion des fichiers nécessaires
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php'; // Configuration DB et constantes
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/ctrlSaisies.php'; // Fonction pour nettoyer les données (sécurité)

// On vérifie si le formulaire a bien été envoyé via la méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // On prépare un tableau vide pour stocker les erreurs potentielles
    $errors = [];
    
    // --- RÉCUPÉRATION ET NETTOYAGE DES DONNÉES ---
    // ctrlSaisies permet d'éviter les failles XSS (supprime les espaces inutiles, échappe les caractères HTML)
    $pseudo = ctrlSaisies($_POST['pseudoMemb']);
    $email1 = ctrlSaisies($_POST['eMailMemb']);
    $email2 = ctrlSaisies($_POST['eMailMembConf']);
    
    // Pour les mots de passe, on ne les nettoie pas avec ctrlSaisies 
    // car on veut autoriser tous les caractères spéciaux (mais on les sécurisera plus tard)
    $pass1  = $_POST['passMemb'];
    $pass2  = $_POST['passMembConf'];
    $prenom = ctrlSaisies($_POST['prenomMemb']);
    $nom    = ctrlSaisies($_POST['nomMemb']);
    $numStat = $_POST['numStat']; // Le statut (ex: 1 pour membre, 2 pour admin)
    $accord = isset($_POST['accordMemb']) ? $_POST['accordMemb'] : 0; // Vérifie si la case RGPD est cochée

    // --- 1. VALIDATION DU PSEUDO ---
    // Vérification de la longueur (entre 6 et 70 caractères)
    if (strlen($pseudo) < 6 || strlen($pseudo) > 70) {
        $errors['pseudoMemb'] = "Le pseudo doit faire entre 6 et 70 caractères.";
    } else {
        // On vérifie en base de données si le pseudo n'est pas déjà pris
        $verif = sql_select("MEMBRE", "numMemb", "pseudoMemb = '$pseudo'");
        if ($verif) {
            $errors['pseudoMemb'] = "Ce pseudo est déjà utilisé.";
        }
    }

    // --- 2. VALIDATION DU MOT DE PASSE ---
    // Regex : 8-15 caractères, au moins 1 Majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial
    $regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,15}$/";
    
    if (!preg_match($regex, $pass1)) {
        $errors['passMemb'] = "Mot de passe invalide (8-15 car, 1 Maj, 1 min, 1 chiffre, 1 spécial).";
    } elseif ($pass1 !== $pass2) {
        // Vérification que les deux saisies correspondent
        $errors['passMembConf'] = "Les mots de passe ne correspondent pas.";
    }

    // --- 3. VALIDATION DE L'EMAIL ---
    // Utilisation d'un filtre natif PHP pour vérifier le format de l'email
    if (!filter_var($email1, FILTER_VALIDATE_EMAIL)) {
        $errors['eMailMemb'] = "Format d'email invalide.";
    } elseif ($email1 !== $email2) {
        // Vérification que les deux emails correspondent
        $errors['eMailMembConf'] = "Les emails ne correspondent pas.";
    }

    // --- 4. VALIDATION RGPD ---
    // L'utilisateur doit obligatoirement accepter les conditions
    if ($accord != 1) {
        $errors['accordMemb'] = "L'accord RGPD est obligatoire.";
    }

    // --- GESTION DES ERREURS ---
    // Si le tableau d'erreurs n'est pas vide, on renvoie l'utilisateur au formulaire
    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors; // On stocke les erreurs pour les afficher
        $_SESSION['old'] = $_POST;     // On stocke les saisies pour remplir les champs automatiquement
        header('Location: /views/backend/members/create.php');
        exit(); // On arrête l'exécution du script
    }

    // --- SI TOUT EST BON : INSCRIPTION ---
    
    // On hache le mot de passe (sécurité : on ne stocke jamais un MDP en clair !)
    $passHash = password_hash($pass1, PASSWORD_DEFAULT);
    
    // On prépare la date actuelle pour la création
    $dtCrea = date("Y-m-d H:i:s");
    
    // Insertion dans la base de données
    // On utilise ici la fonction personnalisée sql_insert
    sql_insert("MEMBRE", 
        "prenomMemb, nomMemb, pseudoMemb, passMemb, eMailMemb, dtCreaMemb, dtMajMemb, accordMemb, numStat", 
        "'$prenom', '$nom', '$pseudo', '$passHash', '$email1', '$dtCrea', NULL, $accord, $numStat"
    );

    // Nettoyage de la session après succès (on enlève les messages d'erreurs et anciennes saisies)
    unset($_SESSION['errors'], $_SESSION['old']);
    
    // Redirection vers la liste des membres avec un message de succès potentiel
    header('Location: /views/backend/members/list.php');
    exit();
}