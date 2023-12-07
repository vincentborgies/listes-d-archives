<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Liste d'utilisateurs</title>
</head>
<body>
    <header><h1>Listes d'archives</h1></header>
    
    <div id="searchandadd">
    <form method="GET" action="index.php" id="searchform">
        <input type="text" id="searchInput" name="searchTerm" placeholder="Rechercher un utilisateur...">
        <button type="submit">Rechercher</button>
    </form>

        <button class="btn" id="openModalBtn">Ajouter un utilisateur</button>
    </div>
    
    <div class="modal-container" id="modalContainer">
        <div class="modal">
            <span class="close-btn" id="closeModalBtn">&times;</span>
            <h2>Ajouter un utilisateur</h2>
            <form method="POST">
                <label for="lastname">Nom :</label>
                <input type="text" id="lastname" name="lastname" required><br><br>

                <label for="firstname">Prénom :</label>
                <input type="text" id="firstname" name="firstname" required><br><br>

                <label for="subscribe_date">Date d'inscription :</label>
                <input type="date" id="subscribe_date" name="subscribe_date" required><br><br>
                <div id="error-message" class="error-message"></div>

                <input class="btn" type="submit" value="Ajouter">
            </form>
        </div>
    </div>

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

    
    
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['lastname']) && isset($_POST['firstname']) && isset($_POST['subscribe_date'])) {
        $lastname = $_POST["lastname"];
        $firstname = $_POST["firstname"];
        $subscribeDate = $_POST["subscribe_date"];

        // Préparez la requête SQL d'insertion
        $request = "INSERT INTO user (lastname, firstname, subscribe_date) VALUES (?, ?, ?)";

        // Préparez la déclaration
        $statement = $connexion->prepare($request);

        if ($statement) {
            // Liez les paramètres et exécutez la requête
            $statement->bind_param("sss", $lastname, $firstname, $subscribeDate);
            if ($statement->execute()) {
                header("Location: index.php"); // Remplacez "votre_page.php" par le chemin de la page souhaitée
            } else {
                echo "Erreur lors de l'ajout de l'utilisateur : " . $statement->error;
            }

            // Fermez la déclaration
            $statement->close();
        } else {
            echo "Erreur de préparation de la requête : " . $connexion->error;
        }
    }

    // Sélectionnez les utilisateurs inscrits il y a 1 an ou moins
    $oneYearAgo = date("Y-m-d", strtotime("-1 year"));
    $sql1Year = "SELECT * FROM user WHERE subscribe_date >= '$oneYearAgo'";
    $result1Year = $connexion->query($sql1Year);

    // Sélectionnez les utilisateurs inscrits il y a entre 1 et 2 ans
    $twoYearsAgo = date("Y-m-d", strtotime("-2 years"));
    $sql1To2Years = "SELECT * FROM user WHERE subscribe_date >= '$twoYearsAgo' AND subscribe_date < '$oneYearAgo'";
    $result1To2Years = $connexion->query($sql1To2Years);

    // Vérifiez s'il y a des utilisateurs dans la base de données
    if ($result1Year->num_rows > 0 || $result1To2Years->num_rows > 0) {
        
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
        //récupération du terme de recherche
        $searchTerm = $_GET['searchTerm'];
        echo $searchTerm;


        $sqlAllUsers = "SELECT * FROM user";
        $resultAllUsers = $connexion->query($sqlAllUsers);

        // Récupérer les données des résultats SQL dans des tableaux numériques
        $allUsersData = $resultAllUsers->fetch_all(MYSQLI_NUM);
        $oneYearData = $result1Year->fetch_all(MYSQLI_NUM);
        $oneToTwoYearsData = $result1To2Years->fetch_all(MYSQLI_NUM);

        echo "<table id='all-users'>";
        echo '<tr><th>Prénom</th><th>Nom</th><th>Date d\'inscription</th></tr>';

        if($searchTerm === '' || !isset($searchTerm)) {
            $filteredUsers = array_filter($allUsersData, function($userr) use ($searchTerm) {
                // Vérifiez si le nom ou le prénom de l'utilisateur contient le terme de recherche
                return (stripos($userr['1'], $searchTerm) !== false) || (stripos($userr['2'], $searchTerm) !== false);
            });

            foreach ($filteredUsers as $user) {
                echo '<tr class="userRow">';
                echo '<td>' . $user[1] . '</td>';
                echo '<td>' . $user[2] . '</td>';
                echo '<td>' . $user[3] . '</td>';
                echo '</tr>';
            }
            
        } else {
            foreach ($allUsersData as $user) {
                echo '<tr class="userRow">';
                echo '<td>' . $user[1] . '</td>';
                echo '<td>' . $user[2] . '</td>';
                echo '<td>' . $user[3] . '</td>';
                echo '</tr>';
            }
        }
    }
        // Utiliser des boucles foreach pour générer les lignes de la table HTML

        echo '</table>';

        echo '<div id="oneyearandless">';
        echo '<h3>Utilisateurs inscrits il y a 1 an et moins :</h3>';
        echo '<table>';
        echo '<tr><th>Prénom</th><th>Nom</th><th>Date d\'inscription</th></tr>';

        foreach ($oneYearData as $user) {
            echo '<tr>';
            echo '<td>' . $user[1] . '</td>';
            echo '<td>' . $user[2] . '</td>';
            echo '<td>' . $user[3] . '</td>';
            echo '</tr>';
        }

        echo '</table>';
        echo '</div>';

        // Affichez les utilisateurs inscrits entre 1 et 2 ans
        echo '<div id="onetotwoyears"><h3>Utilisateurs inscrits entre 1 et 2 ans : </h3>';
        echo '<table>';
        echo '<tr><th>Prénom</th><th>Nom</th><th>Date d\'inscription</th></tr>';

        foreach ($oneToTwoYearsData as $user) {
            echo '<tr>';
            echo '<td>' . $user[1] . '</td>';
            echo '<td>' . $user[2] . '</td>';
            echo '<td>' . $user[3] . '</td>';
            echo '</tr>';
        }

        echo '</table>';
        echo '</div>';
    } else {
        echo "<p>Aucun utilisateur n'a été ajouté.</p>";
    }

    // Automatiquement supprimer les utilisateurs inscrits entre 1 et 2 ans
    $sqlDeleteOldUsers = "DELETE FROM user WHERE subscribe_date < '$twoYearsAgo'";
    if ($connexion->query($sqlDeleteOldUsers) === TRUE) {
        echo "<p class='success'>Les utilisateurs inscrits il y a plus de 2 ans ont été supprimés de la base de données </p>";
    } else {
        echo "Erreur lors de la suppression des utilisateurs : " . $connexion->error;
    }

    // Fermez la connexion à la base de données
    $connexion->close();
    ?>
    <script src="script.js"></script>
</body>
</html>

