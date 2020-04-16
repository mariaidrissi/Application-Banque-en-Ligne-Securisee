<?php
session_start();
if(!isset($_SESSION["connected_user"])){
	$url_redirect = "../index.php";
	header("Location: $url_redirect");
}
//retour depuis virement sans cliquer sur le bouton
if(isset($_SESSION['userVirement'])){
	unset($_SESSION['userVirement']);
}

if(isset($_SESSION['montant'])){
	unset($_SESSION['montant']);
}

if(isset($_SESSION['destination'])){
	unset($_SESSION['destination']);
}
?>

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Accueil</title>
  <link rel="stylesheet" type="text/css" media="all"  href="../css/style.css" />
</head>
<body>
    <header>
	
	<h1>Accueil</h1>

	<div style="float:right;">
        <form method="POST" action="../controller/controller.php">
            <input type="hidden" name="action" value="disconnect">
            <button class="buttonDeconnexion">Déconnexion</button>
	</form>
	</div>
        
        <h2 style="text-align:left;"><?php echo $_SESSION["connected_user"]["prenom"];?> <?php echo $_SESSION["connected_user"]["nom"];?></h2>
    </header>

    <section class="accueil">
        <article>
          <div class="fieldset">
              <div>
                  <h3>Vos informations personnelles</h3>
              </div>
              <div>
                  <label>Login : </label><span><?php echo $_SESSION["connected_user"]["login"];?></span>
              </div>
              <div>
                  <label>Profil : </label><span><?php echo $_SESSION["connected_user"]["profil_user"];?></span>
              </div>
          </div>
        </article>
        
	<article>
	   <div class="fieldset">
              <div>
                  <h3>Votre compte</h3>
              </div>
              <div>
                  <label>N° compte : </label><span><?php echo $_SESSION["connected_user"]["numero_compte"];?></span>
              </div>
              <div>
                  <label>Solde : </label><span><?php echo $_SESSION["connected_user"]["solde_compte"];?> &euro;</span>
	      </div>
	   </div>
        </article>

	<article>
	    <div class="fieldset">
		
		<h3>Messages</h3>
		<form action="../controller/controller.php" method="post">
			<input type="hidden" name="action" value="msglist">
			<input class= "buttonStyle" type="submit" value="Acceder a la messagerie">
		</form>
	    </div>
	</article>

	<article>
	    <div class="fieldset">
		
		<h3>Virement</h3>
		<form action="../controller/controller.php" method="post">
		    <input type="hidden" name="action" value="virement">
		    <input type="hidden" name="numcompte" value="<?php echo $_SESSION['connected_user']['numero_compte'];?>">
		    <input class="buttonStyle" type="submit" value="Faire un virement">
		</form>
	    </div>

	</article>

	<?php
	    if($_SESSION['connected_user']['profil_user'] == "EMPLOYE"){
		echo '<article>';
		echo '<div class="fieldset">';
		echo '<h3>Fiche client</h3>';
		echo '<form action="../controller/controller.php" method=post>';
		echo '<input type="hidden" name="action" value="ficheclient">';
		echo '<input class="buttonStyle" type="submit" value="Acceder aux fiches client">';
		echo '</form>';
		echo '</div>';
		echo '</article>';
	    }
	?>
    </section>
</body>
</html>
