<?php
/**
 * ==========================================================
 * 1. PRÉPARATION ET RÉCUPÉRATION (BACKEND)
 * ==========================================================
 */
include '../../../header.php';

// On charge toutes les thématiques pour la liste déroulante
$thematiques = sql_select('THEMATIQUE');

// On charge TOUS les mots-clés existants en base
$allMotscles = sql_select('MOTCLE');

$article = null;
$selectedKeywords = array();

/**
 * RÉCUPÉRATION DES DONNÉES ACTUELLES :
 * Si l'ID de l'article est présent dans l'URL (?numArt=...)
 */
if (isset($_GET['numArt'])) {
    $numArt = intval($_GET['numArt']);

    // On récupère les infos de l'article avec sa thématique
    $articles = sql_select("ARTICLE a LEFT JOIN THEMATIQUE t ON a.numThem = t.numThem", "a.*, t.libThem", "a.numArt = $numArt");

    if (!empty($articles)) {
        $article = $articles[0];

        // On récupère les IDs des mots-clés actuellement liés à cet article
        $articleKeywords = sql_select('MOTCLEARTICLE', 'numMotCle', "numArt = $numArt");
        // array_column crée un tableau simple d'IDs [1, 5, 12...]
        $selectedKeywords = array_column($articleKeywords, 'numMotCle');
    }
}

/**
 * LOGIQUE DE TRI DES MOTS-CLÉS :
 * $availableKeywords : Mots-clés qui NE sont PAS encore liés à l'article (à gauche).
 * $selectedMotscles : Mots-clés qui SONT déjà liés à l'article (à droite).
 */
$availableKeywords = array_filter($allMotscles, function($motcle) use ($selectedKeywords) {
    return !in_array($motcle['numMotCle'], $selectedKeywords);
});

$selectedMotscles = array_filter($allMotscles, function($motcle) use ($selectedKeywords) {
    return in_array($motcle['numMotCle'], $selectedKeywords);
});
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Modification Article</h1>
        </div>
        <div class="col-md-12">
            <form action="<?php echo ROOT_URL . '/api/articles/update.php' ?>" method="post" enctype="multipart/form-data">
                
                <input type="hidden" name="numArt" value="<?php echo htmlspecialchars($article ? $article['numArt'] : ''); ?>" />

                <div class="form-group">
                    <label for="libTitrArt">Titre de l'article</label>
                    <input id="libTitrArt" name="libTitrArt" class="form-control" type="text" value="<?php echo htmlspecialchars($article ? $article['libTitrArt'] : ''); ?>" required />
                </div>

                <div class="form-group">
                    <label for="urlPhotArt">Photo de l'article</label>
                    <input id="urlPhotArt" name="urlPhotArt" class="form-control" type="file" accept="image/*" onchange="previewImage(event)" />
                    
                    <?php if ($article && $article['urlPhotArt']): ?>
                        <div class="mt-2">
                            <small class="text-muted">Image actuelle :</small><br>
                            <img src="<?php echo ROOT_URL . '/src/uploads/' . htmlspecialchars($article['urlPhotArt']); ?>" alt="Image actuelle" style="max-width: 200px; max-height: 200px;" />
                        </div>
                    <?php endif; ?>
                    
                    <div id="imagePreview" class="mt-2" style="display: none;">
                        <img id="previewImg" src="" alt="Aperçu" style="max-width: 200px; max-height: 200px;" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="numThem">Thématique</label>
                    <select id="numThem" name="numThem" class="form-control" required>
                        <option value="">Sélectionnez une thématique</option>
                        <?php foreach ($thematiques as $thematique): ?>
                            <option value="<?php echo $thematique['numThem']; ?>" <?php echo ($article && $article['numThem'] == $thematique['numThem']) ? 'selected' : ''; ?>>
                                <?php echo $thematique['libThem']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Mots-clés</label>
                    <div class="row">
                        <div class="col-md-5">
                            <select id="availableKeywords" class="form-control" multiple size="6">
                                <?php foreach ($availableKeywords as $motcle): ?>
                                    <option value="<?php echo $motcle['numMotCle']; ?>"><?php echo $motcle['libMotCle']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-2 text-center" style="padding-top: 50px;">
                            <button type="button" id="addKeyword" class="btn btn-success btn-sm">Ajoutez →</button>
                            <button type="button" id="removeKeyword" class="btn btn-danger btn-sm mt-2">← Supprimez</button>
                        </div>
                        
                        <div class="col-md-5">
                            <select id="selectedKeywords" name="motscles[]" class="form-control" multiple size="6">
                                <?php foreach ($selectedMotscles as $motcle): ?>
                                    <option value="<?php echo $motcle['numMotCle']; ?>"><?php echo $motcle['libMotCle']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <a href="list.php" class="btn btn-primary">List</a>
                    <button type="submit" class="btn btn-warning">Confirmer Edit ?</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/src/js/admin_keyword_management.js"></script>

<?php include '../../../footer.php'; ?>