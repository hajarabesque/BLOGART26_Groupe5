<?php 
include '../../../header.php';

if(isset($_GET['numThem'])){
    $numThem = intval($_GET['numThem']);
    // On récupère la thématique actuelle
    $data = sql_select("THEMATIQUE", "libThem", "numThem = $numThem");
    $libThem = $data[0]['libThem'];
}
?>

<div class="container py-5">
    <h1>Modification thématique</h1>
    <form action="/api/thematiques/update.php" method="post" class="mt-4">
        <input type="hidden" name="numThem" value="<?php echo $numThem; ?>" />
        
        <div class="form-group">
            <label for="libThem">Nom actuel</label>
            <input id="libThem" name="libThem" class="form-control" type="text" value="<?php echo $libThem; ?>" required />
        </div>

        <div class="form-group mt-3">
            <a href="list.php" class="btn btn-secondary">Retour</a>
            <button type="submit" class="btn btn-warning">Enregistrer les modifications</button>
        </div>
    </form>
</div>

<?php include '../../../footer.php'; ?>