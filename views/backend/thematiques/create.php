<?php include '../../../header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <h1>Création nouvelle Thématique</h1>
            <form action="/api/thematiques/create.php" method="post" class="mt-4">
                <div class="form-group">
                    <label for="libThem">Nom de la thématique</label>
                    <input id="libThem" name="libThem" class="form-control" type="text" autofocus required />
                </div>
                <div class="form-group mt-3">
                    <a href="list.php" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-success">Confirmer la création</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../../footer.php'; ?>