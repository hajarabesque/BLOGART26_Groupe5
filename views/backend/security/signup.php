<?php
/**
 * ==========================================================
 * 1. LOGIQUE D'INSCRIPTION ET VALIDATION (BACKEND)
 * ==========================================================
 */
include '../../../header.php'; // Contient la connexion $db et la session

$errors = [];
$success = [];

/**
 * TRAITEMENT DU FORMULAIRE :
 * Se déclenche uniquement quand l'utilisateur clique sur "Création".
 */
if (isset($_POST['btn'])) {
    
    // NETTOYAGE : htmlspecialchars évite que l'utilisateur injecte du code HTML/JS
    $pseudo    = htmlspecialchars($_POST['pseudo']);
    $prenom    = htmlspecialchars($_POST['prenom']);
    $nom       = htmlspecialchars($_POST['nom']);
    $email     = htmlspecialchars($_POST['email']);
    $confEmail = htmlspecialchars($_POST['confirm_email']);
    $password  = $_POST['password']; // On ne nettoie pas le password pour ne pas altérer les caractères spéciaux
    $confirm   = $_POST['confirm_password'];

    /**
     * CONTRÔLES DE SÉCURITÉ :
     * On vérifie la cohérence des données avant de toucher à la base.
     */
    if ($email !== $confEmail) {
        $errors[] = "Les adresses emails ne correspondent pas.";
    }
    if ($password !== $confirm) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
    if (strlen($pseudo) < 6) {
        $errors[] = "Le pseudo doit faire au moins 6 caractères.";
    }

    /**
     * COMPLEXITÉ DU MOT DE PASSE (Regex) :
     * Vérifie la présence d'une Majuscule, une minuscule, un chiffre et entre 8 et 15 caractères.
     */
    
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,15}$/', $password)) {
        $errors[] = "Le mot de passe doit contenir entre 8 et 15 caractères (une majuscule, une minuscule et un chiffre).";
    }

    /**
     * VÉRIFICATION DES DOUBLONS :
     * On s'assure que le pseudo ou l'email n'est pas déjà présent dans la table MEMBER.
     */
    $sqlCheck = "SELECT * FROM MEMBER WHERE pseudoMemb = ? OR eMailMemb = ?";
    $check = $db->prepare($sqlCheck);
    $check->execute([$pseudo, $email]);
    
    if ($check->rowCount() > 0) {
        $errors[] = "Le pseudo ou l'email est déjà utilisé.";
    }

    /**
     * INSERTION EN BASE DE DONNÉES :
     * Si le tableau $errors est vide, on procède à l'enregistrement.
     */
    if (empty($errors)) {
        /**
         * HACHAGE DU MOT DE PASSE :
         * On ne stocke JAMAIS le mot de passe en clair. password_hash crée une empreinte sécurisée.
         */
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // numStat = 3 correspond généralement au statut "Membre" par défaut dans ton MCD
        $insert = $db->prepare('INSERT INTO MEMBER (prenomMemb, nomMemb, pseudoMemb, passMemb, eMailMemb, dtCreaMemb, accordMemb, numStat) VALUES (?, ?, ?, ?, ?, NOW(), 1, 3)');
        $result = $insert->execute([$prenom, $nom, $pseudo, $hashedPassword, $email]);

        if ($result) {
            $success[] = "Compte créé avec succès ! <a href='login.php'>Connectez-vous ici</a>";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="../../../src/css/login.css">
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1>Inscription</h1>
        </div>

        <?php if(!empty($errors)): ?>
            <div class="alert-box error">
                <?php foreach($errors as $e) echo $e . "<br>"; ?>
            </div>
        <?php endif; ?>

        <?php if(!empty($success)): ?>
            <div class="alert-box success">
                <?php foreach($success as $s) echo $s . "<br>"; ?>
            </div>
        <?php endif; ?>
        
        <form action="" method="POST">
            <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <input type="text" id="pseudo" name="pseudo" value="<?= $pseudo ?? '' ?>" required>
                <span class="form-hint">(Min. 6 caractères)</span>
            </div>
            
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
                <span class="form-hint">(8-15 car. : Maj, Min, Chiffre)</span>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirmez le Mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <div class="form-group">
                <p>J'accepte la conservation des données (RGPD)</p>
                <div style="display: flex; gap: 20px;">
                    <label><input type="radio" name="data_acceptance" value="1" required> Oui</label>
                    <label><input type="radio" name="data_acceptance" value="0" required> Non</label>
                </div>
            </div>
                
            <div class="form-group captcha-container">
                <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>
            </div>
            
            <button type="submit" name="btn" class="submit-btn">Créer mon compte</button>
        </form>

        <div class="login-link">
            Déjà un compte ? <a href="login.php">Connectez-vous</a>
        </div>
    </div>
</body>
</html>