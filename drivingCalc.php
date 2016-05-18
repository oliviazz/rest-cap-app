 <?php 
    include('rstart.php');
    for($i = 0; $i< count($allRestNames[0]); $i++){
            echo($lat[0][$i] );
            echo($long[0][$i]);
        }
?>
<script>
     
function callback(response, status) {
    if (status == google.maps.DistanceMatrixStatus.OK) {
    var origins = response.originAddresses;
    var destinations = response.destinationAddresses;

    for (var i = 0; i < origins.length; i++) { //Loops through all destinations and origins; in this case only one of each
        var results = response.rows[i].elements;
        for (var j = 0; j < results.length; j++) {
            var element = results[j];
            var distance = element.distance.text;
            var duration = element.duration.text;
            document.getElementById('distance').innerHTML = "Driving Time: " + "<font size = 6px><a class = \"special\">"+ (duration + ", " + distance) + "</a></font>";
            var from = origins[i];
            var to = destinations[j];
        }
    }
  }
}
        
        function  calcRoute(){
            if(!showRoute){ //If it is on nearby traffic mode
                showRoute = true;
                document.getElementById('dirBtn').innerHTML = "Show Nearby";
                var start = new google.maps.LatLng(38.818662, -77.168763); //TJ Address
                var end = new google.maps.LatLng(lat_coord, long_coord);
                var request = {
                    origin:start,
                    destination:end,
                    travelMode: google.maps.TravelMode.DRIVING
                };
                directionsService.route(request, function(result, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        directionsDisplay.setDirections(result);
                    }
                });  
//               
            }
            else{ //If it is already showing route
                initMap();
                showRoute = false;
                document.getElementById('directionsPanel').innerHTML = "";
                document.getElementById('dirBtn').innerHTML = "Show Route";
//                document.getElementById("map").style.height = "500px";
            }
        }
                
        
        
  </script>
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBNSYDKc0j6v9C5vqog8jE79WEIjq7VwOo&callback=initMap">
    </script>
  