<?php
// ==========================================================
// 1. LOGIQUE DE TRAITEMENT (BACKEND)
// ==========================================================

// Inclusion du fichier header qui contient la connexion à la base de données ($db)
include '../../header.php';

// Initialisation des variables pour éviter les erreurs "undefined variable" au premier chargement
$results = [];       // Contiendra la liste des articles trouvés
$keyword_typed = ""; // Contiendra le mot-clé tapé par l'utilisateur

/**
 * VÉRIFICATION DE LA SOUMISSION DU FORMULAIRE :
 * - isset($_POST['keyword']) : Est-ce que le formulaire a été envoyé ?
 * - !empty(trim(...)) : Est-ce que le champ n'est pas vide (on enlève les espaces inutiles) ?
 */
if (isset($_POST['keyword']) && !empty(trim($_POST['keyword']))) {
    
    // Nettoyage de la saisie utilisateur pour éviter les failles XSS
    $keyword_typed = htmlspecialchars($_POST['keyword']);
    
    // Préparation du terme pour SQL avec les jokers "%" (ex: "rock" devient "%rock%")
    // Cela permet de trouver le mot n'importe où dans la phrase.
    $search_term = "%$keyword_typed%";
    
    /**
     * PRÉPARATION DE LA REQUÊTE SQL :
     * - On sélectionne tout (*) de la table ARTICLE.
     * - LIKE ? : Permet de comparer une colonne avec notre mot-clé.
     * - OR : On cherche soit dans le titre, soit dans le chapeau.
     */
    $sql = "SELECT * FROM ARTICLE 
            WHERE libTitrArt LIKE ? 
            OR libChapoArt LIKE ?";
            
    // Utilisation d'une requête préparée pour éviter les injections SQL (SÉCURITÉ)
    $query = $db->prepare($sql);

    // Exécution de la requête en passant le mot-clé deux fois (pour les deux "?" du SQL)
    $query->execute([$search_term, $search_term]);
    
    // fetchAll() : Transforme les résultats de la base de données en un tableau PHP exploitable
    $results = $query->fetchAll();
} 
?>

<link rel="stylesheet" href="/src/css/search.css">

<div class="container mt-5 search-page">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1 class="display-4 search-title">RECHERCHE</h1>
            <p class="lead">Entrez un mot-clé pour explorer les articles.</p>
            <div class="red-line"></div>
        </div>

        <div class="col-md-8 offset-md-2 mt-4">
            <form action="search.php" method="post" class="search-box p-2 rounded shadow d-flex">
                <input type="text" name="keyword" class="form-control border-0" 
                       placeholder="Rechercher..." 
                       value="<?php echo $keyword_typed; ?>" required>
                <button type="submit" class="btn btn-danger px-4 ms-2">RECHERCHER</button>
            </form>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-10 offset-md-1">
            
            <?php if (!empty($keyword_typed)): ?>
                <h4 class="mb-4">Résultats pour : <span class="text-danger">"<?php echo $keyword_typed; ?>"</span></h4>
                
                <?php if (count($results) > 0): ?>
                    
                    <?php foreach ($results as $art): ?>
                        <div class="card mb-3 search-card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">
                                    <?php echo htmlspecialchars($art['libTitrArt'] ?? 'Titre non disponible'); ?>
                                </h5>
                                
                                <p class="card-text text-muted">
                                    <?php echo mb_strimwidth(htmlspecialchars($art['libChapoArt'] ?? ''), 0, 160, "..."); ?>
                                </p>
                                
                                <a href="articles/article1.php?id=<?php echo $art['numArt']; ?>" class="btn btn-sm btn-outline-danger text-uppercase">Lire la suite</a>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <p class="text-center opacity-50">Aucun résultat trouvé pour votre recherche.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
// Inclusion du pied de page
include '../../footer.php'; 
?>