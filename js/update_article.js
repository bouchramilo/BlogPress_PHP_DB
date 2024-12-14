 // Récupérer le bouton et le formulaire
 const updateform = document.querySelectorAll('.updateform');
 const formContainerModifier = document.getElementById('formContainerModifier');

 // Afficher ou masquer le formulaire
 updateform.forEach((form) => {
     form.addEventListener('click', () => {
         formContainerModifier.classList.toggle('hidden');
     });
 });