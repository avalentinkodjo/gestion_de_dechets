// Fonction pour ouvrir la modale avec l'image cliquée
function openModal(imgElement) {
    var modal = document.getElementById("modal");
    var modalImg = document.getElementById("modal-img");
    
    modal.style.display = "flex";
    modalImg.src = imgElement.src;
}

// Fonction pour fermer la modale
function closeModal() {
    document.getElementById("modal").style.display = "none";
}
