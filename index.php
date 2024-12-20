<?php
include('connect_DB.php');
session_start();
?>

<!-- Afficher tout les articles pour les visiteurs ================================================================================================================= -->
<?php

$sql_afficher_All_Atrs = "
    SELECT 
        Articles.ID_article, 
        Articles.Titre AS Titre,
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
    ORDER BY nbr_likes DESC;
";

if ($stmt = $conn->prepare($sql_afficher_All_Atrs)) {
    $stmt->execute();
    $resultAfficherTout = $stmt->get_result();
    $all_articles = $resultAfficherTout->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    die("Erreur lors de la préparation de la requête SQL : " . $conn->error);
}

// bouton "Read more"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['read_more_v'])) {
    $id_article = filter_var($_POST['id_art'], FILTER_SANITIZE_NUMBER_INT);

    if ($id_article) {
        $_SESSION['id_article'] = $id_article;
        header("Location: article.php");
        exit();
    } else {
        echo "ID d'article invalide.";
    }
}
?>

<!-- code HTML CSS TAILWIND JS PHP pour la page home ================================================================================================================ -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>BlogPress - HOME</title>
</head>

<body class="text-white flex flex-col items-center gap-2 bg-black ">

    <!-- header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header -->
    <?php include('header.php'); ?>
    <!-- header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header -->


    <main class="h-max w-full flex flex-col justify-center items-center gap-2">
        <section class="h-96 bg-[url('images/bg2.jpg')] bg-cover w-full flex justify-center items-center">
            <h1 class="lg:text-[60px] max-lg:text-[60px] max-sm:text-[40px] text-center font-bold">Tech News & Blogs</h1>
        </section>

        <section class="flex flex-col items-center gap-4 w-[85%] h-max">
            <h1 class="text-3xl">Popular Stories</h1>


            <div class="w-full grid lg:grid-cols-2 max:lg:grid-cols-2 gap-4 ">

                <?php if (!empty($all_articles) && is_array($all_articles)): ?>
                    <?php foreach ($all_articles as $article): ?>
                        <div
                            class="w-full flex flex-col gap-2 px-2 py-2 bg-[#111111] shadow-md shadow-purple-800 border-0 rounded-sm hover:border-[1px] hover:border-purple-800 hover:shadow-lg hover:shadow-purple-800 ">
                            <p class="hidden" name="id_M_art"><?php echo htmlspecialchars($article['ID_article']); ?></p>
                            <h1 class="lg:text-2xl max-lg:text-2xl max-sm:text-xl text-purple-800"><?php echo htmlspecialchars($article['Titre']); ?></h1>
                            <p class="max-sm:text-sm text-gray-400">date de création : <?php echo htmlspecialchars($article['date_creation']); ?></p>
                            <p class="max-sm:text-sm text-gray-400">Catégorie : <?php echo htmlspecialchars($article['Categorie']); ?> </p>
                            <p class="max-sm:text-sm">
                                <?php
                                $contenu = $article['Contenu_article'] ?? '';
                                $extrait = substr($contenu, 0, 250);
                                echo htmlspecialchars($extrait) . '...';
                                ?>
                            </p>
                            <div class="flex flex-row justify-between w-full gap-2 max-sm:flex-col">

                                <div class="flex flex-row gap-2 justify-around w-1/3">
                                    <p>&#10084; <?php echo htmlspecialchars($article['nbr_likes']); ?></p>
                                    <div class="flex gap-1 ">
                                        <img src="images/icones/eye.png" alt="vue" class="w-6 h-6">
                                        <p><?php echo htmlspecialchars($article['nbr_vues']); ?></p>
                                    </div>
                                    <div class="flex gap-1 ">
                                        <img src="images/icones/comment.png" alt="comment" class="w-6 h-6">
                                        <p><?php echo htmlspecialchars($article['nbr_commentaires']); ?></p>
                                    </div>

                                </div>

                                <form action="" method="POST" class="flex flex-row gap-2 max-sm:justify-end">
                                    <input type="hidden" name="id_art" value="<?php echo htmlspecialchars($article['ID_article']); ?>">
                                    <button name="read_more_v"
                                        class="max-sm:text-sm bg-purple-500 border-2 rounded-sm w-40 lg:h-10 h-8 font-sans hover:bg-purple-800 hover:text-white">
                                        Read more &#10097;&#10097;
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun article disponible.</p>
                <?php endif; ?>

            </div>


            <!-- <div class=" grid lg:grid-cols-2 max:lg:grid-cols-2 gap-4 ">

                <?php if (!empty($all_articles) && is_array($all_articles)): ?>
                    <?php foreach ($all_articles as $article): ?>
                        <div
                            class=" flex flex-col gap-2 px-2 py-2 shadow-md shadow-[#1d1d1d] border-0 rounded-sm hover:border-[1px] hover:border-purple-800 hover:shadow-lg hover:shadow-purple-800">
                                        <h1 class="lg:text-2xl max-lg:text-2xl max-sm:text-xl">
                                            <?php echo htmlspecialchars($article['Titre']); ?>
                                        </h1>
                                        <p class="max-sm:text-sm">
                                            <?php
                                            $contenu = $article['Contenu_article'] ?? '';
                                            $extrait = substr($contenu, 0, 250);
                                            echo htmlspecialchars($extrait) . '...';
                                            ?>
                                        </p>
                                        <form action="" method="POST">
                                            <input type="hidden" name="id_art" value="<?php echo htmlspecialchars($article['ID_article']); ?>">
                                            <button name="read_more_v"
                                                class="max-sm:text-sm bg-purple-500 border-2 rounded-sm w-40 lg:h-10 h-8 font-sans hover:bg-purple-800 hover:text-white">
                                                Read more &#10097;&#10097;
                                            </button>
                                        </form>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Aucun article disponible.</p>
                        <?php endif; ?>

                            </div> -->
        </section>
    </main>

    <!-- footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer -->
    <?php include('footer.php'); ?>
    <!-- footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer -->

</body>

<script src="js/menu_theme.js"></script>

</html>