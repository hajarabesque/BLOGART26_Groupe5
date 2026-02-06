<?php
/**
 * ==========================================================
 * 1. RÉCUPÉRATION DU MOT-CLÉ (BACKEND)
 * ==========================================================
 */
include '../../../header.php';

// On vérifie si l'identifiant du mot-clé est bien présent dans l'URL
if(isset($_GET['numMotCle'])){
    // Sécurisation : on force le type en entier
    $numMotCle = intval($_GET['numMotCle']);
    
    // On récupère le libellé pour l'afficher à l'utilisateur
    $data = sql_select("MOTCLE", "libMotCle", "numMotCle = $numMotCle");
    
    // Si le mot-clé n'existe pas, on pourrait rediriger ici
    $libMotCle = $data[0]['libMotCle'];
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Suppression Mot clé</h1>
        </div>
        <div class="col-md-12">
            <form action="<?php echo ROOT_URL . '/api/keywords/delete.php' ?>" method="post">
                <div class="form-group">
                    <label for="libMotCle">Nom du mot clé</label>
                     <?php
                    /**
                     * CHAMP CACHÉ : On utilise 'display: none' ou 'type="hidden"' 
                     * pour transporter l'ID sans qu'il soit modifiable par l'utilisateur.
                     */
                    ?>
                    <input id="numMotCle" name="numMotCle" style="display: none" type="text" value="<?php echo($numMotCle); ?>" readonly />
                     <?php
                    /**
                     * AFFICHAGE SEUL : On utilise 'disabled' pour que l'utilisateur 
                     * puisse lire le mot sans pouvoir le modifier.
                     */
                     ?>
                    <input id="libMotCle" name="libMotCle" class="form-control" type="text" value="<?php echo($libMotCle); ?>" readonly disabled />
                </div>
                
                <br />
                
                <div class="form-group mt-2">
                    <a href="list.php" class="btn btn-primary">List</a>
                    
                    <button type="submit" class="btn btn-danger">Confirmer delete ?</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../../footer.php'; ?>