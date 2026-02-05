<?php
require_once '../../../header.php'; 

// 1. Récupération de l'ID depuis l'URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // 2. Requête SQL avec les noms de colonnes exacts de ta BDD
    $query = $db->prepare("SELECT * FROM ARTICLE WHERE numArt = ?");
    $query->execute([$id]);
    $article = $query->fetch();

    if (!$article) {
        header('Location: /index.php');
        exit();
    }
} else {
    header('Location: /index.php');
    exit();
}
?>

<link rel="stylesheet" href="/src/css/article.css">

<article class="article-container container py-5">
    <header class="mb-5">
        <h1 class="fw-bold display-4"><?= htmlspecialchars($article['libTitrArt'] ?? 'Sans titre') ?></h1>
        <p class="text-muted">Publié le <?= date('d/m/Y', strtotime($article['dtCreaArt'])) ?></p>
    </header>

    <div class="row g-5 align-items-start">
        <div class="col-md-5">
            <div class="article-body-text fs-5">
                <p class="fw-bold mb-4">
                    <?= nl2br(htmlspecialchars($article['libChapoArt'] ?? '')) ?>
                </p>
                
                <div class="mb-4">
                    <?= nl2br(htmlspecialchars($article['parag1Art'] ?? '')) ?>
                </div>

                <div class="text-secondary small mt-5 border-top pt-3">
                    <?= nl2br(htmlspecialchars($article['libConclArt'] ?? '')) ?>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="article-main-visual">
                <div class="carre-photo-art shadow-sm" 
                     style="background-image: url('../../../src/uploads/<?= $article['urlPhotArt'] ?>');">
                    
                    <?php if(empty($article['urlPhotArt'])): ?>
                        <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted">
                            Aucun visuel disponible
                        </div>
                    <?php endif; ?>
                </div>

            
            </div>
        </div>
    </div>
</article>

<?php require_once '../../../footer.php'; ?>