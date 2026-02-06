<?php 
/**
 * ==========================================================
 * 1. PRÉPARATION ET INCLUSION
 * ==========================================================
 */
// Inclusion du header pour la navigation et la connexion à la base de données
require_once '../../header.php';
?>

<!-- SECTION HERO -->
<section class="hero-banner mb-5" style="height: 350px; background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('/src/images/♥.jpg') center/cover; position: relative; display: flex; align-items: center; justify-content: center;">
    <div class="text-center text-white">
        <h1 class="display-4 fw-bold mb-3">Explorez par Catégorie</h1>
        <p class="lead">Découvrez les différentes facettes de la musique moderne</p>
    </div>
</section>

<!-- SECTION CARTES -->
<main class="py-5">
    <div class="container">
        <div class="row g-4 align-items-stretch">
        
        <?php
        /**
         * 2. RÉCUPÉRER LES THÉMATIQUES DE LA BASE DE DONNÉES
         * On requête la table THEMATIQUE pour obtenir toutes les thématiques.
         */
        try {
            $query = $db->prepare("SELECT numThem, libThem FROM THEMATIQUE ORDER BY libThem ASC");
            $query->execute();
            $thematiques = $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $thematiques = [];
        }

        /**
         * 3. BOUCLE D'AFFICHAGE
         * foreach ($thematiques as $them) : On parcourt chaque thématique.
         */
        foreach ($thematiques as $them) : ?>
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="/views/frontend/articles-by-thematique.php?numThem=<?= $them['numThem']; ?>" class="text-decoration-none text-dark d-block h-100">
                    <div class="card h-100 border-0 shadow-sm rounded overflow-hidden category-card" style="transition: all 0.3s ease;">
                        
                        <!-- IMAGE : Chaque thématique a sa propre image basée sur son numéro -->
                        <div class="position-relative overflow-hidden" style="height: 250px;">
                            <img src="/src/images/them_<?= $them['numThem']; ?>.jpeg" class="card-img-top w-100 h-100" alt="<?= htmlspecialchars($them['libThem']); ?>" style="object-fit: cover; transition: transform 0.3s ease;">
                            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.2); transition: background 0.3s ease;"></div>
                        </div>
                        
                        <!-- CONTENU -->
                        <div class="card-body d-flex flex-column p-4" style="background: #f8f9fa;">
                            <div class="mb-3">
                                <i class="bi bi-music-note-beamed text-danger fs-5"></i>
                            </div>
                            <h3 class="card-title h5 fw-bold mb-2"><?= htmlspecialchars($them['libThem']); ?></h3>
                            <p class="card-text text-muted small flex-grow-1 mb-0">Découvrez tous les articles sur cette thématique</p>
                            <div class="mt-3 pt-3 border-top">
                                <span class="text-primary fw-bold small">Voir les articles →</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
        
        <?php if (empty($thematiques)): ?>
            <div class="col-12">
                <div class="alert alert-info">Aucune thématique disponible pour le moment.</div>
            </div>
        <?php endif; ?>

    </div> 
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php 
// Inclusion du footer pour fermer le document
require_once '../../footer.php'; 
?>