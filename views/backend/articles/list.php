<?php
/**
 * ==========================================================
 * 1. LOGIQUE D'AFFICHAGE GLOBAL (BACKEND)
 * ==========================================================
 */
include '../../../header.php'; // Inclut la connexion à la base via config.php

/**
 * LA REQUÊTE SQL "CUMULATIVE" :
 * C'est la requête la plus complète de ton site.
 * * - LEFT JOIN THEMATIQUE : Pour afficher le nom (Rock, Jazz...) au lieu du chiffre numThem.
 * - LEFT JOIN MOTCLEARTICLE/MOTCLE : Pour récupérer les mots-clés liés.
 * - GROUP_CONCAT : Fonction SQL magique qui rassemble tous les mots-clés d'un article 
 * en une seule ligne de texte (ex: "Bordeaux, Concert, Rock").
 * - GROUP BY a.numArt : Indispensable avec GROUP_CONCAT pour ne pas avoir de doublons de lignes.
 */
$articles = sql_select(
    "ARTICLE a 
     LEFT JOIN THEMATIQUE t ON a.numThem = t.numThem 
     LEFT JOIN MOTCLEARTICLE ma ON a.numArt = ma.numArt 
     LEFT JOIN MOTCLE m ON ma.numMotCle = m.numMotCle", 
    "a.*, t.libThem, GROUP_CONCAT(m.libMotCle SEPARATOR ', ') as keywords", 
    null, 
    "a.numArt"
);
?>

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
                        <th>Mots-clés</th>
                        <th>Date création</th>
                        <th>Dernière modification</th>
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
/**
 * ==========================================================
 * 3. FERMETURE
 * ==========================================================
 */
include '../../../footer.php'; 
?>