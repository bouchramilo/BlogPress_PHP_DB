 // Récupérer le bouton et le formulaire
 const addForm = document.getElementById('addForm');
 const formContainerAdd = document.getElementById('formContainerAdd');

 // Afficher ou masquer le formulaire
 addForm.addEventListener('click', () => {
     formContainerAdd.classList.toggle('hidden');
 });