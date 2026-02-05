<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Blog'Art</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="src/css/header.css" />
    <link rel="stylesheet" href="src/css/index.css" />
    <link rel="shortcut icon" type="image/x-icon" href="src/images/article1.png" />
</head>
<?php
//load config
require_once 'config.php';

try {
    $db = new PDO('mysql:host=localhost;dbname=blogart26;charset=utf8', 'root', 'root');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>



<body>

<nav class="custom-nav d-flex align-items-stretch" style="height: 100px; background: #d12119;">
    <div class="nav-block bg-logo px-4 d-flex align-items-center" style="background: #b30000;">
        <a href="/"><img src="/src/images/logo2.png" alt="Logo" style="height: 60px;"></a>
    </div>

    <div class="nav-block flex-grow-1 d-flex align-items-center justify-content-center gap-4">
        <a href="/index.php" class="text-white text-decoration-none fw-bold">ACCUEIL</a>
        <a href="/views/frontend/events.php" class="text-white text-decoration-none fw-bold">ARTICLES</a>
        <a href="/views/frontend/actors.php" class="text-white text-decoration-none fw-bold">ACTEURS</a>
    </div>

    <div class="nav-block bg-blue px-4 d-flex align-items-center gap-3" style="background: #0044ff;">
        <a href="/views/backend/security/login.php" class="text-white text-decoration-none">Login</a>
        <a href="/views/backend/security/signup.php" class="btn btn-outline-light btn-sm">Sign up</a>
        <a href="/views/frontend/search.php" class="btn btn-outline-light btn-sm">Rechercher</a>
        <a href="/views/backend/dashboard.php" class="btn btn-outline-light btn-sm">Admin</a>
    </div>
</nav>