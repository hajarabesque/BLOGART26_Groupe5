<?php
include '../../../header.php';

// Get comment ID from URL
$numCom = isset($_GET['numCom']) ? $_GET['numCom'] : null;

if (!$numCom) {
    header('Location: list.php');
    exit();
}

// Get comment details with article and member info
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
            <h1 class="display-4">Modération de Commentaire</h1>
            <hr style="border: 2px solid black;">

            <div class="card mb-3">
                <div class="card-header">Article</div>
                <div class="card-body">
                    <?php echo $comment['libTitrArt']; ?>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">Auteur</div>
                <div class="card-body">
                    <?php echo $comment['pseudoMemb']; ?>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">Date</div>
                <div class="card-body">
                    <?php echo $comment['dtCreaCom']; ?>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">Statut</div>
                <div class="card-body">
                    <?php if ($comment['attModOK']) { ?>
                        <span class="badge bg-success">Approuvé</span>
                    <?php } elseif ($comment['delLogiq']) { ?>
                        <span class="badge bg-danger">Supprimé</span>
                    <?php } else { ?>
                        <span class="badge bg-warning">En attente</span>
                    <?php } ?>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">Commentaire original</div>
                <div class="card-body">
                    <blockquote class="blockquote bg-light p-3 rounded">
                        <?php echo nl2br(htmlspecialchars($comment['libCom'])); ?>
                    </blockquote>
                </div>
            </div>

            <div class="mt-4">
                <form action="<?php echo ROOT_URL; ?>/api/comments/update.php" method="post">
                    <input type="hidden" name="numCom" value="<?php echo $comment['numCom']; ?>">

                    <div class="form-group">
                        <button type="submit" name="action" value="approve" class="btn btn-secondary btn-lg">Accepter le commentaire</button>
                        <button type="submit" name="action" value="reject" class="btn btn-secondary btn-lg">Rejeter le commentaire</button>
                        <a href="list.php" class="btn btn-secondary btn-lg">Retour à la liste</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
 
<?php
include '../../../footer.php';
?>

