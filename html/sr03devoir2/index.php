<?php

  // test connection mySQL
  $db_connection_array = parse_ini_file("config/config.ini");

  $max_delay = 1440; //en secondes ->24 minutes comme defini dans le fichier de configuration

  $mysqli = new mysqli($db_connection_array['DB_HOST'], $db_connection_array['DB_USER'], $db_connection_array['DB_PASSWD'], $db_connection_array['DB_NAME']);
  
  //$mysqli = new mysqli('localhost','root', 'root', 'sr03');
  if ($mysqli->connect_error) {
        // problème connection mySQL =>STOP
        echo '<html><head><meta charset="utf-8"><title>MySQL Error</title><link rel="stylesheet" type="text/css" media="all"  href="css/mystyle.css" /></head><body>'.
             '<p>Impossible de se connecter à MySQL.</p>'.
             '</body></html>';        
  } else {
        // mySQL répond bien
	
	session_start();
	unset($_SESSION['connected_used']);
	$_SESSION['tentatives']=0;
	$_SESSION['expire'] = time() + $max_delay; //pour expiration de la session
	header('Location: view/connexion.php');
  }
?>

<html><head><meta charset="utf-8"><title>MySQL Error</title><link rel="stylesheet" type="text/css" media="all"  href="css/mystyle.css" /></head><body>
