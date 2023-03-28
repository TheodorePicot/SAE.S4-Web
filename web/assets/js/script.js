let input_depart = document.getElementById("nomCommuneDepart_id");
let input_arrive = document.getElementById("nomCommuneArrivee_id");
let autocompletionA = document.getElementById("autocompletionA");
let autocompletionD = document.getElementById("autocompletionD");

// function initMap() {
//
// // Créer une nouvelle carte centrée sur la France
//     var map = new google.maps.Map(document.getElementById('map'), {
//         center: {lat: 46.2276, lng: 2.2137},
//         zoom: 5
//     });
//
// // Définir les limites géographiques de la France
//     var franceBounds = new google.maps.LatLngBounds(
//         new google.maps.LatLng(41.333, -5.142),
//         new google.maps.LatLng(51.091, 9.562)
//     );
//
// // Ajuster la carte aux limites géographiques de la France
//     map.fitBounds(franceBounds);
//
//
// }

let featureLayer;

function initMap() {

    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 46.2276, lng: 2.2137},
        zoom: 8,
        mapId: 'dfc432536cbbab6e'
    });

}

window.initMap = initMap;

autocompletionA.addEventListener("click", function (event) {
    input_arrive.value = event.target.textContent;
});

autocompletionD.addEventListener("click", function (event) {
    input_depart.value = event.target.textContent;
});

function callbackA(xhr) {
    let array = JSON.parse(xhr.responseText).map(elem => elem.nom_comm);
    afficheCommuneA(array);
}

function callbackD(xhr) {
    let array = JSON.parse(xhr.responseText).map(elem => elem.nom_comm);
    afficheCommuneD(array);
}

function videCommunes() {
    autocompletionA.innerHTML = "";
    autocompletionD.innerHTML = "";
}

function charger_commune(lettre, callback) {
    let xhr = new XMLHttpRequest();
    let url = `autocompletion/${lettre}`;
    xhr.open("GET", url, true);
    xhr.addEventListener("load", function () {
        callback(xhr);
    });
    xhr.send(null);
}
let timer;

input_depart.addEventListener('input', function () {
    clearTimeout(timer);
    timer = setTimeout(() => {
        if (input_depart.value.length > 1)
            charger_commune(input_depart.value, callbackD);
    }, 200);
});

input_arrive.addEventListener('input', function () {
    clearTimeout(timer);
    timer = setTimeout(() => {
        if (input_arrive.value.length > 1)
            charger_commune(input_arrive.value, callbackA);
    }, 200);
});


function afficheCommuneD(array) {
    videCommunes();
    for (char of array) {
        let p = document.createElement("p");
        p.insertAdjacentHTML('beforeend', char);
        autocompletionD.appendChild(p);
    }
}

function afficheCommuneA(array) {
    videCommunes();
    for (c of array) {
        let p = document.createElement("p");
        p.insertAdjacentHTML('beforeend', char);
        autocompletionA.appendChild(c);
    }
}



