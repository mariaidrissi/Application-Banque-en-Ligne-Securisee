<?php
require_once('../model/model.php');

session_start();

if(!isset($_SESSION['expire']) || time() > $_SESSION['expire']){
	session_unset();
	$url_redirect = "../view/deconnexion.php";
	header("Location: $url_redirect");
	exit();
}

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
$urlredirtransfail = "../view/virement.php?transfail";
$urlredirtrfmt= "../view/virement.php?trfmt";
$urlredirloginchknull = "../view/loginChk.php?nullvalue";
$urlredirlisteclient = "../view/ficheclient.php";
$urlredirdisconnect = "../view/deconnexion.php";
$urlredirmessagerie = "../view/messagerie.php";
$urlredirvirementvide="../view/virement.php?nullvalue";
$urlredirvirement = "../view/virement.php";
$urlredirfalsecompte = "../view/virement.php?false";
$urlredirmessageok = "../view/messagerie.php?msg_ok";
$urlredirmessagefail = "../view/messagerie.php?msg_fail";
$urlredirficheclient = "../view/ficheclient.php";
$urlredirvidecreer = "../view/creerCompte.php?vide";
$urlredircomptecree = "../view/connexion.php?compteok";
$urlredircreefail = "../view/connexion.php?comptefail";

// URL de redirection par défaut (si pas d'action ou action non reconnue)
$url_redirect = $urlredirdefault;

if (isset($_REQUEST['action'])) {
  
      if ($_REQUEST['action'] == 'authentification') {

          if (!isset($_REQUEST['login']) || !isset($_REQUEST['mdp']) || $_REQUEST['login'] == "" || $_REQUEST['mdp'] == "") {
              // manque login ou mot de passe
              $url_redirect = $urlredirvide;
              
          } else {
		//error_log($utilisateur['nom']);
		if($_SESSION['tentatives']==3){
		      	if(time() - $_SESSION['last_login'] < 1440){ //24 minutes ; fixer a 10 pour tests
				header("Location: $urlredirbadvalue");
				return;
			} else
				$_SESSION['tentatives'] = 0;
		}
		//recherche caracteres interdits
		if(isInputInvalid($_REQUEST['login'])){	
			$_SESSION['tentatives']++;
			$_SESSION['last_login']=time();
			header("Location: $urlredirbadvalue");
			return;
		}
		//trouver l'utilisateur dans la base de donnee : les entrees sont traitees dans le modele
	 	$utilisateur = findUser($_REQUEST['login'], $_REQUEST['mdp']);
		//utilisateur sera 'false' si la requete n'a pas aboutie ou si l'utilisateur n'existe pas ; avec en plus la requete parametrees,
		//les injections SQL et autres requetes de decouverte de base de donnees sont impossibles.
		if($utilisateur == false){
			$_SESSION['tentatives']++;
			$_SESSION['last_login']=time();
			$url_redirect = $urlredirbadvalue;

		} else {
			$_SESSION["connected_user"] = $utilisateur;
               		$url_redirect = $urlredircompte;
		}
	 }
      } else if ($_REQUEST['action'] == 'creerCompte') {
		
	      	if(!isset($_REQUEST['nom']) || !isset($_REQUEST['prenom']) ||!isset($_REQUEST['profil']) || !isset($_REQUEST['numcompte']) ||!isset($_REQUEST['solde']) ||!isset($_REQUEST['login']) ||!isset($_REQUEST['mdp']) || $_REQUEST['nom'] == "" || $_REQUEST['prenom'] == "" || $_REQUEST['numcompte'] == "" || $_REQUEST['solde'] == "" || $_REQUEST['login'] == "" || $_REQUEST['mdp'] == ""){
			$url_redirect = $urlredirvidecreer;

	      	} else if (!is_numeric($_REQUEST['numcompte']) || !is_numeric($_REQUEST['solde']) || $_REQUEST['solde'] < 0 || !isPasswordValid($_REQUEST['mdp'])){
			$url_redirect = $urlredircreefail;

	       	} else if(isInputInvalid($_REQUEST['login']) || isInputInvalid($_REQUEST['nom']) || isInputInvalid($_REQUEST['prenom'])){	
			$url_redirect = $urlredircreefail;

		} else {
		      $result = creerUtilisateur($_REQUEST['login'], $_REQUEST['mdp'], $_REQUEST['profil'], $_REQUEST['nom'], $_REQUEST['prenom'], $_REQUEST['numcompte'], $_REQUEST['solde']);
		      if($result)
			$url_redirect = $urlredircomptecree;
		      else
		        $url_redirect = $urlredircreefail;
	      	}
      } else if ($_REQUEST['action'] == 'changeMdp'){
 
		if(!isset($_REQUEST['login']) || !isset($_REQUEST['ancienMdp']) || !isset($_REQUEST['nouveauMdp']) || $_REQUEST['login'] == "" || $_REQUEST['ancienMdp'] == "" || $_REQUEST['nouveauMdp'] == ""){
			header("Location:$urlredirchangevide");
			return;
		}
		
		$result = isPasswordValid($_REQUEST['nouveauMdp']);
		
		if(!$result || isInputInvalid($_REQUEST['login'])){
			header("Location: $urlredirchangefail");
			return;
		}

	      	$utilisateur = findUser($_REQUEST['login'], $_REQUEST['ancienMdp']);
		if($utilisateur == false){			
	      		$url_redirect = $urlredirchangefail;
	        }else{
			$result = updateMdp($_REQUEST['login'], $_REQUEST['nouveauMdp']);
			if($result)
				$url_redirect = $urlredirchangeok;
			else
	      			$url_redirect = $urlredirchangefail;

		}   
      } else if ($_REQUEST['action'] == 'disconnect') {

              $url_redirect = $urlredirdisconnect;

      } else if($_REQUEST['action'] == 'authenticateChk'){

		//on re-verifie a chaque etape
		if($_SESSION['connected_user']['profil_user'] == "CLIENT" && $_SESSION['userVirement']['numero_compte'] != $_SESSION['connected_user']['numero_compte']){
			$url_redirect =  $urlredirdisconnect;

		} else if(!isset($_SESSION['userVirement'])){
			$url_redirect = $urlredirdisconnect;

	      	} else if(!isset($_REQUEST['login']) || !isset($_REQUEST['mdp']) || $_REQUEST['login'] == "" || $_REQUEST['mdp'] == "") {
			$url_redirect = $urlredirloginchknull;

	  	} else {
	        	$utilisateur = findUser($_REQUEST['login'], $_REQUEST['mdp']);
			if($utilisateur==false)
				$url_redirect=$urlredircheckfalse;
			else if($utilisateur["id_user"] == $_SESSION['connected_user']['id_user']){
					
				if (is_numeric ($_SESSION['montant']) && $_SESSION['montant'] > 0 && $_SESSION['userVirement']['solde_compte']-$_SESSION['montant'] >=0) {
	
					$result = transfert($_SESSION['destination'],$_SESSION["userVirement"]["numero_compte"], $_SESSION['montant']);
					if($result==false){
						header("Location: $urlredirtransfail");
						return;
					}
					if($_SESSION['userVirement']['numero_compte'] == $_SESSION['connected_user']['numero_compte']){
						$_SESSION["connected_user"]["solde_compte"] = $_SESSION["connected_user"]["solde_compte"] -  $_SESSION['montant'];
					}
					if($_SESSION['destination'] == $_SESSION['connected_user']['numero_compte']){
						$_SESSION["connected_user"]["solde_compte"] = $_SESSION["connected_user"]["solde_compte"] +  $_SESSION['montant'];
					}
					$url_redirect = $urlredirtransok;

				} else {
              				$url_redirect = $urlredirtrfmt;
				}
			} else
				$url_redirect = $urlredircheckfalse;
	  	}
	} else if ($_REQUEST['action'] == 'faireVirement') {
		//on re-verifie a chaque etape
		if($_SESSION['connected_user']['profil_user'] == "CLIENT" && $_SESSION['userVirement']['numero_compte'] != $_SESSION['connected_user']['numero_compte']){
			$url_redirect = $urlredirdisconnect;

		} else if(!isset($_REQUEST['montant']) || $_REQUEST['montant']=="" || !isset($_REQUEST['destinataire']) || $_REQUEST['destinataire'] == ""){
			$url_redirect = $urlredirvirementvide;

	      	} else if (!is_numeric($_REQUEST['montant']) || $_REQUEST['montant'] < 0 || $_REQUEST['montant'] > $_SESSION['userVirement']['solde_compte']){
			$url_redirect = $urlredirtrfmt;

	      	} else if ($_SESSION['userVirement']['numero_compte'] == $_REQUEST['destinataire']) {
			$url_redirect = $urlredirfalsecompte;

	      	} else {
	      		$_SESSION['montant'] = $_REQUEST['montant'];
			$_SESSION['destination'] = $_REQUEST['destinataire'];
			$url_redirect = $urlredirloginchk;
	      	}
      } else if ($_REQUEST['action'] == 'virement'){

	        //si l'utilisateur est un client et qu'il essaie de faire un virement depuis un autre compte
		if($_SESSION['connected_user']['profil_user'] == "CLIENT" && $_REQUEST['numcompte'] != $_SESSION['connected_user']['numero_compte']){
			header("Location: $urlredirdisconnect");
			return;
		}
		if(isset($_REQUEST['nom'])){
			$_SESSION['nomClientVirement'] = $_REQUEST['nom'];
		}

	      	$_SESSION['userVirement'] = findUserFromCompte($_POST['numcompte']);
		$_SESSION['listeUsers'] = findAllUsers();
		$url_redirect = $urlredirvirement;

      } else if ($_REQUEST['action'] == 'sendmsg') {

		$result = addMessage($_REQUEST['to'],$_SESSION["connected_user"]["id_user"],$_REQUEST['sujet'],$_REQUEST['corps']);
		if($result == false)
			$url_redirect=$urlredirmessagefail;
		else
			$url_redirect = $urlredirmessageok;
              
      } else if ($_REQUEST['action'] == 'msglist') {
		
		if($_SESSION['connected_user']['profil_user'] == "CLIENT")
			$_SESSION['listeEmployes'] = listerEmployes();
		else
			$_SESSION['listeUsers'] = findAllUsers();

         	$_SESSION['messagesRecus'] = findMessagesInbox($_SESSION["connected_user"]["id_user"]);
		$url_redirect = $urlredirmessagerie;

      } else if ($_REQUEST['action'] == 'retourAccueil'){

		unset($_SESSION['userVirement']);
		unset($_SESSION['nomClientVirement']);
		unset($_SESSION['montant']);
		unset($_SESSION['destination']);
		$url_redirect = $urlredircompte;
      
      } else if ($_REQUEST['action'] == 'ficheclient'){
		
	      $_SESSION['listeClients'] = listerClients();
	      $url_redirect = $urlredirficheclient;
      }
}  
header("Location: $url_redirect");
?>
