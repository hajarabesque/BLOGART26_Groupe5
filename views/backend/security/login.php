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

    if (!empty($email) && !empty($password)) {
        
        /**
         * REQUÊTE PRÉPARÉE :
         * On cherche le membre par son email. On utilise ':' pour éviter les injections SQL.
         */
        $query = $db->prepare('SELECT * FROM MEMBER WHERE emailMem = :email');
        $query->execute([':email' => $email]);
        $user = $query->fetch();

        /**
         * VÉRIFICATION DU MOT DE PASSE :
         * password_verify() compare le mot de passe tapé avec l'empreinte (hash) 
         * stockée en base de données.
         */
        if ($user && password_verify($password, $user['passMem'])) {
            
            /**
             * CONNEXION RÉUSSIE :
             * On remplit la variable superglobale $_SESSION.
             * Ces données resteront accessibles sur TOUTES les pages du site.
             */
            $_SESSION['user'] = [
                'id' => $user['numMem'],
                'pseudo' => $user['pseudoMem']
            ];

            // Redirection immédiate vers l'accueil
            header('Location: ../../../index.php');
            exit();

        } else {
            // Sécurité : on ne précise pas si c'est l'email ou le pass qui est faux
            $errors[] = 'Email ou mot de passe incorrect.';
        }
    } else {
        $errors[] = 'Veuillez remplir tous les champs.';
    }
}
?>

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

                <button type="submit" name="btn" class="btn btn-primary w-100">Se connecter</button>
                
                <div class="text-center mt-3">
                    <small>Pas encore de compte ? <a href="signup.php">Inscrivez-vous ici</a></small>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../../footer.php'; ?>