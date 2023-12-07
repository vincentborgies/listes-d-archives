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
    
    $connexion->close();
?>