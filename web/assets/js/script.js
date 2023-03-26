function initMap() {

// Créer une nouvelle carte centrée sur la France
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 46.2276, lng: 2.2137},
        zoom: 5
    });

// Définir les limites géographiques de la France
    var franceBounds = new google.maps.LatLngBounds(
        new google.maps.LatLng(41.333, -5.142),
        new google.maps.LatLng(51.091, 9.562)
    );

// Ajuster la carte aux limites géographiques de la France
    map.fitBounds(franceBounds);
}
