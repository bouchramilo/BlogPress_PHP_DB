
<!-- statistiques  statistiques  statistiques  statistiques  statistiques  statistiques  statistiques  statistiques  statistiques  statistiques -->
<?php

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
// la requete pour séléctionner et compter les statistique de chaque article d'un auteur
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

// fetcher les données (articles et leurs statistiques) dans des tableaux séparer :
while ($row = $resultat_details->fetch_assoc()) {
    $data_articles[] = $row['titre_article'];
    $data_vues[] = $row['total_vues'] ?? 0;
    $data_likes[] = $row['total_likes'] ?? 0;
    $data_comments[] = $row['total_commentaires'] ?? 0;
}

$stmt_details->close();
?>


<!-- afficahge analytics afficahge analytics afficahge analytics afficahge analytics afficahge analytics afficahge analytics afficahge analytics afficahge analytics  -->
<?php

// la requête pour extraire les statistique total des articles d'un auteur : 
$sql_afficher_total = "SELECT 
    a.ID_auteur,
    CONCAT(a.Nom_auteur, ' ', a.Prénom_auteur) AS Nom_complet,
    COUNT(DISTINCT art.ID_article) AS total_articles,
    COUNT(DISTINCT c.ID_Comment) AS total_commentaires,
    SUM(CASE WHEN lv.type = 'like' THEN lv.nbr_L_V ELSE 0 END) AS total_likes,
    SUM(CASE WHEN lv.type = 'vue' THEN lv.nbr_L_V ELSE 0 END) AS total_vues
FROM 
    Auteurs a
LEFT JOIN 
    Articles art ON a.ID_auteur = art.ID_auteur
LEFT JOIN 
    Commentaires c ON art.ID_article = c.id_article
LEFT JOIN 
    likes_vues lv ON art.ID_article = lv.ID_article
WHERE 
    a.ID_auteur = ?
GROUP BY 
    a.ID_auteur, Nom_complet";

$stmt_total = $conn->prepare($sql_afficher_total);
$stmt_total->bind_param("i", $id_auteur); // Lier l'ID de l'article comme entier
$stmt_total->execute();
$resultat_total = $stmt_total->get_result();
$analytics = $resultat_total->fetch_assoc(); // Récupération des données de l'article
$stmt_total->close();

?>
