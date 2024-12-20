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


<!-- DELETE comment ================================================================================ -->
<?php

if (isset($_POST['delete_comment'])) {
    if (isset($_POST['id_commentaire'])) {
        $id_comment = mysqli_real_escape_string($conn, $_POST['id_commentaire']);
        if (!is_numeric($id_comment)) {
            die("ID commentaire invalide !");
        }
    }

    // Requête pour supprimer le commentaire : 
    $stmt = $conn->prepare("DELETE FROM Commentaires WHERE ID_Comment = ?");
    if (!$stmt) {
        die("Erreur de préparation de la requête : " . $conn->error);
    }

    $stmt->bind_param("i", $id_comment);

    if (!$stmt->execute()) {
        die("Erreur lors de la suppression : " . $stmt->error);
    }

    $stmt->close();

    header("Location: readAuteur.php");
    exit;
}
?>