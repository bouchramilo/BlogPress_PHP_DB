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