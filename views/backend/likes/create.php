<?php
include '../../../header.php';
$articles = sql_select("ARTICLE", "numArt, libTitrArt");
$likes = sql_select(
    "likeart 
    INNER JOIN article ON likeart.numArt = article.numArt 
    INNER JOIN membre ON likeart.numMemb = membre.numMemb",
    "likeart.numArt, likeart.numMemb, article.libTitrArt, membre.pseudoMemb, likeart.LikeA"
);
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Création nouveau Like</h1>
        </div>
        <div class="col-md-12">
            <form action="<?php echo ROOT_URL . '/api/likes/create.php' ?>" method="post">
                <div class="form-group">
                    <label for="numMemb">Pseudo du membre</label>
                    <select id="numMemb" name="numMemb" class="form-control" required>
                        <option value="">Sélectionnez un membre</option>
                        <?php foreach ($likes as $like): ?>
                            <option value="<?php echo $like['numMemb']; ?>"><?php echo $like['pseudoMemb']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <hr>
                    <div class="form-group">
                    <label for="numMemb">Titre de l'article</label>
                    <label for="numArt"><Article></Article></label>
                    <select id="numArt" name="numArt" class="form-control" required>
                        <option value="">Sélectionnez un article</option>
                        <?php foreach ($articles as $article): ?>
                            <option value="<?php echo $article['numArt']; ?>"><?php echo $article['libTitrArt']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <br />
                <div class="form-group  mt-2">
                    <a href="list.php" class="btn btn-primary">List</a>
                    <button type="submit" class="btn btn-success">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>