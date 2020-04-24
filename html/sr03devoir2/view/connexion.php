<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Connexion</title>
  <link rel="stylesheet" type="text/css" media="all"  href="../css/style.css" />
</head>
<body>
  <header>
    <h1>Connexion</h1>
  </header>
  
  <section>
        <form method="POST" action="../controller/controller.php">
	    <div class="login">
                <input type="hidden" name="action" value="authentification">
                <input type="text" name="login" placeholder="login"/>
		<input type="password" name="mdp" placeholder="mdp"/>
		<button class="buttonConnexion">Connexion</button>
            </div>
	</form>
	<div>
		<a class="linkMdp" href="changerMdp.php">Changer le mot de passe</a>
	</div>
	<div>
		<a class="linkMdp" href="creerCompte.php">Creer un compte</a>
	</div>
<?php
	session_start();
      	if (isset($_REQUEST["vide"])) {
        	echo '<p class="messageErreur">Merci de saisir votre login et votre mot de passe</p>';
      	} else if (isset($_REQUEST["badvalue"])) {
	      	echo '<p class="messageErreur">Authentification ratee.</p>';
	      	if(isset($_SESSION['tentatives'])){
			if($_SESSION['tentatives']==3)
				echo 'Vous etes bloque, reessayez plus tard';
			else
		      		echo 'Tentatives restantes : '. (3 - $_SESSION['tentatives']);
	      	}
      	} else if (isset($_REQUEST["changemdpfail"])){
		echo '<p class="messageErreur">Votre mot de passe n\'a pas pu etre mis a jour. Ressayez.</p>';
      	} else if (isset($_REQUEST["changeok"])){
		echo '<p class="messageReussite">Votre mot de passe a bien ete mis a jour.</p>';
      	} else if (isset($_REQUEST['compteok'])) {
		echo '<p class="messageReussite">Compte cree.</p>';
      	} else if (isset($_REQUEST['comptefail'])) {
		echo '<p class="messageErreur">Le compte n\'a pas pu etre cree. Ressayez.</p>';
      	}
?>

  </section>

</body>
</html>
