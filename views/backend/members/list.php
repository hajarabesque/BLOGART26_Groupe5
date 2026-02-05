<?php
include '../../../header.php'; // Inclut la config et la connexion BDD

// Requête SQL pour récupérer les membres ET le nom de leur statut (via une jointure)
// On joint la table MEMBRE et la table STATUT pour afficher "Administrateur" au lieu de "1"
$membres = sql_select("MEMBRE INNER JOIN STATUT ON MEMBRE.numStat = STATUT.numStat", "MEMBRE.*, STATUT.libStat");
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="display-4">Membres</h1>
            <hr style="border: 2px solid black;"> <!-- Ligne de séparation sous le titre -->
            
            <table class="table table-hover mt-4">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Pseudo</th>
                        <th>eMail</th>
                        <th>Accord RGPD</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
               <tbody>
    <?php foreach($membres as $membre) { ?>
        <tr>
            <td><?php echo $membre['numMemb']; ?></td>
            <td><?php echo $membre['prenomMemb']; ?></td>
            <td><?php echo $membre['nomMemb']; ?></td>
            <td><?php echo $membre['pseudoMemb']; ?></td>
            <td><?php echo $membre['eMailMemb']; ?></td>
            <td><?php echo ($membre['accordMemb'] == 1) ? 'Oui' : 'Non'; ?></td>
            <td><?php echo $membre['libStat']; ?></td>
            
            <!-- ICI : Une seule cellule d'actions avec seulement 2 boutons -->
            <td class="text-center" style="width: 100px;">
                
                <!-- 1. UN SEUL bouton Modifier -->
                <a href="edit.php?numMemb=<?php echo $membre['numMemb']; ?>" 
                   class="btn btn-outline-warning btn-sm mb-1 d-block">Edit</a>
                
                <!-- 2. UN SEUL bouton Supprimer (avec vérification Admin) -->
                <?php if ($membre['numStat'] == 1): ?>
                    <!-- Si admin : bouton gris et bloqué -->
                    <button class="btn btn-outline-secondary btn-sm d-block w-100" disabled>Delete</button>
                <?php else: ?>
                    <!-- Sinon : bouton rouge normal -->
                    <a href="delete.php?numMemb=<?php echo $membre['numMemb']; ?>" 
                       class="btn btn-outline-danger btn-sm d-block">Delete</a>
                <?php endif; ?>

            </td>
        </tr>
    <?php } ?>
</tbody>
            </table>
            
            <div class="mt-4">
                <a href="create.php" class="btn btn-success">Créer un nouveau membre</a>
            </div>
        </div>
    </div>
</div>

<?php
include '../../../footer.php';
?>