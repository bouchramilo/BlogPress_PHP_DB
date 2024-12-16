<?php
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

    if (isset($_GET['id_auteur'])) {
        // Extraire l'id_auteur à partir de URL 
        $id_auteur = $_GET['id_auteur'];

        $id_auteur = intval($id_auteur);

        echo "L'id_auteur extrait est : " . $id_auteur;
    } else {
        echo "Aucun id_auteur n'a été fourni dans l'URL.";
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

        $id_auteur = mysqli_real_escape_string($conn, $_GET['id_auteur']);

        $sql = "INSERT INTO Articles (Titre, Contenu_article, Categorie, date_creation, ID_auteur) VALUES('$titre', '$contenu', '$categorie', CURDATE(), '$id_auteur')";

        if (mysqli_query($conn, $sql)) {
            echo "Article ajouté avec succès !";
            header("Location: auteur.php?id_auteur=$id_auteur");
        } else {
            echo "Erreur : " . mysqli_error($conn);
        }
    }
} // end of if (isset($_POST['add_article']))
?>

<!-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
<!-- // pour l'affichage des articles d'auteur : ----------------------------------------------------------------------------------------------------------------------- -->

<?php
if (isset($_GET['id_auteur'])) {
    $id_auteur = $_GET['id_auteur'];
} elseif (isset($_POST['id_auteur'])) {
    $id_auteur = $_POST['id_auteur'];
} else {
    die("Erreur : ID auteur non défini.");
}

$sql_afficher_arts_aut = "SELECT 
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
WHERE 
    Auteurs.ID_auteur = '$id_auteur' ;";

$resultAfficher = $conn->query($sql_afficher_arts_aut);

$articles = mysqli_fetch_all($resultAfficher, MYSQLI_ASSOC);

?>


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

    if (isset($_GET['id_auteur'])) {
        // Extraire l'id_auteur à partir de URL 
        $id_auteur = $_GET['id_auteur'];

        $id_auteur = intval($id_auteur);

        echo "L'id_auteur extrait est : " . $id_auteur;
    } else {
        echo "Aucun id_auteur n'a été fourni dans l'URL.";
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

        $id_auteur = mysqli_real_escape_string($conn, $_GET['id_auteur']);

        $sql = "UPDATE Articles SET Titre = '$titreM', Contenu_article = '$contenuM', Categorie = '$categorieM' WHERE ID_article = 9 ";

        if (mysqli_query($conn, $sql)) {
            echo "Article modifié avec succès !";
            header("Location: auteur.php?id_auteur=$id_auteur");
        } else {
            echo "Erreur : " . mysqli_error($conn);
        }
    }
} // end of if (isset($_POST['add_article']))




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
                        class="bg-[#d025a0] border-2 rounded-sm w-44 h-10 font-sans hover:bg-[#830c61] hover:text-white">
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

                <label for="categorieM" class="block mb-2">Categorie d'article :</label>
                <input type="categorieM" id="categorieM" class="w-full p-2 mb-4 border-0 rounded-sm bg-black" name="categorieM"
                    placeholder="Catégorie Modifier">

                <label for="ContenuM" class="block mb-2">Contenu d'article :</label>
                <input type="ContenuM" id="ContenuM"
                    class="w-full p-2 mb-4 border-0 rounded-sm bg-black resize-none h-40" name="ContenuM"
                    placeholder="Contenu Modifier">

                <div class="flex justify-center h-12">
                    <button name="modifier_article"
                        class="bg-[#d025a0] border-2 rounded-sm w-44 h-10 font-sans hover:bg-[#830c61] hover:text-white">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <main class="flex-grow w-full flex flex-col items-center gap-2">
        <section class="h-28 bg-[url('images/bg2.jpg')] bg-cover w-full flex justify-center items-center ">
            <h1 class="text-3xl ">Vos Articles</h1>


        </section>

        <section class="flex flex-col items-center gap-4 w-[85%] h-max">
            <div class="flex justify-end w-full h-max">
                <button id="addForm"
                    class=" max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-44 lg:h-10 h-8 font-sans hover:bg-[#830c61] hover:text-white">
                    &#10011; add article
                </button>
            </div>
            <div class="w-full grid lg:grid-cols-2 max:lg:grid-cols-2 gap-4 ">

                <?php if (!empty($articles) && is_array($articles)): ?>
                    <?php foreach ($articles as $article): ?>
                        <!-- article 1 -->
                        <div
                            class="w-full flex flex-col gap-2 px-2 py-2 bg-[#111111] shadow-md shadow-[#830c61] border-0 rounded-sm hover:border-[1px] hover:border-[#830c61] hover:shadow-lg hover:shadow-[#830c61] ">
                            <p class="hidden"><?php echo htmlspecialchars($article['ID_article']); ?></p>
                            <h1 class="lg:text-2xl max-lg:text-2xl max-sm:text-xl text-[#830c61]"><?php echo htmlspecialchars($article['Titre']); ?></h1>
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
                                        <img src="images/icones/vue.png" alt="comment" class="w-6">
                                        <p><?php echo htmlspecialchars($article['nbr_vues']); ?></p>
                                    </div>
                                    <div class="flex ">
                                        <img src="images/icones/comment.png" alt="comment" class="w-6">
                                        <p><?php echo htmlspecialchars($article['nbr_commentaires']); ?></p>
                                    </div>

                                </div>
                                <div class="flex flex-row gap-2 max-sm:justify-end">
                                    <button
                                        class="max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-16 lg:h-full h-8 font-sans hover:bg-[#830c61] hover:text-white">
                                        read
                                    </button>
                                    <button
                                        class="updateform max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-16 lg:h-full h-8 font-sans hover:bg-[#830c61] hover:text-white"">
                                    update
                                </button>
                                <button
                                    class=" max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-16 lg:h-full h-8 font-sans
                                        hover:bg-[#830c61] hover:text-white"">
                                        delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun article disponible.</p>
                <?php endif; ?>

                <!-- article 1 -->
                <!-- <div
                    class="flex flex-col gap-2 px-2 py-2 shadow-md shadow-[#830c61] border-0 rounded-sm hover:border-[1px] hover:border-[#830c61] hover:shadow-lg hover:shadow-[#830c61] ">
                    <h1 class="lg:text-2xl max-lg:text-2xl max-sm:text-xl text-[#830c61]">titre de article</h1>
                    <p class="max-sm:text-sm text-gray-400">date de création : 14-12-2024</p>
                    <p class="max-sm:text-sm text-gray-400">Catégorie : gere de l'article </p>
                    <p class="max-sm:text-sm">Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita
                        quidem culpa error sunt
                        nemo unde? Fuga alias, nesciunt aliquam aperiam beatae porro distinctio officia asperiores
                        repellendus harum nulla nam rem!
                        Alias, sit voluptate! Explicabo libero, totam temporibus unde modi cum doloremque commodi
                        vero minus natus id nesciunt facilis ex nam corrupti, iste minima amet fugit consectetur
                        esse rerum, omnis ipsum?
                    </p>
                    <div class="flex flex-row justify-between gap-2 max-sm:flex-col">

                        <div class="flex flex-row gap-2">
                            <p>&#10084; 5</p>
                            <div class="flex ">
                                <img src="images/icones/vue.png" alt="comment" class="w-6">
                                <p>7</p>
                            </div>
                            <div class="flex ">
                                <img src="images/icones/comment.png" alt="comment" class="w-6">
                                <p>7</p>
                            </div>

                        </div>
                        <div class="flex flex-row gap-2 max-sm:justify-end">
                            <button
                                class="max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-16 lg:h-full h-8 font-sans hover:bg-[#830c61] hover:text-white">
                                read
                            </button>
                            <button
                                class="updateform max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-16 lg:h-full h-8 font-sans hover:bg-[#830c61] hover:text-white"">
                                update
                            </button>
                            <button
                                class=" max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-16 lg:h-full h-8 font-sans
                                hover:bg-[#830c61] hover:text-white"">
                                delete
                            </button>
                        </div>
                    </div>

                </div> -->


            </div>
        </section>
    </main>


    <!-- footer -->
    <?php include('footer.php'); ?>

</body>

<script src="js/menu_theme.js"></script>
<script src="js/update_article.js"></script>
<script src="js/add_article.js"></script>

</html>