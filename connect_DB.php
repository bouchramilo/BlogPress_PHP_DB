<?php

    $host = 'localhost';
    $user = 'root';
    $password = 'BouchraSamar_13';
    $database = 'BlogPressDB';

    // Connexion à la base de données
    $conn = new mysqli($host, $user, $password, $database);

    if ($conn->connect_error) {
        echo ("Erreur de connexion : " . $conn->connect_error);
    }

?>