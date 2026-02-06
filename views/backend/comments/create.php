<?php
require_once '../../../header.php';
require_once '../../../config.php';

// On récupère les articles pour le select (table en minuscules comme vu sur ton phpMyAdmin)
$articles = sql_select('article', 'numArt, libTitrArt');
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Nouveau commentaire</h1>
        </div>
        <div class="col-md-12">
            <form action="<?php echo ROOT_URL . '/api/comments/create.php' ?>" method="post" onsubmit="return validateForm()">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="pseudoMemb">Pseudo</label>
                        <input id="pseudoMemb" name="pseudoMemb" class="form-control" type="text" required />
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="nomMemb">Nom</label>
                        <input id="nomMemb" name="nomMemb" class="form-control" type="text" required />
                    </div>
                </div>

                <hr>

                <div class="form-group mb-4">
                    <label for="numArt">Article à commenter :</label>
                    <select name="numArt" id="numArt" class="form-control" required>
                        <option value="">--- Choisissez un article ---</option>
                        <?php foreach($articles as $article): ?>
                             <option value="<?php echo $article['numArt']; ?>"><?php echo $article['libTitrArt']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="libCom">Votre commentaire</label>
                    <textarea id="libCom" name="libCom" class="form-control" rows="5" required></textarea>
                </div>

          <!-- Boutons -->
            <div class="form-group mb-5">
                <a href="list.php" class="btn btn-outline-primary px-4">List</a>
                <button type="submit" class="btn btn-outline-success px-4 ms-2">Create</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
function validateForm() {
    const com = document.getElementById('libCom').value.trim();
    if (com === "") {
        alert("Veuillez écrire un commentaire avant de valider.");
        return false;
    }
    return true;
}
</script><?php
require_once '../../../header.php';
require_once '../../../config.php';

// On récupère les articles pour le select (table en minuscules comme vu sur ton phpMyAdmin)
$articles = sql_select('article', 'numArt, libTitrArt');
?>
