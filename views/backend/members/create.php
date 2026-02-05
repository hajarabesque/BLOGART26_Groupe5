<?php
session_start();
include '../../../header.php';

// On récupère les erreurs s'il y en a
$err = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']); // On vide pour la prochaine fois

$statuts = sql_select("STATUT", "*");
?>

<div class="container mt-5">
    <h1>Création nouveau Membre</h1>
    <hr style="border: 2px solid black;">

    <div class="col-md-8 offset-md-2 mt-4">
        <form action="<?php echo ROOT_URL; ?>/api/members/create.php" method="post">

            <!-- Pseudo -->
            <div class="form-group mb-3">
                <label>Pseudo (Entre 6 et 70 car.)</label>
                <input type="text" name="pseudoMemb" class="form-control" value="<?php echo $old['pseudoMemb'] ?? ''; ?>">
                <?php if(isset($err['pseudoMemb'])): ?><span class="text-danger small"><?php echo $err['pseudoMemb']; ?></span><?php endif; ?>
            </div>

            <!-- Prénom -->
            <div class="form-group mb-3">
                <label>Prénom</label>
                <input type="text" name="prenomMemb" class="form-control" value="<?php echo $old['prenomMemb'] ?? ''; ?>">
            </div>

            <!-- Nom -->
            <div class="form-group mb-3">
                <label>Nom</label>
                <input type="text" name="nomMemb" class="form-control" value="<?php echo $old['nomMemb'] ?? ''; ?>">
            </div>

            <!-- Password -->
            <div class="form-group mb-1">
                <label>Password</label>
                <input type="password" id="p1" name="passMemb" class="form-control">
                <small class="text-muted">(Entre 8 et 15 car., au - une majuscule, une minuscule, un chiffre, car. spéciaux acceptés)</small><br>
                <?php if(isset($err['passMemb'])): ?><span class="text-danger small"><?php echo $err['passMemb']; ?></span><?php endif; ?>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" onclick="toggle('p1')"> <label class="form-check-label">Afficher Mot de passe</label>
            </div>

            <!-- Confirmez password -->
            <div class="form-group mb-1">
                <label>Confirmez password</label>
                <input type="password" id="p2" name="passMembConf" class="form-control">
                <?php if(isset($err['passMembConf'])): ?><span class="text-danger small"><?php echo $err['passMembConf']; ?></span><?php endif; ?>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" onclick="toggle('p2')"> <label class="form-check-label">Afficher Mot de passe</label>
            </div>

            <!-- Email -->
            <div class="form-group mb-3">
                <label>eMail</label>
                <input type="email" name="eMailMemb" class="form-control" value="<?php echo $old['eMailMemb'] ?? ''; ?>">
                <?php if(isset($err['eMailMemb'])): ?><span class="text-danger small"><?php echo $err['eMailMemb']; ?></span><?php endif; ?>
            </div>

            <!-- Confirmez Email -->
            <div class="form-group mb-3">
                <label>Confirmez eMail</label>
                <input type="email" name="eMailMembConf" class="form-control" value="<?php echo $old['eMailMembConf'] ?? ''; ?>">
                <?php if(isset($err['eMailMembConf'])): ?><span class="text-danger small"><?php echo $err['eMailMembConf']; ?></span><?php endif; ?>
            </div>

            <!-- RGPD -->
            <div class="form-group mb-3">
                <label>J'accepte que mes données soient conservées :</label><br>
                <input type="radio" name="accordMemb" value="1"> Oui 
                <input type="radio" name="accordMemb" value="0" checked class="ms-3"> Non<br>
                <?php if(isset($err['accordMemb'])): ?><span class="text-danger small"><?php echo $err['accordMemb']; ?></span><?php endif; ?>
            </div>

            <!-- Statut -->
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

            <!-- Captcha (Visuel) -->
            <div class="card p-3 mb-4" style="max-width: 300px; background-color: #f9f9f9;">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" required>
                    <label class="form-check-label">Je ne suis pas un robot</label>
                    <img src="https://www.gstatic.com/recaptcha/api2/logo_48.png" align="right" width="25">
                </div>
            </div>

            <!-- Boutons -->
            <div class="form-group mb-5">
                <a href="list.php" class="btn btn-outline-primary px-4">List</a>
                <button type="submit" class="btn btn-outline-success px-4 ms-2">Create</button>
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

<?php include '../../../footer.php'; ?>