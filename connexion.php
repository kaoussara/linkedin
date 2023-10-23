<?php
session_start(); 

$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$base_de_donnees = "balogun";

$connexion = new mysqli($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

if ($connexion->connect_error) {
    die("Erreur de la connexion à la base de données : " . $connexion->connect_error);
}

$erreur = ""; // Initialiser la variable d'erreur

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Vérifier les informations de connexion dans la base de données
    $verification_query = "SELECT id, email, mot_de_passe FROM utilisateurs WHERE email = ?";
    $stmt = $connexion->prepare($verification_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($mot_de_passe, $row['mot_de_passe'])) {
            // Connexion réussie, enregistrer l'ID de l'utilisateur dans la session
            $_SESSION['utilisateur_id'] = $row['id'];
            header("Location: http://localhost/back-end/vote/page.php"); // Rediriger vers la page du tableau de bord
            exit();
        } else {
            $erreur = "Mot de passe incorrect.";
        }
    } else {
        $erreur = "Adresse e-mail non trouvée.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <form action="connexion.php" method="post">
        <label for="email">Email :</label>
        <input type="email" name="email" required><br><br>

        <label for="mot_de_passe">Mot de passe :</label>
        <input type="password" name="mot_de_passe" required><br><br>

        <input type="submit" value="Se connecter">
    </form>

    <?php
    if (!empty($erreur)) {
        echo '<p style="color: red;">' . $erreur . '</p>';
    }
    ?>
</body>
</html>
