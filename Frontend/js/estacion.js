var map = L.map('map').setView([36.13681, -5.4534], 15);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
    setZoom: 10,
}).addTo(map);

var blips = [
    { lat: 36.13362, lng: -5.44853, contenido: 'Parking Parque María Cristina' },
    { lat: 36.12591, lng: -5.44745, contenido: 'Parking Estación de Autobuses San Bernardo' },
    { lat: 36.13674, lng: -5.45340, contenido: 'Escuela Politécnica Superior de Algeciras' }
];

blips.forEach(function(blip) {
    L.marker([blip.lat, blip.lng]).addTo(map).bindPopup(blip.contenido);
});