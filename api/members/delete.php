<?php
// On charge la configuration (accès BDD) et les fonctions de nettoyage
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/ctrlSaisies.php';

// On vérifie si la demande de suppression vient bien d'un formulaire (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // On récupère l'identifiant (ID) du membre à supprimer
    // On le nettoie par sécurité avec ctrlSaisies
    $numMemb = ctrlSaisies($_POST['numMemb']);

    // --- GESTION DES CONTRAINTES D'INTÉGRITÉ RÉFÉRENTIELLE (CIR) ---
    // Pourquoi faire ça ? 
    // Dans une base de données, tu ne peux pas supprimer un membre s'il a encore 
    // des "liens" (des likes ou des commentaires). Sinon, ces données deviendraient 
    // des "orphelines" (un commentaire sans auteur).
    // On doit donc supprimer les "enfants" avant de supprimer le "parent".

    // 1. On supprime d'abord tous les "Likes" que ce membre a mis sur des articles
    // Table : LIKEART (Lien entre Membre et Article)
    sql_delete("LIKEART", "numMemb = $numMemb");

    // 2. On supprime ensuite tous les commentaires que ce membre a écrits
    // Table : COMMENT
    sql_delete("COMMENT", "numMemb = $numMemb");

    // 3. Une fois que le membre n'est plus lié à rien, on peut enfin le supprimer
    // Table : MEMBRE
    sql_delete("MEMBRE", "numMemb = $numMemb");

    // Une fois la suppression terminée, on redirige l'administrateur vers la liste
    header('Location: /views/backend/members/list.php');
    exit(); // On arrête le script après la redirection
}