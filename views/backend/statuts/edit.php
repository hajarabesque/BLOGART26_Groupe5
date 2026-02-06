<?php
include '../../../header.php';
?>
<?php

// On vérifie si numStat est présent dans l'URL
if (isset($_GET['numStat'])) {
    $numStat = intval($_GET['numStat']);
    
    // On récupère les infos du statut
    $stat = sql_select("STATUT", "*", "numStat = $numStat");

    if (!empty($stat)) {
        $stat = $stat[0];
        $libStat = $stat['libStat'];
    } else {
        // Si l'ID n'existe pas en base
        echo "Statut introuvable.";
        exit;
    }
} else {
    // Si aucun ID n'est passé dans l'URL
    echo "Identifiant manquant.";
    exit;


}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Modification Statut</h1>
        </div>
        <div class="col-md-12">

            <!-- Formulaire vers l'API d'update -->
            <form action="<?php echo ROOT_URL . '/api/statuts/update.php' ?>" method="post">
                
                <!-- On cache l'ID pour l'envoyer à l'API mais on ne permet pas de le modifier -->
                <input type="hidden" name="numStat" value="<?php echo $numStat; ?>" />

                <div class="form-group">
                    <label for="libStat">Nom du statut</label>
                    <!-- Ici, value="..." permet d'afficher le mot actuel dans la case -->
                    <input 
                        id="libStat" 
                        name="libStat" 
                        class="form-control" 
                        type="text" 
                        value="<?php echo htmlspecialchars($libStat); ?>" 
                        autofocus="autofocus" 
                        required 
                    />
                </div>
                
                <br />
                <div class="form-group mt-2">
                    <a href="list.php" class="btn btn-primary">Liste</a>
                    <button type="submit" class="btn btn-warning">Confirmer la modification ?</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include '../../../footer.php';
?>
