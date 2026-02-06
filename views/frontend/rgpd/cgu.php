<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/BLOGART26/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/BLOGART26/functions/ctrlSaisies.php';
?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center">Conditions Générales d'Utilisation (CGU)</h1>
            <p class="text-muted text-center">En vigueur au <?php echo date('d/m/Y'); ?></p>
            <hr>

            <h3>1. Présentation du site</h3>
            <p>
                Le site <strong>BlogArt 2026</strong> a pour objet la diffusion d'articles culturels et la possibilité pour les internautes de poster des commentaires. 
                L'accès au site et son utilisation impliquent l'acceptation sans réserve des présentes CGU.
            </p>

            <h3>2. Responsabilité de l'Éditeur</h3>
            <p>
                L'éditeur s'efforce de fournir des informations précises. Toutefois, il ne peut être tenu responsable des omissions ou des carences dans la mise à jour des informations.
                Le site est normalement accessible à tout moment, sauf interruption pour maintenance.
            </p>

            <h3>3. Espace Commentaires et Modération</h3>
            <p>
                Les utilisateurs peuvent déposer des commentaires sur les articles. 
                <strong>Important :</strong> Conformément à la Loi du 29 juillet 1881, les propos injurieux, diffamatoires, racistes ou violents sont strictement interdits.
            </p>
            <ul>
                <li>L'éditeur se réserve le droit de supprimer tout commentaire ne respectant pas ces règles sans mise en demeure préalable.</li>
                <li>La responsabilité de l'auteur du message peut être engagée en cas de propos illégaux.</li>
            </ul>

            <h3>4. Propriété intellectuelle</h3>
            <p>
                L'ensemble des contenus (textes, images, structure du blog) est protégé par le droit d'auteur. 
                Toute reproduction, représentation ou modification, totale ou partielle, sans l'accord écrit de l'auteur est interdite et constitue une contrefaçon.
            </p>

            <h3>5. Protection des données personnelles (RGPD)</h3>
            <p>
                Conformément au Règlement Général sur la Protection des Données (RGPD), les informations collectées (pseudos, emails dans les formulaires) sont nécessaires au fonctionnement du service de commentaires.
            </p>
            <ul>
                <li>Vous disposez d'un droit d'accès, de rectification et de suppression de vos données.</li>
                <li>Pour exercer ce droit, contactez l'administrateur via la page de contact.</li>
                <li>Le consentement est recueilli de manière claire lors du dépôt d'un commentaire.</li>
            </ul>

            <h3>6. Cookies</h3>
            <p>
                La navigation sur le site peut provoquer l'installation de cookies sur l'ordinateur de l'utilisateur. Un cookie est un fichier de petite taille qui enregistre des informations relatives à la navigation mais ne permet pas l'identification de l'utilisateur.
            </p>

            <h3>7. Droit applicable</h3>
            <p>
                Tout litige en relation avec l'utilisation du site BlogArt 2026 est soumis au droit français.
            </p>
            
            <div class="mt-5">
                <a href="index.php" class="btn btn-primary">Retour à l'accueil</a>
            </div>
        </div>
    </div>
</div>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/BLOGART26/views/footer.php';
?>