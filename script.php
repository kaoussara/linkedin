<?php
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$base_de_donnees = "balogun";

// Création de la connexion à la base de données
$connexion = new mysqli($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

// Vérification de la connexion
if ($connexion->connect_error) {
    die("Erreur de la connexion à la base de données : " . $connexion->connect_error);
}

// Vérifie si la requête est de type POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Vérifie si l'utilisateur est authentifié
    if (isset($_SESSION['utilisateur_connecte'])) {
        // Récupère l'ID du candidat voté à partir des données POST
        $candidat_id = isset($_POST['candidat_id']) ? intval($_POST['candidat_id']) : null;

        if ($candidat_id !== null) {
            $response = array();

            // Requête SQL pour enregistrer le vote dans la table "vote"
            $insert_query = "INSERT INTO vote (ip, candidat_id, points) VALUES (?, ?, 1)";
            $stmt = $connexion->prepare($insert_query);
            $stmt->bind_param("si", $adresse_ip, $candidat_id);

            if ($stmt->execute()) {
                // mettre à jour les points du candidat
                $update_query = "UPDATE candidat SET points = points + 1 WHERE id = ?";
                $stmt = $connexion->prepare($update_query);
                $stmt->bind_param("i", $candidat_id);
                if ($stmt->execute()) {
                    // Requête SQL pour obtenir le nouveau nombre de points du candidat
                    $points_query = "SELECT points FROM candidat WHERE id = ?";
                    $stmt = $connexion->prepare($points_query);
                    $stmt->bind_param("i", $candidat_id);
                    if ($stmt->execute()) {
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        $nouveau_nombre_de_points = $row['points'];
                        $response = array('message' => 'Votre vote a été enregistré avec succès.', 'nouveau_nombre_de_points' => $nouveau_nombre_de_points);
                    } else {
                        $response = array('message' => 'Erreur lors de la récupération du nombre de points mis à jour.');
                    }
                } else {
                    // En cas d'erreur de mise à jour des points
                    $response = array('message' => 'Erreur lors de la mise à jour des points du candidat : ' . $connexion->error);
                }
            } else {
                // En cas d'erreur d'enregistrement du vote
                $response = array('message' => 'Erreur lors de l\'enregistrement de votre vote : ' . $stmt->error);
            }
        } else {
            // Réponse en cas de données de vote manquantes ou invalides
            $response = array('message' => 'Données de vote manquantes ou invalides.');
        }
    } else {
        // Réponse en cas d'utilisateur non authentifié
        $response = array('message' => 'L\'utilisateur doit être authentifié pour voter.');
    }
} else {
    // Réponse en cas de requête incorrecte
    $response = array('message' => 'Requête incorrecte.');
}

// Fermeture de la connexion à la base de données
$connexion->close();

// Renvoie la réponse au format JSON
echo json_encode($response);
?>
