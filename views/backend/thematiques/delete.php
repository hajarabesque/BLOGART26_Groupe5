<?php 
/**
 * ==========================================================
 * 1. RÉCUPÉRATION ET VÉRIFICATION (BACKEND)
 * ==========================================================
 */
include '../../../header.php';

/**
 * RÉCUPÉRATION DE L'ID :
 * On vérifie si 'numThem' est présent dans l'URL (ex: delete.php?numThem=4)
 */
if(isset($_GET['numThem'])){
    // Sécurité : on force la conversion en entier pour éviter les injections SQL
    $numThem = intval($_GET['numThem']);
    
    // On récupère le nom de la thématique pour l'afficher dans le message de confirmation
    $data = sql_select("THEMATIQUE", "libThem", "numThem = $numThem");
    
    // Si la thématique existe, on stocke son nom
    if (!empty($data)) {
        $libThem = $data[0]['libThem'];
    } else {
        // Redirection si l'ID ne correspond à rien
        header('Location: list.php');
        exit();
    }
}
?>

<div class="container py-5">
    <div class="alert alert-danger">
        <h3>Attention !</h3>
        <p>Êtes-vous sûr de vouloir supprimer la thématique : <strong><?php echo htmlspecialchars($libThem); ?></strong> ?</p>
    </div>

    /**
     * FORMULAIRE DE CONFIRMATION :
     * On utilise 'post' pour l'action de suppression réelle afin de respecter 
     * les standards de sécurité (ne jamais supprimer via un lien 'get' direct).
     */
    <form action="/api/thematiques/delete.php" method="post">
        <input type="hidden" name="numThem" value="<?php echo $numThem; ?>" />
        
        <div class="form-group mt-3">
            <a href="list.php" class="btn btn-secondary">Non, annuler</a>
            
            <button type="submit" class="btn btn-danger">Oui, supprimer définitivement</button>
        </div>
    </form>
</div>

<?php 
/**
 * ==========================================================
 * 2. FERMETURE DU DOCUMENT
 * ==========================================================
 */
include '../../../footer.php'; 
?>