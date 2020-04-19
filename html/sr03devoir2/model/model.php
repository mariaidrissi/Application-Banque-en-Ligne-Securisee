<?php

function getMySqliConnection() {
  $db_connection_array = parse_ini_file("../config/config.ini"); 
  //return new mysqli('localhost','root', 'root', 'sr03');
  return new mysqli($db_connection_array['DB_HOST'], $db_connection_array['DB_USER'], $db_connection_array['DB_PASSWD'], $db_connection_array['DB_NAME']);
}

function findUser($login, $pwd) {
  $mysqli = getMySqliConnection();
  $utilisateur = false;
  if ($mysqli->connect_error) {
      echo 'Erreur connection BDD (' . $mysqli->connect_errno . ') '. $mysqli->connect_error;
  } 
  else {

	//$login = $mysqli->real_escape_string($login);
	//$pwd = $mysqli->real_escape_string($pwd);
	
	//error_log(password_hash($pwd, PASSWORD_BCRYPT));
	
	$req = $mysqli->prepare("select nom,prenom,login,mot_de_passe,id_user,numero_compte,profil_user,solde_compte from USERS where login=?;");
	if ($req) {
	 	$req->bind_param('s', $login);
		$req->execute();
		$result = $req->get_result();
         	if ($result->num_rows != 0){
			$utilisateur = $result->fetch_assoc();
			$mdpVerif = password_verify($pwd, $utilisateur['mot_de_passe']);
			if(!$mdpVerif){
				$utilisateur = false;
			}
		}
          	$req->close();
      	 }
      $mysqli->close();
  }
  return $utilisateur;
}

function creerUtilisateur($login, $passw, $profil, $nom, $prenom, $num_compte, $solde_compte)
{
  $mysqli = getMySqliConnection();
  $result = false;

  if ($mysqli->connect_error) {
      echo 'Erreur connection BDD (' . $mysqli->connect_errno . ') '. $mysqli->connect_error;
  } else {
       	  //login et numero_compte sont UNIQUE dans la table donc si ils existaient deja, alors on renverra faux
	  //$passw = $mysqli->real_escape_string($passw);
	  $passw = password_hash($passw, PASSWORD_BCRYPT);
	  //On suppose que on aura une operation supplementaire pour verifier que solde_compte est correct par rapport a la banque.
	  $req = $mysqli->prepare("INSERT INTO USERS (login, mot_de_passe, profil_user, nom, prenom, numero_compte, solde_compte) VALUES (?,?,?,?,?,?,?);");
	  if ($req) {
		$req->bind_param('sssssid',$login, $passw, $profil, $nom, $prenom, $num_compte, $solde_compte);
		$result = $req->execute();
		$req->close();
	}
	$mysqli->close();
  }
  return $result;
}

function updateMdp($login, $pwd)
{
	$mysqli = getMySqliConnection();
	$result = false;
	if ($mysqli->connect_error) {
		echo 'Erreur connection BDD (' . $mysqli->connect_errno . ') '. $mysqli->connect_error;
  	} else {
		//$login = $mysqli->real_escape_string($login);
		//$pwd = $mysqli->real_escape_string($pwd);

		$pwd = password_hash($pwd, PASSWORD_BCRYPT);

		$req = $mysqli->prepare("UPDATE USERS SET mot_de_passe=? WHERE login=?;");
	if ($req) {
	 	$req->bind_param('ss',$pwd,$login);
		$result = $req->execute();
          	$req->close();
      	 }
      $mysqli->close();
  }
  return $result;
}

function findAllUsers() {
  $mysqli = getMySqliConnection();

  $listeUsers = array();

  if ($mysqli->connect_error) {
      echo 'Erreur connection BDD (' . $mysqli->connect_errno . ') '. $mysqli->connect_error;
  } else {
	  
	$req = $mysqli->prepare("select nom,prenom,login,id_user, numero_compte from USERS;");
	if ($req) {
		$req->execute();
		$result = $req->get_result();
		while($unUser = $result->fetch_assoc()){
        	    	$listeUsers[$unUser['id_user']] = $unUser;
		}
		$req->close();
	}
	$mysqli->close();
  }
  return $listeUsers;
}

function findUserFromCompte($compte) {

  $mysqli = getMySqliConnection();
  $utilisateur = false;
  if ($mysqli->connect_error) {
      echo 'Erreur connection BDD (' . $mysqli->connect_errno . ') '. $mysqli->connect_error;
  } 
  else {
	$req = $mysqli->prepare("select nom,prenom,login,mot_de_passe,id_user,numero_compte,profil_user,solde_compte from USERS where numero_compte=?;");
	if ($req) {
	 	$req->bind_param('s', $compte);
		$req->execute();
		$result = $req->get_result();
         	if ($result->num_rows != 0){
			$utilisateur = $result->fetch_assoc();
		}
		$result->free();
          	$req->close();
      	 }
      $mysqli->close();
  }
  return $utilisateur;
}


function transfert($dest, $src, $mt) {
  $mysqli = getMySqliConnection();

  if($mt<=0){
	$mysqli->close();
	return false;
  }

  if ($mysqli->connect_error) {
	  echo 'Erreur connection BDD (' . $mysqli->connect_errno . ') '. $mysqli->connect_error;
	  return false;
  } else {

      $req1 = $mysqli->prepare("UPDATE USERS SET solde_compte=solde_compte+? WHERE numero_compte=?");
      $req2 = $mysqli->prepare("UPDATE USERS SET solde_compte=solde_compte-? WHERE numero_compte=?");

      $req1->bind_param('di',$mt, $dest);
      if (!$result = $req1->execute()) {
	      echo 'Erreur dans le transfert !';
	      return false;
      }
      $req2->bind_param('di', $mt, $src);
      if (!$result = $req2->execute()) {
	      echo 'Erreur dans le transfert !';
	      return false;
      }
      $mysqli->close();
  }
  return true;
}


function findMessagesInbox($userid) {
  $mysqli = getMySqliConnection();

  $listeMessages = array();

  if ($mysqli->connect_error) {
      echo 'Erreur connection BDD (' . $mysqli->connect_errno . ') '. $mysqli->connect_error;
  } else {
	$req=$mysqli->prepare("select id_msg,sujet_msg,corps_msg,u.nom,u.prenom from MESSAGES m, USERS u where m.id_user_from=u.id_user and id_user_to=?;");
	$req->bind_param('i',$userid);  
	  
	if ($req) {
		$req->execute();
		$result = $req->get_result();
        	if ($result->num_rows != 0){
			while ($unMessage = $result->fetch_assoc()) {
		  		$listeMessages[$unMessage['id_msg']] = $unMessage;
			}
		}
		$result->free();
        	$req->close();
        }
        $mysqli->close();
  }
  return $listeMessages;
}


function addMessage($to,$from,$subject,$body) {
  $mysqli = getMySqliConnection();

  if ($mysqli->connect_error) {
	  echo 'Erreur connection BDD (' . $mysqli->connect_errno . ') '. $mysqli->connect_error;
	  return false;
  } else {

	//$subject = htmlspecialchars($subject);
	//$body = htmlspecialchars($body);

	$subject = filter_var($subject, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$body = filter_var($body, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	
	//$subject = preg_replace("/\r\n|\r|\n/",'<br/>',$subject);
	//$body = preg_replace("/\r\n|\r|\n/",'<br/>',$body);

	$req=$mysqli->prepare("insert into MESSAGES(id_user_to,id_user_from,sujet_msg,corps_msg) values(?,?,?,?);");
	$req->bind_param('iiss',$to, $from, $subject, $body);
        if (!$result = $req->execute()) {
	      return false;
	}
	$req->close();
        $mysqli->close();
  }
  return true;
}


function listerEmployes(){

  $listeEmployes = array();
  $mysqli = getMySqliConnection();

  if ($mysqli->connect_error) {
      echo 'Erreur connection BDD (' . $mysqli->connect_errno . ') '. $mysqli->connect_error;
  } else {

	$req = $mysqli->prepare("select id_user, nom, prenom, numero_compte, solde_compte from USERS where profil_user='EMPLOYE';");
	if ($req) {
		$req->execute();
		$result = $req->get_result();
        	if ($result->num_rows != 0){
            		while ($unEmploye = $result->fetch_assoc()) {
		  		$listeEmployes[$unEmploye['id_user']] = $unEmploye;
			}
		}
		$result->free();
        	$req->close();
      	}
      $mysqli->close();
  }
  return $listeEmployes;
}


function listerClients(){

  $listeClients = array();
  $mysqli = getMySqliConnection();

  if ($mysqli->connect_error) {
      echo 'Erreur connection BDD (' . $mysqli->connect_errno . ') '. $mysqli->connect_error;
  } else {

	$req = $mysqli->prepare("select id_user, nom, prenom,login, numero_compte, solde_compte from USERS where profil_user='CLIENT';");
	if ($req) {
		$req->execute();
		$result = $req->get_result();
        	if ($result->num_rows != 0){
          		while ($unClient = $result->fetch_assoc()) {
		  		$listeClients[$unClient['id_user']] = $unClient;
			}
		}
		$result->free();
          	$req->close();
      	}
      	$mysqli->close();
  }
  return $listeClients;
}


function isPasswordValid($mdp)
{
	$maj = preg_match('/[A-Z]/', $mdp);
	$min = preg_match('/[a-z]/', $mdp);
	$num = preg_match('/[0-9]/',$mdp);
	if($num && $maj && $min && strlen($mdp) >= 12)
		return true;
	else return false;
}


?>
