<?php
// Récupérer le score envoyé dans la requête POST
$score = $_POST['score'];

// Connexion à la base de données
try {
$bdd = new PDO('mysql:host=mysql-ubisuft.alwaysdata.net;dbname=ubisuft_slime_runner', 'ubisuft', '69751410');
// Facilite la gestion des erreurs de base de données
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(Exception $e) {
die('ERROR FAIL TO CONNECT TO DATABASE : ' . $e->getMessage());
}

// Préparer la requête SQL pour insérer le score dans la table appropriée
$sql = "INSERT INTO score (score) VALUES ('$score')";

// Exécuter la requête
if ($bdd->query($sql) === TRUE) {
    echo "Le meilleur score a été enregistré avec succès !";
} else {
    echo "Erreur lors de l'enregistrement du meilleur score : " . $conn->error;
}

// Fermer la connexion à la base de données
$bdd->close();
?>
