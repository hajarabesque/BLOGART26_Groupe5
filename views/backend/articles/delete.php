<?php
include '../../../header.php';

// Get article data if deleting
$article = null;
$selectedKeywords = array();

if (isset($_GET['numArt'])) {
    $numArt = intval($_GET['numArt']);

    // Get article data with thematique
    $articles = sql_select("ARTICLE a LEFT JOIN THEMATIQUE t ON a.numThem = t.numThem", "a.*, t.libThem", "a.numArt = $numArt");

    if (!empty($articles)) {
        $article = $articles[0];

        // Get associated keywords for this article
        $articleKeywords = sql_select('MOTCLEARTICLE ma LEFT JOIN MOTCLE m ON ma.numMotCle = m.numMotCle', 'm.libMotCle', "ma.numArt = $numArt");
        $selectedKeywords = array_column($articleKeywords, 'libMotCle');
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Suppression Article</h1>
            <div class="alert alert-warning" role="alert">
                <strong>Attention!</strong> Vous êtes sur le point de supprimer cet article. Cette action est irréversible.
            </div>
        </div>
        <div class="col-md-12">
            <?php if ($article): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Détails de l'article à supprimer</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h4><?php echo htmlspecialchars($article['libTitrArt']); ?></h4>

                                <div class="mb-3">
                                    <strong>Thématique:</strong> <?php echo htmlspecialchars($article['libThem']); ?>
                                </div>

                                <div class="mb-3">
                                    <strong>Chapeau:</strong><br>
                                    <p><?php echo nl2br(htmlspecialchars($article['libChapoArt'])); ?></p>
                                </div>

                                <div class="mb-3">
                                    <strong>Accroche:</strong><br>
                                    <p><?php echo nl2br(htmlspecialchars($article['libAccrochArt'])); ?></p>
                                </div>

                                <div class="mb-3">
                                    <strong>Paragraphe 1:</strong><br>
                                    <p><?php echo nl2br(htmlspecialchars($article['parag1Art'])); ?></p>
                                </div>

                                <?php if ($article['libSsTitr1Art']): ?>
                                <div class="mb-3">
                                    <strong>Sous-titre 1:</strong> <?php echo htmlspecialchars($article['libSsTitr1Art']); ?>
                                </div>
                                <?php endif; ?>

                                <?php if ($article['parag2Art']): ?>
                                <div class="mb-3">
                                    <strong>Paragraphe 2:</strong><br>
                                    <p><?php echo nl2br(htmlspecialchars($article['parag2Art'])); ?></p>
                                </div>
                                <?php endif; ?>

                                <?php if ($article['libSsTitr2Art']): ?>
                                <div class="mb-3">
                                    <strong>Sous-titre 2:</strong> <?php echo htmlspecialchars($article['libSsTitr2Art']); ?>
                                </div>
                                <?php endif; ?>

                                <?php if ($article['parag3Art']): ?>
                                <div class="mb-3">
                                    <strong>Paragraphe 3:</strong><br>
                                    <p><?php echo nl2br(htmlspecialchars($article['parag3Art'])); ?></p>
                                </div>
                                <?php endif; ?>

                                <?php if ($article['libConclArt']): ?>
                                <div class="mb-3">
                                    <strong>Conclusion:</strong><br>
                                    <p><?php echo nl2br(htmlspecialchars($article['libConclArt'])); ?></p>
                                </div>
                                <?php endif; ?>

                                <div class="mb-3">
                                    <strong>Mots-clés:</strong>
                                    <?php if (!empty($selectedKeywords)): ?>
                                        <span class="badge badge-secondary"><?php echo implode('</span> <span class="badge badge-secondary">', array_map('htmlspecialchars', $selectedKeywords)); ?></span>
                                    <?php else: ?>
                                        Aucun
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <strong>Date de création:</strong> <?php echo htmlspecialchars($article['dtCreaArt']); ?>
                                </div>

                                <?php if ($article['dtMajArt']): ?>
                                <div class="mb-3">
                                    <strong>Dernière modification:</strong> <?php echo htmlspecialchars($article['dtMajArt']); ?>
                                </div>
                                <?php endif; ?>
                            </div>

                            <?php if ($article['urlPhotArt']): ?>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <strong>Image:</strong><br>
                                    <img src="<?php echo ROOT_URL . '/src/uploads/' . htmlspecialchars($article['urlPhotArt']); ?>" alt="Image de l'article" class="img-fluid" style="max-width: 100%; max-height: 300px;" />
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-footer">
                        <form action="<?php echo ROOT_URL . '/api/articles/delete.php' ?>" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ? Cette action est irréversible.');">
                            <input type="hidden" name="numArt" value="<?php echo htmlspecialchars($article['numArt']); ?>" />
                            <a href="list.php" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-danger" role="alert">
                    Article non trouvé.
                </div>
                <a href="list.php" class="btn btn-primary">Retour à la liste</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
include '../../../footer.php';
?>
