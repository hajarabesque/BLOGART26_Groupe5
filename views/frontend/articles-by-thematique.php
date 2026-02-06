<?php 
/**
 * ==========================================================
 * PAGE ARTICLES PAR THÉMATIQUE
 * ==========================================================
 */
require_once '../../header.php';

// Récupérer l'ID de la thématique depuis l'URL
$numThem = isset($_GET['numThem']) ? intval($_GET['numThem']) : 0;

// Récupérer les informations de la thématique
$themQuery = $db->prepare("SELECT * FROM THEMATIQUE WHERE numThem = ?");
$themQuery->execute([$numThem]);
$thematique = $themQuery->fetch(PDO::FETCH_ASSOC);

if (!$thematique) {
    header('Location: /views/frontend/Thematique.php');
    exit();
}

// Récupérer tous les articles pour cette thématique
$articlesQuery = $db->prepare("
    SELECT ARTICLE.*, THEMATIQUE.libThem 
    FROM ARTICLE 
    JOIN THEMATIQUE ON ARTICLE.numThem = THEMATIQUE.numThem
    WHERE ARTICLE.numThem = ? 
    ORDER BY ARTICLE.dtCreaArt DESC
");
$articlesQuery->execute([$numThem]);
$articles = $articlesQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- SECTION HERO -->
<section class="hero-banner mb-5" style="height: 350px; background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('/src/images/♥.jpg') center/cover; position: relative; display: flex; align-items: center; justify-content: center;">
    <div class="text-center text-white">
        <h1 class="display-4 fw-bold mb-3"><?= htmlspecialchars($thematique['libThem']); ?></h1>
        <p class="lead">Tous les articles sur cette thématique</p>
    </div>
</section>

<!-- SECTION ARTICLES -->
<main class="py-5">
    <div class="container">
        
        <!-- BOUTON RETOUR -->
        <div class="mb-4">
            <a href="/views/frontend/Thematique.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour aux thématiques
            </a>
        </div>

        <?php if (!empty($articles)): ?>
            <div class="row g-4">
                <?php foreach ($articles as $article): ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <a href="/views/frontend/articles/article1.php?id=<?= $article['numArt']; ?>" class="text-decoration-none text-dark d-block h-100">
                            <div class="card h-100 border-0 shadow-sm rounded overflow-hidden article-card" style="transition: all 0.3s ease;">
                                
                                <!-- IMAGE -->
                                <div class="position-relative overflow-hidden" style="height: 220px;">
                                    <img src="/src/uploads/<?= htmlspecialchars($article['urlPhotArt']); ?>" class="card-img-top w-100 h-100" alt="<?= htmlspecialchars($article['libTitrArt']); ?>" style="object-fit: cover; transition: transform 0.3s ease;">
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-danger"><?= htmlspecialchars($article['libThem']); ?></span>
                                    </div>
                                </div>
                                
                                <!-- CONTENU -->
                                <div class="card-body d-flex flex-column p-4">
                                    <h3 class="card-title h6 fw-bold mb-2"><?= htmlspecialchars($article['libTitrArt']); ?></h3>
                                    <p class="card-text text-muted small flex-grow-1 mb-3">
                                        <?= mb_strimwidth(htmlspecialchars($article['libChapoArt'] ?? ''), 0, 100, "..."); ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-secondary">
                                            <?= date('d/m/Y', strtotime($article['dtCreaArt'])); ?>
                                        </small>
                                        <span class="text-primary fw-bold small">Lire →</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center py-5">
                <h5>Aucun article trouvé</h5>
                <p class="mb-0">Il n'y a pas encore d'articles pour cette thématique.</p>
            </div>
        <?php endif; ?>

    </div>
</main>




<?php 
require_once '../../footer.php'; 
?>
