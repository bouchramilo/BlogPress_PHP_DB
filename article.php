<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>BlogPress - Article</title>
</head>

<body class="text-white flex flex-col items-center gap-2 bg-black ">

<!-- header -->
 <?php include 'header.php'; ?>


    <!-- Formulaire caché -->
    <div id="formContainer" class="fixed inset-0 bg-white bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-[#1d1d1d] opacity-85 text-white p-6 flex flex-col gap-6 rounded-sm shadow-lg w-96">
            <h2 class="text-2xl font-bold text-center">Commenter</h2>
            <form>
                <label for="nom" class="block mb-2">Nom & Prénom :</label>
                <input type="text" id="nom" class="w-full p-2 mb-4 border-0 rounded-sm bg-black"
                    placeholder="Votre nom et prenom">

                <label for="email" class="block mb-2">Email:</label>
                <input type="email" id="email" class="w-full p-2 mb-4 border-0 rounded-sm bg-black"
                    placeholder="Votre adresse e-mail">

                <label for="commentaire" class="block mb-2">Commentaire :</label>
                <textarea type="commentaire" id="commentaire"
                    class="w-full p-2 mb-4 border-0 rounded-sm bg-black resize-none"
                    placeholder="Votre commentaire."></textarea>

                <div class="flex justify-center h-12">
                    <button
                        class="bg-[#d025a0] border-2 rounded-sm w-44 h-10 font-sans hover:bg-[#830c61] hover:text-white">
                        Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <main class="h-max w-full flex flex-col justify-center items-center gap-10">
        <section class="h-56 bg-[url('images/bg3.jpg')] bg-cover w-full flex justify-center items-center ">
            <h1 class="lg:text-[60px] max-lg:text-[60px] max-sm:text-[40px] text-center font-bold"
                style="text-shadow: 0 0 10px #830c61, 0 0 10px #830c61, 0 0 20px #830c61;">Titre d'article</h1>
        </section>

        <section class="flex flex-row max-sm:flex-col gap-4 w-[95%] h-max">
            <div class="w-8/12 max-sm:w-full ">
                <p class="max-sm:text-sm">
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Velit repudiandae repellendus quaerat
                    quasi
                    aliquid architecto dolore accusantium unde perferendis, suscipit tempore veniam eligendi mollitia
                    quis.
                    Ab, officiis mollitia. Nihil, provident!
                    Eligendi amet quaerat in eaque quos laudantium ab porro libero ad veniam dolor tenetur eos,
                    blanditiis
                    esse recusandae nemo quasi nisi placeat delectus perferendis possimus illo quidem non. Nihil, iste.
                    Deserunt, illum quasi et vero recusandae enim nulla ex nesciunt? Molestiae illum amet dolor
                    laboriosam
                    aspernatur harum ipsa, perspiciatis doloribus obcaecati ullam illo sequi sunt temporibus recusandae
                    exercitationem, aperiam velit.
                    Labore hic blanditiis libero minima sed facere accusamus deserunt adipisci. Quisquam dicta pariatur,
                    temporibus nesciunt nemo nostrum ut similique modi, a, eligendi possimus vero culpa dolorum facilis.
                    Iure, dicta magni!
                    Ad eligendi culpa quos. Dolore doloribus voluptatibus a nam qui sapiente alias neque sit rerum, ea
                    atque, quia temporibus impedit amet et. Tempore praesentium delectus laborum autem id dolores nihil!
                    Aspernatur aliquam repellat eius temporibus iste ut eligendi odio molestias praesentium mollitia
                    consectetur in qui, ratione reprehenderit modi repellendus id adipisci iure possimus explicabo dicta
                    rerum perspiciatis incidunt. Quos, voluptate?

                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Velit repudiandae repellendus quaerat
                    quasi
                    aliquid architecto dolore accusantium unde perferendis, suscipit tempore veniam eligendi mollitia
                    quis.
                    Ab, officiis mollitia. Nihil, provident!
                    Eligendi amet quaerat in eaque quos laudantium ab porro libero ad veniam dolor tenetur eos,
                    blanditiis
                    esse recusandae nemo quasi nisi placeat delectus perferendis possimus illo quidem non. Nihil, iste.
                    Deserunt, illum quasi et vero recusandae enim nulla ex nesciunt? Molestiae illum amet dolor
                    laboriosam
                    aspernatur harum ipsa, perspiciatis doloribus obcaecati ullam illo sequi sunt temporibus recusandae
                    exercitationem, aperiam velit.
                    Labore hic blanditiis libero minima sed facere accusamus deserunt adipisci. Quisquam dicta pariatur,
                    temporibus nesciunt nemo nostrum ut similique modi, a, eligendi possimus vero culpa dolorum facilis.
                    Iure, dicta magni!
                    Ad eligendi culpa quos. Dolore doloribus voluptatibus a nam qui sapiente alias neque sit rerum, ea
                    atque, quia temporibus impedit amet et. Tempore praesentium delectus laborum autem id dolores nihil!
                    Aspernatur aliquam repellat eius temporibus iste ut eligendi odio molestias praesentium mollitia
                    consectetur in qui, ratione reprehenderit modi repellendus id adipisci iure possimus explicabo dicta
                    rerum perspiciatis incidunt. Quos, voluptate?

                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Velit repudiandae repellendus quaerat
                    quasi
                    aliquid architecto dolore accusantium unde perferendis, suscipit tempore veniam eligendi mollitia
                    quis.
                    Ab, officiis mollitia. Nihil, provident!
                    Eligendi amet quaerat in eaque quos laudantium ab porro libero ad veniam dolor tenetur eos,
                    blanditiis
                    esse recusandae nemo quasi nisi placeat delectus perferendis possimus illo quidem non. Nihil, iste.
                    Deserunt, illum quasi et vero recusandae enim nulla ex nesciunt? Molestiae illum amet dolor
                    laboriosam
                    aspernatur harum ipsa, perspiciatis doloribus obcaecati ullam illo sequi sunt temporibus recusandae
                    exercitationem, aperiam velit.
                    Labore hic blanditiis libero minima sed facere accusamus deserunt adipisci. Quisquam dicta pariatur,
                    temporibus nesciunt nemo nostrum ut similique modi, a, eligendi possimus vero culpa dolorum facilis.
                    Iure, dicta magni!
                    Ad eligendi culpa quos. Dolore doloribus voluptatibus a nam qui sapiente alias neque sit rerum, ea
                    atque, quia temporibus impedit amet et. Tempore praesentium delectus laborum autem id dolores nihil!
                    Aspernatur aliquam repellat eius temporibus iste ut eligendi odio molestias praesentium mollitia
                    consectetur in qui, ratione reprehenderit modi repellendus id adipisci iure possimus explicabo dicta
                    rerum perspiciatis incidunt. Quos, voluptate?
                </p>
            </div>
            <div class="flex flex-col gap-2 items-center w-4/12 max-sm:w-full h-max py-4 bg-[#1d1d1d]">
                <div class="w-[95%] border-2 border-[#830c62] h-max flex flex-col gap-2 p-2">
                    <p class="text-xl max-sm:text-base">Auteur : <span class="text-lg max-sm:text-base max-sm:text-sm">nom d'auteur.</span></p>
                    <p class="text-xl max-sm:text-base">Catérogie : <span class="text-lg max-sm:text-base max-sm:text-sm">genre d'article</span></p>
                    <p class="text-xl max-sm:text-base">Nombre de 'likes' : <span class="text-lg max-sm:text-base max-sm:text-sm">10</span></p>
                    <p class="text-xl max-sm:text-base">Nombre de commentaires : <span class="text-lg max-sm:text-base max-sm:text-sm">20</span></p>
                </div>
                <div class="w-[95%] h-max flex flex-row gap-2 p-2">
                    <button
                        class="max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-40 h-9 font-sans hover:bg-[#830c61] hover:text-white">
                        like &#10084;
                    </button>
                    <button id="showFormButton" class="max-sm:text-sm bg-[#d025a0] border-2 rounded-sm w-40 h-9 font-sans hover:bg-[#830c61]
                        hover:text-white">
                        add comment
                    </button>
                </div>
                <div class="w-[95%] border-2 border-[#830c62] h-max flex flex-col gap-2 p-2">
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-row gap-2 items-center">
                            <img src="images/icones/comment.png" alt="comment" class="w-8">
                            <p class="text-lg max-sm:text-base">visiteur : <span class="text-lg max-sm:text-base">nom visiteur.</span></p>
                            <p class="text-sm max-sm:text-xs text-gray-500">14-12-2024</p>
                        </div>
                        <div class="pl-10 max-sm:text-sm">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatum placeat repellendus
                            illum mai
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-row gap-2 items-center">
                            <img src="images/icones/comment.png" alt="comment" class="w-8">
                            <p class="text-lg max-sm:text-base">visiteur : <span class="text-lg max-sm:text-base">nom visiteur.</span></p>
                            <p class="text-sm max-sm:text-xs text-gray-500">14-12-2024</p>
                        </div>
                        <div class="pl-10 max-sm:text-sm">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatum placeat repellendus
                            illum mai
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-row gap-2 items-center">
                            <img src="images/icones/comment.png" alt="comment" class="w-8">
                            <p class="text-lg max-sm:text-base">visiteur : <span class="text-lg max-sm:text-base">nom visiteur.</span></p>
                            <p class="text-sm max-sm:text-xs text-gray-500">14-12-2024</p>
                        </div>
                        <div class="pl-10 max-sm:text-sm">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatum placeat repellendus
                            illum mai
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-row gap-2 items-center">
                            <img src="images/icones/comment.png" alt="comment" class="w-8">
                            <p class="text-lg max-sm:text-base">visiteur : <span class="text-lg max-sm:text-base">nom visiteur.</span></p>
                            <p class="text-sm max-sm:text-xs text-gray-500">14-12-2024</p>
                        </div>
                        <div class="pl-10 max-sm:text-sm">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatum placeat repellendus
                            illum mai
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>

    <!-- footer  -->
     <?php include 'footer.php' ?>
</body>
<script src="js/menu_theme.js"></script>
<script src="js/commentFormC.js"></script>

</html>