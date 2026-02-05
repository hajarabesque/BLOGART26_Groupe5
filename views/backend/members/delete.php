<?php
include '../../../header.php';

if (isset($_GET['numMemb'])) {
    $numMemb = $_GET['numMemb'];
    
    // Jointure pour avoir le nom du statut en clair
    $membre = sql_select(
        "MEMBRE INNER JOIN STATUT ON MEMBRE.numStat = STATUT.numStat", 
        "MEMBRE.*, STATUT.libStat", 
        "numMemb = $numMemb"
    )[0];
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>Suppression Membre</h1>
            <hr style="border: 2px solid black;">
        </div>

        <div class="col-md-8 offset-md-2 mt-4">
            <div class="alert alert-danger">
                <strong>Attention :</strong> La suppression d'un membre entraînera la suppression de TOUS ses commentaires et likes.
            </div>

            <form action="<?php echo ROOT_URL; ?>/api/members/delete.php" method="post">
                
                <!-- ID caché indispensable car les champs disabled ne sont pas envoyés -->
                <input type="hidden" name="numMemb" value="<?php echo $membre['numMemb']; ?>">

                <div class="form-group mb-3">
                    <label>Pseudo</label>
                    <input type="text" class="form-control" value="<?php echo $membre['pseudoMemb']; ?>" disabled>
                </div>

                <div class="form-group mb-3">
                    <label>Prénom</label>
                    <input type="text" class="form-control" value="<?php echo $membre['prenomMemb']; ?>" disabled>
                </div>

                <div class="form-group mb-3">
                    <label>Nom</label>
                    <input type="text" class="form-control" value="<?php echo $membre['nomMemb']; ?>" disabled>
                </div>

                <div class="form-group mb-3">
                    <label>eMail</label>
                    <input type="text" class="form-control" value="<?php echo $membre['eMailMemb']; ?>" disabled>
                </div>

                <div class="form-group mb-3">
                    <label>Statut</label>
                    <input type="text" class="form-control" value="<?php echo $membre['libStat']; ?>" disabled>
                </div>

                <div class="form-group mb-3">
                    <label>Accord RGPD</label><br>
                    <input type="radio" <?php echo ($membre['accordMemb'] == 1) ? 'checked' : ''; ?> disabled> Oui
                    <input type="radio" <?php echo ($membre['accordMemb'] == 0) ? 'checked' : ''; ?> disabled class="ms-3"> Non
                </div>

                <!-- Zone reCAPTCHA V3 (Visuel pour ton projet) -->
                <div class="card p-3 mb-4" style="max-width: 300px; background-color: #f9f9f9;">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" required>
                        <label class="form-check-label small">Je ne suis pas un robot</label>
                        <img src="https://www.gstatic.com/recaptcha/api2/logo_48.png" align="right" width="25">
                    </div>
                </div>

                <div class="form-group mb-5">
                    <a href="list.php" class="btn btn-outline-primary px-4">Liste</a>
                    <button type="submit" class="btn btn-danger px-4 ms-2">Confirmer Delete ?</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../../footer.php'; ?>