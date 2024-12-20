<?php
session_start();
include('connect_DB.php');

// Validation de l'ID auteur
if (isset($_POST['id_auteur']) || isset($_SESSION['id_auteur'])) {
    if (isset($_POST['id_auteur'])) {
        $id_auteur = filter_var($_POST['id_auteur'], FILTER_VALIDATE_INT);
        if ($id_auteur === false) {
            die("ID de l'auteur invalide !");
        }
        $_SESSION['id_auteur'] = $id_auteur;
    } else {
        $id_auteur = $_SESSION['id_auteur'];
    }
} else {
    die("ID de l'auteur non défini !");
}

// Préparation de la requête SQL
$sql_details = "SELECT 
    art.ID_article,
    art.Titre AS titre_article,
    SUM(CASE WHEN lv.type = 'vue' THEN lv.nbr_L_V ELSE 0 END) AS total_vues,
    SUM(CASE WHEN lv.type = 'like' THEN lv.nbr_L_V ELSE 0 END) AS total_likes,
    COUNT(DISTINCT c.ID_Comment) AS total_commentaires
FROM 
    Articles art
LEFT JOIN 
    Commentaires c ON art.ID_article = c.id_article
LEFT JOIN 
    likes_vues lv ON art.ID_article = lv.ID_article
WHERE 
    art.ID_auteur = ?
GROUP BY 
    art.ID_article
;";

$stmt_details = $conn->prepare($sql_details);
$stmt_details->bind_param("i", $id_auteur);
$stmt_details->execute();
$resultat_details = $stmt_details->get_result();

$data_articles = [];
$data_vues = [];
$data_likes = [];
$data_comments = [];

while ($row = $resultat_details->fetch_assoc()) {
    $data_articles[] = $row['titre_article'];
    $data_vues[] = $row['total_vues'] ?? 0;
    $data_likes[] = $row['total_likes'] ?? 0;
    $data_comments[] = $row['total_commentaires'] ?? 0;
}

$stmt_details->close();
?>

<!-- ---------------------------------------------------------------------------------------------------------------------------------- -->

<?php
// session_start();
// include('connect_DB.php'); // Connexion à la base de données

$id_auteur = $_SESSION['ID_auteur']; // Supposons que l'ID de l'auteur est stocké en session

$sql = "
    SELECT
    DATE_FORMAT(lv.date_L_V, '%d-%m-%Y') AS date_like_vue,
    SUM(CASE WHEN lv.type = 'vue' THEN lv.nbr_L_V ELSE 0 END) AS total_vues,
    SUM(CASE WHEN lv.type = 'like' THEN lv.nbr_L_V ELSE 0 END) AS total_likes,
    DATE_FORMAT(c.date_comment, '%d-%m-%Y') AS date_commentaire,
    COUNT(c.ID_Comment) AS total_commentaires
FROM Articles a
LEFT JOIN likes_vues lv ON a.ID_article = lv.ID_article
LEFT JOIN Commentaires c ON a.ID_article = c.id_article
WHERE a.ID_auteur = ?
GROUP BY DATE_FORMAT(lv.date_L_V, '%d-%m-%Y'), DATE_FORMAT(c.date_comment, '%d-%m-%Y')

";

// Préparation et exécution de la requête
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_auteur);
$stmt->execute();
$result = $stmt->get_result();

$data_articles = [];
$data_vues = [];
$data_likes = [];
$data_comments = [];

// Récupération des données
while ($row = $result->fetch_assoc()) {
    $data_articles[] = $row['date_creation'];
    $data_vues[] = (int)$row['total_vues'];
    $data_likes[] = (int)$row['total_likes'];
    $data_comments[] = (int)$row['total_commentaires'];
}

// Fermer la connexion
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques des Articles</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        canvas {
            max-width: 100%;
        }
    </style>
</head>

<body>
    <h2>Statistiques des Articles</h2>
    <p>Évolution des vues, likes et commentaires dans le temps</p>

    <div>
        <canvas id="chart"></canvas>
    </div>

    <script>
        // Récupérer les données PHP en JavaScript
        const labels = <?php echo json_encode($data_articles); ?>;
        const dataViews = <?php echo json_encode($data_vues); ?>;
        const dataLikes = <?php echo json_encode($data_likes); ?>;
        const dataComments = <?php echo json_encode($data_comments); ?>;

        // Création du graphique avec Chart.js
        new Chart("chart", {
            type: "line",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Vues",
                        data: dataViews,
                        borderColor: "red",
                        fill: false
                    },
                    {
                        label: "Likes",
                        data: dataLikes,
                        borderColor: "green",
                        fill: false
                    },
                    {
                        label: "Commentaires",
                        data: dataComments,
                        borderColor: "blue",
                        fill: false
                    }
                ]
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: "top"
                    },
                    title: {
                        display: true,
                        text: "Évolution des Statistiques des Articles"
                    }
                },
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: "Dates"
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: "Nombre"
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>

    <!-- Graphiques -->
    <!-- <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div><canvas id="vuesGraph"></canvas></div>
        <div><canvas id="likesGraph"></canvas></div>
        <div><canvas id="commentsGraph"></canvas></div>
    </div> -->

    <!-- <script>
        // Données PHP converties en JavaScript
        const labels = <?php echo json_encode($data_articles); ?>;
        const vuesData = <?php echo json_encode($data_vues); ?>;
        const likesData = <?php echo json_encode($data_likes); ?>;
        const commentsData = <?php echo json_encode($data_comments); ?>;

        // Tableau de couleurs spécifiques pour chaque article
        const colors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#66FF66', '#FF6666', '#6666FF', '#CCFF66'
        ];

        // Fonction pour créer un graphique
        function createChart(ctx, label, data, backgroundColors, borderColor) {
            return new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: borderColor,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                }
            });
        }

        // Création des graphiques avec des couleurs spécifiques
        createChart(
            document.getElementById('vuesGraph').getContext('2d'),
            'Vues',
            vuesData,
            colors.slice(0, labels.length),
            'rgba(54, 162, 235, 1)'
        );

        createChart(
            document.getElementById('likesGraph').getContext('2d'),
            'Likes',
            likesData,
            colors.slice(0, labels.length),
            'rgba(75, 192, 192, 1)'
        );

        createChart(
            document.getElementById('commentsGraph').getContext('2d'),
            'Commentaires',
            commentsData,
            colors.slice(0, labels.length),
            'rgba(255, 159, 64, 1)'
        );
    </script> -->

