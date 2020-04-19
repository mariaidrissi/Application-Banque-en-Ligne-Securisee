<?php
session_start();

if(!isset($_SESSION['expire']) || time() > $_SESSION['expire']){
	session_unset();
	$url_redirect = "deconnexion.php";
	header("Location: $url_redirect");
	exit();
}

if(!isset($_SESSION["connected_user"])){
	$url_redirect = "../index.php";
	header("Location: $url_redirect");
	exit();
}

//si l'utilisateur va directement a la messagerie depuis virement pour eviter de pouvoir faire un transfert
if(isset($_SESSION['userVirement'])){
	unset($_SESSION['userVirement']);
}
?>
<script>
function corpsMessage(body){
 
      	body = body.replace('&', '&amp;');
      	body = body.replace('<', '&lt;');
      	body = body.replace('>', '&gt;');
      	body = body.replace('"', '&quot;');
      	body = body.replace("'", '&#x27;');
      	body = body.replace("/", '&#x2F;');
	body = body.replace("`", '&grave');

	document.getElementById("message").innerHTML = body;
}

</script>

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Messages</title>
  <link rel="stylesheet" type="text/css" media="all"  href="../css/style.css" />
</head>
<body>
    <header>

	<h1>Messagerie</h1>
	<div style="float:right;">
        <form method="POST" action="../controller/controller.php">
            <input type="hidden" name="action" value="disconnect">
            <button class="buttonDeconnexion">Déconnexion</button>
        </form>
	</div>

	<div style="float:right;">
		<button class="buttonStyle" onclick="location.href='accueil.php'">Revenir a l'accueil</button>
	</div>

        <h2 style="text-align:left;"><?php echo $_SESSION["connected_user"]["prenom"];?> <?php echo $_SESSION["connected_user"]["nom"];?></h2>
    </header>

    <section>
        <article>
          <div class="fieldset_messages">
            <table>
              <tr><th>Expéditeur</th><th>Sujet</th><th>Message</th></tr>
	      <?php
		
              foreach ($_SESSION['messagesRecus'] as $cle => $message) {
                echo '<tr>';
                echo '<td>'.$message['nom'].' '.$message['prenom'].'</td>';
                //echo '<td><button type="button" class="sujet" onclick="corpsMessage(\''.$message['corps_msg'].'\')">'.$message['sujet_msg'].'</button></td>';
		echo '<td>'.htmlspecialchars($message['sujet_msg']).'</td>';
		echo '<td>'.htmlspecialchars($message['corps_msg']).'</td>';
		echo '</tr>';
              }
               ?>
	    </table>
<!--
	   <br/>
	   <br/>
	   <div id="message" class="message">
Selectionnez un message.
	   </div>
-->
	  </div>
	</article>

	<article>

          <div class="fieldset_envoi">
	     <form method="POST" action="../controller/controller.php">
             <input type="hidden" name="action" value="sendmsg">
		  
		<div class="titre_envoi">
		  <h3>Envoyer un message</h3>
		</div>
		  
		<div class="contenu_message">
		  <div class="field">
                  	<label>Destinataire : </label>
                  	<select name="to">
			<?php
			if($_SESSION['connected_user']['profil_user']=="CLIENT"){
				foreach($_SESSION['listeEmployes'] as $id => $employe){
					echo '<option value="'.$id.'">'.$employe['nom'].' '.$employe['prenom'].'</option>';
				}
			} else {
                    		foreach ($_SESSION['listeUsers'] as $id => $user) {
                      			echo '<option value="'.$id.'">'.$user['nom'].' '.$user['prenom'].'</option>';
                    		}
			}
                       ?>
		  	</select>
		  </div>

		  <div class="field">
                  <label>Sujet : </label><input type="text" size="20" name="sujet">
		  </div>
		
		  <div class="field">
                  <label>Message : </label><textarea name="corps" cols="25" rows="3"></textarea>
		  </div>
		

                  <button class="buttonStyle" style="margin-top:3%;">Envoyer</button>
              	<?php
              		if (isset($_REQUEST["msg_ok"])) {
                	echo '<p class="messageReussite">Message envoyé avec succès.</p>';
			}else if (isset($_REQUEST['msg_fail'])) {
			echo '<p class="messageErreur">Le message n\'a pas pu etre envoye.</p>';
			}
		?>

	     </div>
	   </form>	
	</div>
	</article>

    </section>
</body>
</html>
