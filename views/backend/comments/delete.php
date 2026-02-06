<?php
include '../../../header.php';
$articles = sql_select(
    "ARTICLE a 
     LEFT JOIN THEMATIQUE t ON a.numThem = t.numThem 
     LEFT JOIN MOTCLEARTICLE ma ON a.numArt = ma.numArt 
     LEFT JOIN MOTCLE m ON ma.numMotCle = m.numMotCle",
    "a.*, t.libThem, GROUP_CONCAT(m.libMotCle SEPARATOR ', ') as keywords"
);
?>
<?php
// Get comment ID from URL
$numCom = isset($_GET['numCom']) ? $_GET['numCom'] : null;

if (!$numCom) {
    header('Location: list.php');
    exit();
}

// Get comment details
$comment = sql_select("COMMENT c INNER JOIN ARTICLE a ON c.numArt = a.numArt INNER JOIN MEMBRE m ON c.numMemb = m.numMemb", "c.*, a.libTitrArt, m.pseudoMemb", "c.numCom = $numCom");

if (empty($comment)) {
    header('Location: list.php');
    exit();
}

$comment = $comment[0];
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="display-4">Supprimer un Commentaire</h1>
            <hr style="border: 2px solid black;">

            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading"> Attention!</h4>
                <p>Vous êtes sur le point de supprimer ce commentaire. Cette action déplacera le commentaire vers la section "Commentaires Supprimés".</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Détails du commentaire</h5>
                </div>
                <div class="card-body">
                    <p><strong>ID:</strong> <?php echo $comment['numCom']; ?></p>
                    <p><strong>Article:</strong> <?php echo $comment['libTitrArt']; ?></p>
                    <p><strong>Auteur:</strong> <?php echo $comment['pseudoMemb']; ?></p>
                    <p><strong>Date:</strong> <?php echo $comment['dtCreaCom']; ?></p>
                    <p><strong>Commentaire:</strong></p>
                    <blockquote class="blockquote">
                        <?php echo nl2br(htmlspecialchars($comment['libCom'])); ?>
                    </blockquote>
                </div>
            </div>

            <div class="mt-4">
                <form action="<?php echo ROOT_URL; ?>/api/comments/delete.php" method="post">
                    <input type="hidden" name="numCom" value="<?php echo $comment['numCom']; ?>">

                    <a href="list.php" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-danger ms-2">
                        <i class="fas fa-trash"></i> Confirmer la suppression
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include '../../../footer.php';
?>

