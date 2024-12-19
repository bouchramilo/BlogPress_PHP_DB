<?php
session_start();
include('connect_DB.php');
?>


<!-- // pour l'ajout des articles : ----------------------------------------------------------------------------------------------------------------------- -->
<?php
$erreur_tab_Add_art = [
    'titre' => '',
    'contenu' => '',
    'categorie' => ''
];

$titre = $categorie = $contenu = $id_auteur = '';

if (isset($_POST['add_article'])) {
    // Validation du titre
    if (empty($_POST['titreAdd'])) {
        $erreur_tab_Add_art['titre'] = 'Le titre est vide!!!';
    } else {
        $titre = $_POST['titreAdd'];
        if (!preg_match('/^[A-Za-zÀ-ÿ0-9\s-]{2,}$/u', $titre)) {
            $erreur_tab_Add_art['titre'] = 'le titre est invalide !!!!';
        }
    }

    // Validation du catégorie
    if (empty($_POST['categorieAdd'])) {
        $erreur_tab_Add_art['categorie'] = 'Le categirie est vide!!!';
    } else {
        $categorie = $_POST['categorieAdd'];
        if (!preg_match('/^[A-Za-zÀ-ÿ\s-]{2,}$/u', $categorie)) {
            $erreur_tab_Add_art['categorie'] = 'Le catégorie est invalide !!!!';
        }
    }

    // Validation du contenue
    if (empty($_POST['contenuAdd'])) {
        $erreur_tab_Add_art['contenu'] = 'Le contenu est vide!!!';
    } else {
        $contenu = $_POST['contenuAdd'];
        if (!preg_match('/^[\w\s.,!?\'"À-ÿ-]{2,}$/u', $contenu)) {
            $erreur_tab_Add_art['contenu'] = 'le contenu est invalide !!!!';
        }
    }


    // Récupération de l'ID article
    if (isset($_POST['id_auteur'])) {
        $id_auteur = $_POST['id_auteur'];
        $_SESSION['id_auteur'] = $id_auteur;
    } else {
        $id_auteur = $_SESSION['id_auteur'] ?? null;
    }


    // set data in BD
    if (array_filter($erreur_tab_Add_art)) {
        echo "Il y a des erreurs dans votre formulaire :";
        foreach ($erreur_tab_Add_art as $champ => $messageErreur) {
            if (!empty($messageErreur)) {
                echo "<p class='text-red-500'>$messageErreur</p>";
            }
        }
    } else {
        $titre = mysqli_real_escape_string($conn, $_POST['titreAdd']);
        $contenu = mysqli_real_escape_string($conn, $_POST['contenuAdd']);
        $categorie = mysqli_real_escape_string($conn, $_POST['categorieAdd']);

        // $id_auteur = mysqli_real_escape_string($conn, $_GET['id_auteur']);

        $sql = "INSERT INTO Articles (Titre, Contenu_article, Categorie, date_creation, ID_auteur) VALUES (?, ?, ?, CURDATE(), ?)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erreur lors de la préparation de la requête : " . $conn->error);
        }

        $stmt->bind_param("sssi", $titre, $contenu, $categorie, $id_auteur);

        if ($stmt->execute()) {
            echo "Article ajouté avec succès !";
            header("Location: auteur.php");
            exit;
        } else {
            echo "Erreur : " . $stmt->error;
        }

        $stmt->close();
    }
} // end of if (isset($_POST['add_article']))
?>

<!-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
<!-- // pour l'affichage des articles d'auteur : ----------------------------------------------------------------------------------------------------------------------- -->

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
}

if ($id_auteur === 0) {
    die("Aucun auteur sélectionné !");
}

// Préparation de la requête
$sql_afficher_arts_aut = "SELECT 
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
WHERE 
    Auteurs.ID_auteur = ?";

$stmt = $conn->prepare($sql_afficher_arts_aut);
if (!$stmt) {
    die("Erreur de préparation de la requête : " . $conn->error);
}

$stmt->bind_param("i", $id_auteur);
$stmt->execute();
$resultAfficher = $stmt->get_result();

if (!$resultAfficher) {
    die("Erreur lors de l'exécution de la requête : " . $conn->error);
}

$articles = $resultAfficher->fetch_all(MYSQLI_ASSOC);





// afficahge analytics afficahge analytics afficahge analytics afficahge analytics afficahge analytics afficahge analytics afficahge analytics afficahge analytics 


// Préparation de la requête
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

<!-- READ MORE ART    READ MORE ART    READ MORE ART    READ MORE ART    READ MORE ART    READ MORE ART    READ MORE ART    READ MORE ART    READ MORE ART   -->

<?php
// Traiter l'action "read_more_atr" (bouton "Read more")
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['read_more_atr'])) {
    $id_article = filter_var($_POST['id_art'], FILTER_SANITIZE_NUMBER_INT);
    if ($id_article) {
        $_SESSION['id_article'] = $id_article;
        header("Location: readAuteur.php");
        exit();
    } else {
        echo "ID d'article invalide.";
    }
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>BlogPress - Auteur</title>
</head>

<body class="text-white flex flex-col min-h-screen gap-2 bg-black">

    <!-- header -->

    <?php include('header.php'); ?>

    <!-- Formulaire ajout caché -->
    <div id="formContainerAdd" class="fixed inset-0 bg-white bg-opacity-50 flex justify-center items-center hidden">
        <div
            class="bg-[#1d1d1d] opacity-85 text-white p-6 flex flex-col gap-6 rounded-sm shadow-lg w-[70%] max-sm:w-full">
            <h2 class="text-2xl font-bold text-center">Ajouter article</h2>
            <form method="POST" action="">
                <label for="titreAdd" class="block mb-2">Titre d'article :</label>
                <input type="text" id="titreAdd" class="w-full p-2 mb-4 border-0 rounded-sm bg-black" name="titreAdd" required value="<?php echo htmlspecialchars($titre); ?>"
                    placeholder="Titre d'article">
                <div class="text-red-500 text-xs"><?php echo  $erreur_tab_Add_art['titre']; ?></div>

                <label for="categorieAdd" class="block mb-2">Categorie d'article :</label>
                <input type="text" id="categorieAdd" class="w-full p-2 mb-4 border-0 rounded-sm bg-black" name="categorieAdd" value="<?php echo htmlspecialchars($categorie); ?>"
                    placeholder="Catégorie d'article">
                <div class="text-red-500 text-xs"><?php echo  $erreur_tab_Add_art['categorie']; ?></div>

                <label for="ContenuM" class="block mb-2">Contenu d'article :</label>
                <input type="text" id="ContenuAdd"
                    class="w-full p-2 mb-4 border-0 rounded-sm bg-black resize-none h-40" name="contenuAdd" value="<?php echo htmlspecialchars($contenu); ?>"
                    placeholder="Contenu d'article">
                <div class="text-red-500 text-xs"><?php echo  $erreur_tab_Add_art['contenu']; ?></div>

                <div class="flex justify-center h-12">
                    <button name="add_article"
                        class="bg-purple-500 border-2 rounded-sm w-44 h-10 font-sans hover:bg-purple-800 hover:text-white">
                        Add Article
                    </button>
                </div>
            </form>
        </div>
    </div>

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

    <main class="flex-grow w-full flex flex-col items-center gap-2">
        <section class="h-32 bg-purple-500 bg-[url('images/bg2.jpg')] bg-cover w-full grid grid-cols-4 max-sm:grid-cols-2 text-sm gap-2 px-2 justify-start items-center ">
            <div class="w-max h-full bg-black flex justify-normal items-center lg:ml-32 max-sm:ml-0">
                <h1 class="lg:text-[60px] max-lg:text-[60px] max-sm:text-[40px] text-center font-bold tracking-[15px] max-sm:tracking-[0px] px-2">Dashboard</h1>
            </div>

        </section>
        <section class="h-max w-full grid grid-cols-4 max-sm:grid-cols-2 text-sm gap-2 px-2 justify-evenly items-center ">
            <div class="w-full h-3/4 border-2 rounded-sm border-purple-800 flex flex-col gap-2 justify-center items-center max-sm:h-full max-sm:w-full max-sm:flex-row px-0 py-2 ">
                <img src="images/icones/total.png" alt="" class="w-1/3 h-1/2">
                <p class="w-full h-1/2 text-center flex items-center justify-center">
                    <?php echo htmlspecialchars($analytics['total_articles']);  ?> articles
                </p>
            </div>
            <div class="w-full h-3/4 border-2 rounded-sm border-purple-800 flex flex-col gap-2 justify-center items-center max-sm:h-full max-sm:w-full max-sm:flex-row px-0 py-2 ">
                <img src="images/icones/eye.png" alt="" class="w-1/3 h-1/2">
                <p class="w-full 1/2ll text-center flex items-center justify-center">
                    <?php echo htmlspecialchars($analytics['total_vues']);  ?> vues
                </p>
            </div>
            <div class="w-full h-3/4 border-2 rounded-sm border-purple-800 flex flex-col gap-2 justify-center items-center max-sm:h-full max-sm:w-full max-sm:flex-row px-0 py-2 ">
                <img src="images/icones/like1.png" alt="" class="w-1/3 h-1/2">
                <p class="w-full h-1/2 text-center flex items-center justify-center">
                    <?php echo htmlspecialchars($analytics['total_likes']);  ?> likes
                </p>
            </div>
            <div class="w-full h-3/4 border-2 rounded-sm border-purple-800 flex flex-col gap-2 justify-center items-center max-sm:h-full max-sm:w-full max-sm:flex-row px-0 py-2 ">
                <img src="images/icones/message.png" alt="" class="w-1/3 h-1/2">
                <p class="w-full h-fu1/2ext-center flex items-center justify-center">
                    <?php echo htmlspecialchars($analytics['total_commentaires']);  ?> comments
                </p>
            </div>
        </section>

        <section class="flex flex-col items-center gap-4 w-[85%] max-sm:w-full max-sm:text-xs h-max">
            <div class="flex justify-end w-full h-max">
                <button id="addForm"
                    class=" max-sm:text-sm bg-purple-500 border-2 rounded-sm w-44 lg:h-10 h-8 font-sans hover:bg-purple-800 hover:text-white">
                    &#10011; add article
                </button>
            </div>
            <!-- <div class="w-full grid lg:grid-cols-2 max:lg:grid-cols-2 gap-4 ">

                <?php if (!empty($articles) && is_array($articles)): ?>
                    <?php foreach ($articles as $article): ?>
                        <div
                            class="w-full flex flex-col gap-2 px-2 py-2 bg-[#111111] shadow-md shadow-purple-800 border-0 rounded-sm hover:border-[1px] hover:border-purple-800 hover:shadow-lg hover:shadow-purple-800 ">
                            <p class="" name="id_M_art"><?php echo htmlspecialchars($article['ID_article']); ?></p>
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
                            <div class="flex flex-row justify-between gap-2 max-sm:flex-col">

                                <div class="flex flex-row gap-2">
                                    <p>&#10084; <?php echo htmlspecialchars($article['nbr_likes']); ?></p>
                                    <div class="flex ">
                                        <img src="images/icones/vue.png" alt="vue" class="w-6">
                                        <p><?php echo htmlspecialchars($article['nbr_vues']); ?></p>
                                    </div>
                                    <div class="flex ">
                                        <img src="images/icones/comment.png" alt="comment" class="w-6">
                                        <p><?php echo htmlspecialchars($article['nbr_commentaires']); ?></p>
                                    </div>

                                </div>
                                <div class="flex flex-row gap-2 max-sm:justify-end">
                                    <button
                                        class="max-sm:text-sm bg-purple-500 border-2 rounded-sm w-16 lg:h-full h-8 font-sans hover:bg-purple-800 hover:text-white">
                                        read
                                    </button>
                                    <button
                                        class="updateform max-sm:text-sm bg-purple-500 border-2 rounded-sm w-16 lg:h-full h-8 font-sans hover:bg-purple-800 hover:text-white"">
                                    update
                                </button>
                                <button name=" delete_art"
                                        class=" max-sm:text-sm bg-purple-500 border-2 rounded-sm w-16 lg:h-full h-8 font-sans
                                        hover:bg-purple-800 hover:text-white"">
                                        delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun article disponible.</p>
                <?php endif; ?>

            </div> -->

            <!-- autre methode d'afficahge des articles :  -->

            <div class=" w-full ">
                <table class=" w-full ">
                    <thead class=" h-14 ">
                        <tr>
                            <th class="text-center bg-purple-800 border border-black rounded-lg hidden">ID</th>
                            <th class="text-center bg-purple-800 border border-black rounded-lg">Title</th>
                            <th class="text-center bg-purple-800 border border-black rounded-lg">Date</th>
                            <th class="text-center bg-purple-800 border border-black rounded-lg">Category</th>
                            <th class="text-center bg-purple-800 border border-black rounded-lg">Views</th>
                            <th class="text-center bg-purple-800 border border-black rounded-lg">Likes</th>
                            <th class="text-center bg-purple-800 border border-black rounded-lg w-18">Comment</th>
                            <th class="text-center bg-purple-800 border border-black rounded-lg">More info</th>

                        </tr>
                    </thead>
                    <tbody class="bg-purple-500">

                        <?php if (!empty($articles) && is_array($articles)): ?>
                            <?php foreach ($articles as $article): ?>
                                <tr>
                                    <td class="h-14 text-center border border-black rounded-lg hidden"><?php echo htmlspecialchars($article['ID_article']); ?></td>
                                    <td class="h-14 text-center border border-black rounded-lg">
                                        <!-- <?php echo htmlspecialchars($article['Titre']); ?> -->
                                        <?php
                                        $title = $article['Titre'] ?? '';
                                        $extrait = substr($title, 0, 10);
                                        echo htmlspecialchars($extrait) . '...';
                                        ?>
                                    </td>
                                    <td class="h-14 text-center border border-black rounded-lg"><?php echo htmlspecialchars($article['date_creation']); ?></td>
                                    <td class="h-14 text-center border border-black rounded-lg">
                                        <!-- <?php echo htmlspecialchars($article['Categorie']); ?> -->
                                        <?php
                                        $Category = $article['Categorie'] ?? '';
                                        $extrait = substr($Category, 0, 10);
                                        echo htmlspecialchars($extrait) . '...';
                                        ?>
                                    </td>
                                    <td class="h-14 text-center border border-black rounded-lg"><?php echo htmlspecialchars($article['nbr_vues']); ?></td>
                                    <td class="h-14 text-center border border-black rounded-lg"><?php echo htmlspecialchars($article['nbr_likes']); ?>&#10084;</td>
                                    <td class="h-14 text-center border border-black rounded-lg"><?php echo htmlspecialchars($article['nbr_commentaires']); ?></td>
                                    <td class="h-14 text-center border border-black rounded-lg">
                                        <form action="" method="POST" class="h-full w-full">
                                            <input type="hidden" name="id_art" value="<?php echo htmlspecialchars($article['ID_article']); ?>">
                                            <button name="read_more_atr" class="w-full h-full hover:border-none rounded-lg hover:bg-purple-800">More</button>
                                        </form>
                                    </td>
                                </tr>

                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Aucun article disponible.</p>
                        <?php endif; ?>

                    </tbody>
                </table>


            </div>



        </section>
    </main>


    <!-- footer -->
    <?php include('footer.php'); ?>



    <script>
        // Récupérer le bouton et le formulaire
        const updateform = document.querySelectorAll('.updateform');
        const formContainerModifier = document.getElementById('formContainerModifier');

        // Afficher ou masquer le formulaire
        updateform.forEach((form) => {
            form.addEventListener('click', () => {
                formContainerModifier.classList.toggle('hidden');
            });
        });
    </script>

</body>

<script src="js/menu_theme.js"></script>
<!-- <script src="js/update_article.js"></script> -->
<script src="js/add_article.js"></script>

</html>