<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de donn�es
include('includes/config.php');


if(strlen($_SESSION['login'])==0) {
	// Si l'utilisateur est d�connect�
	// L'utilisateur est renvoy� vers la page de login : index.php
  header('location:index.php');
} else {
	// On r�cup�re l'identifiant du lecteur dans le tableau $_SESSION
	$readerId = $_SESSION['rdid'];

	// On veut savoir combien de livres ce lecteur a emprunte
	// On construit la requete permettant de le savoir a partir de la table tblissuedbookdetails
     $sql = "SELECT ReaderID FROM tblissuedbookdetails WHERE ReaderID=:readerId";
     $query = $dbh->prepare($sql);
     $query->bindParam(":readerId",$readerId,PDO::PARAM_STR);
     $query->execute();

	// On stocke le r�sultat dans une variable
     $result = $query->fetchAll(PDO::FETCH_OBJ);
	//error_log(var_dump($result));
	
	// On veut savoir combien de livres ce lecteur n'a pas rendu
	// On construit la requete qui permet de compter combien de livres sont associ�s � ce lecteur avec le ReturnStatus � 0 
	$sql = "SELECT ReturnStatus FROM tblissuedbookdetails WHERE ReaderID=:readerId AND ReturnStatus=0";
     $query = $dbh->prepare($sql);
     $query->bindParam(":readerId",$readerId,PDO::PARAM_STR);
     $query->execute();

	// On stocke le r�sultat dans une variable
     $resultNum = $query->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Gestion de librairie en ligne | Tableau de bord utilisateur</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
     <!--On inclue ici le menu de navigation includes/header.php-->
<?php include('includes/header.php');?>
<!-- On affiche le titre de la page : Tableau de bord utilisateur-->
     <div class="content-wrapper">
		<div class="row">
			<div class="col">
				<h3>Tableau de bord utilisateur</h3>
			</div>
		</div>
          <div class="fa fa-border" style="text-align:center">
               <!-- On affiche la carte des livres emprunt�s par le lecteur-->
               <div class="fa-5x fa-bars"></div>
               <span name="livreEmprunter" class="fa-2x"><?php echo count($result);?></span>
               <p class="fa-2x">Livre empruntés</p>
               
          </div>
          <!-- On affiche la carte des livres non rendus le lecteur-->
          <div class="fa fa-border" style="text-align:center">
               <div class="fa-5x fa-recycle"></div>
               <span name="livreNonRendu" class="fa-2x"><?php echo count($resultNum);?></span>
               <p class="fa-2x">Livre non encore rendu</p>
          </div>
	</div>
 
        

<?php include('includes/footer.php');?>
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>
