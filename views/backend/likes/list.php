<?php
require_once '../../../header.php';

// 1. On récupère les likes en joignant les tables article et membre 
// pour avoir les noms au lieu de simples numéros ID.
$likes = sql_select(
    "likeart 
    INNER JOIN article ON likeart.numArt = article.numArt 
    INNER JOIN membre ON likeart.numMemb = membre.numMemb",
    "likeart.numArt, likeart.numMemb, article.libTitrArt, membre.pseudoMemb, likeart.LikeA"
);
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Gestion des Likes</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Pseudo Membre</th>
                        <th>Titre Article</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($likes as $like): ?>
                        <tr>
                            <!-- On affiche les données de la ligne de "like" actuelle -->
                            <td><?php echo $like['pseudoMemb']; ?></td>
                            <td><?php echo $like['libTitrArt']; ?></td>
                            <td>
                            <?php 
                        // Si la clé LikeA existe et vaut 1, on affiche "Liké"
                        // Sinon, on peut juste afficher "👍" car la ligne existe
                        echo (isset($like['LikeA']) && $like['LikeA'] == 1) ? "Liké" : "Non Liké"; 
                            ?>
                            <td>
                                <!-- Pour modifier ou supprimer un like, il faut envoyer les DEUX IDs -->
                                <a href="edit.php?numArt=<?php echo $like['numArt']; ?>&numMemb=<?php echo $like['numMemb']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="delete.php?numArt=<?php echo $like['numArt']; ?>&numMemb=<?php echo $like['numMemb']; ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="create.php" class="btn btn-success">Ajouter un Like</a>
        </div>
    </div>
</div>


<?php
include '../../../footer.php';
?>