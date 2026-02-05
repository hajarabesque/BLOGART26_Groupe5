<?php
/**
 * ==========================================================
 * 1. PRÉPARATION DES DONNÉES (BACKEND)
 * ==========================================================
 */
include '../../../header.php';

// On récupère toutes les thématiques pour remplir la liste déroulante (SELECT)
$thematiques = sql_select('thematique');

// On récupère tous les mots-clés pour le système de double liste
$motscles = sql_select('motcle');
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Création nouvel Article</h1>
        </div>
        
        <div class="col-md-12">
            <form action="<?php echo ROOT_URL . '/api/articles/create.php' ?>" method="post" enctype="multipart/form-data">
                
                <div class="form-group">
                    <label for="libTitrArt">Titre de l'article</label>
                    <input id="libTitrArt" name="libTitrArt" class="form-control" type="text" autofocus="autofocus" required />
                </div>

                <div class="form-group">
                    <label for="libChapoArt">Chapeau</label>
                    <textarea id="libChapoArt" name="libChapoArt" class="form-control" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label for="libAccrochArt">Accroche</label>
                    <input id="libAccrochArt" name="libAccrochArt" class="form-control" type="text" required />
                </div>

                <div class="form-group">
                    <label for="parag1Art">Paragraphe 1</label>
                    <textarea id="parag1Art" name="parag1Art" class="form-control" rows="5" required></textarea>
                </div>

                <div class="form-group">
                    <label for="libSsTitr1Art">Sous-titre 1</label>
                    <input id="libSsTitr1Art" name="libSsTitr1Art" class="form-control" type="text" />
                </div>

                <div class="form-group">
                    <label for="urlPhotArt">Photo de l'article</label>
                    <input id="urlPhotArt" name="urlPhotArt" class="form-control" type="file" accept="image/*" onchange="previewImage(event)" required />
                    <small class="form-text text-muted">Formats acceptés: JPG, JPEG, PNG, GIF. Taille max: 2MB</small>
                    
                    <div id="imagePreview" class="mt-2" style="display: none;">
                        <img id="previewImg" src="" alt="Aperçu" style="max-width: 200px; max-height: 200px;" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="numThem">Thématique</label>
                    <select id="numThem" name="numThem" class="form-control" required>
                        <option value="">Sélectionnez une thématique</option>
                        <?php foreach ($thematiques as $thematique): ?>
                            <option value="<?php echo $thematique['numThem']; ?>"><?php echo $thematique['libThem']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Mots-clés</label>
                    <div class="row">
                        <div class="col-md-5">
                            <label for="availableKeywords">Liste des mots-clés</label>
                            <select id="availableKeywords" class="form-control" multiple size="6">
                                <?php foreach ($motscles as $motcle): ?>
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
                            </select>
                        </div>
                    </div>
                </div>

                <br />
                <div class="form-group mb-5">
                    <a href="list.php" class="btn btn-outline-primary px-4">List</a>
                    <button type="submit" class="btn btn-outline-success px-4 ms-2">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
/**
 * APERÇU DE L'IMAGE :
 * Utilise l'API FileReader du navigateur pour lire le fichier local 
 * et l'afficher dans la balise <img> sans recharger la page.
 */
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
    }
}

/**
 * GESTION DES MOTS-CLÉS :
 * Permet de déplacer les <option> d'un <select> à un autre.
 */
function moveOptions(fromId, toId) {
    const fromSelect = document.getElementById(fromId);
    const toSelect = document.getElementById(toId);
    const selectedOptions = Array.from(fromSelect.selectedOptions);
    
    selectedOptions.forEach(option => {
        fromSelect.removeChild(option);
        toSelect.appendChild(option);
    });
}

// Événement au clic sur les boutons Ajoutez/Supprimez
document.getElementById('addKeyword').addEventListener('click', () => moveOptions('availableKeywords', 'selectedKeywords'));
document.getElementById('removeKeyword').addEventListener('click', () => moveOptions('selectedKeywords', 'availableKeywords'));

/**
 * SÉCURITÉ À L'ENVOI :
 * En HTML, une liste multiple n'envoie que les éléments SÉLECTIONNÉS (bleus).
 * Ce script sélectionne automatiquement TOUS les mots-clés de la liste de droite 
 * au moment où on clique sur "Create", pour être sûr qu'ils soient tous envoyés au PHP.
 */
document.querySelector('form').addEventListener('submit', function() {
    const selectedKeywords = document.getElementById('selectedKeywords');
    Array.from(selectedKeywords.options).forEach(option => option.selected = true);
});
</script>

<?php include '../../../footer.php'; ?>