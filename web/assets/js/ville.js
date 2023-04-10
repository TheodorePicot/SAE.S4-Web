import {applyAndRegister, reactive, startReactiveDom} from "./reactive.js";


let weather = reactive({
    name: null,
    icon: null,
    desc : null,
    temp: null,
}, "weatherHTML")

startReactiveDom();

function callback_weather(req) {
    // weather.removeChild(weather.firstElementChild);
    let xhrJSON = JSON.parse(req.responseText);
    // let weather = document.getElementById("weather");
    let deg = parseFloat(xhrJSON.main.temp - 273.15).toFixed(1);
    let imagejavascript = document.createElement("img");
    imagejavascript.src = "https://openweathermap.org/img/wn/" + xhrJSON.weather[0].icon + "@2x.png";
    weather.appendChild(imagejavascript);
    weather.innerHTML += deg + "°C" + '    ' + xhrJSON.weather[0].description;
}

let input = document.getElementById('nomCommuneArrivee_id');

let autocompletionA = document.getElementById('autocompletionA');

autocompletionA.addEventListener("click", function (event) {
    input.value = event.target.innerHTML;

    let url = "https://api.openweathermap.org/data/2.5/weather?q=" + input.value + "&appid=3ca66a9c2eccaa69d947f55b21f652f3&lang=fr";
    let requete = new XMLHttpRequest();
    requete.open("GET", url, true);
    requete.addEventListener("load", function () {
        callback_weather(requete);
    });
    requete.send(null);

    videCommunes();
});