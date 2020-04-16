<?php
require_once('../model/model.php');

$urlredirdefault = "../index.php";
$urlredirbadvalue = "../view/connexion.php?badvalue";
$urlredirvide = "../view/connexion.php?vide";
$urlredirchangevide = "../view/changerMdp.php?vide";
$urlredirchangefail = "../view/connexion.php?changemdpfail";
$urlredirchangeok = "../view/connexion.php?changeok";
$urlredircompte = "../view/accueil.php";
$urlredirloginchk = "../view/loginChk.php";
$urlredircheckfalse = "../view/virement.php?loginchkfail";
$urlredirtransok = "../view/virement.php?transok";
$urlredirtrfmt= "../view/virement.php?trfmt";
$urlredirloginchknull = "../view/loginChk.php?nullvalue";
$urlredirlisteclient = "../view/ficheclient.php";
$urlredirdisconnect = "../view/deconnexion.php";
$urlredirmessagerie = "../view/messagerie.php";
$urlredirvirementvide="../view/virement.php?nullvalue";
$urlredirvirement = "../view/virement.php";
$urlredircompteok = "../view/messagerie.php?msg_ok";

session_start();

// URL de redirection par défaut (si pas d'action ou action non reconnue)
$url_redirect = $urlredirdefault;

  if (isset($_REQUEST['action'])) {
  
      if ($_REQUEST['action'] == 'authentification') {
          /* ======== AUTHENT ======== */
          if (!isset($_POST['login']) || !isset($_POST['mdp']) || $_POST['login'] == "" || $_POST['mdp'] == "") {
              // manque login ou mot de passe
              $url_redirect = $urlredirnull;
              
          } else {

		  //faire prepared statement pour verifier si bloque et nb tentatives
              	$utilisateur = findUser($_REQUEST['login'], $_REQUEST['mdp']);
		error_log($utilisateur['nom']);
		if($_SESSION['tentatives']==3){
		      	if(time() - $_SESSION['last_login'] < 10*60*60){
				header("Location: $urlredirbadvalue");
				return;
			}
			else
				$_SESSION['tentatives'] = 0;
		} else {
			if($utilisateur == false){
				$_SESSION['tentatives']++;
				$_SESSION['last_login']=time();
				$url_redirect = $urlredirbadvalue;
			}
			else{
				$_SESSION["connected_user"] = $utilisateur;
				if($utilisateur['profil_user'] == "CLIENT"){
					$_SESSION['listeEmployes'] = listerEmployes();
				}else {
	     				$_SESSION['listeClients'] = listerClients();
					$_SESSION['listeUsers'] = findAllUsers();
				}
                		$url_redirect = $urlredircompte;
			}
		}
	  }
      } else if ($_REQUEST['action'] == 'changeMdp'){

	      if (!isset($_POST['login']) || !isset($_POST['ancienmdp']) || !isset($_POST['nouveauMdp']) || $_POST['login'] == "" || $_POST['mdp'] == "" || $_POST['nouveauMdp'] == "" || strlen($_POST['nouveauMdp']) < 12) {
		      $url_redirect = $urlredirchangevide;

	      $utilisateur = findUser($_POST['login'], $_POST['ancienMdp']);
	          if($utilisateur == false){			
	      		$url_redirect = $urlredirchangefail;
	          }else{
			$result = updateMdp($_POST['login'], $_POST['nouveauMdp']);
			if($result)
				$url_redirect = $urlredirchangeok;
			else
	      			$url_redirect = $urlredirchangefail;

	          }
	      }
      } else if ($_REQUEST['action'] == 'disconnect') {

              $url_redirect = $urlredirdisconnect;

      } else if($_REQUEST['action'] == 'authenticateChk'){

	      	if(!isset($_SESSION['userVirement'])){
			$url_redirect = $urlredirdisconnect;
			return;
	      	}    
		if (!isset($_REQUEST['login']) || !isset($_REQUEST['mdp']) || $_REQUEST['login'] == "" || $_REQUEST['mdp'] == "") {
              		// manque login ou mot de passe
		  	$url_redirect = $urlredirloginchknull;
	  	}else {
	        	$utilisateur = findUser($_REQUEST['login'], $_REQUEST['mdp']);
			if($utilisateur==false)
				$url_redirect=$urlredircheckfalse;
			else if($utilisateur["id_user"] == $_SESSION['connected_user']['id_user']){
					
				if (is_numeric ($_SESSION['montant']) && $_SESSION['montant'] > 0 && $_SESSION['userVirement']['solde_compte']-$_SESSION['montant'] >=0) {
	
					transfert($_SESSION['destination'],$_SESSION["userVirement"]["numero_compte"], $_SESSION['montant']);
					if($_SESSION['userVirement']['numero_compte'] == $_SESSION['connected_user']['numero_compte']){
						$_SESSION["connected_user"]["solde_compte"] = $_SESSION["connected_user"]["solde_compte"] -  $_SESSION['montant'];
					}
					$url_redirect = $urlredirtransok;
				} else {
              				$url_redirect = $urlredirtrfmt;
				}
				unset($_SESSION['montant']);
				unset($_SESSION['destination']);
			}
			else
				$url_redirect = $urlredircheckfalse;
	  	}
	} else if ($_REQUEST['action'] == 'faireVirement') {

	      if(!isset($_REQUEST['montant']) || !isset($_REQUEST['destination']) || $_REQUEST['montant']=="" || $_REQUEST['destination']==""){
		$url_redirect = $urlredirvirementvide;
	      } else {
	      	$_SESSION['montant'] = $_REQUEST['montant'];
	      	$_SESSION['destination'] = $_REQUEST['destination'];
		$url_redirect = $urlredirloginchk;
	      }
      } else if ($_REQUEST['action'] == 'virement'){

	        //si l'utilisateur est un client et qu'il essaie de faire un virement depuis un autre compte
		if($_SESSION['connected_user']['profil_user'] == "CLIENT" && $_REQUEST['numcompte'] != $_SESSION['connected_user']['numero_compte']){
			$url_redirect = $urlredirdisconnect;
			return;
		}
		if(isset($_REQUEST['nom'])){
			$_SESSION['nomClientVirement'] = $_REQUEST['nom'];
		}

	      	$_SESSION['userVirement'] = findUserFromCompte($_POST['numcompte']);
	      	$url_redirect = $urlredirvirement;

      } else if ($_REQUEST['action'] == 'sendmsg') {

		addMessage($_REQUEST['to'],$_SESSION["connected_user"]["id_user"],$_REQUEST['sujet'],$_REQUEST['corps']);
		$url_redirect = $urlredircompteok;
              
      } else if ($_REQUEST['action'] == 'msglist') {
	
         	 $_SESSION['messagesRecus'] = findMessagesInbox($_SESSION["connected_user"]["id_user"]);
		 $url_redirect = $urlredirmessagerie;

      }else if ($_REQUEST['action'] == 'retourAccueil'){
	      unset($_SESSION['userVirement']);
	      unset($_SESSION['nomClientVirement']);
	     	unset($_SESSION['montant']);
		unset($_SESSION['destination']);
		$url_redirect = $urlredircompte;
      }
  }  
  
  header("Location: $url_redirect");

?>
