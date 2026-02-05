<?php
include '../../../header.php'; // Contient la connexion $db et session_start()

$errors = [];

if (isset($_POST['btn'])) {
    // 1. On récupère les identifiants saisis
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        
        // 2. On cherche l'utilisateur dans la base par son email
        // Vérifie si ta table est 'MEMBER' ou 'user' et ta colonne 'emailMem' ou 'email'
        $query = $db->prepare('SELECT * FROM MEMBER WHERE emailMem = :email');
        $query->execute([':email' => $email]);
        $user = $query->fetch();

        // 3. On vérifie si l'utilisateur existe ET si le mot de passe est correct
        if ($user && password_verify($password, $user['passMem'])) {
            
            // CONNEXION RÉUSSIE
            // On stocke les infos en session (pour que le header affiche "Déconnexion" par exemple)
            $_SESSION['user'] = [
                'id' => $user['numMem'], // ou idMem
                'pseudo' => $user['pseudoMem']
            ];

            // Redirection vers l'accueil
            header('Location: ../../../index.php');
            exit();

        } else {
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
                    <input type="email" name="email" class="form-control" required>
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