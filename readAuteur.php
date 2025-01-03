<?php
session_start();
include('connect_DB.php');
?>

<?php
include "update_art.php";
include "delete_art_comment.php";
?>

<!-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
<!-- // pour l'affichage des articles aux visiteurs : ========================================================================================================= -->
<?php

if (isset($_POST['id_article']) || isset($_SESSION['id_article'])) {
    if (isset($_POST['id_article'])) {
        $id_article = $_POST['id_article'];
        $_SESSION['id_article'] = $id_article;
    } else {
        $id_article = $_SESSION['id_article'];
    }
    // requete d'affichage les information d'une article : =========================================================
    $sql_Art = "SELECT 
            Articles.ID_article, 
            Articles.Titre,
            Articles.Contenu_article,
            Articles.Categorie,
            DATE_FORMAT(Articles.date_creation, '%d-%m-%Y') AS date_creation,
            Auteurs.Nom_auteur,
            Auteurs.Prénom_auteur,
            Auteurs.ID_auteur,
            (SELECT COUNT(*) FROM likes_vues WHERE likes_vues.ID_article = Articles.ID_article AND likes_vues.type = 'like') AS nbr_likes,
            (SELECT COUNT(*) FROM likes_vues WHERE likes_vues.ID_article = Articles.ID_article AND likes_vues.type = 'vue') AS nbr_vues,
            (SELECT COUNT(*) FROM Commentaires WHERE Commentaires.id_article = Articles.ID_article) AS nbr_commentaires
        FROM 
            Articles
        JOIN 
            Auteurs ON Articles.ID_auteur = Auteurs.ID_auteur
        WHERE 
            Articles.ID_article = ? ;";

    $stmt = $conn->prepare($sql_Art);
    $stmt->bind_param("i", $id_article);
    $stmt->execute();
    $resultat = $stmt->get_result();
    $row_article = $resultat->fetch_assoc();
    $stmt->close();


    // afficher les commentaires de cette article ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    // Requête pour récupérer les commentaires de l'article
    $sql_cmt_Art = "SELECT 
                        ID_Comment, 
                        Nom_visiteur, 
                        email_visiteur, 
                        contenu_comment, 
                        DATE_FORMAT(date_comment, '%d-%m-%Y %H:%i:%s') AS date_comment 
                    FROM 
                        commentaires 
                    WHERE 
                        id_article = ?";
    $stmt = $conn->prepare($sql_cmt_Art);
    $stmt->bind_param("i", $id_article);
    $stmt->execute();
    $resultat_cmt = $stmt->get_result();
    $all_commentaires = $resultat_cmt->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

?>


<!-- =============================================================================================================================== -->
<!-- code html  code html  code html  code html  code html  code html  code html  code html code html code html code html code html  -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>BlogPress - Auteur_Article</title>
</head>

<body class="text-white flex flex-col items-center gap-2 bg-black ">

    <!-- header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header -->
    <?php include 'header.php'; ?>
    <!-- header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header -->


    <!-- Formulaire modification caché -->
    <div id="formContainerModifier"
        class="fixed inset-0 bg-white bg-opacity-50 flex justify-center items-center hidden">
        <div
            class="bg-[#1d1d1d] opacity-85 text-white p-6 flex flex-col gap-6 rounded-sm shadow-lg w-[70%] max-sm:w-full">
            <h2 class="text-2xl font-bold text-center">Modifier l'article</h2>
            <form method="POST" action="">
                <label for="titreM" class="block mb-2">Titre d'article :</label>
                <input type="text" id="titreM" class="w-full p-2 mb-4 border-0 rounded-sm bg-black" name="titreM"
                    placeholder="Titre Modifier">
                <div class="text-red-500 text-xs"><?php echo  $erreur_tab_Modif_art['titre']; ?></div>


                <label for="categorieM" class="block mb-2">Categorie d'article :</label>
                <input type="categorieM" id="categorieM" class="w-full p-2 mb-4 border-0 rounded-sm bg-black"
                    name="categorieM" placeholder="Catégorie Modifier">
                <div class="text-red-500 text-xs"><?php echo  $erreur_tab_Modif_art['categorie']; ?></div>


                <label for="ContenuM" class="block mb-2">Contenu d'article :</label>
                <input type="ContenuM" id="ContenuM"
                    class="w-full p-2 mb-4 border-0 rounded-sm bg-black resize-none h-40" name="contenuM"
                    placeholder="Contenu Modifier">
                <div class="text-red-500 text-xs"><?php echo  $erreur_tab_Modif_art['contenu']; ?></div>


                <div class="flex justify-center h-12 gap-2">
                    <button name="annuler_modifier_article"
                        class="bg-purple-500 border-2 rounded-sm w-44 h-10 font-sans hover:bg-purple-800 hover:text-white">
                        ignore
                    </button>
                    <button name="modifier_article"
                        class="bg-purple-500 border-2 rounded-sm w-44 h-10 font-sans hover:bg-purple-800 hover:text-white">
                        Save
                    </button>
                </div>

            </form>


        </div>
    </div>

    <main class="h-max w-full flex flex-col justify-center items-center gap-6">
        <section
            class="h-44 bg-purple-500 bg-[url('images/bg2.jpg')] bg-cover w-full flex justify-center items-center ">
            <h1 class="lg:text-[60px] max-lg:text-[60px] max-sm:text-[40px] text-center font-bold">
                <?php echo htmlspecialchars($row_article['Titre']); ?></h1>
        </section>

        <section class="flex flex-row max-sm:flex-col gap-4 w-[95%] h-max">
            <div class="w-8/12 max-sm:w-full ">
                <p class="max-sm:text-sm">
                    <?php echo htmlspecialchars($row_article['Contenu_article']); ?>
                </p>
            </div>
            <div class="flex flex-col gap-2 items-center w-4/12 max-sm:w-full h-max py-4 bg-[#1d1d1d]">
                <div class="w-[95%] border-2 border-purple-500 h-max flex flex-col gap-2 p-2">
                    <p class="text-xl max-sm:text-base">Auteur : <span
                            class="text-lg max-sm:text-base max-sm:text-sm"><?php echo htmlspecialchars($row_article['Nom_auteur']) . ' ' . htmlspecialchars($row_article['Prénom_auteur']); ?></span>
                    </p>
                    <p class="text-xl max-sm:text-base">Catérogie : <span
                            class="text-lg max-sm:text-base max-sm:text-sm"><?php echo htmlspecialchars($row_article['Categorie']); ?></span>
                    </p>
                    <p class="text-xl max-sm:text-base">Nombre de 'likes' : <span
                            class="text-lg max-sm:text-base max-sm:text-sm"><?php echo htmlspecialchars($row_article['nbr_likes']); ?></span>
                    </p>
                    <p class="text-xl max-sm:text-base">Nombre de 'Vue' : <span
                            class="text-lg max-sm:text-base max-sm:text-sm"><?php echo htmlspecialchars($row_article['nbr_vues']); ?></span>
                    </p>
                    <p class="text-xl max-sm:text-base">Nombre de commentaires : <span
                            class="text-lg max-sm:text-base max-sm:text-sm"><?php echo htmlspecialchars($row_article['nbr_commentaires']); ?></span>
                    </p>
                </div>
                <div class="w-[95%] h-max flex flex-row gap-2 p-2">
                    <form action="" method="POST" class="w-1/2">
                        <button name="delete_art"
                            class="max-sm:text-sm bg-purple-500 border-2 rounded-sm w-full h-9 font-sans hover:bg-purple-800 hover:text-white">
                            DELETE
                        </button>
                    </form>
                    <button id="showFormButton" class="updateform max-sm:text-sm bg-purple-500 border-2 rounded-sm w-1/2 h-9 font-sans hover:bg-purple-800
                        hover:text-white">
                        UPDATE
                    </button>
                </div>
                <div class="w-[95%] border-2 border-purple-500 h-max flex flex-col gap-2 p-2">

                    <?php if (!empty($all_commentaires) && is_array($all_commentaires)): ?>
                        <?php foreach ($all_commentaires as $comment): ?>
                            <div class="flex flex-col gap-2 border-b-2 border-gray-500 shadow-md">
                                <div class="flex flex-row justify-between gap-2 items-center">
                                    <div class="flex flex-row gap-2 items-center">
                                        <img src="images/icones/comment.png" alt="comment" class="w-6">
                                        <p class="text-lg max-sm:text-base"><span
                                                class="text-lg max-sm:text-base"><?php echo htmlspecialchars($comment['Nom_visiteur']); ?></span>
                                        </p>
                                        <p class="text-sm max-sm:text-xs text-gray-500">
                                            <?php echo htmlspecialchars($comment['date_comment']); ?></p>

                                    </div>
                                    <form action="" method="POST">
                                        <input type="hidden" name="id_commentaire"
                                            value="<?php echo htmlspecialchars($comment['ID_Comment']); ?>">
                                        <button name="delete_comment" class="w-8">&#10005;</button>
                                    </form>
                                </div>
                                <div class="pl-4 flex flex-row justify-between max-sm:text-sm">
                                    <?php echo htmlspecialchars($comment['contenu_comment']); ?>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Aucun commentaire disponible.</p>
                    <?php endif; ?>
                </div>

            </div>
        </section>
    </main>

    <!-- footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  -->
    <?php include 'footer.php' ?>
    <!-- footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  -->

    <!-- pour l'affichage du form de modification des articles : -->
    <script>
        const updateform = document.querySelectorAll('.updateform');
        const formContainerModifier = document.getElementById('formContainerModifier');
        const title = document.getElementById("titreM");
        const categorie = document.getElementById("categorieM");
        const contenu = document.getElementById("ContenuM");

        // Afficher ou masquer le form de update 
        updateform.forEach((form) => {
            form.addEventListener('click', () => {
                title.value = "<?php echo htmlspecialchars($row_article['Titre']); ?>";
                categorie.value = "<?php echo htmlspecialchars($row_article['Categorie']); ?>";
                contenu.value = "<?php echo htmlspecialchars($row_article['Contenu_article']); ?>";
                formContainerModifier.classList.toggle('hidden');
            });
        });
    </script>


</body>
<script src="js/menu_theme.js"></script>
<script src="js/commentFormC.js"></script>

</html>