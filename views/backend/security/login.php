<?php
/**
 * ==========================================================
 * 1. LOGIQUE DE CONNEXION (TOUT DANS LE MÊME FICHIER)
 * ==========================================================
 */
include '../../../header.php'; // Contient session_start() et la connexion $db

$errors = [];
$email_val = ""; // Variable pour garder l'email affiché en cas d'erreur

if (isset($_POST['btn'])) {
    
    // On récupère les saisies
    $email = htmlspecialchars($_POST['email']);
    $email_val = $email; // On le stocke pour le réafficher dans le formulaire
    $password = $_POST['password'];

    // ============================================================
    // VALIDATION DU RECAPTCHA
    // ============================================================
    $recaptchaToken = $_POST['g-recaptcha-response'] ?? '';
    $recaptchaSecretKey = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';
    
    if (empty($recaptchaToken)) {
        $errors[] = "Veuillez cocher le captcha.";
    } else {
        $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $recaptchaUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query(['secret' => $recaptchaSecretKey, 'response' => $recaptchaToken]),
            CURLOPT_RETURNTRANSFER => true
        ]);
        $recaptchaResult = json_decode(curl_exec($curl), true);
        curl_close($curl);
        
        if (!$recaptchaResult['success']) {
            $errors[] = "Échec du captcha. Veuillez réessayer.";
        }
    }

    // ============================================================
    // VÉRIFICATION IDENTIFIANTS (SI PAS D'ERREUR CAPTCHA)
    // ============================================================
    if (empty($errors)) {
        if (!empty($email) && !empty($password)) {
            
            $query = $db->prepare('SELECT * FROM MEMBRE WHERE eMailMemb = :email');
            $query->execute([':email' => $email]);
            $user = $query->fetch();

            if ($user && password_verify($password, $user['passMemb'])) {
                // CONNEXION RÉUSSIE
                $_SESSION['user'] = [
                    'id' => $user['numMemb'],
                    'pseudo' => $user['pseudoMemb']
                ];
                header('Location: ../../../index.php');
                exit();
            } else {
                $errors[] = 'Email ou mot de passe incorrect.';
            }
        } else {
            $errors[] = 'Veuillez remplir tous les champs.';
        }
    }
}
?>

<!-- SCRIPT RECAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5 shadow p-4 rounded bg-white">
            <h1 class="text-center mb-4">Connexion</h1>

            <!-- Affichage des erreurs -->
            <?php foreach($errors as $error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endforeach; ?>

            <!-- Le formulaire renvoie sur lui-même (action vide) -->
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <!-- On utilise value=... pour que l'email reste écrit en cas d'erreur -->
                    <input type="email" name="email" class="form-control" 
                           value="<?php echo htmlspecialchars($email_val); ?>" 
                           placeholder="nom@exemple.com" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3 d-flex justify-content-center">
                    <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>
                </div>

                <button type="submit" name="btn" class="btn btn-primary w-100">Se connecter</button>
                
                <div class="text-center mt-3">
                    <small>Pas encore de compte ? <a href="signup.php">Inscrivez-vous ici</a></small>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../../footer.php'; ?>