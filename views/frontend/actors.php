<?php 
// Assure-toi que le chemin vers header.php est correct par rapport à l'emplacement de ce fichier
require_once '../../header.php';
?>

<section class="hero-banner" style="height: 300px; background: url('/src/images/♥.jpg') center/cover; position: relative;">
    <div class="hero-overlay" style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);"></div>
    <div class="hero-content">
        </div>
</section>

<div class="container py-5">
    <h1 class="titre-section mb-5 text-uppercase fw-bold">Chroniques d'Albums</h1>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        
        <?php
        // Correction des chemins d'images pour correspondre à ton dossier "src/images/"
        $albums = [
            ["artiste" => "Orelsan", "titre" => "La fuite en avant", "img" => "../../src/images/concert3.png"],
            ["artiste" => "Danny Brown", "titre" => "Stardust", "img" => "../../src/images/concert3.png"],
            ["artiste" => "Kendrick Lamar", "titre" => "GNX", "img" => "../../src/images/concert3.png"],
            ["artiste" => "Tyler, The Creator", "titre" => "CHROMAKOPIA", "img" => "../../src/images/concert3.png"],
            ["artiste" => "SCH", "titre" => "JVLIVS III", "img" => "../../src/images/concert3.png"],
            ["artiste" => "Zola", "titre" => "Diamant du Bled", "img" => "../../src/images/concert3.png"],
            ["artiste" => "Laylow", "titre" => "L'Étrange Histoire", "img" => "../../src/images/concert3.png"],
            ["artiste" => "ASAP Rocky", "titre" => "Don't Be Dumb", "img" => "../../src/images/concert3.png"],
        ];

        foreach ($albums as $album) : ?>
            <div class="col">
                <div class="album-card h-100 border-0">
                    <img src="<?= $album['img']; ?>" class="img-fluid rounded shadow-sm mb-2" alt="<?= $album['artiste']; ?>" style="aspect-ratio: 1/1; object-fit: cover; width: 100%;">
                    <div class="album-info">
                        <small class="text-danger fw-bold text-uppercase"><?= $album['artiste']; ?></small>
                        <h3 class="h6 fw-bold"><?= $album['titre']; ?></h3>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div> 
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php 
// J'ai corrigé ton echo ("Acteur") qui était orphelin
echo "<div class='container mb-5'><p class='text-muted'>Catégorie : Acteurs</p></div>";

require_once '../../footer.php'; 
?>