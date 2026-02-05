<?php 
/**
 * ==========================================================
 * 1. LOGIQUE DE RÉCUPÉRATION (BACKEND)
 * ==========================================================
 */
include '../../../header.php';

/**
 * VÉRIFICATION DU PARAMÈTRE :
 * On s'assure qu'un ID de thématique a bien été passé dans l'URL.
 */
if(isset($_GET['numThem'])){
    // Sécurité : conversion forcée en entier (casting)
    $numThem = intval($_GET['numThem']);
    
    // On récupère le nom de la thématique actuelle en base de données
    $data = sql_select("THEMATIQUE", "libThem", "numThem = $numThem");
    
    // Si la thématique n'existe pas, on redirige vers la liste
    if (empty($data)) {
        header('Location: list.php');
        exit();
    }
    
    $libThem = $data[0]['libThem'];
} else {
    // Si aucun ID n'est fourni, on repart sur la liste
    header('Location: list.php');
    exit();
}
?>

<div class="container py-5">
    <h1>Modification thématique</h1>

    /**
     * FORMULAIRE DE MISE À JOUR :
     * - action : Envoie vers l'API d'update.
     * - method="post" : Standard pour la modification de données.
     */
    <form action="/api/thematiques/update.php" method="post" class="mt-4">
        
        <input type="hidden" name="numThem" value="<?php echo $numThem; ?>" />
        
        <div class="form-group">
            <label for="libThem">Nouveau nom de la thématique</label>
            <input id="libThem" name="libThem" class="form-control" type="text" value="<?php echo htmlspecialchars($libThem); ?>" required />
        </div>

        <div class="form-group mt-3">
            <a href="list.php" class="btn btn-secondary">Retour</a>
            
            <button type="submit" class="btn btn-warning">Enregistrer les modifications</button>
        </div>
    </form>
</div>



<?php 
/**
 * ==========================================================
 * 2. PIED DE PAGE
 * ==========================================================
 */
include '../../../footer.php'; 
?>