<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Connexion</title>
  <link rel="stylesheet" type="text/css" media="all"  href="../css/style.css" />
</head>
<body>
  <header>
    <h1>Creer un compte</h1>
    <button class="buttonStyle" onclick="location.href='connexion.php'">Revenir a l'accueil</button>
  </header>
  <section>
        <form method="POST" action="../controller/controller.php">
	    <div class="loginCreer">
		<input type="hidden" name="action" value="creerCompte">
		<input type="text" name="nom" placeholder="nom (plaintext only)"/>
		<input type="text" name="prenom" placeholder="prenom (plaintext only)"/>
		<input type="radio" name="profil" value="CLIENT" checked/> <span>Client</span>
		<input type="radio" name="profil" value="EMPLOYE"/><span>Employe</span>
		<input type="text" name="numcompte" placeholder="numero du compte"/>
		<input type="text" id="solde" name="solde" placeholder="solde initial"/>
                <input type="text" name="login" placeholder="login (plaintext only)"/>
		<input type="password" id="mdp" class="invalid" name="mdp" placeholder="mdp"/>
		<button class="buttonConnexion">Creer le compte</button>

		<p id="indicationPwd" class="messageErreur"> Le mot de passe doit contenir des chiffres, des majuscules et des minuscules. Il doit avoir au minimum 12 caracteres.</p>
<script>
var pwd = document.getElementById("mdp");
var indicP = document.getElementById("indicationPwd");
var ok=false;

function afficher(){
	if(!ok)
	   indicP.style.display="block";
}

function cacher() {
	indicP.style.display="none";
}

pwd.addEventListener("focus", afficher, true);
pwd.addEventListener("blur", cacher, true);

pwd.onkeyup = function() {
	var lowerCaseLetters = /[a-z]/g;
	var upperCaseLetters = /[A-Z]/g;
	var numbers = /[0-9]/g;
	var min = pwd.value.match(lowerCaseLetters);
	var maj = pwd.value.match(upperCaseLetters);
	var num = pwd.value.match(numbers);
	if(min && maj && num && pwd.value.length >= 12){
		pwd.classList.remove("invalid");
		pwd.classList.add("valid");
		ok=true;
		cacher();
	} else {
		ok=false;
		pwd.classList.remove("valid");
		pwd.classList.add("invalid");
		afficher();
	}
}
</script>

<?php
      if (isset($_REQUEST["vide"]))
        echo '<p class="messageErreur">Merci de rentrer toutes les informations (numcompte est un entier et solde un nombre>0).</p>';
?>

            </div>
	</form>
  </section>
</body>
</html>
