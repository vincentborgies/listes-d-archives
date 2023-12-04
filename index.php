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
    echo "  <p class='error'>Méthode de requête incorrecte.</p>";
}

// Sélectionnez les utilisateurs inscrits il y a 1 an ou moins
$oneYearAgo = date("Y-m-d", strtotime("-1 year"));
$sql1Year = "SELECT * FROM user WHERE subscribe_date >= '$oneYearAgo'";
$result1Year = $connexion->query($sql1Year);

// Sélectionnez les utilisateurs inscrits il y a entre 1 et 2 ans
$twoYearsAgo = date("Y-m-d", strtotime("-2 years"));
$sql2Years = "SELECT * FROM user WHERE subscribe_date < '$twoYearsAgo'";
$sql1To2Years = "SELECT * FROM user WHERE subscribe_date >= '$twoYearsAgo' AND subscribe_date < '$oneYearAgo'";
$result1To2Years = $connexion->query($sql1To2Years);

// Affichez les utilisateurs dans les listes appropriées
echo '<div id="oneyearandless"><h3>Utilisateurs inscrits il y a 1 an et moins :</h3>';
echo '<table>';
echo '<tr><th>Nom</th><th>Prénom</th><th>Date d\'inscription</th></tr>';
while ($row = $result1Year->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . $row['lastname'] . '</td>';
    echo '<td>' . $row['firstname'] . '</td>';
    echo '<td>' . $row['subscribe_date'] . '</td>';
    echo '</tr>';
}
echo '</table>';
echo '</div>';


// Affichez les utilisateurs inscrits entre 1 et 2 ans
echo '<div id="1to2years"><h3>Utilisateurs inscrits entre 1 et 2 ans : </h3>';
echo '<table>';
echo '<tr><th>Nom</th><th>Prénom</th><th>Date d\'inscription</th></tr>';
while ($row = $result1To2Years->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . $row['lastname'] . '</td>';
    echo '<td>' . $row['firstname'] . '</td>';
    echo '<td>' . $row['subscribe_date'] . '</td>';
    echo '</tr>';
}
echo '</table>';
echo '</div>';


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