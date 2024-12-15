<?php
// Paramètres de connexion
$host = 'localhost'; // Adresse du serveur MySQL
$user = 'root';      // Nom d'utilisateur MySQL
$password = 'BouchraSamar_13';      // Mot de passe MySQL
$database = 'BlogPressDB';  // Nom de la base de données

// Connexion à la base de données
$conn = new mysqli($host, $user, $password, $database);

// Vérifier si la connexion est réussie
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

echo "Connexion réussie à la base de données !<br>";

// Exemple : Lire les données de la table `users`
$sql = "SELECT * FROM Auteurs order by Nom_auteur";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Afficher les données
    while ($row = $result->fetch_assoc()) {
        echo "ID : " . $row['ID_auteur'] . " | Nom : " . $row['Nom_auteur'] . " | Email : " . $row['Email_auteur'] . "<br>";
    }
} else {
    echo "Aucun utilisateur trouvé.";
}

// Fermer la connexion
$conn->close();
?>
<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->

<?php
// Connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=BlogPressDB';
$username = 'root';
$password = 'BouchraSamar_13';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête SQL d'insertion
    $sql = "INSERT INTO Auteurs (Nom_auteur, Prénom_auteur, Email_auteur, Password)
            VALUES (:nom, :prenom, :email, :password)";

    // Préparation de la requête
    $stmt = $pdo->prepare($sql);

    // Données à insérer
    $data = [
        ':nom' => 'Dupont',
        ':prenom' => 'Jean',
        ':email' => 'jean.dunt@example.com',
        ':password' => password_hash('password1', PASSWORD_DEFAULT), // Hashage sécurisé
    ];

    // Exécution de la requête
    $stmt->execute($data);

    echo "Données insérées avec succès.";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
