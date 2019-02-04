    var geocoder;

    // showLocation() is called when you click on the Search button
    // in the form.  It geocodes the address entered into the form
    function showLocation() {
      geocoder = new GClientGeocoder();
      var address = document.getElementsByName('addressForm')[0].value;
      geocoder.getLocations(address, addAddressToMap);
    }
 
    // addAddressToMap() is called when the geocoder returns an
    // answer.  It adds a marker to the map with an open info window
    // showing the nicely formatted version of the address and the country code.
    function addAddressToMap(response) {
      if (!response || response.Status.code != 200) {
        alert("The Address does not exists");
      } else {
        place = response.Placemark[0];
        point = new GLatLng(place.Point.coordinates[1],
                            place.Point.coordinates[0]);
        document.myForm.Y.value = place.Point.coordinates[0];
        document.myForm.X.value = place.Point.coordinates[1];
        document.getElementById("geoform").submit();
      }
    }