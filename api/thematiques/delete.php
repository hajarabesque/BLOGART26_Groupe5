<?php
/**
 * ==========================================================
 * 1. PRÉPARATION ET RÉCUPÉRATION (BACKEND)
 * ==========================================================
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

/**
 * RÉCUPÉRATION DE L'ID :
 * On récupère l'identifiant de la thématique de manière flexible.
 * - Soit via POST (depuis le bouton de confirmation du formulaire).
 * - Soit via GET (si on arrive directement depuis un lien).
 */
$numThem = isset($_POST['numThem']) ? $_POST['numThem'] : $_GET['numThem'];

/**
 * ==========================================================
 * 2. GESTION DES ERREURS D'INTÉGRITÉ
 * ==========================================================
 * Si la suppression a échoué précédemment (tentative de supprimer
 * une thématique liée à des articles), on affiche une alerte.
 */
if(isset($_GET['error']) && $_GET['error'] == 'is_used'): ?>
    <div class="alert alert-danger">
        Impossible de supprimer : cette thématique est liée à des articles existants !
    </div>
<?php endif; 
?>

<?php 
/**
 * ==========================================================
 * 3. LOGIQUE DE SUPPRESSION (À COMPLÉTER SELON TON API)
 * ==========================================================
 * Normalement, ici tu devrais avoir un appel à :
 * sql_delete('THEMATIQUE', "numThem = $numThem");
 */

/**
 * ==========================================================
 * 4. REDIRECTION ET FIN
 * ==========================================================
 */
header('Location: ../../views/backend/thematiques/list.php');
exit();
?>