<?php

include('connect_DB.php');

$erreur_tab = [
    'name' => '',
    'prenom' => '',
    'email' => '',
    'password1' => '',
    'password2' => ''
];

$name = $prenom = $email = $password1 = $password2 = '';

if (isset($_POST['signup'])) {
    // Validation du nom
    if (empty($_POST['nom_auteur'])) {
        $erreur_tab['name'] = 'Le nom est vide!!!';
    } else {
        $name = htmlspecialchars($_POST['nom_auteur']);
        if (!preg_match('/^[A-Za-z]{2,}([ -][A-Za-z]{2,})*$/', $name)) {
            $erreur_tab['name'] = 'Le nom doit contenir au moins 2 lettres et ne doit pas contenir de chiffres ou de caractères spéciaux.';
        }
    }

    // Validation du prénom
    if (empty($_POST['prenom_auteur'])) {
        $erreur_tab['prenom'] = 'Le prénom est vide!!!';
    } else {
        $prenom = htmlspecialchars($_POST['prenom_auteur']);
        if (!preg_match('/^[A-Za-z]{2,}([ -][A-Za-z]{2,})*$/', $prenom)) {
            $erreur_tab['prenom'] = 'Le prénom doit contenir au moins 2 lettres et ne doit pas contenir de chiffres ou de caractères spéciaux.';
        }
    }

    // Validation de l'email
    if (empty($_POST['email_auteur'])) {
        $erreur_tab['email'] = 'L\'email est vide!!!';
    } else {
        $email = htmlspecialchars($_POST['email_auteur']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur_tab['email'] = 'L\'email n\'est pas valide!!!';
        }
    }

    // Validation des mots de passe
    $passwordRegex = "/^[a-zA-Z0-9$*-+*.&#:?!;,]{8,}$/";

    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    if (empty($password1)) {
        $erreur_tab['password1'] = 'Le mot de passe est vide!!!';
    } elseif (!preg_match($passwordRegex, $password1)) {
        $erreur_tab['password1'] = 'Le mot de passe doit contenir au moins 8 caractères, avec des lettres et des chiffres.';
    }

    if (empty($password2)) {
        $erreur_tab['password2'] = 'Le mot de passe de confirmation est vide!!!';
    } elseif ($password1 !== $password2) {
        $erreur_tab['password2'] = 'Les mots de passe ne correspondent pas.';
    }

    
    if (!array_filter($erreur_tab)) {
        $name = mysqli_real_escape_string($conn, $name);
        $prenom = mysqli_real_escape_string($conn, $prenom);
        $email = mysqli_real_escape_string($conn, $email);
        
        // Hachage du mot de passe
        $password_hashed = password_hash($password1, PASSWORD_BCRYPT);

        
        $statment = $conn->prepare("INSERT INTO Auteurs (Nom_auteur, Prénom_auteur, Email_auteur, Password) VALUES (?, ?, ?, ?)");
        $statment->bind_param("ssss", $name, $prenom, $email, $password_hashed);

        if ($statment->execute()) {
            // Succès
            header('Location: connexion.php');
        } else {
            // Erreur
            echo "Erreur lors de l'insertion des données : " . $statment->error;
        }

        $statment->close();
    }
}

?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>BlogPress - Sign up</title>
</head>

<body class="text-white flex flex-col items-center gap-2 bg-black ">

    <!-- header  header  header  header  header  header  header  header  header  header  header  header  header  header -->
    <?php include('header.php'); ?>
    <!-- header  header  header  header  header  header  header  header  header  header  header  header  header  header -->

    <main class="h-max w-full flex flex-col justify-center items-center gap-2">
        <section class="h-screen bg-[url('images/bg3.jpg')] bg-cover w-full flex justify-center items-center ">
            <div
                class="bg-[#1d1d1d] opacity-85  h-[90%] max-sm:w-full max-lg:w-1/2 lg:w-1/2 flex flex-col justify-center items-center">
                <h1 class="text-2xl font-bold">Sign up</h1>
                <form action="" method="POST" class="flex flex-col gap-2 w-4/5 h-4/5">
                    <label for="name">Nom : </label>
                    <input type="text" name="nom_auteur" id="nom_auteur" placeholder="Votre nom." value="<?php echo htmlspecialchars($name); ?>"
                        class="h-12 text-white bg-black px-2 border-0 hover:border-2 hover:border-[#830c62]">
                        <div class="text-red-500 text-xs"><?php print_r( $erreur_tab['name']); ?></div>
                    

                    <label for="prenom">Prénom : </label>
                    <input type="text" name="prenom_auteur" id="prenom_auteur" placeholder="Votre prénom." value="<?php echo htmlspecialchars($prenom); ?>"
                        class="h-12 text-white bg-black px-2 border-0 hover:border-2 hover:border-[#830c62]">
                    <div class="text-red-500 text-xs"><?php echo  $erreur_tab['prenom']; ?></div>

                    <label for="email">Adresse e-mail : </label>
                    <input type="email" name="email_auteur" id="email_auteur" placeholder="Votre adresse e-mail." value="<?php echo htmlspecialchars($email); ?>"
                        class="h-12 text-white bg-black px-2 border-0 hover:border-2 hover:border-[#830c62]">
                    <div class="text-red-500 text-xs"><?php echo  $erreur_tab['email']; ?></div>

                    <label for="password1">Mot de passe : </label>
                    <input type="password" name="password1" id="password1" placeholder="Votre mot de passe" value="<?php echo htmlspecialchars($password1); ?>"
                        class="h-12 text-white bg-black px-2 border-0 hover:border-2 hover:border-[#830c62]">
                    <div class="text-red-500 text-xs"><?php echo  $erreur_tab['password1']; ?></div>

                    <label for="password2">Confirmer mot de passe : </label>
                    <input type="password" name="password2" id="password2"
                        placeholder="Votre mot de passe (Confirmation)." value="<?php echo htmlspecialchars($password2); ?>"
                        class="h-12 text-white bg-black px-2 border-0 hover:border-2 hover:border-[#830c62]">
                    <div class="text-red-500 text-xs"><?php echo  $erreur_tab['password2']; ?></div>

                    <div class="flex justify-center h-12 pt-4">
                        <button name="signup"
                            class="bg-[#d025a0] border-2 rounded-sm w-44 h-12 font-sans text-xl hover:bg-[#830c61] hover:text-white">
                            sign up
                        </button>
                    </div>
                </form>

            </div>
        </section>
    </main>


    <!-- footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer -->
    <?php include('footer.php'); ?>
    <!-- footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer  footer -->

</body>
<script src="js/menu_theme.js"></script>

</html>