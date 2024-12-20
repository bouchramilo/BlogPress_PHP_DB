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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques des Articles</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="p-4">
    <h2 class="text-xl font-bold mb-4">Statistiques des Articles</h2>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div><canvas id="vuesGraph"></canvas></div>
        <div><canvas id="likesGraph"></canvas></div>
        <div><canvas id="commentsGraph"></canvas></div>
    </div>

    <script>
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
    </script>

</body>

</html>