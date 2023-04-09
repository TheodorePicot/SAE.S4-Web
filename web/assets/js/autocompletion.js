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

inputDepart.addEventListener("click", function () {
    videCommunes(divAutocompletionA);
});

inputArrive.addEventListener("click", function () {
    videCommunes(divAutocompletionD);
});

let currentFocus = -1;
inputArrive.addEventListener("keydown", function(e) {
    let divAutocompletion = document.getElementById("autocompletionA");
    focusHandler(divAutocompletion, e);
});

inputDepart.addEventListener("keydown", function(e) {
    let divAutocompletion = document.getElementById("autocompletionD");
    focusHandler(divAutocompletion, e);
});

function focusHandler(divAutocompletion, e) {
    if (divAutocompletion.hasChildNodes()) {
        switch (e.key) {
            case "ArrowDown":
                currentFocus++;
                addActive(divAutocompletion);
                e.preventDefault();
                break;
            case "ArrowUp":
                currentFocus--;
                addActive(divAutocompletion);
                e.preventDefault();
                break;
            case "Enter":
                if (currentFocus > -1) {
                    if (divAutocompletion) divAutocompletion.children[currentFocus].click();
                    currentFocus = -1;
                }
                e.preventDefault();
                break;
        }
    }
}

function addActive(divAutocompletion) {
    if (!divAutocompletion) return false;
    removeActive(divAutocompletion);
    if (currentFocus >= divAutocompletion.children.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (divAutocompletion.children.length - 1);
    divAutocompletion.children[currentFocus].classList.add("autocomplete-active");
}


function removeActive(divAutocompletion) {
    for (let i = 0; i < divAutocompletion.children.length; i++) {
        divAutocompletion.children[i].classList.remove("autocomplete-active");
    }
}
document.addEventListener("click", function () {
    videCommunes(divAutocompletionD);
    videCommunes(divAutocompletionA);
});

document.addEventListener("keydown", function (e) {
    if (e.key === "Tab" || e.key === "Escape") {
        videCommunes(divAutocompletionA);
        videCommunes(divAutocompletionD);
    }
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
    currentFocus = -1;
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
