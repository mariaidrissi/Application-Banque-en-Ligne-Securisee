<?php
session_start();
if(!isset($_SESSION["connected_user"])){
	$url_redirect = "../index.php";
	header("Location: $url_redirect");
}

if($_SESSION['userVirement']['numero_compte'] != $_SESSION['connected_user']['numero_compte'] && $_SESSION['connected_user']['profil_user']!="EMPLOYE"){
	$url_redirect = "../index.php";
	header("Location: $url_redirect");
}

?>

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Virement</title>
  <link rel="stylesheet" type="text/css" media="all"  href="../css/style.css" />
</head>
<body>
    <header>

	<div><h1>Virement</h1></div>
	
	<div style="float:right;">
        <form method="POST" action="../controller/controller.php">
            <input type="hidden" name="action" value="disconnect">
            <button class="buttonDeconnexion">Déconnexion</button>
        </form>
	</div>

	<div style="float:right;">
	<form action="../controller/controller.php" method="POST">
		<input type="hidden" name="action" value="retourAccueil">
		<input class="buttonStyle" type="submit" value="Revenir a l'acceuil">
	</form>
	</div>

	<h2 style="text-align:left;"><?php echo $_SESSION["connected_user"]["prenom"];?> <?php echo $_SESSION["connected_user"]["nom"];?> <?php if(isset($_SESSION['nomClientVirement'])) echo ' en tant que : '.$_SESSION['nomClientVirement'] ?></h2> 
    </header>

    <section>
	<article>
        <div class="fieldset_virement">
     	<form method="POST" action="../controller/controller.php">
          <input type="hidden" name="action" value="faireVirement">
  	      <div class="field">
                  <h3>Faire un virement : </h3>
              </div>
              <div class="field">
                  <label>N° compte destinataire : </label><input type="text" size="20" name="destination">
              </div>
              <div class="field">
                  <label>Montant à transférer : </label><input type="text" size="10" name="montant">
	      </div>
	      <div class="field">
	      	<button class="buttonStyle">Transférer</button>
	      </div>
	<?php	
	    if(isset($_REQUEST["loginchkfail"])){
	         echo '<p style="color:red;">Authentification ratee, le virement n\'a pas pu etre effectue.</p>';
	    }
	    else if(isset($_REQUEST['trfmt'])){
		echo '<p style="color:red;">Le montant n\'est pas correct. Le virement n\'a pas pu etre effectue.</p>';
	    }
	    else if(isset($_REQUEST['nullvalue'])){
		echo '<p style="color:red;">Les champs ne peuvent pas etre vides.</p>'; 
	    }
	    else if(isset($_REQUEST['transok'])){
		echo '<p style="color:green;">Virement effectue.</p>';
	    }
	?>
	</div>
        </form>
	</article>
    </section>
</body>
</html>
