<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>BlogPress - Auteur</title>
</head>

<body class="text-white flex flex-col min-h-screen gap-2 bg-black">
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
                        Test
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

    <!-- Formulaire ajout caché -->
    <div id="formContainerAdd" class="fixed inset-0 bg-white bg-opacity-50 flex justify-center items-center hidden">
        <div
            class="bg-[#1d1d1d] opacity-85 text-white p-6 flex flex-col gap-6 rounded-sm shadow-lg w-[70%] max-sm:w-full">
            <h2 class="text-2xl font-bold text-center">Ajouter article</h2>
            <form>
                <label for="titreAdd" class="block mb-2">Titre d'article :</label>
                <input type="text" id="titreAdd" class="w-full p-2 mb-4 border-0 rounded-sm bg-black"
                    placeholder="Titre d'article">

                <label for="categorieAdd" class="block mb-2">Categorie d'article :</label>
                <input type="text" id="categorieAdd" class="w-full p-2 mb-4 border-0 rounded-sm bg-black"
                    placeholder="Catégorie d'article">

                <label for="ContenuM" class="block mb-2">Contenu d'article :</label>
                <textarea type="text" id="ContenuAdd"
                    class="w-full p-2 mb-4 border-0 rounded-sm bg-black resize-none h-40"
                    placeholder="Contenu d'article"></textarea>

                <div class="flex justify-center h-12">
                    <button
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
                <input type="text" id="titreM" class="w-full p-2 mb-4 border-0 rounded-sm bg-black"
                    placeholder="Titre Modifier">

                <label for="categorieM" class="block mb-2">Categorie d'article :</label>
                <input type="categorieM" id="categorieM" class="w-full p-2 mb-4 border-0 rounded-sm bg-black"
                    placeholder="Catégorie Modifier">

                <label for="ContenuM" class="block mb-2">Contenu d'article :</label>
                <textarea type="ContenuM" id="ContenuM"
                    class="w-full p-2 mb-4 border-0 rounded-sm bg-black resize-none h-40"
                    placeholder="Contenu Modifier"></textarea>

                <div class="flex justify-center h-12">
                    <button
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
            <div class="grid lg:grid-cols-2 max:lg:grid-cols-2 gap-4 ">
                <!-- article 1 -->
                <div
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

                </div>

                <!-- article 1 -->
                <div
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

                </div>

                <!-- article 1 -->
                <div
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
                                class="updateform max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-16 lg:h-full h-8 font-sans hover:bg-[#830c61] hover:text-white">
                                update
                            </button>
                            <button class=" max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-16 lg:h-full h-8 font-sans
                                hover:bg-[#830c61] hover:text-white">
                                delete
                            </button>
                        </div>
                    </div>

                </div>

                <!-- article 1 -->
                <div
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

                </div>


            </div>
        </section>
    </main>

    <footer class="bg-[#1d1d1d] h-32 w-full flex items-center justify-center">
        <p class="max-sm:text-[12px] max-sm:px-6 text-center">Copyright © 2024 Blog Press | Powered by <span
                class="text-[#d025a0]">BlogPress</span> Theme</p>
    </footer>

</body>

<script src="js/menu_theme.js"></script>
<script src="js/update_article.js"></script>
<script src="js/add_article.js"></script>

</html>