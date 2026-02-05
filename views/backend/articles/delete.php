<?php
/**
 * ==========================================================
 * 1. RÉCUPÉRATION DES DONNÉES (SÉCURITÉ)
 * ==========================================================
 */
include '../../../header.php';

// Initialisation des variables
$article = null;
$selectedKeywords = array();

/**
 * RÉCUPÉRATION DE L'ARTICLE PAR L'URL :
 * On récupère le numéro de l'article via $_GET['numArt']
 */
if (isset($_GET['numArt'])) {
    $numArt = intval($_GET['numArt']); // Sécurisation : on force un entier

    /**
     * JOINTURE SQL :
     * On récupère l'article ET le nom de sa thématique (libThem) 
     * pour que l'administrateur voie des noms clairs au lieu de simples numéros.
     */
    $articles = sql_select("ARTICLE a LEFT JOIN THEMATIQUE t ON a.numThem = t.numThem", "a.*, t.libThem", "a.numArt = $numArt");

    if (!empty($articles)) {
        $article = $articles[0]; // On stocke les données de l'article trouvé

        /**
         * RÉCUPÉRATION DES MOTS-CLÉS ASSOCIÉS :
         * L'article est lié aux mots-clés via une table intermédiaire (MOTCLEARTICLE).
         * On fait une jointure pour récupérer les noms (libMotCle).
         */
        $articleKeywords = sql_select('MOTCLEARTICLE ma LEFT JOIN MOTCLE m ON ma.numMotCle = m.numMotCle', 'm.libMotCle', "ma.numArt = $numArt");
        
        // array_column permet d'extraire uniquement les noms dans un tableau simple
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