<?php
include '../../../header.php'; 

$allComments = sql_select("COMMENT c INNER JOIN ARTICLE a ON c.numArt = a.numArt INNER JOIN MEMBRE m ON c.numMemb = m.numMemb", "c.*, a.libTitrArt, m.pseudoMemb");

$approvedComments = array_filter($allComments, function($comment) {
    return $comment['attModOK'] == 1 && $comment['delLogiq'] == 0;
});

$pendingComments = array_filter($allComments, function($comment) {
    return $comment['attModOK'] == 0 && $comment['delLogiq'] == 0;
});

$deletedComments = array_filter($allComments, function($comment) {
    return $comment['delLogiq'] == 1;
});
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="display-4">Commentaires</h1>
            <hr style="border: 2px solid black;"> 


            <h3 class="mt-5">Commentaires Approuvés (<?php echo count($approvedComments); ?>)</h3>
            <table class="table table-hover mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Article</th>
                        <th>Auteur</th>
                        <th>Commentaire</th>
                        <th>Date création</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($approvedComments as $comment) { ?>
                        <tr>
                            <td><?php echo $comment['numCom']; ?></td>
                            <td><?php echo $comment['libTitrArt']; ?></td>
                            <td><?php echo $comment['pseudoMemb']; ?></td>
                            <td><?php echo substr($comment['libCom'], 0, 100) . (strlen($comment['libCom']) > 100 ? '...' : ''); ?></td>
                            <td><?php echo $comment['dtCreaCom']; ?></td>
                            <td class="text-center" style="width: 150px;">
                                <a href="edit.php?numCom=<?php echo $comment['numCom']; ?>"
                                   class="btn btn-outline-warning btn-sm mb-1 d-block">Modérer</a>
                                <a href="delete.php?numCom=<?php echo $comment['numCom']; ?>"
                                   class="btn btn-outline-danger btn-sm d-block">Supprimer</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- COMMENTAIRES EN ATTENTE -->
            <h3 class="mt-5">Commentaires en Attente (<?php echo count($pendingComments); ?>)</h3>
            <table class="table table-hover mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Article</th>
                        <th>Auteur</th>
                        <th>Commentaire</th>
                        <th>Date création</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pendingComments as $comment) { ?>
                        <tr>
                            <td><?php echo $comment['numCom']; ?></td>
                            <td><?php echo $comment['libTitrArt']; ?></td>
                            <td><?php echo $comment['pseudoMemb']; ?></td>
                            <td><?php echo substr($comment['libCom'], 0, 100) . (strlen($comment['libCom']) > 100 ? '...' : ''); ?></td>
                            <td><?php echo $comment['dtCreaCom']; ?></td>
                            <td class="text-center" style="width: 150px;">
                                <a href="edit.php?numCom=<?php echo $comment['numCom']; ?>"
                                   class="btn btn-outline-success btn-sm mb-1 d-block">Approuver</a>
                                <a href="edit.php?numCom=<?php echo $comment['numCom']; ?>"
                                   class="btn btn-outline-warning btn-sm mb-1 d-block">Modérer</a>
                                <a href="delete.php?numCom=<?php echo $comment['numCom']; ?>"
                                   class="btn btn-outline-danger btn-sm d-block">Supprimer</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- COMMENTAIRES SUPPRIMÉS -->
            <h3 class="mt-5">Commentaires Supprimés (<?php echo count($deletedComments); ?>)</h3>
            <table class="table table-hover mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Article</th>
                        <th>Auteur</th>
                        <th>Commentaire</th>
                        <th>Date création</th>
                        <th>Raison de refus</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($deletedComments as $comment) { ?>
                        <tr>
                            <td><?php echo $comment['numCom']; ?></td>
                            <td><?php echo $comment['libTitrArt']; ?></td>
                            <td><?php echo $comment['pseudoMemb']; ?></td>
                            <td><?php echo substr($comment['libCom'], 0, 100) . (strlen($comment['libCom']) > 100 ? '...' : ''); ?></td>
                            <td><?php echo $comment['dtCreaCom']; ?></td>
                            <td><?php echo $comment['notifComKOAff'] ? $comment['notifComKOAff'] : 'Aucune raison spécifiée'; ?></td>
                            <td class="text-center" style="width: 150px;">
                                <button class="btn btn-outline-secondary btn-sm d-block" disabled>Supprimé</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <div class="mt-4">
                <a href="create.php" class="btn btn-success">Créer un commentaire</a>
            </div>
        </div>
    </div>
</div>

<?php
include '../../../footer.php';
?>

