<?php
// On récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');
// Après la soumission du formulaire de login $_POST['login'] existe
if (true === isset($_POST['login'])){
     // On verifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
     // $_POST["vercode"] et la valeur initialisee $_SESSION["vercode"] lors de l'appel a captcha.php (voir plus bas)
     if ($_POST['vercode'] != $_SESSION['vercode']) {
          // Si le code est incorrect on informe l'utilisateur par une fenetre pop_up
          echo "<script>alert('Code de vérification incorrect')</script>";
     } else {
          // Sinon on continue
          // on recupere l'email et le numero de portable saisi par l'utilisateur
          // et le nouveau mot de passe que l'on encode (fonction password_hash)
          $email = $_POST['email'];
          $mobileNum = $_POST['portable'];
          $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

          // On cherche en base le lecteur avec cet email et ce numero de tel dans la table tblreaders
          $sql = "SELECT EmailId, MobileNumber FROM tblreaders WHERE EmailId=:email AND MobileNumber=:mobileNum";
          $query = $dbh->prepare($sql);
          $query->bindParam(":email", $email,PDO::PARAM_STR);
          $query->bindParam(":mobileNum", $mobileNum,PDO::PARAM_INT);
          $query->execute();

          // On stocke le resultat de recherche dans une variable $result
          $result = $query->fetch(PDO::FETCH_OBJ);

          if(!empty($result)){
               // Si le resultat de recherche n'est pas vide
               // On met a jour la table tblreaders avec le nouveau mot de passe
               // On informa l'utilisateur par une fenetre popup de la reussite ou de l'echec de l'operation
               $sql = "UPDATE tblreaders SET Password=:newPassword WHERE EmailId=:email";
               $query = $dbh->prepare($sql);
               $query->bindParam(":email", $email,PDO::PARAM_STR);
               $query->bindParam(":newPassword", $newPassword,PDO::PARAM_STR);
               $query->execute();
          }else{
               echo "<script>alert(\"Adresse mail ou numéro de téléphone inconnu\")</script>";
          }



     }
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
     <meta charset="utf-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

     <title>Gestion de bibliotheque en ligne | Recuperation de mot de passe </title>
     <!-- BOOTSTRAP CORE STYLE  -->
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
     <!-- FONT AWESOME STYLE  -->
     <link href="assets/css/font-awesome.css" rel="stylesheet" />
     <!-- CUSTOM STYLE  -->
     <link href="assets/css/style.css" rel="stylesheet" />

     <script type="text/javascript">
          // On cree une fonction nommee valid() qui verifie que les deux mots de passe saisis par l'utilisateur sont identiques.
          let valid = () => {
            let message = document.getElementById("message")
            let password = document.getElementById("password")
            let checkPassword = document.getElementById('checkPassword')
            if(password.value === checkPassword.value){
            // TRUE si les mots de passe saisis dans le formulaire sont identiques
        }
        else{
            // FALSE sinon
            message.innerHTML = 'Invalide';
            message.style.color = 'red';
            return false;
        }
        }
     </script>

</head>

<body>
     <!--On inclue ici le menu de navigation includes/header.php-->
     <?php include('includes/header.php'); ?>
     <!-- On insere le titre de la page (RECUPERATION MOT DE PASSE -->
     <div class="container">
          <div class="row">
                    <div class="col">
                         <h3>RECUPERATION MOT DE PASSE</h3>
                    </div>
               </div>

          <!--On insere le formulaire de recuperation-->
          <!--L'appel de la fonction valid() se fait dans la balise <form> au moyen de la propri�t� onSubmit="return valid();"-->
          <div class="row">
               <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
                    <form action="user-forgot-password.php" method="post" onSubmit="return valid();">
                         <div class="form-group">
                              <label for="email">Email</label>
                              <input type="text" name="email" required>
                         </div>
                         <div class="form-group">
                              <label for="portable">Portable</label>
                              <input type="text" name="portable" required>
                         </div>
                    <div class="form-group">
                         <label for="password">Nouveau Mot de passe</label>
                         <input type="password" name="password" id="password" required>
                    </div>
                    <div class="form-group">
                         <label for="checkPassword">Confimez le mot de passe</label>
                         <input type="password" name="checkPassword" id="checkPassword" required><span id="message"></span>
                    </div>
                    <div class="form-group">
                         <!--A la suite de la zone de saisie du captcha, on insère l'image créée par captcha.php : <img src="captcha.php">  -->
                         <label for="vercode">Code de vérification</label>
                         <input type="text" name="vercode" required style="height:25px;">&nbsp;&nbsp;&nbsp;<img src="captcha.php">
                    </div>
                    <input type="submit" name="login" id="btnSubmit" class="btn btn-info" value ="Envoyer"/>|<a href="index.php">Login</a>
                    </form>
               </div>
          </div>
     </div>


     <?php include('includes/footer.php'); ?>
     <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>