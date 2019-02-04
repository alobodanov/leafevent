 /*THIS CAN BE USED FOR A DART

                map = L.map('map').setView([default_lat, default_lng], 13);
                
                L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6IjZjNmRjNzk3ZmE2MTcwOTEwMGY0MzU3YjUzOWFmNWZhIn0.Y8bhBaUMqFiPrDRW9hieoQ', {
                maxZoom: 18,
                attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                  '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                  'Imagery © <a href="http://mapbox.com">Mapbox</a>',
                id: 'mapbox.dark'
              }).addTo(map);



                */


/*THIS SCRIPT WILL SHOW ME ONLY THE MAP WITH EVENTS BUT NO OPTION FOR THE SHOW MY LOCATION  --show be located in home.php

 /*default_lat = 43.5867488;
                 default_lng = -79.3610536;


                map = L.map('map').setView([default_lat, default_lng], 13);
                

                  L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6IjZjNmRjNzk3ZmE2MTcwOTEwMGY0MzU3YjUzOWFmNWZhIn0.Y8bhBaUMqFiPrDRW9hieoQ', {
                          maxZoom: 18,
                          attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                            '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                            'Imagery © <a href="http://mapbox.com">Mapbox</a>',
                          id: 'mapbox.streets'
                  }).addTo(map);




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
                }*/
     
     		</script>



*/



//php for viewEvent page
/*



        <div class="left more-event-pics">
<?php
        if($makerId == $curentUserId){
?>
          <div class="add-more-event-pic">
            <img class="not-added-yeat" src="../Images/event-temp-pic.png" alt="img" />
            <p>More pictures, more people</p>
            <form method="POST" class="new-event-form-add" enctype="multipart/form-data">
              <input type="file" name="morePicsBetter" />
            </form>
          </div>
<?php
        }
?>

        </div>
*/












/*
while($whoIsOnline = mysqli_fetch_assoc($res)){
              $curentlyOnline = $whoIsOnline['online_SQL'];
              $curentlyUserOnlineId = $whoIsOnline['id_SQL'];
              $curentlyUserOnlineName = $whoIsOnline['fname_SQL'];
              $curentlyUserOnlineLastName = $whoIsOnline['lname_SQL'];
              $folderName = $whoIsOnline['folder_name_SQL'];
              $picName = $whoIsOnline['user_pic_name_SQL'];
              if($folderName != null && $picName != null){
                $gPic = "<img class='NewFriendRequestPic' src='../milberUserPhotos/profilePictures/$folderName/$picName' alt='img'/>";
              } else {
                $gPic = "<img class='NewFriendRequestPic' src='../milberUserPhotos/no_prof_pic.png' alt='$username'/>";
              }
              if($curentlyOnline == 'y'){
?>
                <div class="CssIsForNewFriendOnlineFCurentF">
<?=$gPic;?>
                  <p class="Name"><?= $curentlyUserOnlineName; ?><br /><?= $curentlyUserOnlineLastName; ?></p>
                </div>
<?php
              }

            }


*/







