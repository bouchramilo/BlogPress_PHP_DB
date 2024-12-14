<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>BlogPress - HOME</title>
</head>
<body class="text-white">
    <header class="bg-[#1d1d1d] h-16 flex justify-center items-center px-12">
        <nav class="flex flex-row justify-between w-full">
            <!-- Logo -->
            <div class="w-1/12">
                <a href="index.html">
                    <span class="text-2xl text-[#C72E25] font-bold">Blog Press</span>
                </a>
            </div>
            <!-- Menu Links -->
            <div id="menu" class="w-1/2 flex gap-4 flex-shrink lg:flex lg:items-center lg:justify-center hidden flex-col lg:flex-row absolute left-1/2 border-none rounded-2xl lg:relative top-16 lg:top-0 lg:left-auto bg-[#1e1e1e] lg:bg-transparent lg:w-6/12 z-10 lg:z-auto p-4 lg:p-0">
                <a href="" class="w-full lg:w-1/5   lg:mb-0">
                    <button
                        class="border-b-2 border-b-[#1d1d1d] rounded-lg w-full lg:h-full h-8 font-sans text-xl hover:border-b-4 hover:border-[#C72E25]">
                        Home
                    </button>
                </a>
                <a href="" class="w-full lg:w-1/5   lg:mb-0">
                    <button
                        class="border-b-2 border-b-[#1d1d1d] rounded-lg w-full lg:h-full h-8 font-sans text-xl hover:border-b-4 hover:border-[#C72E25]">
                        Library
                    </button>
                </a>
                <a href="" class="w-full lg:w-1/5   lg:mb-0">
                    <button
                        class="border-b-2 border-b-[#1d1d1d] rounded-lg w-full lg:h-full h-8 font-sans text-xl hover:border-b-4 hover:border-[#C72E25]">
                        Account
                    </button>
                </a>
                <a href="" class="w-full lg:w-1/5   lg:mb-0">
                    <button
                        class="bg-[#C72E25] border-2 rounded-lg w-full lg:h-full h-8 font-sans text-xl hover:border-b-4 hover:border-[#C72E25] hover:bg-white hover:text-black">
                        Login
                    </button>
                </a>
                <a href="" class="w-full lg:w-1/5   lg:mb-0">
                    <button
                        class="bg-white border-[#C72E25] text-black border-2 rounded-lg w-full lg:h-full h-8 font-sans text-xl hover:border-b-4 hover:border-[#C72E25] hover:bg-white hover:text-black">
                        Light mode
                    </button>
                </a>
            </div>
            <!-- Hamburger Menu -->
            <div class="lg:hidden justify-end flex w-1/12">
                <button onclick="Menu()" class="flex items-center px-2 py-2 border rounded bg-red-600">
                    <svg class="fill-current h-5 w-5" viewBox="0 0 20 20">
                        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                    </svg>
                </button>
            </div>
        </nav>
    </header>

    <script>
        function Menu() {
            document.getElementById('menu').classList.toggle('hidden');
        }
    </script>
</body>
</html>
