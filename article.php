<?php
include('connect_DB.php');
?>

<!-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
<!-- // pour l'affichage des articles d'auteur : ----------------------------------------------------------------------------------------------------------------------- -->
<?php

if (isset($_GET['ID_article'])) {
    $id_article = mysqli_real_escape_string($conn, $_GET['ID_article']);

    $sql_Art = "SELECT 
    Articles.ID_article, 
    Articles.Titre,
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
WHERE 
    Articles.ID_article = '$id_article' ;";

    $resultat = mysqli_query($conn, $sql_Art);

    $row_article = mysqli_fetch_assoc($resultat);

    // print_r($row_article);

    // afficher les commentaires de cette article ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    $sql_cmt_Art = "SELECT ID_Comment, Nom_visiteur, email_visiteur, contenu_vomment, date_comment, id_article FROM commentaires WHERE id_article = '$id_article' ;";

    // $resultat = mysqli_query($conn, $sql_cmt_Art);

    // $all_commentaires = mysqli_fetch_all($resultat);


    $resultat = $conn->query($sql_cmt_Art);
    $all_commentaires = mysqli_fetch_all($resultat, MYSQLI_ASSOC);
    // print_r($all_commentaires);
}

?>


<!-- système commaentaire : add commentaire -------------------------------------------------------------------------------------- -->
<?php
$erreur_tab_Add_cmt = [
    'nom_prenom' => '',
    'email' => '',
    'commentaire' => ''
];

$nom_prenom = $email =  $commentaire = $id_article = '';

if (isset($_POST['add_comment'])) {
    // Validation du nom_prenom
    if (empty($_POST['nom_prenom_visiteur'])) {
        $erreur_tab_Add_cmt['nom_prenom'] = 'Le nom_prenom est vide!!!';
    } else {
        $nom_prenom = $_POST['nom_prenom_visiteur'];
        if (!preg_match('/^[A-Za-z]{2,}([ -][A-Za-z]{2,})*$/', $nom_prenom)) {
            $erreur_tab_Add_cmt['nom_prenom'] = 'le nom_prenom est invalide !!!!';
        }
    }

    // Validation du catégorie
    if (empty($_POST['email_visiteur'])) {
        $erreur_tab_Add_cmt['email'] = 'Le categirie est vide!!!';
    } else {
        $email = $_POST['email_visiteur'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur_tab_Add_cmt['email'] = 'Le email est invalide !!!!';
        }
    }

    // Validation du contenue
    if (empty($_POST['comment_visiteur'])) {
        $erreur_tab_Add_cmt['commentaire'] = 'Le commentaire est vide!!!';
    } else {
        $commentaire = $_POST['comment_visiteur'];
        if (!preg_match('/^[\w\s.,!?\'"À-ÿ-]{2,}$/u', $commentaire)) {
            $erreur_tab_Add_cmt['commentaire'] = 'le commentaire est invalide !!!!';
        }
    }

    if (isset($_GET['ID_article'])) {
        // Extraire l'id_article à partir de URL 
        $id_article = mysqli_real_escape_string($conn, $_GET['ID_article']);

        echo "L'id_article extrait est : " . $id_article;
    } else {
        echo "Aucun id_article n'a été fourni dans l'URL.";
    }

    // set data in BD
    if (array_filter($erreur_tab_Add_cmt)) {
        echo "Il y a des erreurs dans votre formulaire :";
        foreach ($erreur_tab_Add_cmt as $champ => $messageErreur) {
            if (!empty($messageErreur)) {
                echo "<p class='text-red-500'>$messageErreur</p>";
            }
        }
    } else {
        $nom_prenom = mysqli_real_escape_string($conn, $_POST['nom_prenom_visiteur']);
        $email = mysqli_real_escape_string($conn, $_POST['email_visiteur']);
        $commentaire = mysqli_real_escape_string($conn, $_POST['comment_visiteur']);

        $sql = "INSERT INTO commentaires(Nom_visiteur, email_visiteur, contenu_vomment, id_article) VALUES ('$nom_prenom', '$email', '$commentaire', $id_article);
";

        if (mysqli_query($conn, $sql)) {
            echo "commentaire ajouté avec succès !";
            header("Location: article.php?ID_article=$id_article");
        } else {
            echo "Erreur : " . mysqli_error($conn);
        }
    }
} // end of if (isset($_POST['add_article']))
?>

<!-- système likes -->

<?php

// if (isset($_POST['add_like'])) {

//     if (!isset($id_article)) {
//         die("ID de l'article non défini");
//     }

//     $id_article = mysqli_real_escape_string($conn, $id_article);
//     $sql_like = "SELECT ID_article, COUNT(*) AS total_likes FROM likes_vues WHERE ID_article = '$id_article' AND type = 'like';";

//     $likesRes = mysqli_query($conn, $sql_like);

//     if (!$likesRes) {
//         die("Erreur dans la requête SQL : " . mysqli_error($conn));
//     }

//     $likes = mysqli_fetch_assoc($likesRes);

//     echo "Nombre de likes pour l'article $id_article : " . $likes['total_likes'];
// }




?>


<?php
if (isset($_POST['add_like'])) {

    if (!isset($_GET['ID_article']) || empty($_GET['ID_article'])) {
        die("ID de l'article non défini");
    }

    $id_article = $_GET['ID_article']; 
    $id_article = mysqli_real_escape_string($conn, $id_article);

    if (empty($id_article)) {
        die("L'ID de l'article est vide.");
    }

    $check_like_query = "SELECT COUNT(*) AS already_liked FROM likes_vues WHERE ID_article = '$id_article' AND type = 'like';";
    $result_check = mysqli_query($conn, $check_like_query);
    
    if (!$result_check) {
        die("Erreur lors de la vérification du like : " . mysqli_error($conn));
    }

    $check_data = mysqli_fetch_assoc($result_check);

    if ($check_data['already_liked'] == 0) {
        $sql_like = "INSERT INTO likes_vues (ID_article, nbr_L_V,  type) VALUES ('$id_article', 1, 'like')";

        $like_result = mysqli_query($conn, $sql_like);

        if (!$like_result) {
            die("Erreur lors de l'ajout du like : " . mysqli_error($conn));
        }

        // echo "Like ajouté avec succès!";
        header("Location: article.php?ID_article=$id_article");
    } else {
        // echo "Vous avez déjà liké cet article!";
    }
}


?>













<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>BlogPress - Article</title>
</head>

<body class="text-white flex flex-col items-center gap-2 bg-black ">

    <!-- header -->
    <?php include 'header.php'; ?>


    <!-- Formulaire caché -->
    <div id="formContainer" class="fixed inset-0 bg-white bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-[#1d1d1d] opacity-85 text-white p-6 flex flex-col gap-6 rounded-sm shadow-lg w-96">
            <h2 class="text-2xl font-bold text-center">Commenter</h2>
            <form method="POST">
                <label for="nom" class="block mb-2">Nom & Prénom :</label>
                <input type="text" id="nom" class="w-full p-2 mb-4 border-0 rounded-sm bg-black" name="nom_prenom_visiteur"
                    placeholder="Votre nom et prenom">

                <label for="email" class="block mb-2">Email:</label>
                <input type="email" id="email" class="w-full p-2 mb-4 border-0 rounded-sm bg-black" name="email_visiteur"
                    placeholder="Votre adresse e-mail">

                <label for="commentaire" class="block mb-2">Commentaire :</label>
                <input type="commentaire" id="commentaire"
                    class="w-full p-2 mb-4 border-0 rounded-sm bg-black resize-none" name="comment_visiteur"
                    placeholder="Votre commentaire.">

                <div class="flex justify-center h-12">
                    <button name="add_comment"
                        class="bg-[#d025a0] border-2 rounded-sm w-44 h-10 font-sans hover:bg-[#830c61] hover:text-white">
                        Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <main class="h-max w-full flex flex-col justify-center items-center gap-10">
        <section class="h-56 bg-[url('images/bg3.jpg')] bg-cover w-full flex justify-center items-center ">
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
                        <button name="add_like"
                            class="max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-40 h-9 font-sans hover:bg-[#830c61] hover:text-white">
                            like &#10084;
                        </button>
                    </form>
                    <button id="showFormButton" class="max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-40 h-9 font-sans hover:bg-[#830c61]
                        hover:text-white">
                        commenter
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
                                    <?php echo htmlspecialchars($comment['contenu_vomment']); ?>
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

</html>