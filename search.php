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

// Vérifiez si 'search' est défini dans la requête GET
if (isset($_GET['search'])) {
    $result = $_GET['search'];

    // Utilisez des requêtes préparées pour éviter les injections SQL
    $stmt = $connexion->prepare("SELECT * FROM user WHERE firstname LIKE ? OR lastname LIKE ?");

    // Ajoutez des caractères jokers '%' au début pour rechercher les correspondances au début
    $searchTerm = $result . '%';

    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();

    $resultAllUsers = $stmt->get_result();

    // Gestion des erreurs de requête SQL
    if (!$resultAllUsers) {
        die("Erreur dans la requête SQL : " . $connexion->error);
    }

    // Récupérer les données des résultats SQL dans des tableaux numériques
    $allUsersData = $resultAllUsers->fetch_all(MYSQLI_NUM);

    // Vérifier le nombre de résultats
    if (count($allUsersData) > 0) {
        echo "<div id='all-users'>";
        echo '<h3>Résultat de la recherche:</h3>';
        echo "<table>";
        echo '<tr><th>Prénom</th><th>Nom</th><th>Date d\'inscription</th></tr>';

        foreach ($allUsersData as $user) {
            echo '<tr><td>' . $user[1] . '</td><td>' . $user[2] . '</td><td>' . $user[3] . '</td></tr>';
        }

        echo '</table>';
        echo '</div>';
    } else {
        echo "<div class='center'>Aucun utilisateur ne porte ce nom.</div>";
    }
}

$connexion->close();
?>
