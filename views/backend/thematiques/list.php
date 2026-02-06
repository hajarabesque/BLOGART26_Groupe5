<?php
/**
 * ==========================================================
 * 1. LOGIQUE DE RÉCUPÉRATION (BACKEND)
 * ==========================================================
 */
// On inclut le header (qui contient la connexion $db et config.php)
require_once '../../../header.php'; 

/**
 * RÉCUPÉRATION DES DONNÉES :
 * On utilise ta fonction sql_select pour récupérer toutes les colonnes (*)
 * de la table THEMATIQUE.
 */
$thematiques = sql_select("THEMATIQUE", "*");
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Gestion des Thématiques</h1>
            
            <table class="table table-striped table-hover shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nom de la thématique</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
            
                    <?php 
                    /**
                     * BOUCLE FOREACH :
                     * PHP parcourt chaque thématique trouvée en base de données.
                     * Pour chaque ligne, on crée une rangée <tr> dans le tableau.
                     */
                    
                    foreach($thematiques as $thematique){ ?>
                        <tr>
                            <td><?php echo($thematique['numThem']); ?></td>
                            
                            <td><strong><?php echo htmlspecialchars($thematique['libThem']); ?></strong></td>
                            
                            <td class="text-center">
                            
                                <a href="edit.php?numThem=<?php echo($thematique['numThem']); ?>" class="btn btn-primary btn-sm">Modifier</a>
                                <a href="delete.php?numThem=<?php echo($thematique['numThem']); ?>" class="btn btn-danger btn-sm">Supprimer</a>
                            </td>
                        </tr>
                    <?php }  /**
                                 * LIENS DYNAMIQUES :
                                 * On injecte l'ID (numThem) dans l'URL pour que les pages 
                                 * de destination sachent quelle thématique traiter.
                                 */ ?>
                    
                </tbody>
            </table>

            <div class="mt-4">
                <a href="create.php" class="btn btn-success px-4">Ajouter une thématique</a>
            </div>
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