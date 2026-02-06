<?php
/**
 * ==========================================================
 * 1. CHARGEMENT DES DONNÉES EXISTANTES (BACKEND)
 * ==========================================================
 */
session_start();
include '../../../header.php';

// Gestion des erreurs flash stockées en session par l'API
$err = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);

$membre = null;
$statuts = [];

if (isset($_GET['numMemb'])) {
    /**
     * SÉCURISATION : 
     * Le cast (int) transforme toute saisie malveillante en nombre,
     * bloquant ainsi les injections SQL par l'URL.
     */
    $numMemb = (int)$_GET['numMemb']; 
    
    // On récupère les infos actuelles du membre
    $resultat = sql_select("MEMBRE", "*", "numMemb = $numMemb");
    
    if (!empty($resultat)) {
        $membre = $resultat[0];
    }
    
    // On charge la liste des statuts pour peupler le menu déroulant
    $statuts = sql_select("STATUT", "*");
}
?>

<div class="container mt-5">
    <h1>Modification Membre</h1>
    <hr style="border: 2px solid black;">

    <div class="col-md-8 offset-md-2 mt-4">
        <form action="<?php echo ROOT_URL; ?>/api/members/update.php" method="post">
            <?php
            /**
             * L'ID CACHÉ : 
             * Indispensable pour la clause WHERE de ton UPDATE SQL.
             */
            ?>
            <input type="hidden" name="numMemb" value="<?php echo $membre['numMemb']; ?>">

            <div class="form-group mb-3">
                <label>Pseudo (Identifiant non modifiable)</label>
                <input type="text" class="form-control" value="<?php echo $membre['pseudoMemb']; ?>" disabled>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Prénom</label>
                    <input type="text" name="prenomMemb" class="form-control" value="<?php echo $membre['prenomMemb']; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Nom</label>
                    <input type="text" name="nomMemb" class="form-control" value="<?php echo $membre['nomMemb']; ?>" required>
                </div>
            </div>

            <div class="form-group mb-3">
                <label>eMail</label>
                <input type="email" name="eMailMemb" class="form-control" value="<?php echo $membre['eMailMemb']; ?>" required>
                <?php if(isset($err['email'])): ?><span class="text-danger small"><?php echo $err['email']; ?></span><?php endif; ?>
            </div>
                <?php
            /**
             * GESTION DU MOT DE PASSE :
             * Contrairement à la création, ici le mot de passe est OPTIONNEL.
             * L'API ne doit le hacher et le mettre à jour que s'il n'est pas vide.
             */
                 ?>
            <div class="form-group mb-3">
                <label>Nouveau Password (laisser vide pour conserver l'actuel)</label>
                <div class="input-group">
                    <input type="password" id="p1" name="passMemb" class="form-control">
                    <div class="input-group-text">
                        <input type="checkbox" onclick="toggle('p1')"> <small class="ms-1">Afficher</small>
                    </div>
                </div>
                <?php if(isset($err['pass'])): ?><span class="text-danger small"><?php echo $err['pass']; ?></span><?php endif; ?>
            </div>

            <div class="form-group mb-4">
                <label>Rang / Statut :</label>
                <select name="numStat" class="form-control">
                    <?php foreach($statuts as $statut): 
                        /**
                         * L'attribut 'selected' permet de pré-cocher le statut 
                         * actuel du membre dans la liste.
                         */
                        ?>
                        <option value="<?php echo $statut['numStat']; ?>" <?php echo ($membre['numStat'] == $statut['numStat']) ? 'selected' : ''; ?>>
                            <?php echo $statut['libStat']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group mb-5">
                <a href="list.php" class="btn btn-outline-primary px-4">Retour</a>
                <button type="submit" class="btn btn-warning px-4 ms-2">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</div>
<?php
/**
 * JS : Permet de voir les caractères du mot de passe en changeant le type de l'input
 */
?>
<script>
function toggle(id) {
    var x = document.getElementById(id);
    x.type = (x.type === "password") ? "text" : "password";
}
</script>