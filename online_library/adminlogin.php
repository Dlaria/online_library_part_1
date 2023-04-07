<?php
// On demarre ou on recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion � la base de donn�es
include('includes/config.php');

// On invalide le cache de session $_SESSION['alogin'] = ''
if (isset($_SESSION['login']) && $_SESSION['alogin'] != '') {
    $_SESSION['alogin'] = '';
}

// A faire :
// Apres la soumission du formulaire de login (plus bas dans ce fichier)
if (true === isset($_POST['login'])){
    // On verifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
    // $_POST["vercode"] et la valeur initialis�e $_SESSION["vercode"] lors de l'appel a captcha.php (voir plus bas
    if ($_POST['vercode'] != $_SESSION['vercode']){
        echo "<script>alert('Code de vérification incorrect')</script>";
    }else{
        // Le code est correct, on peut continuer
        // On recupere le nom de l'utilisateur saisi dans le formulaire
        if (preg_match("/^[a-zA-Z-' çèé]*$/",$_POST['name']) && strlen($_POST['name']) <= 20){
            $name = valid_donnee($_POST['name']);
            // On recupere le mot de passe saisi par l'utilisateur et on le crypte (fonction md5)
            $password = password_hash(valid_donnee($_POST['password']), PASSWORD_DEFAULT);

            // On construit la requete qui permet de retrouver l'utilisateur a partir de son nom et de son mot de passe
            // depuis la table admin
            $sql = "SELECT UserName, Password FROM admin WHERE UserName=:name";
            $query = $dbh->prepare($sql);
            $query->bindParam(':name',$name,PDO::PARAM_STR);
            $query->execute();

            $result = $query->fetch(PDO::FETCH_OBJ);

            if (!empty($result) && password_verify(valid_donnee($_POST['password']), $result->Password)){
                // Si le resultat de recherche n'est pas vide 
                // On stocke le nom de l'utilisateur  $_POST['username'] en session $_SESSION
                // On redirige l'utilisateur vers le tableau de bord administration (n'existe pas encore)
                $_SESSION['alogin'] = valid_donnee($_POST['name']);
                // header('location:online_library_part_2/dashboard.php');
                echo "<script>document.location.href='http://localhost/online_library_part_2/admin/dashboard.php';</script>";

            }else{
                // sinon le login est refuse. On le signal par une popup
                echo "<script>alert('Utilisateur inconnu')</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <?php include('includes/header.php'); ?>

    <div class="content-wrapper">
        <!--On affiche le titre de la page-->
        <div class="row">
			<div class="col">
                <h3>ADMIN LOGIN</h3>
			</div>
		</div>
        <!--On affiche le formulaire de login-->
        <div class="row">
		    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
                <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                <div class="form-group">
                    <label for="name">Entrez votre nom</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="form-group">
                    <!--A la suite de la zone de saisie du captcha, on ins�re l'image cr��e par captcha.php : <img src="captcha.php">  -->
                    <label for="vercode">Code de vérification</label>
                    <input type="text" name="vercode" required style="height:25px;">&nbsp;&nbsp;&nbsp;<img src="captcha.php">
                </div>
                <input type="submit" name="login" id="btnSubmit" class="btn btn-info" value ="Login"/>
                </form>
            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>