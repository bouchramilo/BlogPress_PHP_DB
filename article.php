<?php
session_start();
include('connect_DB.php'); // Inclusion du fichier de connexion

// Initialisation des variables
$id_article = null;
$all_commentaires = [];
$row_article = [];

// Vérifier si un article est sélectionné via POST ou une session
if (isset($_POST['id_article']) || isset($_SESSION['id_article'])) {
    if (isset($_POST['id_article'])) {
        $id_article = $_POST['id_article'];
        $_SESSION['id_article'] = $id_article; // Stocker l'ID dans la session
    } else {
        $id_article = $_SESSION['id_article'];
    }

    // Vérifier si l'article a déjà été consulté dans cette session
    if (!isset($_SESSION['viewed_articles'])) {
        $_SESSION['viewed_articles'] = []; // Initialisation des articles déjà vus
    }

    if (!in_array($id_article, $_SESSION['viewed_articles'])) {
        // Ajouter l'article au tableau des articles vus
        $_SESSION['viewed_articles'][] = $id_article;

        // Incrémenter les vues (requête préparée)
        $sql_vue = "INSERT INTO likes_vues (ID_article, nbr_L_V, type) VALUES (?, 1, 'vue')";
        $stmt = $conn->prepare($sql_vue);
        $stmt->bind_param("i", $id_article); // Lier l'ID de l'article comme entier
        if (!$stmt->execute()) {
            die("Erreur lors de l'ajout de la vue : " . $stmt->error);
        }
        $stmt->close();
    }

    // Requête pour récupérer les informations de l'article
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
            Articles.ID_article = ?";

    $stmt = $conn->prepare($sql_Art);
    $stmt->bind_param("i", $id_article); // Lier l'ID de l'article comme entier
    $stmt->execute();
    $resultat = $stmt->get_result();
    $row_article = $resultat->fetch_assoc(); // Récupération des données de l'article
    $stmt->close();

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


<!-- système commaentaire : add commentaire ============================================================================================================================= -->
<?php
$erreur_tab_Add_cmt = [
    'nom_prenom' => '',
    'email' => '',
    'commentaire' => ''
];

$nom_prenom = $email = $commentaire = $id_article = '';

// Validation du formulaire
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

    // Validation de l'email
    if (empty($_POST['email_visiteur'])) {
        $erreur_tab_Add_cmt['email'] = 'L\'email est vide!!!';
    } else {
        $email = $_POST['email_visiteur'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur_tab_Add_cmt['email'] = 'L\'email est invalide !!!!';
        }
    }

    // Validation du commentaire
    if (empty($_POST['comment_visiteur'])) {
        $erreur_tab_Add_cmt['commentaire'] = 'Le commentaire est vide!!!';
    } else {
        $commentaire = $_POST['comment_visiteur'];
        if (!preg_match('/^[\w\s.,!?\'"À-ÿ-]{2,}$/u', $commentaire)) {
            $erreur_tab_Add_cmt['commentaire'] = 'Le commentaire est invalide !!!!';
        }
    }

    // Récupération de l'ID article
    if (isset($_POST['id_article'])) {
        $id_article = $_POST['id_article'];
        $_SESSION['id_article'] = $id_article; 
    } else {
        $id_article = $_SESSION['id_article'] ?? null;
    }

    // Si des erreurs existent
    if (array_filter($erreur_tab_Add_cmt)) {
        echo "Il y a des erreurs dans votre formulaire :";
        foreach ($erreur_tab_Add_cmt as $champ => $messageErreur) {
            if (!empty($messageErreur)) {
                echo "<p class='text-red-500'>$messageErreur</p>";
            }
        }
    } else {
        // Ajouter le commentaire en utilisant des requêtes préparées
        $sql = "INSERT INTO commentaires (Nom_visiteur, email_visiteur, contenu_comment, id_article) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sssi", $nom_prenom, $email, $commentaire, $id_article);

            if ($stmt->execute()) {
                echo "Commentaire ajouté avec succès !";
                header("Location: article.php");
                exit();
            } else {
                echo "Erreur lors de l'ajout du commentaire : " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Erreur de préparation de la requête : " . $conn->error;
        }
    }
}
?>


<!-- système likes ======================================================================================================================================== -->
<?php
if (isset($_POST['add_like'])) {
    // Validation et récupération de l'ID article
    if (isset($_POST['id_article'])) {
        $id_article = filter_var($_POST['id_article'], FILTER_VALIDATE_INT);
        if ($id_article === false) {
            die("ID de l'article invalide !");
        }
        $_SESSION['id_article'] = $id_article;
    } else {
        $id_article = $_SESSION['id_article'] ?? null;
        if ($id_article === null) {
            die("ID de l'article manquant !");
        }
    }

    // Ajout du like à la base de données
    $sql_like = "INSERT INTO likes_vues (ID_article, nbr_L_V, type) VALUES (?, 1, 'like')";
    $stmt_like = $conn->prepare($sql_like);
    if (!$stmt_like) {
        die("Erreur de préparation de la requête : " . $conn->error);
    }

    $stmt_like->bind_param("i", $id_article);
    if ($stmt_like->execute()) {
        echo "Like ajouté avec succès !";
    } else {
        die("Erreur lors de l'ajout du like : " . $stmt_like->error);
    }

    $stmt_like->close();

    // Redirection vers la page de l'article
    header("Location: article.php");
    exit();
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

    <!-- header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header -->
    <?php include 'header.php'; ?>
    <!-- header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header -->
    

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
                        class="bg-purple-500 border-2 rounded-sm w-44 h-10 font-sans hover:bg-purple-800 hover:text-white">
                        Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <main class="h-max w-full flex flex-col justify-center items-center gap-10">
        <section class="h-56 bg-[url('images/bg2.jpg')] bg-cover w-full flex justify-center items-center ">
            <h1 class="lg:text-[60px] max-lg:text-[60px] max-sm:text-[40px] text-center font-bold" ><?php echo htmlspecialchars($row_article['Titre']); ?></h1>
        </section>

        <section class="flex flex-row max-sm:flex-col gap-4 w-[95%] h-max">
            <div class="w-8/12 max-sm:w-full ">
                <p class="max-sm:text-sm">
                    <?php echo htmlspecialchars($row_article['Contenu_article']); ?>
                </p>
            </div>
            <div class="flex flex-col gap-2 items-center w-4/12 max-sm:w-full h-max py-4 bg-[#1d1d1d]">
                <div class="w-[95%] border-2 border-purple-500 h-max flex flex-col gap-2 p-2">
                    <p class="text-xl max-sm:text-base">Auteur : <span class="text-lg max-sm:text-base max-sm:text-sm"><?php echo htmlspecialchars($row_article['Nom_auteur']) . ' ' . htmlspecialchars($row_article['Prénom_auteur']); ?></span></p>
                    <p class="text-xl max-sm:text-base">Catérogie : <span class="text-lg max-sm:text-base max-sm:text-sm"><?php echo htmlspecialchars($row_article['Categorie']); ?></span></p>
                    <p class="text-xl max-sm:text-base">Nombre de 'likes' : <span class="text-lg max-sm:text-base max-sm:text-sm"><?php echo htmlspecialchars($row_article['nbr_likes']); ?></span></p>
                    <p class="text-xl max-sm:text-base">Nombre de 'Vue' : <span class="text-lg max-sm:text-base max-sm:text-sm"><?php echo htmlspecialchars($row_article['nbr_vues']); ?></span></p>
                    <p class="text-xl max-sm:text-base">Nombre de commentaires : <span class="text-lg max-sm:text-base max-sm:text-sm"><?php echo htmlspecialchars($row_article['nbr_commentaires']); ?></span></p>
                </div>
                <div class="w-[95%] h-max flex flex-row gap-2 p-2">
                    <form action="" method="POST" class="w-1/2">
                        <button name="add_like"
                            class="max-sm:text-sm bg-purple-500 border-2 rounded-sm w-full h-9 font-sans hover:bg-purple-800 hover:text-white">
                            like &#10084;
                        </button>
                    </form>
                    <button id="showFormButton" class="max-sm:text-sm bg-purple-500 border-2 rounded-sm w-1/2 h-9 font-sans hover:bg-purple-800
                        hover:text-white">
                        commenter
                    </button>
                </div>
                <div class="w-[95%] border-2 border-purple-500 h-max flex flex-col gap-2 p-2">

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

    <!-- footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  -->
    <?php include 'footer.php' ?>
    <!-- footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  -->
</body>
<script src="js/menu_theme.js"></script>
<script src="js/commentFormC.js"></script>

</html>