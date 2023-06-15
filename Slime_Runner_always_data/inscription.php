<?php
session_start();

$error = null;
$bdd = null;

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
	// Rediriger vers la page de jeu si déjà connecté
    header("Location: game.php"); 
    exit;
}//

// Connexion à la base de données
try {
$bdd = new PDO('mysql:host=mysql-ubisuft.alwaysdata.net;dbname=ubisuft_slime_runner', 'ubisuft', '69751410');
// Facilite la gestion des erreurs de base de données
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(Exception $e) {
die('ERROR FAIL TO CONNECT TO DATABASE : ' . $e->getMessage());
}

// Inscription
if(isset($_POST['btn_register'])) {
    // Récupérer les données d'inscription soumises via le formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $username = $_POST['mail'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    if(!empty($nom) && !empty($prenom) && !empty($username) && !empty($password)) {
        // Verifier si l'email est valide
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
        	$sql = 'SELECT * FROM users WHERE mail = :mail';
        	$reqmail = $bdd->prepare($sql);
        	$reqmail->bindValue(':mail', $username, PDO::PARAM_STR);
        	$reqmail->execute();
			$mailexist = $reqmail->fetch();
			// Vérifier si l'utilisateur existe déjà dans la base de données
        	if($mailexist == 0) {
        		$highScore = 0;
        	    // Insérer un nouvel utilisateur dans la base de données
        	    $sql = 'INSERT INTO users (nom, prenom, mail, mdp, highScore) VALUES (:nom, :prenom, :mail, :mdp, :highScore)';
        	    $req = $bdd->prepare($sql);
        	    $req->bindValue(':nom', $nom, PDO::PARAM_STR);
        	    $req->bindValue(':prenom', $prenom, PDO::PARAM_STR);
        	    $req->bindValue(':mail', $username, PDO::PARAM_STR);
        	    $req->bindValue(':mdp', $password, PDO::PARAM_STR);
        	    $req->bindValue(':highScore', $highScore, PDO::PARAM_STR);
        	    $req->execute();
            	// Rediriger vers la page de connexion après l'inscription
            	header('Location: index.php');
        	} else {
        	    $error = 'User already exists';
        	}
    		} else {
    		    $error = '	Not valid email address';
    		}
			} else {
				$error = 'Missing fields';
			}
        
}
if(isset($_POST['btn_retour'])){
	header('Location: index.php');
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="icon" type="image/png" href="">
	<title>Slime Runner</title>
</head>
<div class="text-center">
<body class="text-light bg-dark">
	<h1>Inscrivez-vous pour jouer !</h1>
	<?php if($error != null) { ?>
		<font color="red"><?= $error ?></font>
	<?php } ?>
	<form action="inscription.php" method="post" id="formulaire">
		<div><input type="text" name="nom" placeholder="Nom"></div>
		<div><input type="text" name="prenom" placeholder="Prenom"></div>
		<div><input type="E-mail" name="mail" placeholder="E-mail"></div>
		<div><input type="password" name="password" placeholder="Mot de passe"></div>
		<div id="button_cont">
			<div><button name="btn_register" type="submit" class="btn btn-success">Valider</button></div>
			<div><button name="btn_retour "type="button" value="back" onclick="history.go(-1)"class="btn btn-secondary">Retour</button></div>
			<div><button name="effacer" type="reset" class="btn btn-danger">Effacer</button></div>
		</div>
	</form>
</body>
</div>
</html>