<div class="catalogue">
    <?php
    $serveur = "localhost";
    $utilisateur = "root";
    $mot_de_passe = "";
    $base_de_donnees = "balogun";

    // Créer une connexion à la base de données
    $connexion = new mysqli($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

    if ($connexion->connect_error) {
        die("Erreur de la connexion à la base de données : " . $connexion->connect_error);
    }

    // Sélectionner les données des candidats
    $sql = "SELECT id, nom_prenom, photo, nomine FROM candidat WHERE nomine = 'producteur_contenu'";
    $resultat = $connexion->query($sql);

    if ($resultat->num_rows > 0) {
        while ($row = $resultat->fetch_assoc()) {
            $id = $row['id'];
            $nom_prenom = $row['nom_prenom'];
            $photo = $row['photo'];
            $nomine = $row['nomine'];

            // Sélectionner la somme des points pour ce candidat depuis la table "vote"
            $sql_points = "SELECT SUM(points) AS somme_points FROM vote WHERE candidat_id = $id";
            $resultat_points = $connexion->query($sql_points);
            $row_points = $resultat_points->fetch_assoc();
            $somme_points = $row_points['somme_points'];

            echo '<div class="yra">';
            echo '<div class="candidat">';
            echo '<div class="vote"><img src="' . $photo . '" alt="Photo de ' . $nom_prenom . '"></div>';
            echo '</div>';
            echo '<div class="zero">';
            echo '<p>' . $nom_prenom . '</p>';
            echo '<div class="balo">';
            echo '<div class="one">' . $somme_points . '</div>'; // Affiche la somme des points
            echo '<form action="vote.php" method="POST">';
            echo '<input type="hidden" name="candidat_id" value="' . $id . '">';
            echo '<button type="submit" class="two">VOTEZ ICI</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        // Aucune donnée trouvée
        echo "Aucun candidat trouvé.";
    }

    // Fermer la connexion à la base de données
    $connexion->close();
    ?>
</div>
