<?php 
// Configuration de la connexion
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','root');
define('DB_NAME','library');

try
{
    // Connexion � la base
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS);
}
catch (PDOException $e)
{
	// Echec de la connexion
    exit("Error: " . $e->getMessage());
}

function valid_donnee($data){
    //trim() supprime les caratères inutiles (espace supplémentaire, tabulation, saut de ligne)
    $data = trim($data);
    //stipslashes() supprime les barres obliques inverses ou anti-slashe (\)
    $data = stripslashes($data);
    //htmlspecialchars() convertit les caractèred spéciaux en entités HTML
    $data = htmlspecialchars($data);
    return $data;
}
?>