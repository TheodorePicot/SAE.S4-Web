let featureLayer;
function initMap() {

    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 46.2276, lng: 2.2137},
        zoom: 6.5,
        mapId: 'dfc432536cbbab6e'
    });

    if(document.getElementById("test").children.length !== 0){
        tracerChemin();
    }

}

function tracerChemin() {

    constDepart = JSON.parse(document.getElementById("coordDepart").innerHTML);
    constArrivee = JSON.parse(document.getElementById("coordArrivee").innerHTML);

    const citiesCoordinates = [
        constDepart,
        constArrivee,
    ];

    const coordinates = JSON.parse(document.getElementById('coord').innerHTML);

    const citiesPath = new google.maps.Polyline({
        path: coordinates,
        geodesic: true,
        strokeColor: "#FF0000",
        strokeOpacity: 1.0,
        strokeWeight: 2,
    });

    citiesPath.setMap(map)

    // Create a marker for each city in France
    citiesCoordinates.forEach(function (city) {
        var marker = new google.maps.Marker({
            position: city,
            map: map,
            title: city.name
        });
    });

}