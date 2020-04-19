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
    <button class="buttonStyle" onclick="location.href='connexion.php'">Revenir a l'accueil</button>
  </header>
  
  <section>
	<form method="POST" action="../controller/controller.php">
	  <div class="login">
           <input type="hidden" name="action" value="changeMdp">
	   <input type="text" name="login" placeholder="login"/>
	   <input type="password" name="ancienMdp" placeholder="ancienMdp"/>
           <input type="password" class="invalid" id="nouveauMdp" name="nouveauMdp" placeholder="nouveauMdp"/>
	  </div>

	<p id="indicationPwdChange" class="messageErreur"> Le mot de passe doit contenir des chiffres, des majuscules et des minuscules. Il doit avoir au minimum 12 caracteres.</p>
<script>
var pwd = document.getElementById("nouveauMdp");
var indicP = document.getElementById("indicationPwdChange");
var ok =false;

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
	}else{
		ok=false;
		pwd.classList.remove("valid");
		pwd.classList.add("invalid");
		afficher();
	}
}
</script>
	  <div>
	   <button class="buttonConnexion">Mettre a jour</button>
	  </div>
	</form>

<?php
      session_start();
      if (isset($_REQUEST["vide"])) {
        echo '<p class="messageErreur">Merci de saisir votre login et votre mot de passe</p>';
      }
      ?>
  </section>

</body>
</html>
