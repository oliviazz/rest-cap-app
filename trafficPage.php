<!DOCTYPE html>
<html>
 <head>
    <title>Restaurant Traffic View</title>
    <link rel="stylesheet" href="CSS/traffic.css" type="text/css">
    <style type="text/css">
     
      #mapPanel{
          width:70%;
          align-content:center;
          margin: auto;
      }
      #map {   
          height: 500px;
          width: 100%;
          margin-left: auto;
          margin-right: auto;
      }
        
      #directionsPanel{
          width: 100%;
          margin:auto;
          font-size:13px;
      }
    </style>
 </head>
 <body align = "center">
     <div id="drivingTime" >
     <?php   
        session_start();
        $name = $_GET["name"];
        echo "<h2 align = \"center\"> Current traffic near <a class = \"special\">",$name, ": </h2></a>";
     ?>
     <?php 
        
      
        $_SESSION['name'] = $_GET["name"];
        $_SESSION['location'] = $_GET["location"];
        $_SESSION['foodType'] = $_GET["foodType"];
        $drivingTimes = [];
       
session_start();
       ?>
     </div>
     <button name = "submit" type = "submit" onclick = "location.href= 'rstart.php';">Back</button>
     
     <div id="lat" >
        <?php  echo $_GET["lat"] ?>  
     </div>
     <br>
     <div id="long" >
        <?php echo $_GET['long']; ?>
     </div>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js">
     </script>
     <form>
     Latitude: <input type = "text" id = "lat_coord" value = "40.7127" onKeyPress = "reloadMap(event)">
     Longitude: <input type = "text" id = "long_coord" value = "-74.0059" onKeyPress = "reloadMap(event)">
         &nbsp; &nbsp; &nbsp;
     <button type = "button" id = "traffBtn" onClick = "toggleTraffic()"> Hide Traffic</button>
     <button type = "button" id = "dirBtn" onClick = "calcRoute()">Show Route</button>
     </form>
     <br>
     <div id = "distance"> 
         Driving Time/Distance: 
     </div>
    <div id = "mapPanel">
        <div id="map" align = "center"></div>
        <div id="directionsPanel"></div>
    </div> 
    
    <br>
</body>
    
    <script type="text/javascript">
        var directionsDisplay;
        var directionsService; 
        var showTraffic = true;
        var showRoute = false;
        var map, origin1;
        var trafficLayer;
        
        var lat_coord = parseFloat(document.getElementById('lat').innerHTML);
        var long_coord = parseFloat(document.getElementById('long').innerHTML);
        document.getElementById('lat_coord').value = lat_coord;
        document.getElementById('long_coord').value = long_coord; 
        
        var latdiv = document.getElementById( 'lat' );
        latdiv.parentNode.removeChild( latdiv );
        var longdiv = document.getElementById( 'long' );
        longdiv.parentNode.removeChild(longdiv );
        
        
        if(lat_coord == "" || long_coord == ""){
            lat_coord = 24.3; //Change to Current Location 
            long_coord = 29.3; //Change to Current Location 
        }
        
        function initMap() {
          directionsDisplay = new google.maps.DirectionsRenderer();
          directionsService = new google.maps.DirectionsService();
          map = new google.maps.Map(document.getElementById('map'),{
            center: {lat: lat_coord, lng: long_coord},
            zoom: 19
            });
          
          directionsDisplay.setMap(map);
          directionsDisplay.setPanel(document.getElementById("directionsPanel"));
          trafficLayer = new google.maps.TrafficLayer();
            
          if(showTraffic){
            trafficLayer.setMap(map);
          }
            origin1 = new google.maps.LatLng(38.818662, -77.168763);
            var destinationA = new google.maps.LatLng(lat_coord, long_coord);
            var service = new google.maps.DistanceMatrixService();
            service.getDistanceMatrix(
            {
            origins: [origin1],
            destinations: [destinationA],
            travelMode: google.maps.TravelMode.DRIVING,

            }, callback);
        }
        
        function  calcRoute(){
            if(!showRoute){ //If it is on nearby traffic mode
                showRoute = true;
                document.getElementById('dirBtn').innerHTML = "Show Nearby";
                //var start = new google.maps.LatLng(38.818662, -77.168763); //TJ Address
                var start = new google.maps.LatLng(38.8234582,-77.2725977);
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
                
        
        function toggleTraffic(){
          if(showTraffic){
              showTraffic = false;
              trafficLayer.setMap(null); 
              document.getElementById('traffBtn').innerHTML = "Show Traffic";
          }
          else{
              showTraffic = true;
              trafficLayer.setMap(map); 
              document.getElementById('traffBtn').innerHTML = "Hide Traffic";
          }
      
          
      }

      function reloadMap(e){

        var newlat = parseFloat(document.getElementById('lat_coord').value);
        var newlong = parseFloat(document.getElementById('long_coord').value); 
        if(!(newlat != lat_coord || newlong != long_coord || newlat == NaN || newlong == NaN)){
           lat_coord = parseFloat(document.getElementById('lat_coord').value);
           long_coord = parseFloat(document.getElementById('long_coord').value);
           initMap();
        }
      }

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

        
  </script>
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBNSYDKc0j6v9C5vqog8jE79WEIjq7VwOo&callback=initMap">
    </script>
  
 
</html>