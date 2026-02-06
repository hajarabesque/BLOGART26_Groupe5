<?php
/**
 * ==========================================================
 * 1. LOGIQUE PHP : GESTION DE L'ÉTAT DU CONSENTEMENT
 * ==========================================================
 * Le but ici est de déterminer si on doit afficher la fenêtre surgissante (modale).
 * * $_COOKIE est une superglobale PHP qui contient tous les cookies envoyés par le navigateur.
 * isset() vérifie si la clé 'cookie_consent' existe.
 * le "!" (NOT) inverse le résultat : 
 * - Si le cookie n'existe pas -> $showCookieModal devient TRUE.
 * - Si le cookie existe déjà -> $showCookieModal devient FALSE.
 */
$showCookieModal = !isset($_COOKIE['cookie_consent']);
?>

<link rel="stylesheet" href="/src/css/footer.css">

<footer class="main-footer mt-5">

    <div class="container d-flex mp-5 justify-content-center align-items-center">
        
        <!-- Le rond bleu (image de fond appliquée en CSS sur ce div) -->
        <div class="footer-label-center d-flex flex-column align-items-center justify-content-center">
            
            <!-- Logo blanc en haut -->
            <img src="/src/images/logo2.png" alt="Logo" class="footer-logo mb-2">

            <!-- Réseaux Sociaux -->
            <div class="social-box d-flex gap-3 mb-4">
                <a href="#"><i class="bi bi-instagram fs-4"></i></a>
                <a href="#"><i class="bi bi-facebook fs-4"></i></a>
                <a href="#"><i class="bi bi-tiktok fs-4"></i></a>
            </div>

       <div class="footer-links-grid">
    <a href="mentions.php">Mentions légales</a>
    <a href="/views/frontend/rgpd/rgpd.php">Politique RGPD</a>
    <a href="views/frontend/rgpd/cgu.php">CGU</a>
</div>
        </div>
    </div>
</footer>

<!-- Import des icônes Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<script>
    /**
     * ==========================================================
     * 2. LOGIQUE JAVASCRIPT : INTERACTION UTILISATEUR
     * ==========================================================
     */
    
    /* INJECTION PHP DANS JS : 
       Si $showCookieModal est vrai, le code à l'intérieur s'exécute.
       Sinon, le navigateur ne verra même pas ces lignes de code.
    */
    <?php if ($showCookieModal): ?>
    document.addEventListener('DOMContentLoaded', function() {
        // Sélection de l'élément HTML de la modale
        var modalElement = document.getElementById('cookieModal');
        
        // Initialisation de l'objet Modal de Bootstrap
        if (modalElement) {
            var myModal = new bootstrap.Modal(modalElement);
            myModal.show(); // Affichage forcé au chargement de la page
        }
    });
    <?php endif; ?>

    /**
     * FONCTION : Création d'un cookie dans le navigateur
     * @param {string} value - La valeur à stocker ('accepted' ou 'rejected')
     */
    function setCookieConsent(value) {
        var date = new Date();
        // Calcul de la date d'expiration : Date actuelle + 30 jours (en millisecondes)
        // 30 jours * 24h * 60min * 60s * 1000ms
        date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000));
        
        // Construction de la chaîne du cookie :
        // - cookie_consent=... : nom et valeur
        // - expires=... : date de fin de validité
        // - path=/ : le cookie est disponible sur tout le site
        document.cookie = "cookie_consent=" + value + "; expires=" + date.toUTCString() + "; path=/";
        
        // Récupération de l'instance de la modale pour la fermer après l'action
        var modalInstance = bootstrap.Modal.getInstance(document.getElementById('cookieModal'));
        if (modalInstance) {
            modalInstance.hide();
        }
    }

    // ÉCOUTEUR D'ÉVÉNEMENT : Clic sur "Accepter"
    // Le "?." (Optional Chaining) évite une erreur si le bouton n'existe pas dans la page
    document.getElementById('acceptCookies')?.addEventListener('click', function() {
        setCookieConsent('accepted');
        console.log("Cookies acceptés par l'utilisateur.");
    });

    // ÉCOUTEUR D'ÉVÉNEMENT : Clic sur "Refuser"
    document.getElementById('rejectCookies')?.addEventListener('click', function() {
        setCookieConsent('rejected');
        console.log("Cookies refusés par l'utilisateur.");
    });
</script>

</body>
</html>