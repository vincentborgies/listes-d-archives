<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <div id="lists">
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

            <input class="btn" type="submit" value="Ajouter">
        </form>
    </div>
    </div>
    <script src="script.js"></script>
</body>
</html>


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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
} else {
    echo "Méthode de requête incorrecte.";
}

// Sélectionnez les utilisateurs inscrits il y a 1 an ou moins
$oneYearAgo = date("Y-m-d", strtotime("-1 year"));
$sql1Year = "SELECT * FROM user WHERE subscribe_date >= '$oneYearAgo'";
$result1Year = $connexion->query($sql1Year);

// Sélectionnez les utilisateurs inscrits il y a plus de 2 ans
$twoYearsAgo = date("Y-m-d", strtotime("-2 years"));
$sql2Years = "SELECT * FROM user WHERE subscribe_date < '$twoYearsAgo'";
$result2Years = $connexion->query($sql2Years);

// Affichez les utilisateurs dans les listes appropriées
echo '<div id="1yearandless"><h2>inscrit il y a 1 an et moins</h2><ul>';
while ($row = $result1Year->fetch_assoc()) {
    echo '<li>' . $row['firstname'] . ' ' . $row['lastname'] . ' (' . $row['subscribe_date'] . ')</li>';
}
echo '</ul></div>';

echo '<div id="2yearsandmore"><h2>inscrit il y a plus de 2 ans</h2><ul>';
while ($row = $result2Years->fetch_assoc()) {
    echo '<li>' . $row['firstname'] . ' ' . $row['lastname'] . ' (' . $row['subscribe_date'] . ')</li>';
}
echo '</ul></div>';

// Fermez la connexion à la base de données
$connexion->close();


?>