<?php
session_start();
if(!isset($_SESSION["connected_user"])){
	$url_redirect = "index.php";
	header("Location: $url_redirect");
}
?>

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Fiche client</title>
  <link rel="stylesheet" type="text/css" media="all"  href="../css/style.css" />
</head>
<body>
    <header>
	<h1>Fiches client</h1>
	<div style="float:right;">
        <form method="POST" action="../controller/controller.php">
            <input type="hidden" name="action" value="disconnect">
            <button class="buttonDeconnexion">Déconnexion</button>
        </form>
	</div>

	<div style="float:right;">
	<form action="accueil.php" method="get">
		<input class="buttonStyle" type="submit" value="Revenir a l'acceuil">
	</form>
	</div>

	<h2 style="text-align:left;">Employe : <?php echo $_SESSION['connected_user']['nom']?> <?php echo $_SESSION['connected_user']['prenom']?></h2>
    </header>

    <section>
	<article>
	  <div class="ficheclient">	
            <table>
              <tr><th>Client</th><th>Numero Compte</th><th>Solde compte</th><th>Virement</th></tr>
	      <?php
		
              foreach ($_SESSION['listeClients'] as $cle => $client) {
                echo '<tr>';
                echo '<td>'.$client['nom'].' '.$client['prenom'].'</td>';
		echo '<td>'.$client['numero_compte'].'</td>';
		echo '<td>'.$client['solde_compte'].'</td>';
		echo '<td> <form action="../controller/controller.php" method="post"><input type="hidden" name="action" value="virement"><input type="hidden" name="numcompte" value="'.$client['numero_compte'].'"><input type="hidden" name="nom" value="'.$client['prenom'].' '.$client['nom'].'"><input class="buttonStyle" type="submit" value="Effectuer un virement pour ce client"></form></td>';
		echo '</tr>';
              }
               ?>
	    </table>
	  </div>
	</article>
    </section>
</body>
</html>

