<?php
  ini_set('session.cookie_secure', 'on');
  ini_set('session.cookie_httponly', '1');

  // test connection mySQL
  $db_connection_array = parse_ini_file("config/config.ini");
    
  $mysqli = new mysqli($db_connection_array['DB_HOST'], $db_connection_array['DB_USER'], $db_connection_array['DB_PASSWD'], $db_connection_array['DB_NAME']);
  
  //$mysqli = new mysqli('localhost','root', 'root', 'sr03');
  if ($mysqli->connect_error) {
        // problème connection mySQL =>STOP
        echo '<html><head><meta charset="utf-8"><title>MySQL Error</title><link rel="stylesheet" type="text/css" media="all"  href="css/mystyle.css" /></head><body>'.
             '<p>Impossible de se connecter à MySQL.</p>'.
             '<p>Voici le message d\'erreur : <b>'. utf8_encode($mysqli->connect_error) . '</b></p>'.
             '<br/>Vérifiez vos paramètres dans le config.ini'.
             '</body></html>';        
  } else {
        // mySQL répond bien
	
	session_start();
	unset($_SESSION['connected_used']);
	$_SESSION['tentatives']=0;
	header('Location: view/connexion.php');
  }
?>

<html><head><meta charset="utf-8"><title>MySQL Error</title><link rel="stylesheet" type="text/css" media="all"  href="css/mystyle.css" /></head><body>
