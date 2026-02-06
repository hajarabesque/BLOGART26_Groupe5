<?php
/**
 * ==========================================================
 * 1. PRÉPARATION ET GESTION DES SESSIONS (BACKEND)
 * ==========================================================
 */
session_start();
include '../../../header.php';

/**
 * RÉCUPÉRATION DES ERREURS :
 * Si le fichier api/members/create.php détecte des erreurs, il redirige ici.
 * On récupère les messages et les anciennes saisies ($old) pour ne pas tout retaper.
 */
$err = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']); // Nettoyage de la session après lecture

// On récupère la liste des statuts pour remplir le menu déroulant
$statuts = sql_select("STATUT", "*");
?>

<div class="container mt-5">
    <h1>Création nouveau Membre</h1>
    <hr style="border: 2px solid black;">

    <div class="col-md-8 offset-md-2 mt-4">
        <form action="<?php echo ROOT_URL; ?>/api/members/create.php" method="post">

            <div class="form-group mb-3">
                <label>Pseudo (Entre 6 et 70 car.)</label>
                <input type="text" name="pseudoMemb" class="form-control" value="<?php echo $old['pseudoMemb'] ?? ''; ?>">
                <?php if(isset($err['pseudoMemb'])): ?>
                    <span class="text-danger small"><?php echo $err['pseudoMemb']; ?></span>
                <?php endif; ?>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Prénom</label>
                    <input type="text" name="prenomMemb" class="form-control" value="<?php echo $old['prenomMemb'] ?? ''; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Nom</label>
                    <input type="text" name="nomMemb" class="form-control" value="<?php echo $old['nomMemb'] ?? ''; ?>">
                </div>
            </div>

            <div class="form-group mb-1">
                <label>Password</label>
                <input type="password" id="p1" name="passMemb" class="form-control">
                <small class="text-muted">(Majuscule, minuscule, chiffre requis)</small><br>
                <?php if(isset($err['passMemb'])): ?><span class="text-danger small"><?php echo $err['passMemb']; ?></span><?php endif; ?>
            </div>
            
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" onclick="toggle('p1')"> 
                <label class="form-check-label">Afficher Mot de passe</label>
            </div>

            <div class="form-group mb-3">
                <label>eMail</label>
                <input type="email" name="eMailMemb" class="form-control" value="<?php echo $old['eMailMemb'] ?? ''; ?>">
                <?php if(isset($err['eMailMemb'])): ?><span class="text-danger small"><?php echo $err['eMailMemb']; ?></span><?php endif; ?>
            </div>

            <div class="form-group mb-3">
                <label>J'accepte que mes données soient conservées :</label><br>
                <input type="radio" name="accordMemb" value="1"> Oui 
                <input type="radio" name="accordMemb" value="0" checked class="ms-3"> Non
            </div>

            <div class="form-group mb-4">
                <label>Statut :</label>
                <select name="numStat" class="form-control">
                    <option value="">--- Choisissez un statut ---</option>
                    <?php foreach($statuts as $statut): ?>
                        <option value="<?php echo $statut['numStat']; ?>" <?php echo (isset($old['numStat']) && $old['numStat'] == $statut['numStat']) ? 'selected' : ''; ?>>
                            <?php echo $statut['libStat']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group mb-5">
                <a href="list.php" class="btn btn-outline-primary px-4">List</a>
                <button type="submit" class="btn btn-outline-success px-4 ms-2">Create Member</button>
            </div>
        </form>
    </div>
</div>

<?php/**
 * SCRIPT JS : Basculer entre le type 'password' et 'text'
 */
?>

<script>
function toggle(id) {
    var x = document.getElementById(id);
    x.type = (x.type === "password") ? "text" : "password";
}
</script>

<?php include '../../../footer.php'; ?>