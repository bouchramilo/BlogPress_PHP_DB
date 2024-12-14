<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>BlogPress - HOME</title>
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
                <a href="auteur.php" class="w-full lg:w-1/5   lg:mb-0">
                    <button
                        class="border-b-2 border-b-[#1d1d1d] rounded-sm w-full lg:h-full h-8 font-sans text-xl hover:border-b-4 hover:border-[#d025a0]">
                        auteur
                    </button>
                </a>

                <a href="connexion.php" class="w-full lg:w-1/5   lg:mb-0">
                    <button
                        class="max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-full lg:h-full h-8 font-saxl hover:bg-[#830c61] hover:text-white">
                        Connexion
                    </button>
                </a>
                <a href="inscription.php" class="w-full lg:w-1/5   lg:mb-0">
                    <button
                        class="max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-full lg:h-full h-8 font-saxl hover:bg-[#830c61] hover:text-white">
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
        <section class="h-96 bg-[url('images/bg2.jpg')] bg-cover w-full flex justify-center items-center ">
            <h1 class="lg:text-[60px] max-lg:text-[60px] max-sm:text-[40px] text-center font-bold"
                style="text-shadow: 0 0 10px #830c61, 0 0 10px #830c61, 0 0 20px #830c61;">Tech News & Blogs</h1>
        </section>

        <section class="flex flex-col items-center gap-4 w-[85%] h-max">
            <h1 class="text-3xl">Popular Stories</h1>
            <div class="grid lg:grid-cols-2 max:lg:grid-cols-2 gap-4 ">
                <div
                    class="flex flex-col gap-2 px-2 py-2 shadow-md shadow-[#830c61] border-0 rounded-sm hover:border-[1px] hover:border-[#830c61] hover:shadow-lg hover:shadow-[#830c61] ">
                    <h1 class="lg:text-2xl max-lg:text-2xl max-sm:text-xl">titre de article</h1>
                    <p class="max-sm:text-sm">Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita quidem
                        culpa error sunt nemo
                        unde? Fuga alias, nesciunt aliquam aperiam beatae porro distinctio officia asperiores
                        repellendus harum nulla nam rem!
                        Alias, sit voluptate! Explicabo libero, totam temporibus unde modi cum doloremque commodi vero
                        minus natus id nesciunt facilis ex nam corrupti, iste minima amet fugit consectetur esse rerum,
                        omnis ipsum?
                        Maiores, dolores? Sunt porro ex magnam iste, earum beatae, esse hic distinctio error est quos.
                        Doloremque doloribus sint recusandae aliquam, quae saepe placeat harum nulla excepturi rerum
                        dolore nesciunt in.
                    </p>
                    <button
                        class="max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-40 lg:h-full h-8 font-sans hover:bg-[#830c61] hover:text-white"">
                        Read more &#10097;&#10097;
                    </button>
                </div>
                <div class=" flex flex-col gap-2 px-2 py-2 shadow-md shadow-[#830c61] border-0 rounded-sm
                        hover:border-[1px] hover:border-[#830c61] hover:shadow-lg hover:shadow-[#830c61] ">
                    <h1 class=" lg:text-2xl max-lg:text-2xl max-sm:text-xl">titre de article</h1>
                        <p class="max-sm:text-sm">Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita
                            quidem culpa error sunt
                            nemo unde? Fuga alias, nesciunt aliquam aperiam beatae porro distinctio officia asperiores
                            repellendus harum nulla nam rem!
                            Alias, sit voluptate! Explicabo libero, totam temporibus unde modi cum doloremque commodi
                            vero minus natus id nesciunt facilis ex nam corrupti, iste minima amet fugit consectetur
                            esse rerum, omnis ipsum?
                            Maiores, dolores? Sunt porro ex magnam iste, earum beatae, esse hic distinctio error est
                            quos. Doloremque doloribus sint recusandae aliquam, quae saepe placeat harum nulla excepturi
                            rerum dolore nesciunt in.
                        </p>
                        <button
                            class="max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-40 lg:h-full h-8 font-sans hover:bg-[#830c61] hover:text-white">
                            Read more &#10097;&#10097;
                        </button>
                </div>
                <div class=" flex flex-col gap-2 px-2 py-2 shadow-md shadow-[#830c61] border-0 rounded-sm
                            hover:border-[1px] hover:border-[#830c61] hover:shadow-lg hover:shadow-[#830c61] ">
                    <h1 class="lg:text-2xl max-lg:text-2xl max-sm:text-xl">titre de article</h1>
                    <p class="max-sm:text-sm">Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita quidem
                        culpa error sunt
                        nemo unde? Fuga alias, nesciunt aliquam aperiam beatae porro distinctio officia
                        asperiores repellendus harum nulla nam rem!
                        Alias, sit voluptate! Explicabo libero, totam temporibus unde modi cum doloremque
                        commodi vero minus natus id nesciunt facilis ex nam corrupti, iste minima amet fugit
                        consectetur esse rerum, omnis ipsum?
                        Maiores, dolores? Sunt porro ex magnam iste, earum beatae, esse hic distinctio error est
                        quos. Doloremque doloribus sint recusandae aliquam, quae saepe placeat harum nulla
                        excepturi rerum dolore nesciunt in.
                    </p>
                    <button
                        class="max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-40 lg:h-full h-8 font-sans hover:bg-[#830c61] hover:text-white"">
                        Read more &#10097;&#10097;
                    </button>
                </div>
                <div class=" flex flex-col gap-2 px-2 py-2 shadow-md shadow-[#830c61] border-0 rounded-sm
                        hover:border-[1px] hover:border-[#830c61] hover:shadow-lg hover:shadow-[#830c61] ">
                    <h1 class=" lg:text-2xl max-lg:text-2xl max-sm:text-xl">titre de article</h1>
                        <p class="max-sm:text-sm">Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita
                            quidem culpa error
                            sunt nemo unde? Fuga alias, nesciunt aliquam aperiam beatae porro distinctio officia
                            asperiores repellendus harum nulla nam rem!
                            Alias, sit voluptate! Explicabo libero, totam temporibus unde modi cum doloremque
                            commodi vero minus natus id nesciunt facilis ex nam corrupti, iste minima amet fugit
                            consectetur esse rerum, omnis ipsum?
                            Maiores, dolores? Sunt porro ex magnam iste, earum beatae, esse hic distinctio error
                            est quos. Doloremque doloribus sint recusandae aliquam, quae saepe placeat harum
                            nulla excepturi rerum dolore nesciunt in.
                        </p>
                        <button
                            class="max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-40 lg:h-full h-8 font-sans hover:bg-[#830c61] hover:text-white"">
                        Read more &#10097;&#10097;
                    </button>
                </div>
                <div class=" flex flex-col gap-2 px-2 py-2 shadow-md shadow-[#830c61] border-0 rounded-sm
                            hover:border-[1px] hover:border-[#830c61] hover:shadow-lg hover:shadow-[#830c61] ">
                    <h1 class=" lg:text-2xl max-lg:text-2xl max-sm:text-xl">titre de article</h1>
                            <p class="max-sm:text-sm">Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita
                                quidem culpa
                                error sunt nemo unde? Fuga alias, nesciunt aliquam aperiam beatae porro
                                distinctio officia asperiores repellendus harum nulla nam rem!
                                Alias, sit voluptate! Explicabo libero, totam temporibus unde modi cum
                                doloremque commodi vero minus natus id nesciunt facilis ex nam corrupti, iste
                                minima amet fugit consectetur esse rerum, omnis ipsum?
                                Maiores, dolores? Sunt porro ex magnam iste, earum beatae, esse hic distinctio
                                error est quos. Doloremque doloribus sint recusandae aliquam, quae saepe placeat
                                harum nulla excepturi rerum dolore nesciunt in.
                            </p>
                            <button
                                class="max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-40 lg:h-full h-8 font-sans hover:bg-[#830c61] hover:text-white"">
                        Read more &#10097;&#10097;
                    </button>
                </div>

            </div>
        </section>
    </main>

    <footer class=" bg-[#1d1d1d] h-32 w-full flex items-center justify-center">
                                <p class="max-sm:text-[12px] max-sm:px-6 text-center">Copyright © 2024 Blog Press |
                                    Powered by <span class="text-[#d025a0]">BlogPress</span> Theme</p>
                                </footer>


</body>
<script src="js/menu_theme.js"></script>

</html>