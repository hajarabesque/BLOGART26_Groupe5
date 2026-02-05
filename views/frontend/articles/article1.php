<?php
/**
 * ==========================================================
 * 1. LOGIQUE DE RÉCUPÉRATION DYNAMIQUE (BACKEND)
 * ==========================================================
 */

// Inclusion du header pour la connexion $db et le design global
require_once '../../../header.php';

/**
 * SÉCURITÉ ET RÉCUPÉRATION DE L'ID :
 * - On vérifie si 'id' existe dans l'URL (ex: article.php?id=12).
 * - Si absent, on redirige vers l'accueil pour éviter une erreur.
 */
if (!isset($_GET['id'])) {
    header('Location: /index.php');
    exit();
}

/**
 * NETTOYAGE DE L'ID :
 * - intval() force la donnée à être un nombre entier.
 * - Cela empêche les injections de texte malveillant dans l'URL.
 */
$id = intval($_GET['id']);

// Requête préparée pour récupérer uniquement l'article cliqué
$query = $db->prepare("SELECT * FROM ARTICLE WHERE numArt = ?");
$query->execute([$id]);
$article = $query->fetch(); // fetch() car on ne veut qu'UN SEUL résultat

// Si l'ID dans l'URL ne correspond à rien en base, on redirige
if (!$article) {
    header('Location: /index.php');
    exit();
}
?>

<link rel="stylesheet" href="../../src/css/article.css">

<article class="container py-5">
    
    <header class="mb-5">
        <h1 class="fw-bold display-4"><?= htmlspecialchars($article['libTitrArt'] ?? 'Sans titre') ?></h1>
        <p class="text-muted">Publié le <?= date('d/m/Y', strtotime($article['dtCreaArt'])) ?></p>
    </header>

    <div class="row g-5 align-items-start">
        
        <div class="col-md-5">
            <div class="fs-5">
                <p class="fw-bold mb-4"><?= nl2br(htmlspecialchars($article['libChapoArt'] ?? '')) ?></p>
                
                <div class="mb-4"><?= nl2br(htmlspecialchars($article['parag1Art'] ?? '')) ?></div>
                
                <div class="text-secondary small border-top pt-3 mt-5">
                    <?= nl2br(htmlspecialchars($article['libConclArt'] ?? '')) ?>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="article-main-visual">
                 <div class="carre-photo-art shadow-sm">
                    <img src="<?php echo '../../../src/uploads/' . htmlspecialchars($article['urlPhotArt']); ?>" alt="Photo de l'article" class="img-fluid">
                    
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

<?php 
// Inclusion du footer
require_once '../../../footer.php'; 
?>