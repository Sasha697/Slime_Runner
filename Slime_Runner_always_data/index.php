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

// Connexion
if(isset($_POST['btn_connect'])) {
	// Récupérer les données de connexion soumises via le formulaire
	$username = $_POST['mail'];
	$password = $_POST['password'];
	if(!empty($username)){
		if(!empty($password)) {
		$sql = 'SELECT * FROM users WHERE mail = :mail'; /* requete sql de base */
		$req = $bdd->prepare($sql);
		$req->bindValue(':mail', $username, PDO::PARAM_STR);
		$req->execute();
		// Cherche les infos de la data base de données
		$data = $req->fetch(); 
			// data est la table de la base de données
			if($data) {
				if(password_verify($password, $data['mdp'])) { 
					$_SESSION['id'] = $data['id'];
					$_SESSION['username'] = $data['mail'];
					$_SESSION['password'] = $data['mdp'];
					$_SESSION['nom'] = $data['nom'];
					$_SESSION['prenom'] = $data['prenom'];
					$_SESSION['highScore'] = $data['highScore'];
					$_SESSION['loggedin'] = true;
					header('Location: game.php');/*renvoie sur le site demander*/
					exit;
				} else {
					$error = 'Bad password';
				}
			} else {
				$error = 'User unknown';
			}
		} else {
			$error = 'Missing password';
		}
	} else {
		$error = "Missing mail";
	}
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
	<h1>Connectez-vous pour jouer !</h1>
	<?php if($error != null) { ?>
		<font color="red"><?= $error ?></font>
	<?php } ?>
	<form action="index.php" method="post" id="formulaire">
		<div><input type="text" name="mail" placeholder="E-mail"></div>
		<div><input type="password" name="password" placeholder="Mot de passe"></div>
		<div id="button_cont">
			<div><button name="btn_connect" type="submit" class="btn btn-primary">Se connecter</button></div>
			<div><a href="inscription.php" class="btn btn-success">S'inscrire</a></div>
			<div><button name="effacer" type="reset" class="btn btn-danger">Effacer</button></div>
		</div>
	</form>
</body>
</div>
</html>

