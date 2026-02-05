<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

// On récupère l'ID soit par POST (formulaire) soit par GET (lien)
$numThem = isset($_POST['numThem']) ? $_POST['numThem'] : $_GET['numThem'];

if(isset($_GET['error']) && $_GET['error'] == 'is_used'): ?>
    <div class="alert alert-danger">
        Impossible de supprimer : cette thématique est liée à des articles existants !
    </div>
<?php endif; 
?>

<?php 


header('Location: ../../views/backend/thematiques/list.php');
exit();
?>


