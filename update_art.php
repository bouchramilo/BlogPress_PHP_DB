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
        if (!preg_match('/^[A-Za-zÀ-ÿ0-9\s-]{2,}$/u', $titreM)) {
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


    // Récupération de l'ID article
    if (isset($_POST['id_article'])) {
        $id_article = $_POST['id_article'];
        $_SESSION['id_article'] = $id_article;
    } else {
        $id_article = $_SESSION['id_article'] ?? null;
    }


    // set data in BD apres la validation :
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

        $stmt = $conn->prepare("UPDATE Articles SET Titre = ?, Contenu_article = ?, Categorie = ? WHERE ID_article = ?");
        if (!$stmt) {
            die("Erreur lors de la préparation de la requête : " . $conn->error);
        }

        $stmt->bind_param("sssi", $titreM, $contenuM, $categorieM, $id_article);

        if (!$stmt->execute()) {
            die("Erreur lors de la modification : " . $stmt->error);
        }

        echo "Article modifié avec succès !";
        header("Location: readAuteur.php");
    }
} // end of if (isset($_POST['add_article']))

?>