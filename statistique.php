

<!-- statistiques  -->
<?php

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
