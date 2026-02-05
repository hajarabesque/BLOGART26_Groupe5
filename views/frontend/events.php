<?php
// ==========================================================
// 1. LOGIQUE PHP : RÉCUPÉRATION DES DONNÉES (SQL COMPLEXE)
// ==========================================================

// Inclusion du header pour récupérer la connexion $db et le menu
require_once '../../header.php';

/**
 * LA REQUÊTE SQL AVEC JOINTURE (INNER JOIN) :
 * - a.* : On récupère toutes les colonnes de la table ARTICLE (alias 'a').
 * - t.libThem : On récupère seulement le nom de la thématique dans la table THEMATIQUE (alias 't').
 * - INNER JOIN : On lie les deux tables là où le 'numThem' est identique.
 * - ORDER BY : On classe par date de création, du plus récent au plus ancien.
 */
$sql = "SELECT a.*, t.libThem FROM ARTICLE a 
        INNER JOIN THEMATIQUE t ON a.numThem = t.numThem 
        ORDER BY a.dtCreaArt DESC";

$query = $db->prepare($sql);
$query->execute();
// $articles contient maintenant un tableau de tous les articles avec le nom de leur thématique
$articles = $query->fetchAll();
?>

<link rel="stylesheet" href="../../src/css/actu.css">

<div class="container py-5">
    <h1 class="text-center fw-bold mb-5">Actualités</h1>

    <?php if (empty($articles)): ?>
        <p class="text-center text-muted">Aucune actualité disponible.</p>
    <?php else: ?>
        
        <?php foreach ($articles as $art): ?>
            <section class="mb-5">
                <div class="row g-4 align-items-center">
                    
                    <div class="col-md-5">
                        <p class="text-uppercase text-primary fw-bold mb-2">
                            <?= date('F Y', strtotime($art['dtCreaArt'])) ?>
                        </p>
                        
                        <h2 class="h4 fw-bold mb-3">
                            <a href="/views/frontend/articles/article1.php?id=<?= $art['numArt'] ?>" class="text-decoration-none text-dark">
                                <?= htmlspecialchars($art['libTitrArt'] ?? '') ?>
                            </a>
                        </h2>
                        
                        <p class="text-muted">
                            <?= mb_strimwidth(htmlspecialchars($art['libChapoArt'] ?? ''), 0, 180, "...") ?>
                        </p>
                    </div>

                    <div class="col-md-7">
                        <div class="carre-bleu shadow-sm">
                            <?php if(!empty($art['urlPhotArt'])): ?>
                                <img src="../../src/uploads/<?= $art['urlPhotArt']; ?>" alt="Photo">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                                    Image manquante
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <hr class="my-5" style="opacity: 0.3;">
            </section>
        <?php endforeach; ?>
        <?php endif; ?>
</div>

<?php 
// Inclusion du footer pour fermer les balises HTML
require_once '../../footer.php'; 
?>