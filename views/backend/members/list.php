<?php
/**
 * ==========================================================
 * 1. PRÉPARATION DES DONNÉES (BACKEND)
 * ==========================================================
 */
include '../../../header.php'; // Inclut la config et la connexion BDD

/**
 * LA REQUÊTE AVEC JOINTURE (INNER JOIN) :
 * On ne se contente pas de la table MEMBRE. On va chercher le 'libStat' 
 * dans la table STATUT pour que l'humain puisse lire "Admin" ou "Membre" 
 * au lieu d'un simple chiffre ID.
 */

$membres = sql_select("MEMBRE INNER JOIN STATUT ON MEMBRE.numStat = STATUT.numStat", "MEMBRE.*, STATUT.libStat");
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="display-4">Gestion des Membres</h1>
            <hr style="border: 2px solid black;"> 
            
            <table class="table table-hover mt-4 shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Pseudo</th>
                        <th>eMail</th>
                        <th>RGPD</th>
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
            <td><strong><?php echo $membre['pseudoMemb']; ?></strong></td>
            <td><?php echo $membre['eMailMemb']; ?></td>
            
            <td>
                <span class="badge <?php echo ($membre['accordMemb'] == 1) ? 'bg-success' : 'bg-secondary'; ?>">
                    <?php echo ($membre['accordMemb'] == 1) ? 'Oui' : 'Non'; ?>
                </span>
            </td>
            
            <td><?php echo $membre['libStat']; ?></td>
            
            <td class="text-center">
                
                <a href="edit.php?numMemb=<?php echo $membre['numMemb']; ?>" 
                   class="btn btn-outline-warning btn-sm mb-1 d-block">Modifier</a>
                
                /**
                 * SÉCURITÉ DE SUPPRESSION :
                 * On vérifie si le membre est un Administrateur (numStat == 1).
                 * Si oui, on désactive le bouton pour éviter de supprimer le seul accès admin !
                 */
                <?php if ($membre['numStat'] == 1): ?>
                    <button class="btn btn-outline-secondary btn-sm d-block w-100" disabled title="Impossible de supprimer un administrateur">Supprimer</button>
                <?php else: ?>
                    <a href="delete.php?numMemb=<?php echo $membre['numMemb']; ?>" 
                       class="btn btn-outline-danger btn-sm d-block">Supprimer</a>
                <?php endif; ?>

            </td>
        </tr>
    <?php } ?>
</tbody>
            </table>
            
            <div class="mt-4 mb-5">
                <a href="create.php" class="btn btn-success">Ajouter un nouveau membre</a>
            </div>
        </div>
    </div>
</div>

<?php
include '../../../footer.php';
?>