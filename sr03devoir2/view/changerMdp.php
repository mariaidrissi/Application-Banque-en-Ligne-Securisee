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
  </header>
  
  <section>
      <div class="login-page">
        <div class="form">
            <form method="POST" action="../controller/controller.php">
                <input type="hidden" name="action" value="changeMdp">
		<input type="text" name="login" placeholder="login"/>
		<input type="password" name="ancienMdp" placeholder="ancienMdp"/>
                <input type="password" name="nouveauMdp" placeholder="nouveauMdp"/>
                <button>Mettre a jour</button>
	    </form>
	   <form action="connexion.php">
		<input type="submit" value="Retour"/>
	   </form>
        </div>
      </div>

<?php
      session_start();
      if (isset($_REQUEST["vide"])) {
        echo '<p class="errmsg">Merci de saisir votre login et votre mot de passe</p>';
      }
      ?>
  </section>

</body>
</html>
