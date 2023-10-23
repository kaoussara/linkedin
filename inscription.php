<?php
$serveur="localhost";
$utilisateur="root";
$mot_de_passe="";
$base_de_donnees="balogun";

$connexion = new mysqli($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

if ($connexion->connect_error) {
    die("Erreur de la connexion à la base de données : ". $connexion->connect_error);
}

$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$email = $_POST['email'];
$mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);


$insert_query = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe) VALUES(?,?,?,?)";
$stmt = $connexion->prepare($insert_query);
$stmt->bind_param("ssss", $nom, $prenom, $email, $mot_de_passe);


if ($stmt->execute()) {
    header("Location: connexion.php");
    echo "Inscription réussie. Vous pouvez maintenant vous connecter.";
} else {
    echo "Erreur lors de l'inscription : " . $stmt->error;
}


$stmt->close();
$connexion->close();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
</head>
<body>
    <h2>Inscription</h2>
    <form action="inscription.php" method="post">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" required><br><br>

        <label for="prenom">Prenom :</label>
        <input type="text" name="prenom" required><br><br>

        <label for="email">Email :</label>
        <input type="email" name="email" required><br><br>

        <label for="mot_de_passe">Mot de passe :</label>
        <input type="password" name="mot_de_passe" required><br><br>

        <input type="submit" value="S'inscrire">
    </form>
</body>
</html>

