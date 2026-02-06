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

<footer class="footer">
    <div class="footer-container">
        
        <div class="footer-logo-section">
            <img src="/src/images/logo2.png" alt="Logo" class="footer-logo">
        </div>

        <div class="footer-socials">
            <h3>Nous suivre</h3>
            <div class="social-icons">
                <a href="#" class="icon-link" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" class="icon-link" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="icon-link" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
            </div>
        </div>

        <div class="footer-legal">
            <ul>
                <li><a href="/mentions-legales.php">Mentions légales</a></li>
                <li><a href="/cgu.php">Conditions d'utilisation</a></li>
                <li><a href="/views/frontend/rgpd/rgpd.php">Politique de confidentialité & Cookies</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2026 BlogArt. Tous droits réservés.</p>
    </div>
</footer>

<div class="modal fade" id="cookieModal" tabindex="-1" aria-labelledby="cookieModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cookieModalLabel">🍪 Consentement aux Cookies</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <p>Nous utilisons des cookies pour améliorer votre expérience sur notre site. Ces cookies nous permettent de :</p>
                <ul>
                    <li>Mémoriser votre préférence</li>
                    <li>Améliorer la performance du site</li>
                    <li>Vous proposer du contenu personnalisé</li>
                </ul>
                <p><a href="/views/frontend/rgpd/rgpd.php">Lire notre politique de confidentialité</a></p>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="rejectCookies">Refuser</button>
                <button type="button" class="btn btn-primary" id="acceptCookies">Accepter</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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