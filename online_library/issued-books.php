<?php
// On r�cup�re la session courante
session_start();

// On inclue le fichier de configuration et de connexion � la base de donn�es
include('includes/config.php');

// Si l'utilisateur n'est pas connecte, on le dirige vers la page de login
if(strlen($_SESSION['login'])==0){
    // On le redirige vers la page de login
    header('location:index.php');
}else{
    // Sinon on peut continuer
    //var_dump($_SESSION);
    $readerId = $_SESSION['rdid'];
    $sqlReader = "SELECT BookId, IssuesDate, ReturnDate FROM tblissuedbookdetails WHERE ReaderId=:readerId";
    $query = $dbh->prepare($sqlReader);
    $query->bindParam(':readerId',$readerId,PDO::PARAM_STR);
    $query->execute();
    
    
    //var_dump($result);

    
    //var_dump($reponse);
}
//	Si le bouton de suppression a ete clique($_GET['del'] existe)
		//On recupere l'identifiant du livre
		// On supprime le livre en base
		// On redirige l'utilisateur vers issued-book.php
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Gestion des livres</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
      <!--On insere ici le menu de navigation T-->
<?php include('includes/header.php');?>
	<!-- On affiche le titre de la page : LIVRES SORTIS --> 
    <div class="container">
       <div class="row">
           <div class="col">
               <h3>LIVRES SORTIS</h3>
           </div>
       </div>     
    </div>     
    <table>
        <tr>
            <th>
                #
            </th>
            <th>
                Titre
            </th>
            <th>
                ISBN
            </th>
            <th>
                Date de sortie
            </th>
            <th>
                Date de retour
            </th>
        </tr>
        <!-- On affiche la liste des sorties contenus dans $results sous la forme d'un tableau -->
        <?php 
           $lineNumber = 0;
           while ($result = $query->fetch()){
            $bookId = $result['BookId'];
            $sql = "SELECT BookName, ISBNNumber FROM tblbooks WHERE ISBNNumber=:bookId";
            $query2 = $dbh->prepare($sql);
            $query2->bindParam(':bookId',$bookId,PDO::PARAM_STR);
            $query2->execute();
            $reponse = $query2->fetch();
               $lineNumber++;
               ?>
            <tr>
                <td><?php echo $lineNumber; ?></td>
                <td><?php echo $reponse['BookName'];?></td>
                <td><?php echo $reponse['ISBNNumber'];?></td>
                <td><?php echo $result['IssuesDate']; ?></td>

                <td><?php if (empty($result['ReturnDate'])){
                    echo 'Non retourné';
                }else{
                    echo $result['ReturnDate']; 
                } ?></td>
            </tr>
            <?php
        }
        ?>
        </table>
           <!-- Si il n'y a pas de date de retour, on affiche non retourne --> 


  <?php include('includes/footer.php');?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>

