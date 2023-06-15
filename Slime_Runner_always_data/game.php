<?php 
session_start();

$bdd = null;

// Vérifier si l'utilisateur est déjà connecté
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
    // Rediriger vers la page d'index si l'utilisateur n'est pas connecté
    $error = "Connectez-vous !";
    header("Location: index.php");
    exit;
}

$inactive_timeout = 5 * 60; // 5 minutes en secondes
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive_timeout) {
	echo"<script>alert('5 minutes sans aucune activité sur l'application, vous allez être amener à vous reconnecter!')</script>";
    // Détruire la session et rediriger vers la page de connexion
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
} else {
	// Mettre à jour la dernière activité
    $_SESSION['last_activity'] = time(); 
}

// Connexion à la base de données
try {
$bdd = new PDO('mysql:host=mysql-ubisuft.alwaysdata.net;dbname=ubisuft_slime_runner', 'ubisuft', '69751410');
// Facilite la gestion des erreurs de base de données
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(Exception $e) {
die('ERROR FAIL TO CONNECT TO DATABASE : ' . $e->getMessage());
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Slime Runner</title>
</head>
<body>
<section>
	<div class="game">
		<div id ="character">
			<img src="assets/img/character/Slime.gif">
		</div>
		<div id ="obstacle">
			<img src="assets/img/traps/spike.png">
		</div>
	</div>
</section>
<div class="controls">
	<a onclick="Start()">START</a>
    <a class="btn btn-danger" onclick="logout()">Quit</a>
	<p>Score: <span id="score">00</span></p>
	<p>Record: <span id="highScore">00</span></p>
</div>
</body>
<script src="script.js"></script>
</html>