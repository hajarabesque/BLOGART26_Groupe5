

<?php
include '../../../header.php';

if(isset($_GET['numThem'])){
    $numThem = $_GET['numThem'];
    $libThem = sql_select("THEMATIQUE", "libThem", "numThem = $numThem")[0]['libThem'];
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Modification thématique</h1>
        </div>
        <div class="col-md-12">
            <!-- L'action pointe vers l'API d'update -->
            <form action="<?php echo ROOT_URL . '/api/thematiques/update.php' ?>" method="post">
                <div class="form-group">
                    <label for="libThem">Nom du thematiques</label>
                    
                
                    <input id="numThem" name="numThem" type="hidden" value="<?php echo($numThem); ?>" />
                    
                    <input id="libThem" name="libThem" class="form-control" type="text" value="<?php echo($libThem); ?>" autofocus="autofocus" />
                </div>
                <br />
                <div class="form-group mt-2">
                    <a href="list.php" class="btn btn-primary">Liste</a>
                    <button type="submit" class="btn btn-warning">Confirmer la modification</button>
                </div>
            </form>
        </div>
    </div>

</div>
<?php
include '../../../footer.php';
?>