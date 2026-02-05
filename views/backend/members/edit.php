<?php
session_start();
include '../../../header.php';

$err = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
$membre = null;
$statuts = [];

if (isset($_GET['numMemb'])) {
    // 2. Sécurisation : on force la valeur en entier (int) pour bloquer l'injection SQL
    $numMemb = (int)$_GET['numMemb']; 
    
    // 3. On récupère les données
    $resultat = sql_select("MEMBRE", "*", "numMemb = $numMemb");
    
    // 4. On vérifie si on a trouvé un membre avant d'accéder à [0]
    if (!empty($resultat)) {
        $membre = $resultat[0];
    }
    
    $statuts = sql_select("STATUT", "*");
}
?>
<div class="container mt-5">
    <h1>Modification Membre</h1>
    <hr style="border: 2px solid black;">

    <div class="col-md-8 offset-md-2 mt-4">
        <form action="<?php echo ROOT_URL; ?>/api/members/update.php" method="post">
            
            <!-- ID caché (obligatoire pour l'update) -->
            <input type="hidden" name="numMemb" value="<?php echo $membre['numMemb']; ?>">

            <!-- Pseudo (NON modifiable) -->
            <div class="form-group mb-3">
                <label>Pseudo (Non modifiable)</label>
                <input type="text" class="form-control" value="<?php echo $membre['pseudoMemb']; ?>" disabled>
            </div>

            <!-- Prénom -->
            <div class="form-group mb-3">
                <label>Prénom</label>
                <input type="text" name="prenomMemb" class="form-control" value="<?php echo $membre['prenomMemb']; ?>" required>
            </div>

            <!-- Nom -->
            <div class="form-group mb-3">
                <label>Nom</label>
                <input type="text" name="nomMemb" class="form-control" value="<?php echo $membre['nomMemb']; ?>" required>
            </div>

            <!-- Double Email -->
            <div class="form-group mb-3">
                <label>eMail</label>
                <input type="email" name="eMailMemb" class="form-control" value="<?php echo $membre['eMailMemb']; ?>" required>
                <?php if(isset($err['email'])): ?><span class="text-danger small"><?php echo $err['email']; ?></span><?php endif; ?>
            </div>
            <div class="form-group mb-3">
                <label>Confirmez eMail</label>
                <input type="email" name="eMailMembConf" class="form-control" value="<?php echo $membre['eMailMemb']; ?>" required>
            </div>

            <!-- Double Password -->
            <div class="form-group mb-1">
                <label>Nouveau Password (laisser vide pour ne pas changer)</label>
                <input type="password" id="p1" name="passMemb" class="form-control">
                <input type="checkbox" onclick="toggle('p1')"> <small>Afficher</small><br>
                <?php if(isset($err['pass'])): ?><span class="text-danger small"><?php echo $err['pass']; ?></span><?php endif; ?>
            </div>
            <div class="form-group mb-1">
                <label>Confirmez nouveau password</label>
                <input type="password" id="p2" name="passMembConf" class="form-control">
                <input type="checkbox" onclick="toggle('p2')"> <small>Afficher</small>
            </div>

            <!-- Date Création (NON modifiable) -->
            <div class="form-group mb-3">
                <label>Date création</label>
                <input type="text" class="form-control" value="<?php echo $membre['dtCreaMemb']; ?>" disabled>
            </div>

            <!-- Accord RGPD (NON modifiable) -->
            <div class="form-group mb-3">
                <label>Accord RGPD (Non modifiable)</label><br>
                <input type="radio" <?php echo ($membre['accordMemb'] == 1) ? 'checked' : ''; ?> disabled> Oui
                <input type="radio" <?php echo ($membre['accordMemb'] == 0) ? 'checked' : ''; ?> disabled class="ms-3"> Non
            </div>

            <!-- Statut -->
            <div class="form-group mb-4">
                <label>Statut :</label>
                <select name="numStat" class="form-control">
                    <?php foreach($statuts as $statut): ?>
                        <option value="<?php echo $statut['numStat']; ?>" <?php echo ($membre['numStat'] == $statut['numStat']) ? 'selected' : ''; ?>>
                            <?php echo $statut['libStat']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group mb-5">
                <a href="list.php" class="btn btn-outline-primary px-4">List</a>
                <button type="submit" class="btn btn-outline-warning px-4 ms-2">Confirmer la modification</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggle(id) {
    var x = document.getElementById(id);
    x.type = (x.type === "password") ? "text" : "password";
}
</script>