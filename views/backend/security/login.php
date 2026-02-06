<?php
/**
 * ==========================================================
 * 1. LOGIQUE DE CONNEXION (SÉCURITÉ ET SESSIONS)
 * ==========================================================
 */
// On inclut le header (qui contient la connexion $db et le session_start())
include '../../../header.php'; 

// Tableau pour stocker les messages d'erreur à afficher à l'utilisateur
$errors = [];

/**
 * DÉTECTION DE LA SOUMISSION :
 * On vérifie si le bouton 'btn' a été cliqué.
 */
if (isset($_POST['btn'])) {
    
    // Nettoyage de l'email (protection contre les failles XSS élémentaires)
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password']; // On ne nettoie pas le mot de passe (on en a besoin tel quel)

    // ============================================================
    // VALIDATION DU RECAPTCHA : Protection contre les attaques bot
    // ============================================================
    // On récupère le token généré par Google depuis le formulaire
    $recaptchaToken = $_POST['g-recaptcha-response'] ?? '';
    
    // ATTENTION : À REMPLACER PAR VOS VRAIES CLÉS RECAPTCHA !
    // Obtenez-les gratuitement sur : https://www.google.com/recaptcha/admin
    // Ces clés de test ne fonctionnent qu'en développement local
    $recaptchaSecretKey = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';
    
    // Si aucun token n'est présent, l'utilisateur n'a pas coché le captcha
    if (empty($recaptchaToken)) {
        $errors[] = "Veuillez cocher le captcha pour prouver que vous n'êtes pas un robot.";
    } else {
        // ============================================================
        // APPEL À L'API GOOGLE : Vérification du token côté serveur
        // ============================================================
        // Préparation de la requête HTTPS POST vers Google
        $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
        
        // Options pour la requête cURL (communication avec le serveur Google)
        $curlOptions = [
            CURLOPT_URL => $recaptchaUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'secret' => $recaptchaSecretKey,
                'response' => $recaptchaToken
            ]),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true, // Vérification du certificat SSL (sécurité)
            CURLOPT_TIMEOUT => 10 // Délai d'attente max 10 secondes
        ];
        
        // Initialisation de cURL et exécution de la requête
        $curl = curl_init();
        curl_setopt_array($curl, $curlOptions);
        $response = curl_exec($curl);
        curl_close($curl);
        
        // Décodage de la réponse JSON reçue de Google
        $recaptchaResult = json_decode($response, true);
        
        // Vérification des conditions de réussite du captcha
        // success : booléen indiquant si le captcha est valide
        // score : entre 0 et 1 (0 = probablement bot, 1 = probablement humain) - pour reCAPTCHA v3
        if (
            !isset($recaptchaResult['success']) || 
            $recaptchaResult['success'] !== true || 
            (isset($recaptchaResult['score']) && $recaptchaResult['score'] < 0.5)
        ) {
            $errors[] = "Échec du captcha. Veuillez réessayer.";
        }
        // Si tout est valide, on continue vers la vérification des identifiants
    }

    if (!empty($email) && !empty($password) && empty($errors)) {
        
        /**
         * REQUÊTE PRÉPARÉE :
         * On cherche le membre par son email. On utilise ':' pour éviter les injections SQL.
         */
        $query = $db->prepare('SELECT * FROM MEMBRE WHERE eMailMemb = :email');
        $query->execute([':email' => $email]);
        $user = $query->fetch();

        /**
         * VÉRIFICATION DU MOT DE PASSE :
         * password_verify() compare le mot de passe tapé avec l'empreinte (hash) 
         * stockée en base de données.
         */
        if ($user && password_verify($password, $user['passMemb'])) {
            
            /**
             * CONNEXION RÉUSSIE :
             * On remplit la variable superglobale $_SESSION.
             * Ces données resteront accessibles sur TOUTES les pages du site.
             */
            $_SESSION['user'] = [
                'id' => $user['numMemb'],
                'pseudo' => $user['pseudoMemb']
            ];

            // Redirection immédiate vers l'accueil
            header('Location: ../../../index.php');
            exit();

        } else {
            // Sécurité : on ne précise pas si c'est l'email ou le pass qui est faux
            $errors[] = 'Email ou mot de passe incorrect.';
        }
    } elseif (!empty($email) && !empty($password)) {
        // Les identifiants sont remplis mais le captcha a échoué
        $errors[] = 'Veuillez compléter le captcha.';
    } else {
        $errors[] = 'Veuillez remplir tous les champs.';
    }
}
?>

<!-- ============================================================
     SCRIPT GOOGLE RECAPTCHA V2
     ============================================================
     Ce script est OBLIGATOIRE pour que le captcha fonctionne
     Il crée une clé globale 'grecaptcha' accessible en JavaScript
-->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5 shadow p-4 rounded bg-white">
            <h1 class="text-center mb-4">Connexion</h1>

            <?php foreach($errors as $error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endforeach; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="nom@exemple.com" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <!-- ============================================================
                     RECAPTCHA GOOGLE V2 : Système de protection contre les robots
                     ============================================================
                     data-sitekey : Clé PUBLIQUE fournie par Google (visible côté client)
                     
                     À REMPLACER PAR VOTRE VRAIE CLÉ PUBLIC RECAPTCHA !
                -->
                <div class="mb-3 d-flex justify-content-center">
                    <!-- Cette div sera remplacée par un widget reCAPTCHA -->
                    <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>
                </div>

                <!-- MESSAGE SI JAVASCRIPT DÉSACTIVÉ : Le captcha ne fonctionne pas sans JS -->
                <noscript>
                    <div class="alert alert-warning" role="alert">
                        ⚠️ Le captcha reCAPTCHA nécessite JavaScript. Veuillez activer JavaScript dans votre navigateur.
                    </div>
                </noscript>

                <button type="submit" name="btn" class="btn btn-primary w-100">Se connecter</button>
                
                <div class="text-center mt-3">
                    <small>Pas encore de compte ? <a href="signup.php">Inscrivez-vous ici</a></small>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../../footer.php'; ?>