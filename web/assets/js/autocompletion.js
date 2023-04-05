let inputDepart = document.getElementById("nomCommuneDepart_id");
let inputArrive = document.getElementById("nomCommuneArrivee_id");
let divAutocompletionA = document.getElementById("autocompletionA");
let divAutocompletionD = document.getElementById("autocompletionD");

divAutocompletionA.addEventListener("click", function (event) {
    inputArrive.value = event.target.textContent;
    videCommunes(divAutocompletionA);
});

divAutocompletionD.addEventListener("click", function (event) {
    inputDepart.value = event.target.textContent;
    videCommunes(divAutocompletionD);
});

function callback(xhr, afficherCommunes) {
    let array = JSON.parse(xhr.responseText).map(elem => elem.nom_comm);
    afficherCommunes(array);
}

function videCommunes(divAutocompletion) {
    while (divAutocompletion.lastElementChild) {
        divAutocompletion.removeChild(divAutocompletion.lastElementChild);
    }
}

function chargerCommunes(lettre, afficherCommunes) {
    let xhr = new XMLHttpRequest();
    let url = `autocompletion/${lettre}`;
    xhr.open("GET", url, true);
    xhr.addEventListener("load", function () {
        callback(xhr, afficherCommunes);
    });
    xhr.send(null);
}

let timer;

inputDepart.addEventListener('input', function () {
    clearTimeout(timer);
    timer = setTimeout(() => {
        if (inputDepart.value.length > 1)
            chargerCommunes(inputDepart.value, afficherCommunesD);
    }, 200);
});

inputArrive.addEventListener('input', function () {
    clearTimeout(timer);
    timer = setTimeout(() => {
        if (inputArrive.value.length > 1)
            chargerCommunes(inputArrive.value, afficherCommunesA);
    }, 200);
});


function afficherCommunesD(array) {
    videCommunes(divAutocompletionD);
    for (char of array) {
        let p = document.createElement("p");
        p.insertAdjacentHTML('beforeend', char);
        divAutocompletionD.appendChild(p);
    }
}

function afficherCommunesA(array) {
    videCommunes(divAutocompletionA);
    for (char of array) {
        let p = document.createElement("p");
        p.insertAdjacentHTML('beforeend', char);
        divAutocompletionA.appendChild(p);
    }
}
