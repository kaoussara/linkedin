<!DOCTYPE html>
<html>
<head>
    <title>Formulaire d'Inscription/Connexion</title>
</head>
<body>
    <h2>Bienvenue sur notre site</h2>
    <p>Veuillez choisir une option :</p>
    <form action="#" method="post">
        <input type="submit" name="inscription" value="Inscription">
        <input type="submit" name="connexion" value="Connexion">
    </form>

    <?php
    if (isset($_POST['inscription'])) {
        header("Location: inscription.php"); // Rediriger vers la page d'inscription
        exit();
    }

    if (isset($_POST['connexion'])) {
        header("Location: connexion.php"); // Rediriger vers la page de connexion
        exit();
    }
    ?>
</body>
</html>
