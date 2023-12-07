// Sélectionnez les éléments du DOM
const openModalBtn = document.getElementById('openModalBtn')
const closeModalBtn = document.getElementById('closeModalBtn')
const modalContainer = document.getElementById('modalContainer')
const searchInput = document.getElementById('searchInput')
const searchform = document.getElementById('searchform')

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
searchform.addEventListener('input', (e) => {
    const trimmedValue = e.target.value.trim();
    if (trimmedValue !== '') {
        console.log(trimmedValue)
        fetch('index.php?searchTerm=' + encodeURIComponent(trimmedValue), {
            method: 'GET'
        })        
    }
});






