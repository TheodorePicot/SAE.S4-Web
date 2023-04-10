function supprimerFavoris(event) {
    let button = event.target;
    let idTrajet = button.dataset.idTrajet;
    let URL = apiBase + "supprimerFavoris/" + idTrajet;

    fetch(URL, {method: "DELETE"})
        .then(response => {
            if (response.status === 200) {
                // Plus proche ancêtre <div class="feedy">
                let divCard = button.closest("div.card");
                divCard.remove();
            }
        });
}

let elements = document.getElementsByClassName("delete-trajet");
for (let i = 0; i < elements.length; i++) {
    elements[i].addEventListener('click', supprimerFavoris, false);
}
