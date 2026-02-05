<?php
// On remonte de 2 niveaux pour atteindre la racine (ajuste si besoin)
include '../../header.php';

$results = [];
$keyword_typed = "";

if (isset($_POST['keyword']) && !empty(trim($_POST['keyword']))) {
    $keyword_typed = htmlspecialchars($_POST['keyword']);
    
    // Recherche simple (on utilise la variable $db qui vient du header)
    $search_term = "%$keyword_typed%";
    $query = $db->prepare("SELECT * FROM ARTICLE WHERE libTitreArt LIKE ? OR chapoArt LIKE ?");
    $query->execute([$search_term, $search_term]);
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
            <form action="search.php" method="post" class="search-box p-2 rounded shadow">
                <input type="text" name="keyword" class="form-control border-0" 
                       placeholder="Rechercher un article..." 
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
                        <div class="card mb-3 search-card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $art['libTitreArt']; ?></h5>
                                <p class="card-text text-muted"><?php echo $art['chapoArt']; ?></p>
                                <a href="article.php?numArt=<?php echo $art['numArt']; ?>" class="btn btn-sm btn-outline-danger">Lire la suite</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center opacity-50">Aucun résultat trouvé.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../../footer.php'; ?>