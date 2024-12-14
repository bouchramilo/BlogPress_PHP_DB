<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>BlogPress - Sign in</title>
</head>

<body class="text-white flex flex-col items-center gap-2 bg-black ">
    <header class="bg-[#1d1d1d] w-full h-16 flex justify-center items-center px-12">
        <nav class="flex flex-row justify-between w-full">
            <!-- Logo -->
            <div class="w-1/12">
                <a href="index.html">
                    <span class="text-2xl text-[#d025a0] hover:text-[#830c61] font-bold">BlogPress</span>
                </a>
            </div>
            <!-- Menu Links -->
            <div id="menu"
                class="w-1/2 flex gap-4 flex-shrink lg:flex lg:items-center lg:justify-center hidden flex-col lg:flex-row absolute left-1/2 border-none rounded-2xl lg:relative top-16 lg:top-0 lg:left-auto bg-[#1e1e1e] lg:bg-transparent lg:w-6/12 z-10 lg:z-auto p-4 lg:p-0">
                <a href="" class="w-full lg:w-1/5   lg:mb-0">
                    <button
                        class="border-b-2 border-b-[#1d1d1d] rounded-sm w-full lg:h-full h-8 font-sans text-xl hover:border-b-4 hover:border-[#d025a0]">
                        Home
                    </button>
                </a>
                <a href="" class="w-full lg:w-1/5   lg:mb-0">
                    <button
                        class="border-b-2 border-b-[#1d1d1d] rounded-sm w-full lg:h-full h-8 font-sans text-xl hover:border-b-4 hover:border-[#d025a0]">
                        test
                    </button>
                </a>

                <a href="connexion.php" class="w-full lg:w-1/5   lg:mb-0">
                    <button
                        class="bg-[#d025a0] border-2 rounded-sm w-full lg:h-full h-8 font-sans text-xl hover:bg-[#830c61] hover:text-white">
                        Connexion
                    </button>
                </a>
                <a href="inscription.php" class="w-full lg:w-1/5   lg:mb-0">
                    <button
                        class="bg-[#d025a0] border-2 rounded-sm w-full lg:h-full h-8 font-sans text-xl hover:bg-[#830c61] hover:text-white">
                        Inscription
                    </button>
                </a>
            </div>
            <!-- Hamburger Menu -->
            <div class="lg:hidden justify-end flex w-1/12">
                <button onclick="Menu()" class="flex items-center px-2 py-0 my-4 border h-10 rounded bg-[#d025a0]">
                    <svg class="fill-current h-6 w-6" viewBox="0 0 20 20">
                        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                    </svg>
                </button>
            </div>
        </nav>
    </header>

    <main class="h-max w-full flex flex-col justify-center items-center gap-2">

        <section class="h-screen bg-[url('images/bg2.jpg')] bg-cover w-full flex justify-center items-center ">
            <div
                class="bg-[#1d1d1d] opacity-85  h-1/2 max-sm:w-full max-lg:w-1/2 lg:w-1/2 flex flex-col justify-center items-center">
                <h1 class="text-2xl font-bold">Sign in</h1>
                <form action="" method="POST" class="flex flex-col gap-2 w-4/5 h-max">

                    <label for="email">Adresse e-mail : </label>
                    <input type="email" name="email_auteur" id="email_auteur" placeholder="Votre adresse e-mail."
                        class="h-12 text-white bg-black px-2 border-0 hover:border-2 hover:border-[#830c62]">

                    <label for="password">Mot de passe : </label>
                    <input type="password" name="password" id="password" placeholder="Votre mot de passe"
                        class="h-12 text-white bg-black px-2 border-0 hover:border-2 hover:border-[#830c62]">


                    <div class="flex justify-center h-12 pt-4">
                        <button
                            class="bg-[#d025a0] border-2 rounded-sm w-44 h-12 font-sans text-xl hover:bg-[#830c61] hover:text-white">
                            sign in
                        </button>
                    </div>
                </form>

            </div>
        </section>
    </main>

    <footer class="bg-[#1d1d1d] h-32 w-full flex items-center justify-center">
        <p class="max-sm:text-[12px] max-sm:px-6 text-center">Copyright © 2024 Blog Press | Powered by <span
                class="text-[#d025a0]">BlogPress</span> Theme</p>
    </footer>


</body>
<script src="js/menu_theme.js"></script>

</html>