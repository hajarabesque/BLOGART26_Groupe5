<?php
// ==========================================================
// 1. PRÉPARATION DES DONNÉES (LOGIQUE PHP)
// ==========================================================

// Inclusion du header qui contient la connexion à la base de données ($db)
require_once 'header.php';

/**
 * RÉCUPÉRATION DES ARTICLES :
 * - On sélectionne tout (*) de la table ARTICLE.
 * - On trie par date de création (dtCreaArt) de la plus récente à la plus ancienne (DESC).
 * - On limite à 4 pour ne pas surcharger la page d'accueil.
 */
$articlesIndex = $db->query("SELECT * FROM ARTICLE ORDER BY dtCreaArt DESC LIMIT 4")->fetchAll();

/**
 * RÉPARTITION DES ARTICLES :
 * $principal : On prend le tout premier article du tableau (l'index 0).
 * $secondaires : On crée un nouveau tableau qui contient les 3 suivants (on "découpe" à partir de l'index 1).
 */
$principal = isset($articlesIndex[0]) ? $articlesIndex[0] : null;
$secondaires = array_slice($articlesIndex, 1);
?>

<main id="page-top">
    
    <header class="hero">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="src/images/concert.png" class="d-block w-100" alt="Concert 1">
                </div>
                <div class="carousel-item">
                    <img src="src/images/concert2.png" class="d-block w-100" alt="Concert 2">
                </div>
                <div class="carousel-item">
                    <img src="src/images/concert3.png" class="d-block w-100" alt="Concert 3">
                </div>
            </div>
        </div>
        <div class="carousel-overlay"></div>
        <div class="title-section">
            <div class="container px-4 px-lg-5">
                <p class="subtitle">Bordeaux à travers</p>
                <h1>LA MUSIQUE<span>Moderne</span></h1>
            </div>
        </div>
    </header>

    <section class="page-section styles-musique" id="services">
        <div class="container px-4 px-lg-5">
            <h2 class="text-center mt-0 titre-principal">STYLES DE MUSIQUE</h2>
            <div class="conteneur-flex mt-5">
                <div class="bloc-texte texte-gauche">
                    <p>Ici, pas juste de la musique : des trajectoires.</p>
                </div>
                <div class="centre-visuel">
                    <img src="src/images/tv2.png" alt="TVs" class="image-centrale">
                    <img src="src/images/onde.png" alt="Onde" class="onde-hero">
                </div>
                <div class="bloc-texte texte-droite">
                    <p>Ce qui bouge, ici.<br>Des voix nouvelles.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="page-section bg-light actualites" id="portfolio">
        <div class="container px-4 px-lg-5">
            <h2 class="text-center mt-0 titre-section">Actualités</h2>
            <div class="row g-5 mt-4">
                
                <div class="col-lg-7"> 
                    <?php if ($principal): ?>
                        <a href="/views/frontend/articles/article1.php?id=<?= $principal['numArt'] ?>" class="text-decoration-none text-dark card-article-principal">
                            <div class="carre-bleu-grand shadow-sm mb-3"
                                 style="background-image: url('<?php echo ROOT_URL . '/src/uploads/' . htmlspecialchars($principal['urlPhotArt']); ?>'); background-size: cover; background-position: center; height: 400px; border-radius: 8px;">
                            </div>
                            
                            <h3 class="h3 mb-2 fw-bold text-primary-hover"><?= htmlspecialchars($principal['libTitrArt'] ?? '') ?></h3>
                            
                            <p class="text-muted mb-3">
                                <?= mb_strimwidth(htmlspecialchars($principal['libChapoArt'] ?? ''), 0, 150, "...") ?>
                            </p>
                        </a>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="text-secondary small">Publié le <?= date('d/m/Y', strtotime($principal['dtCreaArt'] ?? 'now')) ?></span>
                            <a href="/views/frontend/events.php" class="btn btn-primary">Voir toutes les actus</a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">Aucun article principal à afficher.</div>
                    <?php endif; ?>
                </div>

                <div class="col-lg-5 d-flex flex-column gap-4">
                    <?php if (!empty($secondaires)): ?>
                        <?php foreach ($secondaires as $art) : ?>
                        <a href="/views/frontend/articles/article1.php?id=<?= $art['numArt'] ?>" class="text-decoration-none text-dark card-hover">
                            <div class="d-flex gap-3 align-items-start article-secondaire p-2 shadow-sm rounded bg-white">
                                <div class="carre-bleu-petit flex-shrink-0">
                                    <img src="../../src/uploads/<?= htmlspecialchars($art['urlPhotArt']); ?>" alt="Photo de l'article" class="img-fluid" style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px;">
                                </div>
                                <div>
                                    <h4 class="h6 fw-bold mb-1"><?= htmlspecialchars($art['libTitrArt'] ?? '') ?></h4>
                                    <p class="small text-muted mb-0" style="font-size: 0.8rem;">
                                        <?= mb_strimwidth(htmlspecialchars($art['libChapoArt'] ?? ''), 0, 70, "...") ?>
                                    </p>
                                </div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Pas d'autres actualités.</p>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>
</main>

<?php 
// Inclusion du footer pour fermer les balises HTML et ajouter les scripts JS
require_once 'footer.php'; 
?>