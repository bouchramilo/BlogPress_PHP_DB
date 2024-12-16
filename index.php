<?php
include('connect_DB.php');
?>

<!-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
<!-- // pour l'affichage des articles d'auteur : ----------------------------------------------------------------------------------------------------------------------- -->

<?php
// if (isset($_GET['id_auteur'])) {
//     $id_auteur = $_GET['id_auteur'];
// } elseif (isset($_POST['id_auteur'])) {
//     $id_auteur = $_POST['id_auteur'];
// } else {
//     die("Erreur : ID auteur non défini.");
// }

$sql_afficher_All_Atrs = "SELECT 
    Articles.ID_article, 
    Articles.Titre as Titre,
    Articles.Contenu_article,
    Articles.Categorie,
    DATE_FORMAT(Articles.date_creation, '%d-%m-%Y') AS date_creation,
    Auteurs.Nom_auteur,
    Auteurs.Prénom_auteur,
    (SELECT COUNT(*) FROM likes_vues WHERE likes_vues.ID_article = Articles.ID_article AND likes_vues.type = 'like') AS nbr_likes,
    (SELECT COUNT(*) FROM likes_vues WHERE likes_vues.ID_article = Articles.ID_article AND likes_vues.type = 'vue') AS nbr_vues,
    (SELECT COUNT(*) FROM Commentaires WHERE Commentaires.id_article = Articles.ID_article) AS nbr_commentaires
FROM 
    Articles
JOIN 
    Auteurs ON Articles.ID_auteur = Auteurs.ID_auteur 
ORDER BY  Titre;";

$resultAfficherTout = $conn->query($sql_afficher_All_Atrs);

$all_articles = mysqli_fetch_all($resultAfficherTout, MYSQLI_ASSOC);

?>













<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>BlogPress - HOME</title>
</head>

<body class="text-white flex flex-col items-center gap-2 bg-black ">

    <?php include('header.php'); ?>

    <main class="h-max w-full flex flex-col justify-center items-center gap-2">
        <section class="h-96 bg-[url('images/bg2.jpg')] bg-cover w-full flex justify-center items-center ">
            <h1 class="lg:text-[60px] max-lg:text-[60px] max-sm:text-[40px] text-center font-bold"
                style="text-shadow: 0 0 10px #830c61, 0 0 10px #830c61, 0 0 20px #830c61;">Tech News & Blogs</h1>
        </section>

        <section class="flex flex-col items-center gap-4 w-[85%] h-max">
            <h1 class="text-3xl">Popular Stories</h1>
            <div class="grid lg:grid-cols-2 max:lg:grid-cols-2 gap-4 ">

                <?php if (!empty($all_articles) && is_array($all_articles)): ?>
                    <?php foreach ($all_articles as $article): ?>
                        <!-- article 1 -->
                        <div
                            class="flex flex-col gap-2 px-2 py-2 shadow-md shadow-[#830c91] border-0 rounded-sm hover:border-[1px] hover:border-[#830c91] hover:shadow-lg hover:shadow-[#830c91] ">
                            <h1 class="lg:text-2xl max-lg:text-2xl max-sm:text-xl"><?php echo htmlspecialchars($article['Titre']); ?></h1>
                            <p class="max-sm:text-sm">
                                <?php
                                $contenu = $article['Contenu_article'] ?? '';
                                $extrait = substr($contenu, 0, 250);
                                echo htmlspecialchars($extrait) . '...';
                                ?>
                            </p>
                            <a href="article.php?ID_article=<?php echo htmlspecialchars($article['ID_article']); ?>">
                                <button
                                    class="max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-40 lg:h-10 h-8 font-sans hover:bg-[#830c91] hover:text-white">
                                    Read more &#10097;&#10097;
                                </button>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun article disponible.</p>
                <?php endif; ?>




            </div>
        </section>
    </main>


    <?php
    include('footer.php');
    ?>



</body>
<script src=" js/menu_theme.js"></script>

</html>