<?php 
echo "Hello World! This is the comments section.";
?>
<div class="comment-section mt-5 p-4 border rounded bg-light shadow-sm">
    <h3 class="mb-4 text-primary">Espace Discussion</h3>

    <form action="<?php echo ROOT_URL; ?>/api/comments/create.php" method="post">
        
        <!-- On passe l'ID de l'article (variable définie dans article.php) -->
        <input type="hidden" name="numArt" value="<?php echo $numArt; ?>">
        <!-- On précise l'origine pour que l'API sache où nous rediriger -->
        <input type="hidden" name="origin" value="front">

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label">Pseudo</label>
                <input type="text" name="pseudoMemb" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="passMemb" class="form-control" placeholder="Votre mot de passe" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Votre message</label>
            <textarea name="libCom" class="form-control" rows="4" placeholder="Écrivez votre commentaire ici..." required></textarea>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="submit" class="btn btn-primary px-5">Publier</button>
        </div>
    </form>
</div>