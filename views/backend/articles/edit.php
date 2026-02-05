<?php
include '../../../header.php';

// Load thematiques for the select
$thematiques = sql_select('THEMATIQUE');

// Load all keywords
$allMotscles = sql_select('MOTCLE');

// Get article data if editing
$article = null;
$selectedKeywords = array();

if (isset($_GET['numArt'])) {
    $numArt = intval($_GET['numArt']);

    // Get article data with thematique
    $articles = sql_select("ARTICLE a LEFT JOIN THEMATIQUE t ON a.numThem = t.numThem", "a.*, t.libThem", "a.numArt = $numArt");

    if (!empty($articles)) {
        $article = $articles[0];

        // Get associated keywords for this article
        $articleKeywords = sql_select('MOTCLEARTICLE', 'numMotCle', "numArt = $numArt");
        $selectedKeywords = array_column($articleKeywords, 'numMotCle');
    }
}

// Filter keywords - available ones are those not already selected
$availableKeywords = array_filter($allMotscles, function($motcle) use ($selectedKeywords) {
    return !in_array($motcle['numMotCle'], $selectedKeywords);
});

// Selected keywords are those associated with the article
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
                    <input id="libTitrArt" name="libTitrArt" class="form-control" type="text" value="<?php echo htmlspecialchars($article ? $article['libTitrArt'] : ''); ?>" autofocus="autofocus" required />
                </div>
                <div class="form-group">
                    <label for="libChapoArt">Chapeau</label>
                    <textarea id="libChapoArt" name="libChapoArt" class="form-control" rows="3" required><?php echo htmlspecialchars($article ? $article['libChapoArt'] : ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="libAccrochArt">Accroche</label>
                    <input id="libAccrochArt" name="libAccrochArt" class="form-control" type="text" value="<?php echo htmlspecialchars($article ? $article['libAccrochArt'] : ''); ?>" required />
                </div>
                <div class="form-group">
                    <label for="parag1Art">Paragraphe 1</label>
                    <textarea id="parag1Art" name="parag1Art" class="form-control" rows="5" required><?php echo htmlspecialchars($article ? $article['parag1Art'] : ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="libSsTitr1Art">Sous-titre 1</label>
                    <input id="libSsTitr1Art" name="libSsTitr1Art" class="form-control" type="text" value="<?php echo htmlspecialchars($article ? $article['libSsTitr1Art'] : ''); ?>" />
                </div>
                <div class="form-group">
                    <label for="parag2Art">Paragraphe 2</label>
                    <textarea id="parag2Art" name="parag2Art" class="form-control" rows="5"><?php echo htmlspecialchars($article ? $article['parag2Art'] : ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="libSsTitr2Art">Sous-titre 2</label>
                    <input id="libSsTitr2Art" name="libSsTitr2Art" class="form-control" type="text" value="<?php echo htmlspecialchars($article ? $article['libSsTitr2Art'] : ''); ?>" />
                </div>
                <div class="form-group">
                    <label for="parag3Art">Paragraphe 3</label>
                    <textarea id="parag3Art" name="parag3Art" class="form-control" rows="5"><?php echo htmlspecialchars($article ? $article['parag3Art'] : ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="libConclArt">Conclusion</label>
                    <textarea id="libConclArt" name="libConclArt" class="form-control" rows="4"><?php echo htmlspecialchars($article ? $article['libConclArt'] : ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="urlPhotArt">Photo de l'article</label>
                    <input id="urlPhotArt" name="urlPhotArt" class="form-control" type="file" accept="image/*" onchange="previewImage(event)" />
                    <small class="form-text text-muted">Formats acceptés: JPG, JPEG, PNG, GIF. Taille max: 2MB</small>
                    <?php if ($article && $article['urlPhotArt']): ?>
                        <div class="mt-2">
                            <small class="text-muted">Image actuelle:</small><br>
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
                            <option value="<?php echo $thematique['numThem']; ?>" <?php echo ($article && $article['numThem'] == $thematique['numThem']) ? 'selected' : ''; ?>><?php echo $thematique['libThem']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Mots-clés</label>
                    <div class="row">
                        <div class="col-md-5">
                            <label for="availableKeywords">Liste des mots-clés</label>
                            <select id="availableKeywords" class="form-control" multiple size="6">
                                <?php foreach ($availableKeywords as $motcle): ?>
                                    <option value="<?php echo $motcle['numMotCle']; ?>"><?php echo $motcle['libMotCle']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2 text-center" style="padding-top: 50px;">
                            <button type="button" id="addKeyword" class="btn btn-success btn-sm mb-2">Ajoutez →</button><br>
                            <button type="button" id="removeKeyword" class="btn btn-danger btn-sm">← Supprimez</button>
                        </div>
                        <div class="col-md-5">
                            <label for="selectedKeywords">Mots-clés ajoutés</label>
                            <select id="selectedKeywords" name="motscles[]" class="form-control" multiple size="6">
                                <?php foreach ($selectedMotscles as $motcle): ?>
                                    <option value="<?php echo $motcle['numMotCle']; ?>"><?php echo $motcle['libMotCle']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <small class="form-text text-muted">Sélectionnez un ou plusieurs mots-clés dans la liste de gauche et cliquez sur "Ajoutez"</small>
                </div>
                <br />
                <div class="form-group mt-2">
                    <a href="list.php" class="btn btn-primary">List</a>
                    <button type="submit" class="btn btn-warning">Confirmer Edit ?</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}

// Keyword management functions
document.getElementById('addKeyword').addEventListener('click', function() {
    moveOptions('availableKeywords', 'selectedKeywords');
});

document.getElementById('removeKeyword').addEventListener('click', function() {
    moveOptions('selectedKeywords', 'availableKeywords');
});

function moveOptions(fromId, toId) {
    const fromSelect = document.getElementById(fromId);
    const toSelect = document.getElementById(toId);

    // Get selected options
    const selectedOptions = Array.from(fromSelect.selectedOptions);

    // Move each selected option
    selectedOptions.forEach(option => {
        fromSelect.removeChild(option);
        toSelect.appendChild(option);
    });

    // Sort the destination list alphabetically
    sortSelect(toSelect);
}

function sortSelect(selectElement) {
    const options = Array.from(selectElement.options);
    options.sort((a, b) => a.text.localeCompare(b.text));

    // Clear and re-add sorted options
    selectElement.innerHTML = '';
    options.forEach(option => selectElement.appendChild(option));
}
</script>

<?php
include '../../../footer.php';
?>