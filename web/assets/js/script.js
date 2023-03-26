let xhr = new XMLHttpRequest();
let input_départ = document.getElementById("nomCommuneDepart_id");
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


function callbackA() {
    let array = [];
    console.log(xhr.responseText);

    let xhrJSON = JSON.parse(xhr.responseText);
    for (commune of xhrJSON) {
        array.unshift(commune.nom_comm);
    }
    afficheCommuneA(array);
}

function callbackD() {
    let array = [];
    let xhrJSON = JSON.parse(xhr.responseText);
    console.log(xhrJSON);
    for (commune of xhrJSON) {
        array.unshift(commune.nom_comm);
    }
    afficheCommuneD(array);
}

function videCommunes() {
    autocompletionA.innerHTML = "";
    autocompletionD.innerHTML = "";
}

function charger_commune(lettre, callback) {
    let url = `autocompletion/${lettre}`;
    xhr.open("GET", url, true);
    xhr.addEventListener("load", function () {
        callback();
    });
    xhr.send(null);
}


input_départ.addEventListener('input', function () {
    charger_commune(input_départ.value, callbackD);
});

input_arrive.addEventListener('input', function () {
    charger_commune(input_départ.value, callbackA);
});


function afficheCommuneD(array) {
    videCommunes();
    for (c of array) {
        let elem = document.createElement("p");
        elem.innerHTML = c;
        autocompletionD.append(c);
    }
}

function afficheCommuneA(array) {
    videCommunes();
    for (c of array) {
        let elem = document.createElement("p");
        elem.innerHTML = c;
        autocompletionA.append(c);
    }
}



