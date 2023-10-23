<?php
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$base_de_donnees = "balogun"; 
$connexion = new mysqli($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

if ($connexion->connect_error) {
    die("Erreur de connexion à la base de données : " . $connexion->connect_error);
}

// Récupère les données du formulaire de vote
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $candidat_id = $_POST['candidat_id'];
    $adresse_ip = $_SERVER['REMOTE_ADDR']; // Adresse IP de l'utilisateur

    // Vérifie si l'utilisateur a déjà voté aujourd'hui 
    $verification_query = "SELECT COUNT(*) as total FROM vote WHERE ip = ? AND DATE(vote_date) = CURDATE()";
    $stmt = $connexion->prepare($verification_query);
    $stmt->bind_param("s", $adresse_ip);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $nombre_de_votes = $row['total'];

    if ($nombre_de_votes > 0) {
        echo "Vous avez déjà voté aujourd'hui.";
    } else {
        // Enregistre le vote dans la base de données
        $insert_query = "INSERT INTO vote (ip, candidat_id, points) VALUES (?, ?, 1)";
        $stmt = $connexion->prepare($insert_query);
        $stmt->bind_param("si", $adresse_ip, $candidat_id);

        if ($stmt->execute()) {
            echo "Votre vote a été enregistré avec succès.";

            // Mettre à jour les résultats des candidats
            $update_query = "UPDATE candidat SET points = points + 1 WHERE id = ?";
            $stmt = $connexion->prepare($update_query);
            $stmt->bind_param("i", $candidat_id);
            $stmt->execute();
        } else {
            echo "Erreur lors de l'enregistrement de votre vote : " . $stmt->error;
        }
    }
}

// Fermer la connexion à la base de données
$connexion->close();
?>
