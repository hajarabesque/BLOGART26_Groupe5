<?php
include '../../../header.php';


if (isset($_GET['numMotCle'])) {
	$numStat = intval($_GET['numMotCle']);
	$stat = sql_select('MOTCLE', '*', "numMotCle = $numStat");
	if (!empty($stat)) {
		$stat = $stat[0];
		$numStatVal = $stat['numMotCle'];
		$libStat = $stat['libMotCle'];
	} else {
		$numStatVal = '';
		$libStat = '';
	}
} else {
	$numStat = '';
	$numStatVal = '';
	$libStat = '';
}
?>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1 class="mt-4">Modification Mot Clés</h1>
			<hr />
		</div>
		<div class="col-md-8">
			<form action="<?php echo ROOT_URL . '/api/keywords/update.php' ?>" method="post">
				<input type="hidden" name="numMotCle" value="<?php echo htmlspecialchars($numStat); ?>" />

				<div class="form-group mb-3">
					<label for="libMotCle">Libellé</label>
					<input id="libMotCle" name="libMotCle" class="form-control" type="text" value="<?php echo htmlspecialchars($libStat); ?>" />
				</div>

				<div class="form-group mt-3">
					<a href="list.php" class="btn btn-outline-primary">List</a>
					<button type="submit" class="btn btn-warning">Confirmer Edit ?</button>
				</div>
			</form>
		</div>
	</div>
</div>