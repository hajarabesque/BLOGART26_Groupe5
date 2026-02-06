<?php
/**
 * ==========================================================
 * 1. LOGIQUE DE RÉCUPÉRATION (BACKEND)
 * ==========================================================
 */
include '../../../header.php'; // Inclut la connexion BDD ($db) et les fonctions (sql_select)

/**
 * RÉCUPÉRATION DES MOTS-CLÉS :
 * On récupère toutes les lignes de la table MOTCLE.
 * Chaque ligne contiendra 'numMotCle' (l'ID) et 'libMotCle' (le texte).
 */
$statuts = sql_select("MOTCLE", "*");
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Mots Clés</h1>
            
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nom des mot-clés</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                     <?php
                    /**
                     * BOUCLE FOREACH :
                     * On parcourt le tableau de résultats $statuts.
                     * Pour chaque entrée, on génère une ligne de tableau (<tr>).
                     */
                     ?>
                    <?php foreach($statuts as $statut){ ?>
                        <tr>
                            <td><?php echo($statut['numMotCle']); ?></td>
                            
                            <td><?php echo($statut['libMotCle']); ?></td>
                            
                            <td>
                          <?php
                                /**
                                 * BOUTONS D'ACTION :
                                 * On passe l'identifiant 'numMotCle' dans l'URL.
                                 * Cela permet à edit.php et delete.php de savoir 
                                 * précisément quel mot-clé l'utilisateur veut cibler.
                                 */
                              ?>
                                <a href="edit.php?numMotCle=<?php echo($statut['numMotCle']); ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="delete.php?numMotCle=<?php echo($statut['numMotCle']); ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <a href="create.php" class="btn btn-success">Créer un mot-clé</a>
        </div>
    </div>
</div>



<?php
/**
 * ==========================================================
 * 3. FERMETURE
 * ==========================================================
 */
include '../../../footer.php'; // Ferme les balises HTML et inclut Bootstrap JS
?>