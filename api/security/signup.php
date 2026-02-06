<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
session_start();

if (isset($_POST['btn'])) {
    $email = htmlspecialchars($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $recaptchaToken = $_POST['g-recaptcha-response'] ?? '';
    
    // 1. Vérification champs vides
    if (empty($email) || empty($password)) {
        header('Location: ../views/backend/security/login.php?error=empty');
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
        header('Location: ../views/backend/security/login.php?error=captcha');
        exit();
    }

    // 3. Recherche de l'utilisateur
    // On récupère numMemb (ID), pseudoMemb et numStat (le grade)
    $query = $db->prepare('SELECT numMemb, pseudoMemb, passMemb, numStat FROM MEMBRE WHERE eMailMemb = :email');
    $query->execute([':email' => $email]);
    $user = $query->fetch();

    // 4. Vérification du mot de passe haché
    if ($user && password_verify($password, $user['passMemb'])) {
        
        // On aligne la session sur ce que fait l'inscription
        $_SESSION['user'] = [
            'id'     => $user['numMemb'],
            'pseudo' => $user['pseudoMemb'],
            'statut' => $user['numStat'] // Utile pour savoir si c'est un admin (1) ou membre (3)
        ];
        
        header('Location: ../../../index.php');
        exit();
    } else {
        header('Location: ../views/backend/security/login.php?error=bad_creds');
        exit();
    }

} else {
    header('Location: ../views/backend/security/login.php');
    exit();
}