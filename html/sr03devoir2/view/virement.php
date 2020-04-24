<?php
session_start();

if(!isset($_SESSION['expire']) || time() > $_SESSION['expire']){
	session_unset();
	$url_redirect = "deconnexion.php";
	header("Location: $url_redirect");
	exit();
}

if(!isset($_SESSION["connected_user"])){
	$url_redirect = "../index.php";
	header("Location: $url_redirect");
	exit();
}

//si l'utilisateur essaie de faire un virement pour quelqu'un d'autre mais qu'il n'est pas un employe
if($_SESSION['userVirement']['numero_compte'] != $_SESSION['connected_user']['numero_compte'] && $_SESSION['connected_user']['profil_user']!="EMPLOYE"){
	$url_redirect = "../index.php";
	header("Location: $url_redirect");
	exit();
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

	<h2 style="text-align:left;"><?php echo htmlspecialchars($_SESSION["connected_user"]["prenom"]);?> <?php echo htmlspecialchars($_SESSION["connected_user"]["nom"]);?> <?php if(isset($_SESSION['nomClientVirement'])) echo ' en tant que : '.htmlspecialchars($_SESSION['nomClientVirement']) ?></h2> 
    </header>

    <section>
	<article>
        <div class="fieldset_virement">
     	<form method="POST" action="../controller/controller.php">
          <input type="hidden" name="action" value="faireVirement">
  	      <div class="field">
                  <h3>Faire un virement : </h3>
	      </div>
	      <div style="text-align:left"> 
		  <label>Destination : </label>
                  <select name="destinataire">
		  <?php
			foreach ($_SESSION['listeUsers'] as $id => $user) {
				if($_SESSION['userVirement']['numero_compte'] != $user['numero_compte'])
                      			echo '<option value="'.$user['numero_compte'].'">'.$user['numero_compte'].' - '.htmlspecialchars($user['nom']).' '.htmlspecialchars($user['prenom']).'</option>';
                    	}
                  ?>
		  </select>
                <div class="field">
                  <label>Montant à transférer : </label><input type="text" size="10" name="montant">
	        </div>
	      </div>

	      <div class="field">
	      	<button class="buttonStyle">Transférer</button>
	      </div>
	<?php	
	    if(isset($_REQUEST["loginchkfail"])){
	         echo '<p class="messageErreur">Authentification ratee, le virement n\'a pas pu etre effectue.</p>';
	    }
	    else if(isset($_REQUEST['trfmt'])){
		echo '<p class="messageErreur">Le montant n\'est pas correct. Le virement n\'a pas pu etre effectue.</p>';
	    }
	    else if(isset($_REQUEST['nullvalue'])){
		echo '<p class="messageErreur">Les champs ne peuvent pas etre vides.</p>'; 
	    }
	    else if(isset($_REQUEST['transok'])){
		echo '<p class="messageReussite">Virement effectue.</p>';
	    }
	    else if(isset($_REQUEST['transfail'])){
		echo '<p class="messageErreur">La transaction n\'a pas pu etre effectuee.</p>';
	    } 
	    else if (isset($_REQUEST['false'])) {
		echo '<p class="messageErreur">Erreur.</p>';
	    }
	?>
	</form>
        </div>
	</article>
    </section>
</body>
</html>
