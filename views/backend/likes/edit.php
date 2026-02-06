<?php
require_once '../../../header.php';
require_once '../../../config.php';

// 1. Récupération des identifiants depuis l'URL
$numArt = $_GET['numArt'] ?? null;
$numMemb = $_GET['numMemb'] ?? null;

if (!$numArt || !$numMemb) {
    header('Location: list.php');
    exit();
}

// 2. Récupération des infos complètes (Jointure pour avoir le pseudo et le titre)

$likes = sql_select(
    "likeart 
    INNER JOIN article ON likeart.numArt = article.numArt 
    INNER JOIN membre ON likeart.numMemb = membre.numMemb",
    "likeart.numArt, likeart.numMemb, article.libTitrArt, membre.pseudoMemb, likeart.LikeA"
);

// Si le like n'existe pas, on retourne à la liste
$likeData = array_filter($likes, function($like) use ($numArt, $numMemb) {
    return $like['numArt'] == $numArt && $like['numMemb'] == $numMemb;
});
if (empty($likeData)) {
    header('Location: list.php');
    exit();
}
$like = array_shift($likeData); // On prend le premier résultat (il n'y en aura qu'un)
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Modifier le Like</h1>
        </div>
        <div class="col-md-12">
            <form action="<?php echo ROOT_URL . '/api/likes/update.php' ?>" method="post">
                <input type="hidden" name="numArt" value="<?php echo $like['numArt']; ?>">
                <input type="hidden" name="numMemb" value="<?php echo $like['numMemb']; ?>">

                <div class="form-group">
                    <label for="pseudoMemb">Pseudo du membre</label>
                    <input id="pseudoMemb" name="pseudoMemb" class="form-control" type="text" value="<?php echo $like['pseudoMemb']; ?>" readonly />
                </div>
                <div class="form-group">
                    <label for="libTitrArt">Titre de l'article</label>
                    <input id="libTitrArt" name="libTitrArt" class="form-control" type="text" value="<?php echo $like['libTitrArt']; ?>" readonly />
                </div>
                <div class="form-group">
                    <label for="LikeA">Statut du Like</label>
                    <select id="LikeA" name="LikeA" class="form-control" required>
                        <option value="1" <?php echo ($like['LikeA'] == 1) ? 'selected' : ''; ?>>Liké</option>
                        <option value="0" <?php echo ($like['LikeA'] == 0) ? 'selected' : ''; ?>>Non Liké</option>
                    </select>
                </div>
                <br />
                <div class="form-group mt-2">
                    <a href="list.php" class="btn btn-primary">List</a>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>