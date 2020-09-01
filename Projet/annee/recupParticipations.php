<?php

if(isset($_GET['annee'])){

?>


		<?php include('../includes/menu.html'); ?>

		<a href="javascript:history.back()">Page Précédente</a>

		<h1>Participations du Tour de France <?php echo $_GET['annee']; ?></h1>

		<?php include('recupParticipationsRequete.php'); ?>


	</body>
	<?php include('../includes/footer.html'); ?>
</html>
<?php
}
?>
