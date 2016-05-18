<?php 
session_start();
?>
<!DOCTYPE html>

<html>
 <head>
    <title>Restaurant Traffic View</title>
    <link rel="stylesheet" href="CSS/traffic.css" type="text/css">
     
    <style type="text/css">
       html, body {
        height: 100%;
        margin: 0;
        padding: 0;
           color:black;
      }
      #map {
        height: 100%;
        text-shadow: none;
     
      }
      a{
            color:blue;
        }
      #floating-panel {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }
      #dPanel {
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 9px;
        text-shadow: none;
      }

      #dPanel select, #dPanel input {
        font-size: 15px;
      }

      #dPanel select {
        width: 100%;
      }

      #dPanel i {
        font-size: 12px;
      }
        #bigPanel{
            align-self:center;
            align-content:center;
            width: 90%;  
            height:100%;
            margin: auto;
        }
      #dPanel {
        height: 100%;
        float: right;
        width: 400px;
        overflow: auto;
          
      }
        
        

.adp-step, .adp-substep {
    border-top: 1px solid blue;
    margin: 0;
    padding: .3em 1px;
    vertical-align: top;
}

td, th {
    display: table-cell;
    vertical-align: inherit;
}

.adp, .adp table {
    font-family: Roboto,Arial,sans-serif;
    font-weight: 300;
    color: blue;
}
      #map {
        margin-right: 400px;
      }
      #floating-panel {
        background: #fff;
        padding: 5px;
        font-size: 14px;
        font-family: Arial;
        border: 1px solid #ccc;
        box-shadow: 0 2px 2px rgba(33, 33, 33, 0.4);
        display: none;
      }
      @media print {
        #map {
          height: 500px;
          margin: 0;
        }
        #dPanel {
          float: right;
          width: auto;
       
        }
      }
/*           margin-right: 10px;
        }
    </style>
     
 </head>
 <body align = "center">
     
     <div id="drivingTime" >
     <?php   
       
        $restName = $_GET["restName"];
        $restNameFormat = $_GET["restNameFormat"];
        $myName = $_GET["myName"];
        $lat = $_GET["lat"];
        $long = $_GET["long"];
        $startLocLat = $_GET["startLocLat"];
        $startLocLng = $_GET["startLocLng"];
        $startLocTxt =  $_GET["startLocTxt"];
        $foodType = $_GET["foodType"];
        $location = $_GET["location"];
//have lat long start values, but still need ot communicate into calculations
        ?>
        
         <a  href = <?php echo('rstart.php?restName='.$restName.'&foodType='.$foodType.'&startLoc='.$startLocTxt.'&location='.$location.'&myName='.$myName); ?>><i><font color = #0085b2><b>Back to Results</b></i></font></a><br>
         
    <?php
        $name = str_replace('_','&nbsp;', $name); //for url passing purposes
        
        $_SESSION['myName'] = $myName;
        $_SESSION['foodType'] = $foodType;
        $_SESSION['startLoc'] = $startLoc;

        echo('<div id=startLocLat style="visibility: hidden">'.$startLocLat.'</div>');
        echo('<div id=startLocLng style="visibility: hidden">'.$startLocLng.'</div>');
        echo('<div id=startLoc style="visibility: hidden">'.$startLocTxt.'</div>');
        echo "<h2 align = \"center\"> Real-Time Traffic near <a class = \"special\">",$restNameFormat, ": </h2></a>";

     ?>
     <?php 
        
        $drivingTimes = [];
       
session_start();
       ?>
     </div>
     
     
     <div id="lat" >
        <?php  echo $lat ?>  
     </div>
     <br>
     <div id="long" >
        <?php echo $long; ?>
     </div>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js">
     </script>
     <form id = 'newStart' action = 'traffic.php'>
     Start Location: <input type = "text" id = "startLocBox" value = "" style = "color:black;" >
     Latitude: <input type = "text" id = "lat_coord" value = "40.7127">
     Longitude: <input type = "text" id = "long_coord" value = "-74.0059">
         &nbsp; &nbsp; &nbsp;
     <button type = "button" id = "traffBtn" onClick = "toggleTraffic()"> Hide Traffic</button>
     <button type = "button" id = "dirBtn" onClick = "calcRoute()">Show Route</button>
     <input id = "sBut" type="submit" accesskey="s" name="submit" value = "Recalculate" onclick = "reloadMap(event)"> 
  
     </form>
     <br>
     <div id = "distance"> 
         Driving Time/Distance: 
     </div>
     <div id = "bigPanel">
    <div id="dPanel" ></div>
    <div id="map" align = "center"></div>
    </div>
    

    <br>
</body>
    
    <script type="text/javascript">
        
        document.getElementById('startLocBox').value = document.getElementById('startLoc').innerHTML;
        
        
        var directionsDisplay;
        var directionsService; 
        var showTraffic = true;
        var showRoute = false;
        var map, origin1;
        var trafficLayer;
        
        var startlat_coord = parseFloat(document.getElementById('startLocLat').innerHTML);
        var startlong_coord = parseFloat(document.getElementById('startLocLng').innerHTML);
        
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
          
          directionsDisplay.setPanel(document.getElementById("dPanel"));
        directionsDisplay.setMap(map);
       
          trafficLayer = new google.maps.TrafficLayer();
            
          if(showTraffic){
            trafficLayer.setMap(map);
          }
            origin1 = new google.maps.LatLng(startlat_coord, startlong_coord);
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
                document.getElementById('dPanel').style = "background-color:white; color:navy;";
                document.getElementById('dirBtn').innerHTML = "Show Nearby";
                //var start = new google.maps.LatLng(38.818662, -77.168763); //TJ Address
                var start = new google.maps.LatLng(parseFloat(document.getElementById('startLocLat').innerHTML),parseFloat(document.getElementById('startLocLng').innerHTML)); 
                
                
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
                document.getElementById('dirBtn').innerHTML = "Show Route";
                document.getElementById('dPanel').style = "background-color:white; color:navy;";
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
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBNSYDKc0j6v9C5vqog8jE79WEIjq7VwOo&callback=initMap&signed_in=true">
    </script>
  
 
</html>