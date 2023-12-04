// Sélectionnez les éléments du DOM
const openModalBtn = document.getElementById('openModalBtn')
const closeModalBtn = document.getElementById('closeModalBtn')
const modalContainer = document.getElementById('modalContainer')

// Ajoutez un gestionnaire d'événement pour ouvrir la modal
openModalBtn.addEventListener('click', () => {
    modalContainer.style.display = 'block'
})

// Ajoutez un gestionnaire d'événement pour fermer la modal
closeModalBtn.addEventListener('click', () => {
    modalContainer.style.display = 'none'
})
