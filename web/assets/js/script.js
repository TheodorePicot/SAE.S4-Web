
let featureLayer;

function initMap() {

    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 46.2276, lng: 2.2137},
        zoom: 5.5,
        mapId: 'dfc432536cbbab6e'
    });

}



//API Météo
// function callback_weather(req) {
//     let xhrJSON = JSON.parse(req.responseText);
//     let weather = document.getElementById("weather");
//     let weather2 = document.getElementById("weather2");
//     weather.innerHTML = xhrJSON.weather[0].description;
//     let deg = parseFloat(xhrJSON.main.temp - 273.15).toFixed(1);
//     weather2.innerHTML = "Température : " +  deg + "°C";
//     let imagejavascript = document.createElement("img");
//     imagejavascript.src = "https://openweathermap.org/img/wn/" + xhrJSON.weather[0].icon + "@2x.png";
//     weather.appendChild(imagejavascript);
// }
//
// let input = document.getElementById('nomCommuneArrivee_id');
// let buttonCalculer = document.getElementById('buttonCalculer');
// buttonCalculer.addEventListener("click", function (event) {
//     input.value = event.target.innerHTML;
//
//     let url = "https://api.openweathermap.org/data/2.5/weather?q=" + input.value + "&appid=3ca66a9c2eccaa69d947f55b21f652f3&lang=fr";
//     let requete = new XMLHttpRequest();
//     requete.open("GET", url, true);
//     requete.addEventListener("load", function () {
//         callback_weather(requete);
//     });
//     requete.send(null);
//
//     videCommunes();
// });
//


