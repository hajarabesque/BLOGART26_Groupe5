<?php
include '../../../header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Création nouveau Mot Clé</h1>
        </div>
        <div class="col-md-12">
            <form action="<?php echo ROOT_URL . '/api/keywords/create.php' ?>" method="post">
                <div class="form-group">
                    <label for="libMotCle">Libellé du mot-clé</label>
                    <input id="libMotCle" name="libMotCle" class="form-control" type="text" autofocus="autofocus" required />
                </div>
                <br />
                <div class="form-group mt-2">
                    <a href="list.php" class="btn btn-primary">List</a>
                    <button type="submit" class="btn btn-success">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>