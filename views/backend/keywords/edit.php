<?php
/**
 * ==========================================================
 * 1. CHARGEMENT DU MOT-CLÉ (BACKEND)
 * ==========================================================
 */
include '../../../header.php';

// On vérifie si l'ID du mot-clé est bien passé dans l'URL
if (isset($_GET['numMotCle'])) {
    /**
     * SÉCURISATION :
     * intval() transforme la saisie en nombre entier.
     * Si quelqu'un injecte du texte malveillant, il sera converti en 0.
     */
    $numStat = intval($_GET['numMotCle']);
    
    // On récupère toutes les colonnes du mot-clé correspondant
    $stat = sql_select('MOTCLE', '*', "numMotCle = $numStat");
    
    if (!empty($stat)) {
        $stat = $stat[0];
        $numStatVal = $stat['numMotCle'];
        $libStat = $stat['libMotCle'];
    } else {
        // En cas d'ID inexistant en base
        $numStatVal = '';
        $libStat = '';
    }
} else {
    // En cas d'absence totale d'ID dans l'URL
    $numStat = '';
    $numStatVal = '';
    $libStat = '';
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-4">Modification Mot Clé</h1>
            <hr />
        </div>
        <div class="col-md-8">
             <?php
            /**
             * FORMULAIRE DE MISE À JOUR :
             * - action : Envoie vers l'API de traitement (update.php).
             * - method="post" : Standard pour envoyer des modifications de données.
             */
             ?>
            <form action="<?php echo ROOT_URL . '/api/keywords/update.php' ?>" method="post">
                 <?php
                /**
                 * CHAMP CACHÉ (HIDDEN) :
                 * Il contient l'ID (numMotCle). C'est le "marqueur" qui permettra 
                 * à la requête SQL (UPDATE ... WHERE numMotCle = ?) de savoir 
                 * quelle ligne exacte modifier.
                 */
                 ?>
                <input type="hidden" name="numMotCle" value="<?php echo htmlspecialchars($numStat); ?>" />

                <div class="form-group mb-3">
                    <label for="libMotCle">Libellé du mot-clé</label>
                     <?php
                    /**
                     * htmlspecialchars() : Sécurité contre les failles XSS.
                     * Empêche l'exécution de scripts si le libellé contient des balises <script>.
                     */
                     ?>
                    <input id="libMotCle" name="libMotCle" class="form-control" type="text" value="<?php echo htmlspecialchars($libStat); ?>" />
                </div>

                <div class="form-group mt-3">
                    <a href="list.php" class="btn btn-outline-primary">List</a>
                    
                    <button type="submit" class="btn btn-warning">Confirmer Edit ?</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
// Inclusion du pied de page (Bootstrap JS, scripts, etc.)
include '../../../footer.php'; 
?>