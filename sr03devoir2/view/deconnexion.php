<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Deconnexion</title>
  <link rel="stylesheet" type="text/css" media="all"  href="../css/style.css" />
</head>
<body>
  <header>
    <h1>Au revoir !</h1>
  </header>
<?php	
	session_start();
	$_SESSION = array();
	
	if(ini_get("session.use_cookies")){
		$params = session_get_cookie_params();
		setcookie(session_name(),"", time() - 50000,$params["path"], $params["domain"],$params["secure"], $params["httponly"]);
		
	}
	session_destroy();
?>
  <section>

	<p>Vous avez bien ete deconnecte !</p>
      <div style="margin:2%;">
	<form action="../index.php">
		<input class="buttonConnexion" type="submit" value="Se connecter"/>
	</form>
      </div>
  </section>
</body>
</html>
