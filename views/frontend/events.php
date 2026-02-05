<?php 
require_once '../../header.php'; 

// 1. Requête SQL avec les noms de colonnes exacts de ta capture
$sql = "SELECT a.*, t.libThem 
        FROM ARTICLE a 
        INNER JOIN THEMATIQUE t ON a.numThem = t.numThem 
        ORDER BY a.dtCreaArt DESC";

$query = $db->prepare($sql);
$query->execute();
$articles = $query->fetchAll();
?>

<link rel="stylesheet" href="/src/css/actu.css">

<div class="container container-actu py-5">
    <h1 class="titre-page mb-5 text-center fw-bold">Actualités</h1>
    
    <?php if (empty($articles)): ?>
        <p class="text-center">Aucune actualité pour le moment.</p>
    <?php else: ?>
        <?php foreach ($articles as $art): ?>
            <section class="mb-5">
                <div class="row align-items-center g-5">
                    
                    <div class="col-md-5">
                        <p class="news-date text-primary fw-bold text-uppercase mb-2">
                            <?= date('F Y', strtotime($art['dtCreaArt'])) ?>
                        </p>

                        <h2 class="h3 fw-bold mb-3">
                            <a href="/views/frontend/articles/article1.php?id=<?= $art['numArt'] ?>" class="text-decoration-none text-dark">
                                <?= htmlspecialchars($art['libTitrArt'] ?? '') ?>
                            </a>
                        </h2>

                        <p class="text-muted mb-0 fs-5">
                            <?= mb_strimwidth(htmlspecialchars($art['libChapoArt'] ?? $art['libAccrochtArt'] ?? ''), 0, 180, "...") ?>
                        </p>
                    </div>

                    <div class="col-md-7">
                        <a href="/views/frontend/articles/article1.php?id=<?= $art['numArt'] ?>" class="image-link d-block">
                            <div class="carre-bleu rounded shadow-sm" 
                                 style="height: 400px; 
                                        style="background-image: url('../../src/uploads/<?= $art['urlPhotArt'] ?>');"
                                        background-size: cover; 
                                        background-position: center;
                                        border: 1px solid #eee;">
                                
                                <?php if(empty($art['urlPhotArt'])): ?>
                                    <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted">
                                        Image manquante
                                    </div>
                                <?php endif; ?>
                            </div> 
                        </a>
                    </div>
                </div>

                <div class="separator my-5" style="border-bottom: 1px solid #eee; opacity: 0.5;"></div>
            </section>
        <?php endforeach; ?> 
    <?php endif; ?> 
</div>

<?php 
require_once '../../footer.php'; 
?>