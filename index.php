<?php
// ==========================================================
// 1. PRÉPARATION DES DONNÉES (LOGIQUE PHP)
// ==========================================================

// Inclusion du fichier header.php qui contient la configuration et la connexion PDO à la base de données via $db
require_once 'header.php';

/**
 * RÉCUPÉRATION DES ARTICLES DEPUIS LA BDD :
 * - SELECT * : on récupère toutes les colonnes.
 * - FROM ARTICLE : dans la table des articles.
 * - ORDER BY dtCreaArt DESC : on trie par date de création décroissante (du plus récent au plus ancien).
 * - LIMIT 4 : on ne récupère que les 4 derniers articles pour l'affichage en page d'accueil.
 * - fetchAll() : transforme le résultat SQL en un tableau PHP manipulable.
 */
$articlesIndex = $db->query("SELECT * FROM ARTICLE ORDER BY dtCreaArt DESC LIMIT 4")->fetchAll();

/**
 * RÉPARTITION DES ARTICLES POUR LA MISE EN PAGE :
 * L'objectif est d'isoler le premier article pour le mettre en avant (grosse colonne).
 */

// $principal : On vérifie si un article existe à l'index 0, sinon on met null.
$principal = isset($articlesIndex[0]) ? $articlesIndex[0] : null;

// $secondaires : On récupère tous les articles du tableau SAUF le premier (on commence à l'index 1).
$secondaires = array_slice($articlesIndex, 1);
?>

<!-- Début du contenu principal de la page -->
<main id="page-top">
    
    <!-- SECTION HERO : Le bandeau avec le carrousel d'images -->
    <header class="hero">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- Images du diaporama (statiques ici) -->
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
        <!-- Superposition sombre pour rendre le texte plus lisible par-dessus les images -->
        <div class="carousel-overlay"></div>
        <!-- Titre principal de la page d'accueil -->
        <div class="title-section">
            <div class="container px-4 px-lg-5">
                <p class="subtitle">Bordeaux à travers</p>
                <h1>LA MUSIQUE<span>Moderne</span></h1>
            </div>
        </div>
    </header>

    <!-- SECTION PRÉSENTATION : Styles de musique -->
    <section class="page-section styles-musique" id="services">
        <div class="container px-4 px-lg-5">
            <h2 class="text-center mt-0 titre-principal">STYLES DE MUSIQUE</h2>
            <div class="conteneur-flex mt-5">
                <!-- Texte décoratif à gauche -->
                <div class="bloc-texte texte-gauche">
                    <p>Ici, pas juste de la musique : des trajectoires.</p>
                </div>
                <!-- Visuel central (Télévisions et ondes sonores) -->
                <div class="centre-visuel">
                    <img src="src/images/tv2.png" alt="TVs" class="image-centrale">
                    <img src="src/images/onde.png" alt="Onde" class="onde-hero">
                </div>
                <!-- Texte décoratif à droite -->
                <div class="bloc-texte texte-droite">
                    <p>Ce qui bouge, ici.<br>Des voix nouvelles.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION ACTUALITÉS : Affichage dynamique des articles de la BDD -->
    <section class="page-section bg-light py-5  actualites" id="portfolio">
        <div class="container px-5 px-lg-5">
            <h2 class="text-center mt-0 titre-section">Actualités</h2>
            <div class="row g-5 mt-5">
                
                <!-- COLONNE GAUCHE (7/12) : L'article principal (le plus récent) -->
                <div class="col-lg-7"> 
                    <?php if ($principal): // On vérifie qu'il y a au moins un article en BDD ?>
                        <a href="/views/frontend/articles/article1.php?id=<?= $principal['numArt'] ?>" class="text-decoration-none text-dark card-article-principal">
                            <!-- Image de l'article chargée dynamiquement via le chemin de l'upload -->
                            <div class="carre-bleu-grand shadow-sm mb-3"
                                 style="background-image: url('<?php echo ROOT_URL . '/src/uploads/' . htmlspecialchars($principal['urlPhotArt']); ?>'); background-size: cover; background-position: center; height: 400px; border-radius: 8px;">
                            </div>
                            
                            <!-- Titre de l'article (sécurisé avec htmlspecialchars pour éviter les failles XSS) -->
                            <h3 class="h3 mb-2 fw-bold text-primary-hover"><?= htmlspecialchars($principal['libTitrArt'] ?? '') ?></h3>
                            
                            <!-- Chapô (introduction) de l'article : coupé à 150 caractères pour garder une mise en page propre -->
                            <p class="text-muted mb-3">
                                <?= mb_strimwidth(htmlspecialchars($principal['libChapoArt'] ?? ''), 0, 150, "...") ?>
                            </p>
                        </a>

                        <!-- Informations de date et bouton d'action -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="text-secondary small">Publié le <?= date('d/m/Y', strtotime($principal['dtCreaArt'] ?? 'now')) ?></span>
                            <a href="/views/frontend/events.php" class="btn btn-primary">Voir toutes les actus</a>
                        </div>
                    <?php else: ?>
                        <!-- Si aucun article n'a été trouvé dans la base de données -->
                        <div class="alert alert-info">Aucun article principal à afficher.</div>
                    <?php endif; ?>
                </div>

                <!-- COLONNE DROITE (5/12) : Liste des 3 articles secondaires -->
                <div class="col-lg-5 d-flex flex-column gap-4">
                    <?php if (!empty($secondaires)): // On vérifie s'il reste d'autres articles ?>
                        <?php foreach ($secondaires as $art) : // Boucle pour afficher chaque article secondaire ?>
                        <a href="/views/frontend/articles/article1.php?id=<?= $art['numArt'] ?>" class="text-decoration-none text-dark card-hover">
                            <div class="d-flex gap-3 align-items-start article-secondaire p-2 shadow-sm rounded bg-white">
                                <!-- Vignette de l'article -->
                                <div class="carre-bleu-petit flex-shrink-0">
                                    <img src="../../src/uploads/<?= htmlspecialchars($art['urlPhotArt']); ?>" alt="Photo de l'article" class="img-fluid" style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px;">
                                </div>
                                <!-- Texte résumé de l'article -->
                                <div>
                                    <h4 class="h6 fw-bold mb-1"><?= htmlspecialchars($art['libTitrArt'] ?? '') ?></h4>
                                    <p class="small text-muted mb-0" style="font-size: 0.8rem;">
                                        <!-- Texte coupé à 70 caractères ici car la colonne est plus étroite -->
                                        <?= mb_strimwidth(htmlspecialchars($art['libChapoArt'] ?? ''), 0, 70, "...") ?>
                                    </p>
                                </div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Si le tableau $secondaires est vide -->
                        <p class="text-muted">Pas d'autres actualités.</p>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>









<section class="discover">


           <img src="/src/images/Dark Blue Paper Texture Textured Background Wallpaper Image For Free Download - Pngtree.png"
           alt="" class="deco deco-paper-left">


           <img src="/src/images/red-paper.png"
           alt=""   class="deco deco-red-diagonal"/>


           <img src="/src/images/star.png" alt="" class="deco deco-star star-top-left">
           <img src="/src/images/star.png" alt="" class="deco deco-star star-top-right">
           <img src="/src/images/star.png" alt="" class="deco deco-star star-bottom-right">


           <div class="lace-strip">
           <img src="/src/images/red.png" alt="">
           <img src="/src/images/red.png" alt="">
           <img src="/src/images/red.png" alt="">
           </div>


           <div class="deco deco-lace-bottom">
           <img src="/src/images/redbas.png" alt="">
           <img src="/src/images/redbas.png" alt="">
           <img src="/src/images/redbas.png" alt="">
           </div>
          
           <div class="discover-title">
              
               <img src="src/images/titre.png"
               alt="" class="deco deco-title-bg">
              
               <h1 class="title">DÉCOUVRIR DES ARTISTES</h1>
               </div>


           <div class="carousel-wrapper">
               <div class="carousel">


                   <div class="vinyl-container">
                       <div class="vinyl left" data-index="0" data-audio="/src/Audio/j999de.mp3">
                           <img src="/src/images/tht.png" alt="j999de">
                           <p>j999de</p>
                       </div>


                   <div class="vinyl center" data-index="1" data-audio="/src/Audio/Web.mp3">
                       <img src="/src/images/b.png" alt="Thats_web">
                           <p>Thats_web</p>
                       </div>


                   <div class="vinyl right" data-index="2" data-audio="/src/Audio/Tvrzan.mp3">
                       <img src="/src/images/pleease.png" alt="TVRZAN">
                           <p>TVRZAN</p>
                       </div>
                   </div>


                  
                   <div class="controls">
                      
                       <button class="arrow arrow-left" aria-label="Artiste précédent">◀</button>
                           <div class="player-ui">


                       <button class="cassette-btn" aria-label="Lecture musique">
                           <img src="/src/images/cassette.png" alt="Lire la musique">
                       </button>


            

                       </div>


                       <button class="arrow arrow-right" aria-label="Artiste suivant">▶</button>
                   </div>


               </div>
           </div>


           <audio id="player"></audio>
       </section>


<script src="src/js/script.js"></script>

</main>


<?php 
// Inclusion du pied de page (footer) qui ferme les balises </body> et </html>
require_once 'footer.php'; 
?>