<?php
/**
 * ==========================================================
 * 1. LOGIQUE D'INSCRIPTION ET VALIDATION (BACKEND)
 * ==========================================================
 */
// Ajuste le chemin vers ton header si nécessaire
include '../../../header.php'; 

$errors = [];
$success = [];

/**
 * TRAITEMENT DU FORMULAIRE
 */
if (isset($_POST['btn'])) {
    
    // NETTOYAGE
    $pseudo    = htmlspecialchars($_POST['pseudo'] ?? '');
    $prenom    = htmlspecialchars($_POST['prenom'] ?? '');
    // On garde le nom vide si tu l'as supprimé du formulaire, pour ne pas faire bugger la BDD
    $nom       = ""; 
    $email     = htmlspecialchars($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? ''; 
    $confirm   = $_POST['confirm_password'] ?? '';
    $accord    = $_POST['data_acceptance'] ?? '0';

    /**
     * CONTRÔLES DE SÉCURITÉ
     */
    if ($password !== $confirm) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
    if (strlen($pseudo) < 6) {
        $errors[] = "Le pseudo doit faire au moins 6 caractères.";
    }
    if ($accord == '0') {
        $errors[] = "Vous devez accepter la conservation des données (RGPD).";
    }

    /**
     * COMPLEXITÉ DU MOT DE PASSE (Regex)
     */
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,15}$/', $password)) {
        $errors[] = "Le mot de passe doit contenir entre 8 et 15 caractères (une majuscule, une minuscule et un chiffre).";
    }

    /**
     * VÉRIFICATION DES DOUBLONS
     */
    $sqlCheck = "SELECT * FROM MEMBRE WHERE pseudoMemb = ? OR eMailMemb = ?";
    $check = $db->prepare($sqlCheck);
    $check->execute([$pseudo, $email]);
    
    if ($check->rowCount() > 0) {
        $errors[] = "Le pseudo ou l'email est déjà utilisé.";
    }

    /**
     * INSERTION EN BASE DE DONNÉES
     */
    if (empty($errors)) {
        // HACHAGE
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insertion (on insère une chaîne vide pour nomMemb puisque tu l'as retiré du formulaire)
        $insert = $db->prepare('INSERT INTO MEMBRE (prenomMemb, nomMemb, pseudoMemb, passMemb, eMailMemb, dtCreaMemb, accordMemb, numStat) VALUES (?, ?, ?, ?, ?, NOW(), ?, 3)');
        $result = $insert->execute([$prenom, $nom, $pseudo, $hashedPassword, $email, $accord]);

        if ($result) {
            // Redirection automatique vers l'accueil après inscription
            header('Location: ../../../index.php');
            exit();
        } else {
            $errors[] = "Erreur technique lors de l'enregistrement.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Musique Moderne</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Bootstrap (utile pour les classes form-control, mb-3, etc.) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="../../../src/css/login.css">
</head>
<body class="bg-light">
    <div class="form-container shadow-sm p-4 bg-white rounded mx-auto mt-5" style="max-width: 500px;">
        <div class="form-header text-center mb-4">
            <h1 class="h2 fw-bold text-uppercase">Inscription</h1>
        </div>

        <!-- Affichage des erreurs -->
        <?php if(!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach($errors as $e) echo $e . "<br>"; ?>
            </div>
        <?php endif; ?>

        <!-- Affichage du succès -->
        <?php if(!empty($success)): ?>
            <div class="alert alert-success">
                <?php foreach($success as $s) echo $s . "<br>"; ?>
            </div>
        <?php endif; ?>
        
        <form action="" method="POST">
            <!-- PSEUDO -->
            <div class="mb-3">
                <label for="pseudo" class="form-label fw-bold">Pseudo</label>
                <input type="text" id="pseudo" name="pseudo" class="form-control" value="<?= $pseudo ?? '' ?>" required>
                <div class="form-text">Min. 6 caractères</div>
            </div>
            
            <!-- PRENOM -->
            <div class="mb-3">
                <label for="prenom" class="form-label fw-bold">Prénom</label>
                <input type="text" id="prenom" name="prenom" class="form-control" value="<?= $prenom ?? '' ?>" required>
            </div>

            <!-- EMAIL -->
            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Adresse E-mail</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="votre@email.com" value="<?= $email ?? '' ?>" required>
            </div>

            <!-- MOT DE PASSE -->
            <div class="mb-3">
                <label for="password" class="form-label fw-bold">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control" required>
                <div class="form-text">8-15 car. (Majuscule, minuscule, chiffre)</div>
            </div>

            <!-- CONFIRMATION -->
            <div class="mb-3">
                <label for="confirm_password" class="form-label fw-bold">Confirmez le Mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>

            <!-- RGPD -->
            <div class="mb-4">
                <p class="mb-2 fw-bold" style="font-size: 0.9rem;">J'accepte la conservation des données (RGPD)</p>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="data_acceptance" id="rgpd_oui" value="1" required>
                        <label class="form-check-label" for="rgpd_oui">Oui</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="data_acceptance" id="rgpd_non" value="0" required>
                        <label class="form-check-label" for="rgpd_non">Non</label>
                    </div>
                </div>
            </div>
                
            <!-- CAPTCHA -->
            <div class="mb-4 d-flex justify-content-center">
                <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>
            </div>
            
            <!-- BOUTON -->
            <div class="d-grid">
                <button type="submit" name="btn" class="btn btn-dark btn-lg text-uppercase fw-bold">Créer mon compte</button>
            </div>
        </form>

        <div class="login-link text-center mt-4">
            Déjà un compte ? <a href="login.php" class="text-decoration-none fw-bold">Connectez-vous</a>
        </div>
    </div>
</body>
</html>