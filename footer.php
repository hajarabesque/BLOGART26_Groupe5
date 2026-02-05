<?php
/**
 * ==========================================================
 * 1. LOGIQUE PHP : GESTION DES COOKIES
 * ==========================================================
 * On vérifie si l'utilisateur a déjà accepté les cookies.
 * $_COOKIE['cookie_consent'] est une donnée stockée sur l'ordinateur du visiteur.
 * Si elle n'existe pas (!isset), on passe $showCookieModal à TRUE.
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
                <a href="#" class="icon-link"><i class="fab fa-instagram"></i></a>
                <a href="#" class="icon-link"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="icon-link"><i class="fab fa-youtube"></i></a>
            </div>
        </div>

        <div class="footer-legal">
            <ul>
                <li><a href="/mentions-legales.php">Mentions légales</a></li>
                <li><a href="/cgu.php">Conditions d'utilisation</a></li>
                <li><a href="/rgpd.php">Politique de confidentialité & Cookies</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2026 BlogArt. Tous droits réservés.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    /**
     * GESTION DE LA MODALE DE COOKIES
     * Ce script utilise un mélange de PHP et de JavaScript.
     */
    
    /* Si PHP a déterminé que le cookie n'existe pas, on injecte ce bloc JS */
    <?php if ($showCookieModal): ?>
    document.addEventListener('DOMContentLoaded', function() {
        // On récupère l'élément HTML de la modale par son ID
        var modalElement = document.getElementById('cookieModal');
        
        // Si la modale existe bien dans le DOM, on utilise Bootstrap pour l'afficher
        if (modalElement) {
            var myModal = new bootstrap.Modal(modalElement);
            myModal.show(); // Déclenche l'apparition visuelle
        }
    });
    <?php endif; ?>
</script>

</body>
</html>