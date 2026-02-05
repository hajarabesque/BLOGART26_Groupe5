<?php
/**
 * ==========================================================
 * 1. INITIALISATION (BACKEND)
 * ==========================================================
 */
// Le header contient la connexion à la base de données et la configuration
include '../../../header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Création nouveau Mot Clé</h1>
        </div>
        <div class="col-md-12">
            /**
             * LE FORMULAIRE :
             * - action : Envoie les données vers l'API qui fera le INSERT SQL.
             * - method="post" : Sécurise l'envoi des données dans le corps de la requête.
             */
            <form action="<?php echo ROOT_URL . '/api/keywords/create.php' ?>" method="post">
                
                <div class="form-group">
                    <label for="libMotCle">Libellé du mot-clé</label>
                    /**
                     * autofocus : Place le curseur sur ce champ dès l'ouverture.
                     * required : Empêche l'envoi d'un mot-clé vide.
                     */
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



<?php
/**
 * ==========================================================
 * 3. PIED DE PAGE
 * ==========================================================
 */
include '../../../footer.php';
?>