<?php
include '../../../header.php'; // Contient la connexion $db

$errors = [];
$success = [];


if (isset($_POST['btn'])) {
    // 1. Récupération et nettoyage des données
    $pseudo    = htmlspecialchars($_POST['pseudo']);
    $prenom    = htmlspecialchars($_POST['prenom']);
    $nom       = htmlspecialchars($_POST['nom']);
    $email     = htmlspecialchars($_POST['email']);
    $confEmail = htmlspecialchars($_POST['confirm_email']);
    $password  = $_POST['password'];
    $confirm   = $_POST['confirm_password'];

    // 2. Vérifications de sécurité
    if ($email !== $confEmail) {
        $errors[] = "Les adresses emails ne correspondent pas.";
    }
    if ($password !== $confirm) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
    if (strlen($prenom) < 6) {
        $errors[] = "Le prénom doit faire au moins 6 caractères.";
    }
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,15}$/', $password)) {
        $errors[] = "Le mot de passe doit contenir entre 8 et 15 caractères (une majuscule, une minuscule et un chiffre).";
    }

    // 3. LA CORRECTION : On utilise MEMBER en majuscules
 // On prépare la requête (Assure-toi que c'est bien écrit MEMBER)
    $sqlCheck = "SELECT * FROM MEMBER WHERE pseudoMemb = ? OR eMailMemb = ?";
    $check = $db->prepare($sqlCheck);
    // C'est cette ligne qui causait l'erreur si MEMBER était mal écrit au-dessus
    $check->execute([$pseudo, $email]);
    
    if ($check->rowCount() > 0) {
        $errors[] = "Le pseudo ou l'email est déjà utilisé.";
    }

    // 4. Insertion avec les noms de colonnes de ta capture d'écran
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // On insère dans MEMBER avec les colonnes se terminant par Memb
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
            <div style="background: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
                <?php foreach($errors as $e) echo $e . "<br>"; ?>
            </div>
        <?php endif; ?>

        <?php if(!empty($success)): ?>
            <div style="background: #dcfce7; color: #15803d; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
                <?php foreach($success as $s) echo $s . "<br>"; ?>
            </div>
        <?php endif; ?>
        
        <form action="" method="POST">
            <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <input type="text" id="pseudo" name="pseudo" value="<?= isset($pseudo) ? $pseudo : '' ?>" required>
            </div>
            
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" required>
                <span class="form-hint">(Min. 6 caractères)</span>
            </div>
            
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            
            <div class="form-group">
                <label for="email">eMail</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_email">Confirmez eMail</label>
                <input type="email" id="confirm_email" name="confirm_email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <span class="form-hint">(8-15 car. : Maj, Min, Chiffre)</span>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirmez Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <div class="form-group">
                <p style="font-size: 18px; margin-bottom: 10px;">J'accepte la conservation des données</p>
                <div style="display: flex; gap: 20px;">
                    <label><input type="radio" name="data_acceptance" value="1" required> Oui</label>
                    <label><input type="radio" name="data_acceptance" value="0" required> Non</label>
                </div>
            </div>
                
            <div class="form-group" style="display: flex; justify-content: center; margin: 20px 0;">
                <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>
            </div>
            
            <button type="submit" name="btn" class="submit-btn">Création</button>
        </form>

        <div class="login-link">
            Déjà un compte ? <a href="login.php">Connectez-vous</a>
        </div>
    </div>
</body>
</html>