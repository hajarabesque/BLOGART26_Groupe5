<?php
include '../../header.php';

$results = [];
$keyword_typed = "";

if (isset($_POST['keyword']) && !empty(trim($_POST['keyword']))) {
    $keyword_typed = htmlspecialchars($_POST['keyword']);
    $search_term = "%$keyword_typed%";
    
    // On simplifie la requête pour éviter l'erreur "Column not found"
    // On cherche dans le titre et le chapeau (colonnes confirmées)
    $sql = "SELECT * FROM ARTICLE 
            WHERE libTitrArt LIKE ? 
            OR libChapoArt LIKE ?";
            
    $query = $db->prepare($sql);

    // On exécute avec 2 paramètres car on a 2 "?"
    $query->execute([$search_term, $search_term]);
    
    // IMPORTANT : On récupère les données dans la variable $results
    $results = $query->fetchAll();
} // Fermeture du IF
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

<?php include '../../footer.php'; ?>