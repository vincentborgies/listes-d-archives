// Sélectionnez les éléments du DOM
const openModalBtn = document.getElementById('openModalBtn')
const closeModalBtn = document.getElementById('closeModalBtn')
const modalContainer = document.getElementById('modalContainer')
const searchInput = document.getElementById('search')
const outputDiv = document.getElementById('display-search')
const allUsers = document.getElementById('all-users')
const displayDefault = document.getElementById('display-default')

// Ajoutez un gestionnaire d'événement pour ouvrir la modal
openModalBtn.addEventListener('click', (e) => {
    e.preventDefault()
    modalContainer.style.display = 'flex';
})

// Ajoutez un gestionnaire d'événement pour fermer la modal
closeModalBtn.addEventListener('click', (e) => {
    e.preventDefault()
    modalContainer.style.display = 'none'
})

// Ajoutez un gestionnaire d'événement pour fermer la modal
searchInput.addEventListener('input', (e) => {
    const trimmedValue = e.target.value.trim();
    if (trimmedValue !== '') {
        displayDefault.style.display = "none"
        fetch('search.php?search=' + encodeURIComponent(trimmedValue), {
            method: 'GET'
        })
        .then(response => response.text())
        .then(result => {
            outputDiv.innerHTML = result;
        })
        .catch(error => {
            console.error('Une erreur s\'est produite :', error);
        });
    } else {
        outputDiv.innerHTML = ''; // Effacez le contenu de l'élément de sortie si l'input est vide
        displayDefault.style.display = "block"
    }
});




