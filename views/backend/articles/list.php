<?php
include '../../../header.php'; // contains the header and call to config.php

// Query to get articles with thematique names and keywords
$articles = sql_select("ARTICLE a LEFT JOIN THEMATIQUE t ON a.numThem = t.numThem LEFT JOIN MOTCLEARTICLE ma ON a.numArt = ma.numArt LEFT JOIN MOTCLE m ON ma.numMotCle = m.numMotCle", "a.*, t.libThem, GROUP_CONCAT(m.libMotCle SEPARATOR ', ') as keywords", null, "a.numArt");
?>

<!-- Bootstrap default layout to display all articles in foreach -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Articles</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Thématique</th>
                        <th>Mot-clés</th>
                        <th>Date de création</th>
                        <th>Date de modification</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($articles as $article){ ?>
                        <tr>
                            <td><?php echo($article['numArt']); ?></td>
                            <td><?php echo($article['libTitrArt']); ?></td>
                            <td><?php echo($article['libThem']); ?></td>
                            <td><?php echo($article['keywords'] ? $article['keywords'] : 'Aucun'); ?></td>
                            <td><?php echo($article['dtCreaArt']); ?></td>
                            <td><?php echo($article['dtMajArt'] ? $article['dtMajArt'] : 'N/A'); ?></td>
                            <td>
                                <a href="edit.php?numArt=<?php echo($article['numArt']); ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="delete.php?numArt=<?php echo($article['numArt']); ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <a href="create.php" class="btn btn-success">Créer un article</a>
        </div>
    </div>
</div>

<?php
include '../../../footer.php'; // contains the footer
?>