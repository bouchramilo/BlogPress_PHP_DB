<?php

include('connect_DB.php'); // Connexion à la base de données
session_start();

$notFound = ''; // Message d'erreur pour l'utilisateur non trouvé

// Tableau des erreurs
$erreur_tab_conn = [
    'email' => '',
    'password' => ''
];

$email = $password = $id_auteur = '';

if (isset($_POST['signin'])) {

    // Validation de l'email
    if (empty($_POST['email_auteur'])) {
        $erreur_tab_conn['email'] = 'L\'email est vide!!!';
    } else {
        $email = $_POST['email_auteur'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur_tab_conn['email'] = 'L\'email n\'est pas valide!!!';
        }
    }

    // Validation du mot de passe
    $passwordRegex = "/^[a-zA-Z0-9$*-+*.&#:?!;,]{8,}$/";

    $password = $_POST['password'];

    if (empty($password)) {
        $erreur_tab_conn['password'] = 'Le mot de passe est vide!!!';
    } elseif (!preg_match($passwordRegex, $password)) {
        $erreur_tab_conn['password'] = 'Le mot de passe doit contenir au moins 8 caractères, avec des lettres et des chiffres.';
    }

    // Si aucune erreur dans le formulaire
    if (empty($erreur_tab_conn['email']) && empty($erreur_tab_conn['password'])) {
        // Requête préparée pour éviter les injections SQL
        $stmt = $conn->prepare("SELECT ID_auteur, Password, Nom_auteur FROM Auteurs WHERE Email_auteur = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Utilisateur trouvé
            $row = $result->fetch_assoc();
            $hashed_password = $row['Password']; // Mot de passe haché récupéré

            // Vérification du mot de passe
            if (password_verify($password, $hashed_password)) {
                $id_auteur = $row['ID_auteur']; // Récupération de l'ID de l'auteur

                $_SESSION['id_auteur'] = $id_auteur;

                echo  $_SESSION['id_auteur'];

                $_SESSION['Nom_auteur'] = $row['Nom_auteur'];
                echo $_SESSION['Nom_auteur'];

                // Redirection vers la page 'auteur.php' avec l'ID dans l'URL
                header("Location: auteur.php");
                // header("Location: auteur.php?id_auteur=$id_auteur");
                exit(); // Terminer le script après la redirection
            } else {
                $notFound = "Mot de passe incorrect.";
            }
        } else {
            $notFound = "Aucun utilisateur trouvé avec cet email.";
        }

        $stmt->close();
    }
}

?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>BlogPress - Sign in</title>
</head>

<body class="text-white flex flex-col items-center gap-2 bg-black ">

    <!-- header -->
    <?php include('header.php'); ?>

    <main class="h-max w-full flex flex-col justify-center items-center gap-2">

        <section class="h-screen bg-[url('images/bg2.jpg')] bg-cover w-full flex justify-center items-center ">
            <div
                class="bg-[#1d1d1d] opacity-85  h-1/2 max-sm:w-full max-lg:w-1/2 lg:w-1/2 flex flex-col justify-center items-center">
                <div class="text-red-500 text-sm"><?php echo  $notFound; ?></div>

                <h1 class="text-2xl font-bold">Sign in</h1>
                <form action="" method="POST" class="flex flex-col gap-2 w-4/5 h-max">

                    <label for="email">Adresse e-mail : </label>
                    <input type="email" name="email_auteur" id="email_auteur" placeholder="Votre adresse e-mail." value="<?php echo htmlspecialchars($email); ?>"
                        class="h-12 text-white bg-black px-2 border-0 hover:border-2 hover:border-purple-500">
                    <div class="text-red-500 text-xs"><?php echo  $erreur_tab_conn['email']; ?></div>


                    <label for="password">Mot de passe : </label>
                    <input type="password" name="password" id="password" placeholder="Votre mot de passe" value="<?php echo htmlspecialchars($password); ?>"
                        class="h-12 text-white bg-black px-2 border-0 hover:border-2 hover:border-purple-500">
                    <div class="text-red-500 text-xs"><?php echo  $erreur_tab_conn['password']; ?></div>


                    <div class="flex justify-center h-12 pt-4">
                        <button name="signin"
                            class="bg-purple-500 border-2 rounded-sm w-44 h-12 font-sans text-xl hover:bg-purple-800 hover:text-white">
                            sign in
                        </button>
                    </div>
                </form>

            </div>
        </section>
    </main>


    <!-- footer -->
    <?php include('footer.php'); ?>

</body>
<script src="js/menu_theme.js"></script>

</html>