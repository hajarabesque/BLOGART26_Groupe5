<?php
/**
 * ==========================================================
 * 1. LOGIQUE D'INSCRIPTION ET VALIDATION (BACKEND)
 * ==========================================================
 */

// Importation du fichier de configuration global (connexion BDD $db, fonctions, etc.)
include '../../../header.php'; 

// Initialisation des tableaux pour stocker les messages de retour à l'utilisateur
$errors = [];
$success = [];

/**
 * TRAITEMENT DU FORMULAIRE : Se déclenche si l'index 'btn' existe dans la superglobale $_POST
 */
if (isset($_POST['btn'])) {
    
    // DÉSINFECTION DES DONNÉES : htmlspecialchars neutralise les balises HTML pour éviter les failles XSS
    // L'opérateur '??' (null coalescing) définit une chaîne vide si la donnée est absente
    $pseudo    = htmlspecialchars($_POST['pseudo'] ?? '');
    $prenom    = htmlspecialchars($_POST['prenom'] ?? '');

    // Variable 'nom' forcée à vide car absente du formulaire mais requise par la structure de la BDD
    $nom       = ""; 
    $email     = htmlspecialchars($_POST['email'] ?? '');
    
    // Récupération des mots de passe (on ne les désinfecte pas pour ne pas modifier les caractères spéciaux)
    $password  = $_POST['password'] ?? ''; 
    $confirm   = $_POST['confirm_password'] ?? '';
    
    // Récupération du choix RGPD (par défaut '0' si non coché)
    $accord    = $_POST['data_acceptance'] ?? '0';

    /**
     * CONTRÔLES DE SÉCURITÉ : Vérifications avant insertion
     */
    // Comparaison stricte des deux mots de passe saisis
    if ($password !== $confirm) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
    // Vérification de la longueur minimale du pseudo
    if (strlen($pseudo) < 6) {
        $errors[] = "Le pseudo doit faire au moins 6 caractères.";
    }
    // Validation obligatoire du consentement RGPD
    if ($accord == '0') {
        $errors[] = "Vous devez accepter la conservation des données (RGPD).";
    }

    /**
     * COMPLEXITÉ DU MOT DE PASSE (Regex) :
     * Vérifie : 1 minuscule (?=.*[a-z]), 1 Majuscule (?=.*[A-Z]), 1 chiffre (?=.*\d), entre 8 et 15 caractères .{8,15}
     */
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,15}$/', $password)) {
        $errors[] = "Le mot de passe doit contenir entre 8 et 15 caractères (une majuscule, une minuscule et un chiffre).";
    }

    /**
     * VÉRIFICATION DES DOUBLONS : Empêche d'utiliser deux fois le même pseudo ou email
     */
    $sqlCheck = "SELECT * FROM MEMBRE WHERE pseudoMemb = ? OR eMailMemb = ?";
    $check = $db->prepare($sqlCheck); // Utilisation d'une requête préparée contre les injections SQL
    $check->execute([$pseudo, $email]);
    
    // Si rowCount > 0, cela signifie qu'une entrée existe déjà
    if ($check->rowCount() > 0) {
        $errors[] = "Le pseudo ou l'email est déjà utilisé.";
    }

    /**
     * INSERTION EN BASE DE DONNÉES : Si aucune erreur n'a été détectée
     */
    if (empty($errors)) {
        /**
         * HACHAGE DU MOT DE PASSE : Transformation du texte clair en empreinte irréversible
         * PASSWORD_DEFAULT utilise actuellement l'algorithme sécurisé Bcrypt
         */
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Requête d'insertion avec des marqueurs '?' pour la sécurité
        // numStat = 3 : Définit l'utilisateur comme simple "Membre"
        $insert = $db->prepare('INSERT INTO MEMBRE (prenomMemb, nomMemb, pseudoMemb, passMemb, eMailMemb, dtCreaMemb, accordMemb, numStat) VALUES (?, ?, ?, ?, ?, NOW(), ?, 3)');
        $result = $insert->execute([$prenom, $nom, $pseudo, $hashedPassword, $email, $accord]);

        if ($result) {
            // CONNEXION AUTOMATIQUE : On ouvre la session immédiatement après l'inscription
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            // Stockage des informations essentielles en session
            $_SESSION['user'] = [
                'id' => $db->lastInsertId(), // Récupère l'ID auto-incrémenté généré par la BDD
                'pseudo' => $pseudo,
            ];
            // Redirection vers la page d'accueil (index.php)
            header('Location: ../../../index.php');
            exit(); // Arrête l'exécution du script après redirection
        } else {
            $errors[] = "Erreur technique lors de l'enregistrement.";
        }
    }
}
?>

<body class="bg-light"> <!-- Arrière-plan gris clair de Bootstrap -->
    <!-- Conteneur principal centré avec ombre portée et coins arrondis -->
    <div class="form-container shadow-sm p-4 bg-white rounded mx-auto mt-5" style="max-width: 500px;">
        
        <div class="form-header text-center mb-4">
            <h1 class="h2 fw-bold text-uppercase">Inscription</h1>
        </div>

        <!-- AFFICHAGE DES ERREURS : Boucle sur le tableau $errors si non vide -->
        <?php if(!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach($errors as $e) echo $e . "<br>"; ?>
            </div>
        <?php endif; ?>

        <!-- AFFICHAGE DU SUCCÈS : Optionnel ici car la redirection est prioritaire -->
        <?php if(!empty($success)): ?>
            <div class="alert alert-success">
                <?php foreach($success as $s) echo $s . "<br>"; ?>
            </div>
        <?php endif; ?>
        
        <!-- Formulaire pointant vers lui-même (action="") via la méthode POST (sécurisée) -->
        <form action="" method="POST">
            
            <!-- PSEUDO : value="<? $pseudo ?? '' ?>" permet de garder la saisie en cas d'erreur (Sticky Form) -->
            <div class="mb-3">
                <label for="pseudo" class="form-label fw-bold">Pseudo</label>
                <input type="text" id="pseudo" name="pseudo" class="form-control" value="<?= $pseudo ?? '' ?>" required>
                <div class="form-text">Min. 6 caractères</div>
            </div>
            
            <!-- PRÉNOM -->
            <div class="mb-3">
                <label for="prenom" class="form-label fw-bold">Prénom</label>
                <input type="text" id="prenom" name="prenom" class="form-control" value="<?= $prenom ?? '' ?>" required>
            </div>

            <!-- ADRESSE EMAIL : type="email" force la validation du format (présence du @) par le navigateur -->
            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Adresse E-mail</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="votre@email.com" value="<?= $email ?? '' ?>" required>
            </div>

            <!-- MOT DE PASSE : type="password" masque les caractères saisis -->
            <div class="mb-3">
                <label for="password" class="form-label fw-bold">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control" required>
                <div class="form-text">8-15 car. (Majuscule, minuscule, chiffre)</div>
            </div>

            <!-- CONFIRMATION DU MOT DE PASSE -->
            <div class="mb-3">
                <label for="confirm_password" class="form-label fw-bold">Confirmez le Mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>

            <!-- SECTION RGPD : Utilisation de boutons radio pour un choix binaire Oui/Non -->
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
                
            <!-- RECAPTCHA GOOGLE : Système de protection contre les robots (nécessite le JS de Google) -->
            <div class="mb-4 d-flex justify-content-center">
                <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>
            </div>
            
            <!-- BOUTON DE SOUMISSION : name="btn" est crucial pour le test isset($_POST['btn']) au début du script -->
            <div class="d-grid">
                <button type="submit" name="btn" class="btn btn-dark btn-lg text-uppercase fw-bold">Créer mon compte</button>
            </div>
        </form>

        <!-- Lien de navigation vers la page de connexion -->
        <div class="login-link text-center mt-4">
            Déjà un compte ? <a href="login.php" class="text-decoration-none fw-bold">Connectez-vous</a>
        </div>
    </div>
</body>
</html>