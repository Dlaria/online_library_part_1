<?php 

// On inclue le fichier de configuration et de connexion a la base de donnees
require_once("includes/config.php");
// On recupere dans $_GET l email soumis par l'utilisateur
$get_email = $_GET["email"];

	// On verifie que l'email est un email valide (fonction php filter_var)
	if(filter_var($get_email, FILTER_VALIDATE_EMAIL) === false){
		// Si ce n'est pas le cas, on fait un echo qui signale l'erreur
		echo "Ce n'est pas une adresse mail valide";
	}else{
		// Si c'est bon
		// On prepare la requete qui recherche la presence de l'email dans la table tblreaders
		$sql = "SELECT * FROM tblreaders WHERE EmailId=:get_mail";
		$query = $dbh->prepare($sql);
		$query->bindParam(":get_mail", $get_email,PDO::PARAM_STR);
		// On execute la requete et on stocke le resultat de recherche
		$query->execute();
		$search_email = $query->fetch();
		if($search_email){
			// Si le resultat n'est pas vide. On signale a l'utilisateur que cet email existe deja et on desactive le bouton
			// de soumission du formulaire
			echo "Cette adresse mail existe déjà";
			
			
		}else{
			// Sinon on signale a l'utlisateur que l'email est disponible et on active le bouton du formulaire
			echo "Cette adresse mail est disponible";
			
		}
	}
		


