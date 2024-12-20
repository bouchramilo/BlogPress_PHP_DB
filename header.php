<?php
// session_start();

if (isset($_POST['deconnexion'])) {
    unset($_SESSION['id_auteur']);
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

// extraire ID de l'auteur
$id_auteur = $_SESSION['id_auteur'] ?? null;
$nom_auteur = $_SESSION['Nom_auteur'] ?? null;
if (isset($_POST['id_auteur'])) {
    $id_auteur = filter_var($_POST['id_auteur'], FILTER_VALIDATE_INT);
    if ($id_auteur === false) {
        die("ID de l'auteur invalide !");
    }
    $_SESSION['id_auteur'] = $id_auteur;
    $nom_auteur = $_SESSION['Nom_auteur'];
}
?>

<!-- header selon si un auteur si connecter ou non :  -->
<header class="bg-[#1d1d1d] w-full h-20 flex justify-center items-center px-12">
    <nav class="flex h-full flex-row justify-between w-full ml-10">
        <!-- Logo -->
        <div class="w-1/12 h-full flex justify-center items-center">
            <a href="index.php">
                <span class="text-2xl text-purple-500 hover:text-purple-800 font-bold">BlogPress</span>
            </a>
        </div>

        <!-- Menu Links -->
        <div id="menu"
            class="w-1/2 h-full flex gap-4 flex-shrink lg:flex lg:items-center lg:justify-center hidden flex-col lg:flex-row absolute left-1/2 border-none rounded-2xl lg:relative top-16 lg:top-0 lg:left-auto bg-[#1e1e1e] lg:bg-transparent lg:w-6/12 z-10 lg:z-auto p-4 lg:p-0">
            <a href="index.php" class="w-full lg:w-1/5 h-3/5 lg:mb-0">
                <button
                    class="border-b-2 border-b-[#1d1d1d] rounded-sm w-full lg:h-full h-8 font-sans text-xl hover:border-b-4 hover:border-purple-500">
                    Home
                </button>
            </a>

            <?php if ($id_auteur): ?>
                <!-- Menu pour auteur connecté -->
                <a href="auteur.php" class="w-full lg:w-1/5 h-3/5 lg:mb-0">
                    <button
                        class="border-b-2 border-b-[#1d1d1d] rounded-sm w-full lg:h-full h-8 font-sans text-xl hover:border-b-4 hover:border-purple-500">
                        <?= $id_auteur ? "Dashboard" : "Auteur" ?>
                    </button>
                </a>
                <form action="index.php" method="POST" class="w-full lg:w-1/5 h-3/5 lg:mb-0">
                    <button name="deconnexion"
                        class="max-sm:text-sm bg-purple-500 border-2 rounded-sm w-full lg:h-full h-8 font-saxl hover:bg-purple-800 hover:text-white">
                        Déconnexion
                    </button>
                </form>
                <a href="profile.php" class="w-full lg:w-1/5 h-3/5 lg:mb-0 flex justify-center items-center gap-2">
                    <img src="images/bg2.jpg" alt="Profile" class="w-14 h-14 border-none rounded-full">
                    <?php echo htmlspecialchars($nom_auteur); ?>
                </a>
            <?php else: ?>
                <!-- Menu pour auteur non connecté -->
                <a href="connexion.php" class="w-full lg:w-1/5 h-3/5 lg:mb-0">
                    <button
                        class="max-sm:text-sm bg-purple-500 border-2 rounded-sm w-full lg:h-full h-8 font-saxl hover:bg-purple-800 hover:text-white">
                        Connexion
                    </button>
                </a>
                <a href="inscription.php" class="w-full lg:w-1/5 h-3/5 lg:mb-0">
                    <button
                        class="max-sm:text-sm bg-purple-500 border-2 rounded-sm w-full lg:h-full h-8 font-saxl hover:bg-purple-800 hover:text-white">
                        Inscription
                    </button>
                </a>
            <?php endif; ?>
        </div>

        <!-- Menu pour les screens petites -->
        <div class="lg:hidden justify-end flex w-1/12">
            <button onclick="Menu()" class="flex items-center px-2 py-0 my-4 border h-10 rounded bg-purple-500">
                <svg class="fill-current h-6 w-6" viewBox="0 0 20 20">
                    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                </svg>
            </button>
        </div>
    </nav>
</header>