<?php 
/**
 * ==========================================================
 * 1. INCLUSION DU HEADER
 * ==========================================================
 * Le header contient la configuration (ROOT_URL), la connexion 
 * à la base de données ($db) et les fonctions globales.
 */
include '../../../header.php'; 
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <h1>Création nouvelle Thématique</h1>

            /**
             * FORMULAIRE DE SAISIE :
             * - action : Vers quel fichier envoyer les données (l'API de traitement).
             * - method="post" : Pour envoyer les données de manière sécurisée (non visible dans l'URL).
             */
            <form action="/api/thematiques/create.php" method="post" class="mt-4">
                
                <div class="form-group">
                    <label for="libThem">Nom de la thématique</label>
                    /**
                     * autofocus : Le curseur se place directement ici au chargement de la page.
                     * required : Empêche l'envoi du formulaire si le champ est vide.
                     */
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



<?php 
/**
 * ==========================================================
 * 2. INCLUSION DU FOOTER
 * ==========================================================
 * Ferme les balises HTML et inclut les scripts Bootstrap.
 */
include '../../../footer.php'; 
?>