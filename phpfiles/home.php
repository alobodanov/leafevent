<?php $title_name = "Leafevent"; ?>
<?php require_once "milbertoolbar.php"; ?>
<?php require_once "Library.php" ?>
<?php
	if($_SESSION['userE_login'] == "" ){
		header('Location: logout.php');
		die();
   	}
	$cUserID = $_SESSION['user_id'];
	$UFname = $_SESSION['userName_login'];
    $ULname = $_SESSION['userlName_login'];
	$user_posted_to = "";			//if they posted something spasific, it will be shound only on thier post NOTE: still working on it
	$dbLink = new DBLink();
	//echo $cUserID;
	$eventTable = "SELECT * FROM MILBER.milberevents";
	$eventResult = $dbLink->query($eventTable);
?>
	<script type="text/javascript">
		//JS function that helps us to have a sliding efect on a opening and closing "vies"
		$(document).ready( function() {
			loadEvents('<?php echo $userID; ?>');
			loadFriendsPosts('<?php echo $userID; ?>');
		});
	</script>
	<div class="MainContent">
		<aside id="GoUp"></aside>
		<div class="content">
			<div id="map">
				<a href='#' id='geolocate' class='ui-button-map'>Find me</a>
			</div>
			<style>
				a.ui-button-map {
				  background-color: #fff;
				  color: #000;
				  display:block;
				  position:absolute;
				  top:2%;
				  left:50%;
				  width:160px;
				  margin:0 0 0 -80px;
				  z-index:100;
				  text-align:center;
				  padding:5px;
				  border-radius:3px;
				  opacity: 0.8;
				  }
				  .ui-button:hover {
				    background:#fff;
				    color:#000;
				    }
			</style>
			<script>
				L.mapbox.accessToken = 'pk.eyJ1IjoibWFwYm94IiwiYSI6IjZjNmRjNzk3ZmE2MTcwOTEwMGY0MzU3YjUzOWFmNWZhIn0.Y8bhBaUMqFiPrDRW9hieoQ';
				var geolocate = document.getElementById('geolocate');
				var default_lat = 43.7553722;
       			var default_lng = -79.3507146;

       			var day = document.getElementById("nightandday");
       			day = day.title;
       			if(day == "off"){
					var map = L.mapbox.map('map', 'mapbox.streets').setView([default_lat, default_lng], 9);
				} else if(day == "on"){
					var map = L.mapbox.map('map', 'mapbox.dark').setView([default_lat, default_lng], 9);
				} else {

				}
				var myLayer = L.mapbox.featureLayer().addTo(map);

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

	                var myIcon = L.icon({
	                    iconUrl: '../Images/pin24.png',
	                    iconRetinaUrl: '../Images/pin48.png',
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

				if (!navigator.geolocation) {
				    geolocate.innerHTML = 'Geolocation is not available';
				} else {
				    geolocate.onclick = function (e) {
				        e.preventDefault();
				        e.stopPropagation();
				        map.locate();
				    };
				}

				map.on('locationfound', function(e) {
				    map.fitBounds(e.bounds);

				    myLayer.setGeoJSON({
				        type: 'Feature',
				        geometry: {
				            type: 'Point',
				            coordinates: [e.latlng.lng, e.latlng.lat]
				        },
				        properties: {
				            'title': 'I am right here!',
				            'marker-color': '#ff8888',
				            'marker-symbol': 'star'
				        }
				    });
				    geolocate.parentNode.removeChild(geolocate);
				});

				map.on('locationerror', function() {
				    geolocate.innerHTML = 'Position could not be found, try again.';
				});
     		</script>
		</div>
	</div>
	<div id="UserInterestsEvents" class="contentInfo">
	</div>
	<div class="CopyRight">
		<a href="termsL.php">Terms</a>
		<script>
			var curentDate = new Date();
			var years = curentDate.getFullYear();
			document.write('<p>Leafevent © ' + years + '</p>');
		</script>
	</div>
	<div class="wanttoPostanEvent">
		<div class="col-lg-6">
			<form id="form-newEventPost" method="POST" id="newPost" class="usersPostBox">
				<div class="testareaAndSubmit">
					<div><a href="userFriendInfo.php?u=<?= $userID; ?>"><?= $imagePath; ?></a></div>
					<textarea id="eventPost" name="eventPostsTextPostMesgPost" placeholder="Do you have anything to share?" class="form-control" onkeyup="textAreaAdjust(this)"></textarea><br />
					<input type="button" name="Publish" value="Publish" class="input-group-addon btn btn-success" onclick="saveNewPost('#form-newEventPost')"/>
				</div>
				<div id='newCPost-error-msg' class='text-danger'></div>
				<input type="hidden" name="user_posted_to" value=""/>
				<input type="hidden" name="user_id" value="<?=$userID;?>"/>
				<input type="hidden" name="user_name" value="<?=($username.' '.$userlname);?>"/>
			</form>
			<script>
			    $('#form-newEventPost').keydown(function() {
					var key = e.which;
					if (key == 13) {
					// As ASCII code for ENTER key is "13"
					$('#my_form').submit(); // Submit form code
					}
				});
			</script>
		</div>
	</div>
	<div id="goUp"><a href="javascript:scroll(0,0)">Up</a></div>
	<div id="viewFriendsPosts" class="viewAllFriendPost">
	<!--div id="viewFriendsPosts" class="OneB ">
		<div class="panel-heading">Panel heading</div>
	</div-->
	</div>

</body> 
</html>