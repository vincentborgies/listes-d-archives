<?php
    $server = "localhost";
    $user = "root";
    $password = "";
    $db = "cda";
    
    // Créez une connexion à la base de données
    $connexion = new mysqli($server, $user, $password, $db);
    
    // Vérifiez si la connexion a réussi
    if ($connexion->connect_error) {
        die("Échec de la connexion à la base de données : " . $connexion->connect_error);
    }

    // Sélectionnez les utilisateurs inscrits il y a 1 an ou moins
    $oneYearAgo = date("Y-m-d", strtotime("-1 year"));
    $sql1Year = "SELECT * FROM user WHERE subscribe_date >= '$oneYearAgo'";
    $result1Year = $connexion->query($sql1Year);

    // Sélectionnez les utilisateurs inscrits il y a entre 1 et 2 ans
    $twoYearsAgo = date("Y-m-d", strtotime("-2 years"));
    $sql1To2Years = "SELECT * FROM user WHERE subscribe_date >= '$twoYearsAgo' AND subscribe_date < '$oneYearAgo'";
    $result1To2Years = $connexion->query($sql1To2Years);

    // Vérifiez s'il y a des utilisateurs dans la base de données-
    if ($result1Year->num_rows > 0 || $result1To2Years->num_rows > 0) {

        $sqlAllUsers = "SELECT * FROM user";
        $resultAllUsers = $connexion->query($sqlAllUsers);

        // Récupérer les données des résultats SQL dans des tableaux numériques
        $allUsersData = $resultAllUsers->fetch_all(MYSQLI_NUM);
        $oneYearData = $result1Year->fetch_all(MYSQLI_NUM);
        $oneToTwoYearsData = $result1To2Years->fetch_all(MYSQLI_NUM);

        echo "<div id='display-default'>";
        echo "<div id='all-users'>";
        echo '<h3>Tous les utilisateurs :</h3>';
        echo "<table>";
        echo '<tr><th>Prénom</th><th>Nom</th><th>Date d\'inscription</th></tr>';

        foreach ($allUsersData as $user) {
            echo '<tr><td>' . $user[1] . '</td><td>' . $user[2] . '</td><td>' . $user[3] . '</td></tr>';
        }

        echo '</table>';
        echo '</div>';



        echo '<div id="oneyearandless">';
        echo '<h3>Utilisateurs inscrits il y a 1 an et moins :</h3>';
        echo '<table>';
        echo '<tr><th>Prénom</th><th>Nom</th><th>Date d\'inscription</th></tr>';

        foreach ($oneYearData as $user) {
            echo '<tr><td>' . $user[1] . '</td><td>' . $user[2] . '</td><td>' . $user[3] . '</td></tr>';

        }

        echo '</table>';
        echo '</div>';

        // Affichez les utilisateurs inscrits entre 1 et 2 ans
        echo '<div id="onetotwoyears"><h3>Utilisateurs inscrits entre 1 et 2 ans : </h3>';
        echo '<table>';
        echo '<tr><th>Prénom</th><th>Nom</th><th>Date d\'inscription</th></tr>';

        foreach ($oneToTwoYearsData as $user) {
            echo '<tr><td>' . $user[1] . '</td><td>' . $user[2] . '</td><td>' . $user[3] . '</td></tr>';
        }

        echo '</table>';
        echo '</div>';
    } else {
        echo "<p>Aucun utilisateur n'a été ajouté.</p>";
    }
    echo "</div>";

    // Automatiquement supprimer les utilisateurs inscrits entre 1 et 2 ans
    $sqlDeleteOldUsers = "DELETE FROM user WHERE subscribe_date < '$twoYearsAgo'";
    if ($connexion->query($sqlDeleteOldUsers) === TRUE) {
        echo "<p class='success'>Les utilisateurs inscrits il y a plus de 2 ans ont été supprimés de la base de données </p>";
        
    } else {
        echo "Erreur lors de la suppression des utilisateurs : " . $connexion->error;
    }

    $connexion->close();
?>  
