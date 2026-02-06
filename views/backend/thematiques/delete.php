<?php
include '../../../header.php';

// Vérification de l'ID dans l'URL
if(isset($_GET['numThem'])){
    $numThem = $_GET['numThem'];
<<<<<<< HEAD
    // On récupère le libellé pour l'afficher (pour que l'utilisateur sache ce qu'il supprime)
=======
>>>>>>> e8486efd7714ef339d3770a9c34db183bc2cacbc
    $libThem = sql_select("THEMATIQUE", "libThem", "numThem = $numThem")[0]['libThem'];
}
?>

<<<<<<< HEAD
=======
<!-- Bootstrap form to create a new statut -->
>>>>>>> e8486efd7714ef339d3770a9c34db183bc2cacbc
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Suppression Thématique</h1>
<<<<<<< HEAD
            <!-- Message d'avertissement -->
            <div class="alert alert-warning">
                Attention : La suppression d'une thématique peut être impossible si elle est liée à des articles.
            </div>
        </div>
        <div class="col-md-12">
            <!-- Formulaire pointant vers l'API de suppression -->
            <form action="<?php echo ROOT_URL . '/api/thematiques/delete.php' ?>" method="post">
                <div class="form-group">
                    <label for="libThem">Nom de la thématique</label>
                    
                    <!-- ID caché pour l'envoyer au serveur -->
                    <input id="numThem" name="numThem" class="form-control" style="display: none" type="text" value="<?php echo($numThem); ?>" readonly="readonly" />
                    
                    <!-- Libellé affiché mais grisé -->
                    <input id="libThem" name="libThem" class="form-control" type="text" value="<?php echo($libThem); ?>" readonly="readonly" disabled />
                </div>
                
                <br />
                <div class="form-group mt-2">
                    <a href="list.php" class="btn btn-primary">Retour à la liste</a>
                    <button type="submit" class="btn btn-danger">Confirmer la suppression ?</button>
=======
        </div>
        <div class="col-md-12">
            <!-- Form to create a new statut -->
            <form action="<?php echo ROOT_URL . '/api/thematiques/delete.php' ?>" method="post">
                <div class="form-group">
                    <label for="libThem">Nom du thematiques</label>
                    <input id="numThem" name="numThem" class="form-control" style="display: none" type="text" value="<?php echo($numThem); ?>" readonly="readonly" />
                    <input id="libThem" name="libThem" class="form-control" type="text" value="<?php echo($libThem); ?>" readonly="readonly" disabled />
                </div>
                <br />
                <div class="form-group mt-2">
                    <a href="list.php" class="btn btn-primary">List</a>
                    <button type="submit" class="btn btn-danger">Delete ?</button>
>>>>>>> e8486efd7714ef339d3770a9c34db183bc2cacbc
                </div>
            </form>
        </div>
    </div>
</div>