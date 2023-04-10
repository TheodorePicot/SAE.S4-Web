import {applyAndRegister, reactive, startReactiveDom} from "./reactive.js";

let weather = reactive({
    titre: null,
    src: null,
    desc : null,
    temp: null,
}, "weatherHTML")

startReactiveDom();

function callback_weather(req) {
    let xhrJSON = JSON.parse(req.responseText);
    console.log(xhrJSON);
    console.log(weather);
    weather.titre = "Météo à " + xhrJSON.name;
    weather.desc = xhrJSON.weather[0].description;
    weather.src = "https://openweathermap.org/img/wn/" + xhrJSON.weather[0].icon + "@2x.png";
    weather.temp = "Temperature : " + parseFloat(xhrJSON.main.temp - 273.15).toFixed(1) + "°C";
}


divAutocompletionA.addEventListener("click", function (event) {
    inputArrive.value = event.target.innerHTML;
    let url = "https://api.openweathermap.org/data/2.5/weather?q=" + inputArrive.value + "&appid=3ca66a9c2eccaa69d947f55b21f652f3&lang=fr";
    let requete = new XMLHttpRequest();
    requete.open("GET", url, true);
    requete.addEventListener("load", function () {
        callback_weather(requete);
    });
    requete.send(null);
    videCommunes(divAutocompletionA);
});