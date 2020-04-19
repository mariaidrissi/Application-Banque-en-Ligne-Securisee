<?php
session_start();

if(!isset($_SESSION['expire']) || time() > $_SESSION['expire']){
	session_unset();
	$url_redirect = "deconnexion.php";
	header("Location: $url_redirect");
	exit();
}

if(!isset($_SESSION["connected_user"])){
	$url_redirect = "index.php";
	header("Location: $url_redirect");
	exit();
}
?>

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Verification</title>
  <link rel="stylesheet" type="text/css" media="all"  href="../css/style.css" />
</head>
<body>
  <header>
    <h1>Verification de votre identite.</h1>
   <div style="float:right;">
	<form action="../controller/controller.php" method="POST">
		<input type="hidden" name="action" value="retourAccueil">
		<input class="buttonDeconnexion" type="submit" value="Annuler">
	</form>
   </div>
  </header>
  
  <section>
        <div>
            <form method="POST" action="../controller/controller.php">
                <input type="hidden" name="action" value="authenticateChk">
                <input type="text" name="login" placeholder="login"/>
                <input type="password" name="mdp" placeholder="mot de passe"/>
                <button class="buttonConnexion">Login</button>
            </form>
        </div>
<?php
      if (isset($_REQUEST["nullvalue"])) {
        echo '<p class="messageErreur">Merci de saisir votre login et votre mot de passe</p>';
      }
      ?>
  </section>

</body>
</html>



