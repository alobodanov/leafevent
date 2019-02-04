function changeLocation(position) {
                default_lat = position.coords.latitude;
                default_lng = position.coords.longitude;


                map = L.map('map').setView([default_lat, default_lng], 13);
                
                L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6IjZjNmRjNzk3ZmE2MTcwOTEwMGY0MzU3YjUzOWFmNWZhIn0.Y8bhBaUMqFiPrDRW9hieoQ', {
                maxZoom: 18,
                attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                  '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                  'Imagery © <a href="http://mapbox.com">Mapbox</a>',
                id: 'mapbox.streets'
              }).addTo(map);
                /*L.tileLayer('https://api.mapbox.com/v4/mapbox.streets/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '',
                id: 'examples.map-i875mjb7'
                }).addTo(map);*/
                
                var temp = L.marker([default_lat, default_lng], {icon: L.icon ({
                  iconUrl: '../Images/test1.png',
                  //shadowUrl: '../images/leaf-shadow.png',
                  iconSize:     [24, 45], // size of the icon
                  //shadowSize:   [50, 64], // size of the shadow
                  iconAnchor:   [9, 44], // point of the icon which will correspond to marker's location
                  //shadowAnchor: [4, 62],  // the same for the shadow
                  popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
                })}).addTo(map);

                var marker = L.marker();
                function onMapClick(e) {
                  marker
                    .setLatLng(e.latlng)
                    .addTo(map);
                  document.getElementById('latitude').value = e.latlng.lat;
                  document.getElementById('longitude').value = e.latlng.lng;
                  document.getElementById('latitude').type = "text";
                  document.getElementById('longitude').type = "text";
                  document.getElementById('submit').className = "btn btn-primary"
                }

                //map.on('click', onMapClick);

                var myIcon = L.icon({
                    iconUrl: '../images/pin24.png',
                    iconRetinaUrl: '../images/pin48.png',
                    iconSize: [29, 24],
                    iconAnchor: [9, 21],
                    popupAnchor: [0, -14]
                });

                for ( var i=0; i < markers.length; ++i ) 
                {
                   L.marker( [markers[i].lat, markers[i].lng], {icon: myIcon} )
                      .bindPopup( 'Event Name: ' + markers[i].name + '<br />Description: ' + markers[i].description + '<br />Address: ' + markers[i].address )
                      .addTo( map );
                }
              }
              if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(changeLocation); 
              }