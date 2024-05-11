// Sélectionner l'élément du corps du document
let body = document.body;

// Sélectionner l'élément du profil utilisateur dans l'en-tête
let profile = document.querySelector('.header .flex .profile');

// Ajouter un gestionnaire d'événements au bouton utilisateur pour afficher/masquer le profil
document.querySelector('#user-btn').onclick = () => {
   profile.classList.toggle('active'); // Basculer l'état actif du profil
   searchForm.classList.remove('active'); // Masquer le formulaire de recherche si actif
}

// Sélectionner l'élément du formulaire de recherche dans l'en-tête
let searchForm = document.querySelector('.header .flex .search-form');

// Ajouter un gestionnaire d'événements au bouton de recherche pour afficher/masquer le formulaire de recherche
document.querySelector('#search-btn').onclick = () => {
   searchForm.classList.toggle('active'); // Basculer l'état actif du formulaire de recherche
   profile.classList.remove('active'); // Masquer le profil si actif
}

// Sélectionner l'élément de la barre latérale
let sideBar = document.querySelector('.side-bar');

// Ajouter un gestionnaire d'événements au bouton de menu pour afficher/masquer la barre latérale
document.querySelector('#menu-btn').onclick = () => {
   sideBar.classList.toggle('active'); // Basculer l'état actif de la barre latérale
   body.classList.toggle('active'); // Basculer l'état actif du corps du document
}

// Ajouter un gestionnaire d'événements au bouton de fermeture de la barre latérale pour masquer la barre latérale
document.querySelector('.side-bar .close-side-bar').onclick = () => {
   sideBar.classList.remove('active'); // Retirer l'état actif de la barre latérale
   body.classList.remove('active'); // Retirer l'état actif du corps du document
}

// Ajouter un gestionnaire d'événements pour le défilement de la fenêtre
window.onscroll = () => {
   profile.classList.remove('active'); // Masquer le profil
   searchForm.classList.remove('active'); // Masquer le formulaire de recherche

   // Si la largeur de la fenêtre est inférieure à 1200 pixels
   if (window.innerWidth < 1200) {
      sideBar.classList.remove('active'); // Masquer la barre latérale
      body.classList.remove('active'); // Retirer l'état actif du corps du document
   }
}
