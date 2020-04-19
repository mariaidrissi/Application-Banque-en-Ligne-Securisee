<?php
session_start();

if(!isset($_SESSION['expire']) || time() > $_SESSION['expire']){
	session_unset();
	$url_redirect = "deconnexion.php";
	header("Location: $url_redirect");
	exit();
}

if(!isset($_SESSION["connected_user"]) || $_SESSION['connected_user']['profil_user'] != 'EMPLOYE'){
	$url_redirect = "../index.php";
	header("Location: $url_redirect");
	exit();
}
?>

<script>

function afficherClient(nom, prenom, login, num, solde)
{
	var infoC = document.getElementById("infoClient");
	var infos = "<p><span class='titreInfo'>Nom client :</span> "+prenom+" "+nom+"</p>";
	infos += "<p><span class='titreInfo'>Login :</span> "+login+"</p>";
	infos += "<p><span class='titreInfo'>Numero compte :</span> "+num+"</p>";
	infos += "<p><span class='titreInfo'>Solde compte :</span> "+solde+"</p>";
	infos +=  '<form action="../controller/controller.php" method="post"><input type="hidden" name="action" value="virement"><input type="hidden" name="numcompte" value="'+num+'"><input type="hidden" name="nom" value="'+prenom+' '+nom+'"><input class="buttonStyle" type="submit" value="Effectuer un virement pour ce client"></form>';
	infoC.innerHTML = infos;
}


</script>
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
		<button class="buttonStyle" onclick="location.href='accueil.php'">Revenir a l'accueil</button>
	</div>

	<h2 style="text-align:left;">Employe : <?php echo $_SESSION['connected_user']['nom']?> <?php echo $_SESSION['connected_user']['prenom']?></h2>
    </header>

    <section class="ficheclient">
	<article class="clientList">
	  <div class="clientTable">	
            <table>
              <tr><th>Clients</th>
	      <?php
		
              foreach ($_SESSION['listeClients'] as $cle => $client) {
                echo '<tr>';
		echo '<td><button class="linkClient" onclick="afficherClient(\''.$client['nom'].'\', \''.$client['prenom'].'\', \''.$client['login'].'\', \''.$client['numero_compte'].'\', \''.$client['solde_compte'].'\')">'.$client['nom'].' '.$client['prenom'].'</button></td>';
              }
               ?>
	    </table>
	  </div>
	</article>
	<article>
		<div id="infoClient" class="fieldset">
		Selectionnez un client.
		</div>
	</article>
    </section>
</body>
</html>

