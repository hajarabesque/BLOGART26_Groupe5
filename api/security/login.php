<?php
// On inclut la config pour $db
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
session_start(); 

if (isset($_POST['btn'])) {
    $email = htmlspecialchars($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $recaptchaToken = $_POST['g-recaptcha-response'] ?? '';
    
    // 1. Vérification des champs
    if (empty($email) || empty($password)) {
        header('Location: login.php?error=empty');
        exit();
    }

    // 2. Vérification du Captcha (Même clé que ton inscription)
    $recaptchaSecretKey = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query(['secret' => $recaptchaSecretKey, 'response' => $recaptchaToken]),
        CURLOPT_RETURNTRANSFER => true
    ]);
    $response = json_decode(curl_exec($curl), true);
    curl_close($curl);

    if (!$response['success']) {
        header('Location: login.php?error=captcha');
        exit();
    }

    // 3. Recherche de l'utilisateur dans la table MEMBRE
    // On sélectionne les colonnes exactes que tu utilises dans ton inscription
    $query = $db->prepare('SELECT numMemb, pseudoMemb, passMemb, numStat FROM MEMBRE WHERE eMailMemb = :email');
    $query->execute([':email' => $email]);
    $user = $query->fetch();

    // 4. Vérification du mot de passe (Haché avec password_hash dans l'inscription)
    if ($user && password_verify($password, $user['passMemb'])) {
        
        // --- C'EST ICI QUE TU ALIGNES LE LOGIN SUR L'INSCRIPTION ---
        $_SESSION['user'] = [
            'id'     => $user['numMemb'],    // Correspond à lastInsertId() de l'inscription
            'pseudo' => $user['pseudoMemb'], // Correspond au pseudo de l'inscription
            'statut' => $user['numStat']    // Correspond au '3' (Membre) de l'inscription
        ];
        
        // Redirection vers l'accueil
        header('Location: /index.php');
        exit();
    } else {
        header('Location: login.php?error=bad_creds');
        exit();
    }

} else {
    header('Location: login.php');
    exit();
}