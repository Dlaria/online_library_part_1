<?php 
// On r�cup�re la session courante
session_start();

// On inclue le fichier de configuration et de connexion � la base de donn�es
include('includes/config.php');

// Si l'utilisateur n'est plus logu�
if(strlen($_SESSION['login'])==0){
    // On le redirige vers la page de login
    header('location:index.php');
}else{
	// Sinon on peut continuer. Apr�s soumission du formulaire de profil
    if (true === isset($_POST['login'])){
        // On recupere l'id du lecteur (cle secondaire)
        $readerId = $_SESSION['rdid'];
        // On recupere le nom complet du lecteur
        $fullName = $_POST['fullName'];
        // On recupere le numero de portable
        $mobileNum = $_POST['portable'];
        // On recupere l'adresse mail
        $email = $_POST['email'];

        // On update la table tblreaders avec ces valeurs
        $sql = "UPDATE tblreaders SET FullName=:fullName, EmailId=:email, MobileNumber=:mobileNum WHERE ReaderId=:readerId";
        $query = $dbh->prepare($sql);
        $query->bindParam(':fullName',$fullName,PDO::PARAM_STR);
        $query->bindParam(':email',$email,PDO::PARAM_STR);
        $query->bindParam(':mobileNum',$mobileNum,PDO::PARAM_STR);
        $query->bindParam(':readerId',$readerId,PDO::PARAM_STR);
        $query->execute();

        // On informe l'utilisateur du resultat de l'operation
        echo "<script>alert('Nom modifier : ".$fullName." Email modifier : ".$email." Numéro de téléphone modifier :".$mobileNum."')</script>";
    }
    // On souhaite voir la fiche de lecteur courant.
    // On recupere l'id de session dans $_SESSION
    $readerId = $_SESSION['rdid'];
    // On prepare la requete permettant d'obtenir
    $sql = "SELECT RegDate, UpdateDate, Status, FullName, EmailId, MobileNumber FROM tblreaders WHERE ReaderId=:readerId";
    $query = $dbh->prepare($sql);
    $query->bindParam(':readerId',$readerId,PDO::PARAM_STR);
    $query->execute();

    $result = $query->fetch(PDO::FETCH_OBJ);

    if (!empty($result)){
        $regDate = $result->RegDate;
        $upDate = $result->UpdateDate;
        $statusNum = $result->Status;
        $name = $result->FullName;
        $email = $result->EmailId;
        $mobileNumber = $result->MobileNumber;

        if ($statusNum = 1){
            $status = "Actif";
        }else{
            $status = "Inactif";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Profil</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />

</head>
<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
<?php include('includes/header.php');?>
<!--On affiche le titre de la page : EDITION DU PROFIL-->
<div class="container">
       <div class="row">
           <div class="col">
               <h3>EDITION DU PROFIL</h3>
           </div>
       </div>
   
       <div class="row">
           <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
               <form method="post" action="my-profile.php">
                    <!--On affiche le formulaire-->
					<div class="form-group">
                        <!--On affiche l'identifiant - non editable-->
                        <span name="rdId">Identifiant : <?php echo $_SESSION['rdid'];?></span>
					</div>

					<div class="form-group">
                        <!--On affiche la date d'enregistrement - non editable-->
						<span name="regDate">Data d'enregistrement : <?php echo $regDate;?></span>
					</div>

					<div class="form-group">
                        <!--On affiche la date de derniere mise a jour - non editable-->
                        <span name="upData">Dernière mise à jour : <?php echo $upDate;?></span>
					</div>
					<div class="form-group">
                        <!--On affiche la statut du lecteur - non editable-->
                        <span name="status">Status : <?php echo $status;?></span>

					</div>
					<div class="form-group">
                        <!--On affiche le nom complet - editable-->
                        <label for="fullName">Nom complet :</label>
                        <input type="text" name="fullName" value="<?php echo $name; ?>" required>

					</div>
					<div class="form-group">
                        <!--On affiche le numero de portable- editable-->
                        <label for="portable">Numéro de portable :</label>
                        <input type="text" name="portable" value="<?php echo $mobileNumber; ?>" required>

					</div>
					<div class="form-group">
                        <!--On affiche l'email- editable-->
                        <label for="email">Email :</label>
                        <input type="text" name="email" value="<?php echo $email; ?>" required>

					</div>

					<button type="submit" name="login" class="btn btn-info">Mettre à jour</button>
				</form>
			</div>
		</div>
	</div>

    <?php include('includes/footer.php');?>
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
