<?php
include '../../../header.php';

if (isset($_GET['id'])) {
	$numStat = intval($_GET['id']);
	$stat = sql_select('STATUT', '*', "numStat = $numStat");
	if (!empty($stat)) {
		$stat = $stat[0];
		$numStatVal = $stat['numStat'];
		$dtCreaStat = $stat['dtCreaStat'];
		$libStat = $stat['libStat'];
	} else {
		$numStatVal = '';
		$dtCreaStat = '';
		$libStat = '';
	}
} else {
	$numStat = '';
	$numStatVal = '';
	$dtCreaStat = '';
	$libStat = '';
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Modification Statut</h1>
        </div>
        <div class="col-md-12">
            <form action="<?php echo ROOT_URL . '/api/statuts/update.php' ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="numStat" value="<?php echo htmlspecialchars($numStat); ?>" />
                <div class="form-group">
                    <label for="libStat">Libellé du statut</label>
                    <input id="libStat" name="libStat" class="form-control" type="text" value="<?php echo htmlspecialchars($libStat); ?>" autofocus="autofocus" required />
                </div>

				<div class="form-group mt-3">
					<a href="list.php" class="btn btn-outline-primary">List</a>
					<button type="submit" class="btn btn-warning">Confirmer Edit ?</button>
				</div>
			</form>
		</div>
	</div>
</div>

