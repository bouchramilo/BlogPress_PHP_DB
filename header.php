<header class="bg-[#1d1d1d] w-full h-16 flex justify-center items-center px-12">
        <nav class="flex flex-row justify-between w-full">
            <!-- Logo -->
            <div class="w-1/12">
                <a href="index.php">
                    <span class="text-2xl text-[#d025a0] hover:text-[#830c61] font-bold">BlogPress</span>
                </a>
            </div>
            <!-- Menu Links -->
            <div id="menu"
                class="w-1/2 flex gap-4 flex-shrink lg:flex lg:items-center lg:justify-center hidden flex-col lg:flex-row absolute left-1/2 border-none rounded-2xl lg:relative top-16 lg:top-0 lg:left-auto bg-[#1e1e1e] lg:bg-transparent lg:w-6/12 z-10 lg:z-auto p-4 lg:p-0">
                <a href="index.php" class="w-full lg:w-1/5   lg:mb-0">
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