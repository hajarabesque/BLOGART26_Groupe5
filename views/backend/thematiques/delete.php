<?php 
include '../../../header.php';

if(isset($_GET['numThem'])){
    $numThem = intval($_GET['numThem']);
    $data = sql_select("THEMATIQUE", "libThem", "numThem = $numThem");
    $libThem = $data[0]['libThem'];
}
?>

<div class="container py-5">
    <div class="alert alert-danger">
        <h3>Attention !</h3>
        <p>Êtes-vous sûr de vouloir supprimer la thématique : <strong><?php echo $libThem; ?></strong> ?</p>
    </div>

    <form action="/api/thematiques/delete.php" method="post">
        <input type="hidden" name="numThem" value="<?php echo $numThem; ?>" />
        
        <div class="form-group mt-3">
            <a href="list.php" class="btn btn-secondary">Non, annuler</a>
            <button type="submit" class="btn btn-danger">Oui, supprimer définitivement</button>
        </div>
    </form>
</div>

<?php include '../../../footer.php'; ?>