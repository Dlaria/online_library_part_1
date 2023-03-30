<?php
// On récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

if (isset($_SESSION['login']) && $_SESSION['login'] != '') {
	$_SESSION['login'] = '';
}
// Après la soumission du formulaire de compte (plus bas dans ce fichier)
// On vérifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
// $_POST["vercode"] et la valeur initialisée $_SESSION["vercode"] lors de l'appel à captcha.php (voir plus bas)
if (true === isset($_POST['login'])){
if ($_POST['vercode'] != $_SESSION['vercode']){
    echo "<script>alert('Code de vérification incorrect')</script>";
}else{
    //On lit le contenu du fichier readerid.txt au moyen de la fonction 'file'. Ce fichier contient le dernier identifiant lecteur cree.
    $file_readerid = file('http://localhost/online_library_part_1/online_library/readerid.txt');
    foreach ($file_readerid as $reader_id) {
        // On incrémente de 1 la valeur lue
        $reader_id++;
        // On ouvre le fichier readerid.txt en écriture
        $fichier = fopen("readerid.txt", "c+b");
        // On écrit dans ce fichier la nouvelle valeur
        fwrite($fichier, $reader_id);
        // On referme le fichier
        fclose($fichier);

        // echo $reader_id;
    }
    // On récupère le nom saisi par le lecteur
    $reader_name = $_POST['fullName'];
    // On récupère le numéro de portable
    $reader_mobile = $_POST['portable'];
    // On récupère l'email
    $reader_email = $_POST['email'];
    // On récupère le mot de passe
    $reader_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    // On fixe le statut du lecteur à 1 par défaut (actif)
    $reader_status = 1;
    // On prépare la requete d'insertion en base de données de toutes ces valeurs dans la table tblreaders
    $sql = "INSERT INTO tblreaders (ReaderId, FullName, EmailId, MobileNumber, Password, Status)
    VALUE (:reader_id, :reader_name, :reader_email, :reader_mobile, :reader_password, :reader_status)";
    $query = $dbh->prepare($sql);
    $query->bindParam(":reader_id", $reader_id,PDO::PARAM_STR);
    $query->bindParam(":reader_name",$reader_name,PDO::PARAM_STR);
    $query->bindParam(":reader_email",$reader_email,PDO::PARAM_STR);
    $query->bindParam(":reader_mobile",$reader_mobile,PDO::PARAM_STR);
    $query->bindParam(":reader_password",$reader_password,PDO::PARAM_STR);
    $query->bindParam(":reader_status",$reader_status,PDO::PARAM_INT);
    // On éxecute la requete
    $query->execute();

    // On récupère le dernier id inséré en bd (fonction lastInsertId)
    $hit = $dbh->lastInsertId();
    if ($hit !== null){
        // Si ce dernier id existe, on affiche dans une pop-up que l'opération s'est bien déroulée, et on affiche l'identifiant lecteur (valeur de $hit[0])
        echo '<script>alert("L\'opération s\'est bien déroulée Id: '.$hit.'")</script>';
    }else{
        // Sinon on affiche qu'il y a eu un problème
        echo '<script>alert("L\'opération ne s\'est pas bien déroulée")</script>';
}}}?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <title>Gestion de bibliotheque en ligne | Signup</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <!-- link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' / -->
    <script type="text/javascript">
        // On cree une fonction valid() sans paramètre qui renvoie 
        let valid = () => {
            let message = document.getElementById("message")
            let password = document.getElementById("password")
            let checkPassword = document.getElementById('checkPassword')
            if(password.value === checkPassword.value){
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

        // On cree une fonction avec l'email passé en paramêtre et qui vérifie la disponibilité de l'email
        // Cette fonction effectue un appel AJAX vers check_availability.php
        let checkAvailability = (email) => {
            console.log(email);
            let btnSubmit = document.getElementById('btnSubmit');
            if (email.length != 0){
                //      ===Voir en méthode fetch()===
            //Création d'un nouvel objet XMLHttpRequest()
            const xhttp = new XMLHttpRequest();
            //Définition d'un fonction à appeler lorsque la demande est chargée
            xhttp.onload = function(){
                
                //responseText renvoie la réponse du serveur sous forme d'une chaîne de caractère
                document.getElementById("verif").innerHTML = this.responseText;
                if(this.responseText === "Cette adresse mail existe déjà"){
                    btnSubmit.setAttribute('disabled',false);
                }else{
                    btnSubmit.removeAttribute('disabled',false);
                }
            }
            //@param GET spécifie la méthode de requete 
            //@param true spécifie que la requete est asynchrone
            //comme ça javascript n'a pas à attrendre la réponse du serveur
            xhttp.open("GET", "check_availability.php?email="+email,true);
            //Envoie la requete au serveur
            xhttp.send();
            //console.log(xhttp)
        }
    }
    </script>
</head>

<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <?php include('includes/header.php'); ?>
    <!--On affiche le titre de la page : CREER UN COMPTE-->
    <div class="container">
        <div class="row">
                <div class="col">
                    <h3>CREER UN COMPTE</h3>
                </div>
            </div>
        <!--On affiche le formulaire de creation de compte-->
        <!-- On appelle la fonction valid() dans la balise <form> onSubmit="return valid(); -->
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
                <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']);?>" onSubmit="return valid();">
                    <div class="form-group">
                        <label for="allName">Entrez votre nom complet</label>
                        <input type="text" name="fullName" required>
                    </div>
                    <div class="form-group">
                        <label for="portable">Portable</label>
                        <input type="text" name="portable" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" onBlur="checkAvailability(this.value);" required><span id="verif"></span>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
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
                    <input type="submit" name="login" id="btnSubmit" class="btn btn-info" value ="Enregister"/>
                </form>
            </div>
        </div>
    </div>
    
    
    <!-- On appelle la fonction checkAvailability() dans la balise <input> de l'email onBlur="checkAvailability(this.value)" -->

    <?php include('includes/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>