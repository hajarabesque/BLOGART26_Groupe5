<?php
require_once '../../../header.php';

?>

<?php 
// 1. Inclusion du header (Ajuste le chemin si nécessaire)
require_once 'header.php'; 
?>

<div class="container py-5 mt-5">
    <!-- Utilisation de la classe 'titre-page' pour rester cohérent avec ta page Actu -->
    <h1 class="titre-page mb-5">Politique de Confidentialité (RGPD)</h1>

    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            
            <!-- Section 1 : Introduction -->
            <section class="mb-5">
                <h2 class="h4 fw-bold text-primary">1. Introduction</h2>
                <p>Dans le cadre de l'utilisation du site <strong>Bordeaux Musique Moderne</strong>, nous sommes amenés à collecter et traiter certaines de vos données personnelles. Cette page a pour but de vous informer en toute transparence sur la manière dont nous gérons vos données, conformément au Règlement Général sur la Protection des Données (RGPD).</p>
            </section>

            <!-- Section 2 : Les Cookies (Lien direct avec ton footer) -->
            <section class="mb-5">
                <h2 class="h4 fw-bold text-primary">2. Gestion des Cookies</h2>
                <p>Comme vous l'avez vu lors de votre arrivée sur le site, nous utilisons une fenêtre de consentement pour les cookies.</p>
                <ul>
                    <li><strong>Cookie de consentement :</strong> Ce cookie sert uniquement à mémoriser si vous avez cliqué sur "Accepter" ou "Refuser" dans la bannière en bas de page.</li>
                    <li><strong>Durée de conservation :</strong> Ce choix est mémorisé pendant 30 jours.</li>
                    <li><strong>Finalité :</strong> Éviter de vous afficher la bannière à chaque nouvelle page consultée.</li>
                </ul>
                <p class="small text-muted">Note : Si vous refusez les cookies, aucune donnée de navigation n'est conservée par notre site.</p>
            </section>

            <!-- Section 3 : Collecte des données -->
            <section class="mb-5">
                <h2 class="h4 fw-bold text-primary">3. Données collectées</h2>
                <p>Nous ne collectons que les données strictement nécessaires :</p>
                <ul>
                    <li>Données de navigation (via cookies si acceptés).</li>
                    <li>Informations envoyées via d'éventuels formulaires de contact (Nom, Email).</li>
                </ul>
                <p>Nous ne revendons jamais vos données à des tiers.</p>
            </section>

            <!-- Section 4 : Vos Droits -->
            <section class="mb-5 p-4 bg-light border-start border-primary border-4">
                <h2 class="h4 fw-bold">4. Vos Droits</h2>
                <p>Conformément à la loi "Informatique et Libertés", vous disposez des droits suivants :</p>
                <ul>
                    <li>Droit d'accès et de rectification de vos données.</li>
                    <li>Droit à l'effacement (droit à l'oubli).</li>
                    <li>Droit de retirer votre consentement aux cookies à tout moment en vidant le cache de votre navigateur.</li>
                </ul>
            </section>

            <!-- Section 5 : Contact -->
            <section class="mb-5">
                <h2 class="h4 fw-bold text-primary">5. Contact</h2>
                <p>Pour toute question concernant vos données personnelles ou pour exercer vos droits, vous pouvez contacter le responsable du site à l'adresse suivante :</p>
                <p class="fw-bold">contact@bordeaux-musique-moderne.fr</p>
            </section>

            <div class="text-center mt-5">
                <a href="index.php" class="btn btn-primary">Retour à l'accueil</a>
            </div>
        </div>
    </div>
</div>

<?php 
// 2. Inclusion du footer
require_once 'footer.php'; 
?>


echo ("Ici RGPD");