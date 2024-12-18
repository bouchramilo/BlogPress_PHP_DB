<?php
session_start();
include('connect_DB.php');
?>

<!-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
<!-- // pour l'affichage des articles aux visiteurs : ========================================================================================================= -->
<?php

// Vérifier si un article est sélectionné via POST ou une session
if (isset($_POST['id_article']) || isset($_SESSION['id_article'])) {
    if (isset($_POST['id_article'])) {
        $id_article = $_POST['id_article'];
        $_SESSION['id_article'] = $id_article; // Stocker l'ID dans la session
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
    $stmt->bind_param("i", $id_article); // Lier l'ID de l'article comme entier
    $stmt->execute();
    $resultat = $stmt->get_result();
    $row_article = $resultat->fetch_assoc(); // Récupération des données de l'article
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
    $stmt->bind_param("i", $id_article); // Lier l'ID de l'article comme entier
    $stmt->execute();
    $resultat_cmt = $stmt->get_result();
    $all_commentaires = $resultat_cmt->fetch_all(MYSQLI_ASSOC); // Récupération des commentaires
    $stmt->close();

}

?>


<!-- Modifier Articles : ==================================================================================================== -->
<?php

$erreur_tab_Modif_art = [
    'titre' => '',
    'contenu' => '',
    'categorie' => ''
];

if (isset($_POST['modifier_article'])) {
    // Validation du titre
    if (empty($_POST['titreM'])) {
        $erreur_tab_Modif_art['titre'] = 'Le titre est vide!!!';
    } else {
        $titreM = $_POST['titreM'];
        if (!preg_match('/^[A-Za-zÀ-ÿ0-9\s-]{2,}$/u', $titre)) {
            $erreur_tab_Modif_art['titre'] = 'le titre est invalide !!!!';
        }
    }

    // Validation du catégorie
    if (empty($_POST['categorieM'])) {
        $erreur_tab_Modif_art['categorie'] = 'Le categirie est vide!!!';
    } else {
        $categorie = $_POST['categorieM'];
        if (!preg_match('/^[A-Za-zÀ-ÿ\s-]{2,}$/u', $categorie)) {
            $erreur_tab_Modif_art['categorie'] = 'Le catégorie est invalide !!!!';
        }
    }

    // Validation du contenue
    if (empty($_POST['contenuM'])) {
        $erreur_tab_Modif_art['contenu'] = 'Le contenu est vide!!!';
    } else {
        $contenu = $_POST['contenuM'];
        if (!preg_match('/^[\w\s.,!?\'"À-ÿ-]{2,}$/u', $contenu)) {
            $erreur_tab_Modif_art['contenu'] = 'le contenu est invalide !!!!';
        }
    }

    if (isset($_GET['ID_article'])) {
        // Extraire l'id_article à partir de URL 
        $id_article = $_GET['ID_article'];

        $id_article = intval($id_article);

        echo "L'id_article extrait est : " . $id_article;
    } else {
        echo "Aucun id_article n'a été fourni dans l'URL.";
    }

    // set data in BD
    if (array_filter($erreur_tab_Modif_art)) {
        echo "Il y a des erreurs dans votre formulaire :";
        foreach ($erreur_tab_Modif_art as $champ => $messageErreur) {
            if (!empty($messageErreur)) {
                echo "<p class='text-red-500'>$messageErreur</p>";
            }
        }
    } else {
        $titreM = mysqli_real_escape_string($conn, $_POST['titreM']);
        $contenuM = mysqli_real_escape_string($conn, $_POST['contenuM']);
        $categorieM = mysqli_real_escape_string($conn, $_POST['categorieM']);

        $id_article = mysqli_real_escape_string($conn, $_GET['ID_article']);

        $sql = "UPDATE Articles SET Titre = '$titreM', Contenu_article = '$contenuM', Categorie = '$categorieM' WHERE ID_article = '$id_article' ";

        $update_result = mysqli_query($conn, $sql);
        if (!$update_result) {
            echo "Erreur lors de la modification : " . mysqli_error($conn);
        }

        echo "Article modifié avec succès !";
        header("Location: readAuteur.php?ID_article=$id_article");
    }
} // end of if (isset($_POST['add_article']))

?>

<!-- DELETE ARTICLE ================================================================================ -->
<?php

if (isset($_POST['delete_art'])) {
    if (isset($_POST['id_article'])) {
        $id_article = mysqli_real_escape_string($conn, $_POST['id_article']);
        if (!is_numeric($id_article)) {
            die("ID article invalide !");
        }
        $_SESSION['id_article'] = $id_article;
    } else {
        $id_article = $_SESSION['id_article'] ?? null;
        if (empty($id_article) || !is_numeric($id_article)) {
            die("ID article non spécifié ou invalide !");
        }
    }

   // Requête pour supprimer l'article
   $stmt = $conn->prepare("DELETE FROM Articles WHERE ID_article = ?");
   if (!$stmt) {
       die("Erreur de préparation de la requête : " . $conn->error);
   }

   $stmt->bind_param("i", $id_article);

   if (!$stmt->execute()) {
       die("Erreur lors de la suppression : " . $stmt->error);
   }

   $stmt->close();

    header("Location: auteur.php");
    exit;
}
?>


<!-- ======================================================================================= -->
<!-- code html  code html  code html  code html  code html  code html  code html  code html  -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>BlogPress - Auteur_Article</title>
</head>

<body class="text-white flex flex-col items-center gap-2 bg-black ">

    <!-- header -->
    <?php include 'header.php'; ?>


    <!-- Formulaire modification caché -->
    <div id="formContainerModifier"
        class="fixed inset-0 bg-white bg-opacity-50 flex justify-center items-center hidden">
        <div
            class="bg-[#1d1d1d] opacity-85 text-white p-6 flex flex-col gap-6 rounded-sm shadow-lg w-[70%] max-sm:w-full">
            <h2 class="text-2xl font-bold text-center">Modifier l'article</h2>
            <form>
                <label for="titreM" class="block mb-2">Titre d'article :</label>
                <input type="text" id="titreM" class="w-full p-2 mb-4 border-0 rounded-sm bg-black" name="titreM"
                    placeholder="Titre Modifier">
                <div class="text-red-500 text-xs"><?php echo  $erreur_tab_Modif_art['titre']; ?></div>


                <label for="categorieM" class="block mb-2">Categorie d'article :</label>
                <input type="categorieM" id="categorieM" class="w-full p-2 mb-4 border-0 rounded-sm bg-black" name="categorieM"
                    placeholder="Catégorie Modifier">
                <div class="text-red-500 text-xs"><?php echo  $erreur_tab_Modif_art['contenu']; ?></div>


                <label for="ContenuM" class="block mb-2">Contenu d'article :</label>
                <input type="ContenuM" id="ContenuM"
                    class="w-full p-2 mb-4 border-0 rounded-sm bg-black resize-none h-40" name="ContenuM"
                    placeholder="Contenu Modifier">
                <div class="text-red-500 text-xs"><?php echo  $erreur_tab_Modif_art['categorie']; ?></div>


                <div class="flex justify-center h-12">
                    <button name="modifier_article"
                        class="bg-purple-500 border-2 rounded-sm w-44 h-10 font-sans hover:bg-purple-800 hover:text-white">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <main class="h-max w-full flex flex-col justify-center items-center gap-10">
        <section class="h-56 bg-[url('images/bg.jpg')] bg-cover w-full flex justify-center items-center ">
            <h1 class="lg:text-[60px] max-lg:text-[60px] max-sm:text-[40px] text-center font-bold"
                style="text-shadow: 0 0 10px #830c61, 0 0 10px #830c61, 0 0 20px #830c61;"><?php echo htmlspecialchars($row_article['Titre']); ?></h1>
        </section>

        <section class="flex flex-row max-sm:flex-col gap-4 w-[95%] h-max">
            <div class="w-8/12 max-sm:w-full ">
                <p class="max-sm:text-sm">
                    <?php echo htmlspecialchars($row_article['Contenu_article']); ?>
                </p>
            </div>
            <div class="flex flex-col gap-2 items-center w-4/12 max-sm:w-full h-max py-4 bg-[#1d1d1d]">
                <div class="w-[95%] border-2 border-[#830c62] h-max flex flex-col gap-2 p-2">
                    <p class="text-xl max-sm:text-base">Auteur : <span class="text-lg max-sm:text-base max-sm:text-sm"><?php echo htmlspecialchars($row_article['Nom_auteur']) . ' ' . htmlspecialchars($row_article['Prénom_auteur']); ?></span></p>
                    <p class="text-xl max-sm:text-base">Catérogie : <span class="text-lg max-sm:text-base max-sm:text-sm"><?php echo htmlspecialchars($row_article['Categorie']); ?></span></p>
                    <p class="text-xl max-sm:text-base">Nombre de 'likes' : <span class="text-lg max-sm:text-base max-sm:text-sm"><?php echo htmlspecialchars($row_article['nbr_likes']); ?></span></p>
                    <p class="text-xl max-sm:text-base">Nombre de 'Vue' : <span class="text-lg max-sm:text-base max-sm:text-sm"><?php echo htmlspecialchars($row_article['nbr_vues']); ?></span></p>
                    <p class="text-xl max-sm:text-base">Nombre de commentaires : <span class="text-lg max-sm:text-base max-sm:text-sm"><?php echo htmlspecialchars($row_article['nbr_commentaires']); ?></span></p>
                </div>
                <div class="w-[95%] h-max flex flex-row gap-2 p-2">
                    <form action="" method="POST">
                        <!-- auteur.php?id_auteur=<?php echo htmlspecialchars($row_article['ID_auteur']); ?> -->
                        <button name="delete_art"
                            class="max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-40 h-9 font-sans hover:bg-[#830c61] hover:text-white">
                            DELETE
                        </button>
                    </form>
                    <button id="showFormButton" class="updateform max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-40 h-9 font-sans hover:bg-[#830c61]
                        hover:text-white">
                        UPDATE
                    </button>
                </div>
                <div class="w-[95%] border-2 border-[#830c62] h-max flex flex-col gap-2 p-2">

                    <?php if (!empty($all_commentaires) && is_array($all_commentaires)): ?>
                        <?php foreach ($all_commentaires as $comment): ?>
                            <div class="flex flex-col gap-2">
                                <div class="flex flex-row gap-2 items-center">
                                    <img src="images/icones/comment.png" alt="comment" class="w-8">
                                    <p class="text-lg max-sm:text-base"><span class="text-lg max-sm:text-base"><?php echo htmlspecialchars($comment['Nom_visiteur']); ?></span></p>
                                    <p class="text-sm max-sm:text-xs text-gray-500"><?php echo htmlspecialchars($comment['date_comment']); ?></p>
                                </div>
                                <div class="pl-10 max-sm:text-sm">
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

    <!-- footer  -->
    <?php include 'footer.php' ?>
</body>
<script src="js/menu_theme.js"></script>
<script src="js/commentFormC.js"></script>
<script src="js/update_article.js"></script>

</html>