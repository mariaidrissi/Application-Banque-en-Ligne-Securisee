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
  <title>Verification</title>
  <link rel="stylesheet" type="text/css" media="all"  href="../css/style.css" />
</head>
<body>
  <header>
    <h1>Verification de votre identite.</h1>
  </header>
  
  <section>
      <div>
        <div>
            <form method="POST" action="../controller/controller.php">
                <input type="hidden" name="action" value="authenticateChk">
                <input type="text" name="login" placeholder="login"/>
                <input type="password" name="mdp" placeholder="mot de passe"/>
                <button class="buttonConnexion">Login</button>
            </form>
        </div>
      </div>

<?php
      if (isset($_REQUEST["nullvalue"])) {
        echo '<p class="errmsg">Merci de saisir votre login et votre mot de passe</p>';
      }
      ?>
  </section>

</body>
</html>



