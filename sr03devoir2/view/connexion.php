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
	<div>
            <form method="POST" action="../controller/controller.php">
                <input type="hidden" name="action" value="authentification">
                <input type="text" name="login" placeholder="login"/>
                <input type="password" name="mdp" placeholder="mdp"/>
		<button class="buttonConnexion">Connexion</button>
            </form>
	</div>
	<div style="margin:2%;">
		<a class="linkMdp" href="changerMdp.php">Changer le mot de passe</a>
	</div>
<?php
      session_start();
      if (isset($_REQUEST["vide"])) {
        echo '<p style="color:red;">Merci de saisir votre login et votre mot de passe</p>';
      } else if (isset($_REQUEST["badvalue"])) {
	      echo '<p style="color:red;">Votre login/mot de passe est incorrect</p>';
	      if(isset($_SESSION['tentatives'])){
			if($_SESSION['tentatives']==3)
				echo 'Vous etes bloque, reessayez plus tard';
			else
		      		echo 'Tentatives restantes : '. (3 - $_SESSION['tentatives']);
	      }
      } else if (isset($_REQUEST["changemdpfail"])){
		echo '<p style="color:red;">Votre mot de passe n\'a pas pu etre mis a jour. Ressayez.</p>';
      } else if (isset($_REQUEST["changeok"])){
		echo '<p style="color:green;">Votre mot de passe a bien ete mis a jour.</p>';
      }
      ?>
  </section>

</body>
</html>
