<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Mot de passe</title>
  <link rel="stylesheet" type="text/css" media="all"  href="../css/style.css" />
</head>
<body>
  <header>
    <h1>Changez votre mot de passe</h1>
    <form action="connexion.php">
	<input class="buttonStyle" type="submit" value="Retour"/>
    </form>
  </header>
  
  <section>
	<form method="POST" action="../controller/controller.php">
	  <div class="login">
           <input type="hidden" name="action" value="changeMdp">
	   <input type="text" name="login" placeholder="login"/>
	   <input type="password" name="ancienMdp" placeholder="ancienMdp"/>
           <input type="password" name="nouveauMdp" placeholder="nouveauMdp"/>
	  </div>
	  <div>
	   <button class="buttonConnexion">Mettre a jour</button>
	  </div>
	</form>

<?php
      session_start();
      if (isset($_REQUEST["vide"])) {
        echo '<p class="messageErreur">Merci de saisir votre login et votre mot de passe</p>';
      }
      ?>
  </section>

</body>
</html>
