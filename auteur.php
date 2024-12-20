<?php
session_start();
include('connect_DB.php');
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

// la requête pour efficher à un auteur toutes leurs articles : 
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

<!-- des includes pour des parties de code (l'ajout des articles + les statistiques des articles ) : -->
<?php
include "ajouter_article.php";
include "statistique.php";

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>BlogPress - Auteur</title>
</head>

<body class="text-white flex flex-col min-h-screen gap-2 bg-black">

    <!-- header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header -->
    <?php include('header.php'); ?>
    <!-- header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header  header -->

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

    <main class="flex-grow w-full flex flex-col items-center gap-2">
        <section class="h-32 bg-purple-500 bg-[url('images/bg2.jpg')] bg-cover w-full grid grid-cols-4 max-sm:grid-cols-2 text-sm gap-2 px-2 justify-start items-center ">
            <div class="w-max h-full bg-black flex justify-normal items-center lg:ml-32 max-sm:ml-0">
                <h1 class="lg:text-[60px] max-lg:text-[60px] max-sm:text-[40px] text-center font-bold tracking-[15px] max-sm:tracking-[0px] px-2">Dashboard</h1>
            </div>
        </section>

        </section>
        <section class="flex flex-row items-center gap-6 w-full max-sm:w-full max-sm:text-xs h-max">
            <hr class="bg-white size-1 w-2/5">
            <p class="w-1/5 text-center font-bold text-xl max-sm:text-sm">Vos Articles</p>
            <hr class="bg-white size-1 w-2/5">
        </section>

        <section class="flex flex-col items-center gap-4 w-[85%] max-sm:w-full max-sm:text-xs h-max">
            <div class="flex justify-end w-full h-max">
                <button id="addForm"
                    class=" max-sm:text-sm bg-purple-500 border-2 rounded-sm w-44 lg:h-10 h-8 font-sans hover:bg-purple-800 hover:text-white">
                    &#10011; add article
                </button>
            </div>
            <!-- L'afficahge des articles :  -->
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

        <!-- statistiques  statistiques  statistiques  statistiques  statistiques  statistiques  statistiques  statistiques  statistiques  statistiques -->
        <section class="flex flex-row items-center gap-6 w-full max-sm:w-full max-sm:text-xs h-max">
            <hr class="bg-white size-1 w-2/5">
            <p class="w-1/5 text-center font-bold text-xl max-sm:text-sm">Statistiques</p>
            <hr class="bg-white size-1 w-2/5">
        </section>

        <section class="h-max w-[85%] grid grid-cols-4 max-sm:grid-cols-2 text-sm gap-2 px-2 justify-evenly items-center ">
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

        <section class="flex flex-col items-center gap-4 w-[95%] max-sm:w-full max-sm:text-xs h-max">
            <!-- Graphiques -->
            <div class="w-full flex justify-center items-center">
                <div class="w-1/2"><canvas id="total"></canvas></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-20">
                <div class=""><canvas id="vuesGraph"></canvas></div>
                <div class=""><canvas id="likesGraph"></canvas></div>
                <div class=""><canvas id="commentsGraph"></canvas></div>
            </div>
        </section>
    </main>


    <!-- footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer -->
    <?php include('footer.php'); ?>
    <!-- footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer -->

    <script>
        const labels = <?php echo json_encode($data_articles); ?>;
        const vuesData = <?php echo json_encode($data_vues); ?>;
        const likesData = <?php echo json_encode($data_likes); ?>;
        const commentsData = <?php echo json_encode($data_comments); ?>;

        // Tableau de couleurs pour spécifier un color pour chaque article : 
        const colors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#9966FF', '#4BC0C0', '#FF9F40', '#66FF66', '#FF6666', '#6666FF', '#CCFF66'
        ];

        // Fonction pour créer un graphique selon les données (vues, likes, comments)
        function createChart(ctx, label, data, backgroundColors, borderColor, title) {
            return new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: borderColor,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: title
                        }
                    }
                }
            });
        }

        createChart(
            document.getElementById('vuesGraph').getContext('2d'),
            'Vues',
            vuesData,
            colors.slice(0, labels.length),
            'white',
            "Statistiques des Vues."
        );

        createChart(
            document.getElementById('likesGraph').getContext('2d'),
            'Likes',
            likesData,
            colors.slice(0, labels.length),
            'white',
            "Statistiques des Likes."
        );

        createChart(
            document.getElementById('commentsGraph').getContext('2d'),
            'Commentaires',
            commentsData,
            colors.slice(0, labels.length),
            'white',
            "Statistiques des commentaires."
        );
    </script>

</body>

<script src="js/menu_theme.js"></script>
<script src="js/add_article.js"></script>

</html>