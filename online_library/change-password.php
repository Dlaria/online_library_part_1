<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion � la base de donn�es
include('includes/config.php');

if(strlen($_SESSION['login'])==0){
	// Si l'utilisateur n'est pas logue, on le redirige vers la page de login (index.php)
    header('location:index.php');
}else{
	// sinon, on peut continuer,
	// si le formulaire a ete envoye : $_POST['change'] existe
	if (true === isset($_POST['change'])){
		// On recupere le mot de passe et on le crypte (fonction php password_hash)
		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

		// On recupere l'email de l'utilisateur dans le tabeau $_SESSION
		$email = $_SESSION['login'];

		// On cherche en base l'utilisateur avec ce mot de passe et cet email
		$sql = "SELECT EmailId, Password FROM tblreaders WHERE EmailId=:email";
		$query = $dbh->prepare($sql);
		$query->bindParam(':email',$email,PDO::PARAM_STR);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_OBJ);

		if (!empty($result) && password_verify($_POST['password'], $result->Password)){
			// Si le resultat de recherche n'est pas vide
			// On met a jour en base le nouveau mot de passe (tblreader) pour ce lecteur
			$newPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
			$sql = "UPDATE tblreaders SET Password=:newPassword WHERE EmailId=:email";
			$query = $dbh->prepare($sql);
			$query->bindParam(':email',$email,PDO::PARAM_STR);
			$query->bindParam(':newPassword',$newPassword,PDO::PARAM_STR);
			$query->execute();
			// On stocke le message d'operation reussie
			$message = "Votre mot de passe a bien été changer";
		}else{
			// sinon (resultat de recherche vide)
			// On stocke le message "mot de passe invalide"
			$erreurs = "Mot de passe invalide";
		}

	}
}

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

	<title>Gestion de bibliotheque en ligne | changement de mot de passe</title>
	<!-- BOOTSTRAP CORE STYLE  -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<!-- FONT AWESOME STYLE  -->
	<link href="assets/css/font-awesome.css" rel="stylesheet" />
	<!-- CUSTOM STYLE  -->
	<link href="assets/css/style.css" rel="stylesheet" />

	<!-- Penser au code CSS de mise en forme des message de succes ou d'erreur -->

</head>
<script type="text/javascript">
	/* On cree une fonction JS valid() qui verifie si les deux mots de passe saisis sont identiques 
	Cette fonction retourne un booleen*/
	let valid = () => {
            let message = document.getElementById("message")
            let newPassword = document.getElementById("newPassword")
            let checkPassword = document.getElementById('checkPassword')
            if(newPassword.value === checkPassword.value){
            // TRUE si les mots de passe saisis dans le formulaire sont identiques
			return true;
        }
        else{
            // FALSE sinon
            message.innerHTML = 'Invalide';
            message.style.color = 'red';
            return false;
        }
        }
</script>

<body>
	<!-- Mettre ici le code CSS de mise en forme des message de succes ou d'erreur -->
	<?php include('includes/header.php'); ?>
	<!--On affiche le titre de la page : CHANGER MON MOT DE PASSE-->
	<div class="container">
       <div class="row">
           <div class="col">
               <h3>CHANGER MON MOT DE PASSE</h3>
           </div>
       </div>
	   <!--  Si on a une erreur, on l'affiche ici -->
	   <?php 
    if (isset($erreurs)){ 
        ?>
            <p><?php
             echo $erreurs;
            ?></p>
        <?php 
    }
    ?>
		<!--  Si on a un message, on l'affiche ici -->
	<?php     
    if (isset($message)){ 
        ?>
            <p><?php
             echo $message;
            ?></p>
        <?php 
    }
    ?>     
	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
				   <!--On affiche le formulaire-->
				   <!-- la fonction de validation de mot de passe est appelee dans la balise form : onSubmit="return valid();"-->
                    <form action="change-password.php" method="post" onSubmit="return valid();">
                         <div class="form-group">
                              <label for="password">Mot de passe actuel</label>
                              <input type="password" name="password" required>
                         </div>
                    <div class="form-group">
                         <label for="newPassword">Nouveau Mot de passe</label>
                         <input type="password" name="newPassword" id="newPassword" required>
                    </div>
                    <div class="form-group">
                         <label for="checkPassword">Confimez le mot de passe</label>
                         <input type="password" name="checkPassword" id="checkPassword" required><span id="message"></span>
                    </div>
                    <input type="submit" name="change" id="btnSubmit" class="btn btn-info" value ="Changer"/>
                    </form>
               </div>
          </div>
    </div>



	<?php include('includes/footer.php'); ?>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>