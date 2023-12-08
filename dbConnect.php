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
