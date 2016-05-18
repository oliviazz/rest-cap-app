<?php 
session_start();
?>
<!DOCTYPE html>

 <html>
 <head>
  <title>Restaurant Search Results</title>
      <link rel="stylesheet" href="CSS/rstart.css" type="text/css">
 </head>
 <body>
    <br><br>
    <?php  

//if(!is_null($_POST['pastPage'])){
//    echo($_POST['pastPage']);
//}
   //else  //is_null($_POST['pastPage'])
 if(true){
        date_default_timezone_set('America/New_York');
        $dayofWeek =  date("l");
        $currentTime = date("G:ia");

    ob_start();
    
    #Sets session variables if they are not already set 
    if(is_null($_SESSION['location']) && is_null($_SESSION['foodType']) && is_null( $_SESSION['location'])){ //is
        $_SESSION['location'] == $_POST['location'];
         $_SESSION['foodType'] == $_POST['foodType'];
        $_SESSION['name'] == $_POST['name'];   
    }

        if (isset($_POST['location'], $_POST['foodType']) ||!is_null($_SESSION['location']) || $_GET["foodType"]!= null){
    
        #Takes POST variables and uses them 
          if (isset($_POST['location'], $_POST['foodType'])){  
            $my_location = $_POST['location']; 
            $myName = $_POST['myName'];
            $name = $_POST['name'];
            $term = $_POST['foodType'];
            $numResults = intval($_POST['numResults']);
            $startLoc = $_POST['startLoc'];
            $foodType = $_POST['foodType'];
          }
            
        #Or, if they are communicated through a form, takes them from POST instead
        if (isset($_GET["foodType"])){  
            $my_location = $_GET["location"]; 
            $name = $_GET["name"];
            $myName = $_GET["myName"];
            $term = $_GET["foodType"];
            $numResults = intval($_GET['numResults']);
            $startLoc = $_GET['startLoc'];
            $foodType = $_GET['foodType'];
          }
            
            if(strlen($startLoc) == 0){
                #startLoc comes from rstart page; is entered or should eventually be connected to user
                $startLoc = "6560 Braddock Rd. Alexandria, Virginia"; //To be default value - should stay with user
                
                echo('<div id=startLocValue style="visibility: hidden">'.$startLoc.'</div>');
            }
        #Default number results shown is 10
        if($numResults == 0){
            $numResults = 10;
        }
            
        #If no name specified, just says guest   
        if(strlen($myName) == 0){
            $myName = "Guest";
        }
            
            
    ?>
     <div id = "name">
         
         Hi, <?php echo $myName ?>.
         Here are <?php echo $foodType; ?> restaurants 
         near <?php echo $my_location;
         echo "<br> Results generated for <font color = lightgray>" .$dayofWeek.", " .date('F j Y \a\t h:i A')."</font>";
         ?>.
         <br>
     </div>
     
     <form action = "rstart.php" method = "post" id = "form2">
         <div id = "content">
            Show
            <select name = "numResults" id = "num">
            <option value = "3" id = 3>3</option>
            <option value = "5" selected = "selected"id = 5>5</option>
            <option value = "10"  id = 10>10</option>
            <option value = "15" id = all>All</option>
            </select>
            Results &nbsp;&nbsp;&nbsp;
            <input type = "hidden" id = "loc" name = "location" value = <?php echo $my_location ?>>
            <input type = "hidden" id = "na" name = "myName" value = <?php echo $myName ?> >
            <input type = "hidden" id = "ft" name = "foodType" value = <?php echo $term ?> >
            Calculate From:
            &nbsp;&nbsp;&nbsp;<input name = "startLoc" id = "startLocBox" type = text style = "width: 200px;" placeholder = "Enter Start Location">
           
            <input id = "sBut" type="submit" accesskey="s" name="submit" value = "Submit" onClick = "showLoad()"> 
         </div>
         <div id = "loadPic" style = "visibility:hidden;">
    <font color = "limegreen" size = "2">Recalculating . . .</font><img src= 51.gif  >
        </div>
     <br><br><br>
     </form>
     
     <br>
     <div id = "searchResults">
        <table>        
        <?php
            
            include 'yelpOriginal2.php';
            
            $searchResults = printResults($term, $my_location, $numResults);
          
            $getName =  "/(?<=\"name\": \").{3,50}(?=\", \"rating_img_url_small)/";
            $getLat = "/(?<=\"latitude\":).{1,30}(?=, \"longitude\")/";
            $getLong = "/(?<=\"longitude\":).{1,30}(?=},)/";
            $getRating = "/(?<=\"rating\":).{1,5}(?=, \"mobile_url\")/";
            $getURL = "/(?<=\"url\": \").{1,300}(?=\", \"categories\")/";   
            $isClosed = "/(?<=\"is_closed\":).{2,6}(?=, \"location)/";
            
            preg_match_all($getName, $searchResults, $allRestNames);
            preg_match_all($getLat, $searchResults, $lat);
            preg_match_all($getLong, $searchResults, $long);
            preg_match_all($getRating, $searchResults, $rating);
            preg_match_all($getURL, $searchResults, $url);
        
            if(sizeof($allRestNames[0]) < $numResults){
                $numResults = sizeof($allRestNames[0]);
            }
//           $numResults = 5;
        
            echo("Showing &nbsp; ".$numResults . " Results " );
            echo("<div id = hiddennumber style = \"display:none\">".$numResults."</div>");   
        
            $apikey = 'AIzaSyB-54gXHBi3GfNguJE8qhqkjhVQfcUVVBI';
                //"AIzaSyDRkicInDSoOxevtYZpe1QxOpIAox9j6mk";
            
            $startLoc =  urlencode($startLoc);
                
                $geocode_url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$startLoc.'&key='.$apikey;
                $startcoords =json_decode(file_get_contents($geocode_url))->results[0]->geometry->location;
                $startLat = $startcoords->lat;
                $startLng = $startcoords->lng;
                //echo($geocode_url. "<br>");
                //echo($startLat. $startLng);
                
            
          
            for ($i = 0; $i < $numResults; $i++){
               
                $restName = preg_replace('/\s+/', '_', $allRestNames[0][$i]);
                $restNameFormat = unescapeUTF8EscapeSeq(preg_replace('/\s+/', '&nbsp;', $allRestNames[0][$i]));
                $restLat = trim($lat[0][$i]);
                $restLong = trim($long[0][$i]);
                $waitTime = 0;
                
                
                
                $service_url = 'https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$startLat.",".$startLng.'&destinations='.$restLat.",".$restLong."&key=".$apikey;
//                echo($startLoc. 'to'.$restName);
//                echo('  <a href ='.$service_url.'>r</a>');
//                
                $routes=json_decode(file_get_contents($service_url))->rows[0];
                $myDis = ($routes->elements[0]->distance->text);
                $myTime = ($routes->elements[0]->duration->text);
//                echo($myDis.$myTime);
            
                $openTable_url = 'https://opentable.herokuapp.com/api/restaurants?country=US&name='.$restName.'';
                $price = json_decode(file_get_contents($openTable_url))->restaurants[0]->price;
                $reserveURL = json_decode(file_get_contents($openTable_url))->restaurants[0]->reserve_url;
                print("<tr><td align = \"center\" class = blackname>".$restNameFormat." </td>");
                print('<td><a href = '.$openTable_url .' target=\"_blank\>');
                
        
                
                if(!(is_null($price) != null)){
                    for($k = 0; $k <intval($price); $k++){
                     print('$');
                    } 
                    $priceVal = 2.0; 
                }
                else{
                    print('$');
                    $priceVal = 1.0;
                }
                
                $waitTime = calcCurWait();
                
                //Convert to float so it displays properly
                $waitTime = floatval($waitTime)*floatval($priceVal);
                //echo($waitTime);
                
                print('</td>');
                print("<td><a href = ".$url[0][$i]." target=\"_blank\" class = yelp>Yelp: [".$rating[0][$i]." / 5 ]</a> </td>");
                print("<td>");
                if(!is_null($reserveURL)){
                    print("<a href = ".$reserveURL." target=\"_blank\" >Reserve</a>");
                }
                print("</td>");
                $trafHTML ='<a href=trafficPage.php?restName='.$restName.'&foodType='.$foodType.'&long='.$restLong.'&lat='.$restLat.'&startLocTxt='.$startLoc.'&startLocLat='.$startLat."&startLocLng=".$startLng.'&myName='.$myName.'&restNameFormat='.$restNameFormat.'&location='.$my_location.'>'.$myTime.' traffic </a>';
                $reserveHTML = $waitTime.' min wait ';
           
            print('<td>'.($waitTime + $myTime).' min to seat ('. $trafHTML. ', '.$reserveHTML.') </td>');
             
            print("</tr>"); 
            }
           
            $_SESSION['restName'] = $allRestNames[0][0];
            $_SESSION['latitude'] = $lat[0][0];
            $_SESSION['longitude'] = $long[0][0];
            $_SESSION['rating'] = $rating[0];
        
        ?>
         </table>
         </div> 
     
        <?php
            print("<div> <br><br><a href = \"index.php\" class = \"back\">Back to Main Page</a>  </div>");
            $_POST['pastPage'] = ob_get_contents();
            file_put_contents('newPage.html', ob_get_contents());
        }
     
        else{
            echo($_SESSION['location']);
            
            print("<br><br><br><br><font size = 6 ><a href = \"index.php\" class = error>No values entered.<br>To view results, <i><u>return</u></i> to main page <br> and complete required fields</font></a>"); 
            print("<br><br><br><br><br><br><br><br><br>");  
        }
              
}  
   
 ?>
  
     
  <script>
        document.getElementById('num').style.width = "60px";
        var number = document.getElementById('hiddennumber').innerHTML;
        document.getElementById('num').value = number;
        document.getElementById('startLocBox').value = document.getElementById('startLocValue').innerHTML;
      
    function showLoad(){
        document.getElementById('loadPic').style = "visibility: visible;";
        document.getElementById('content').style = "display:none;";

    }
        
</script>
        
 </body>

</html>    
<?php
    function unescapeUTF8EscapeSeq($str) {
                return preg_replace_callback("/\\\u([0-9a-f]{4})/i",
                create_function('$matches',
                'return html_entity_decode(\'&#x\'.$matches[1].\';\', ENT_QUOTES, \'UTF-8\');'
                ), $str);
    }
        


    function calcCurWait(){
       
        $dayofWeek =  date("l");
        $currentHour = date("g");
      
        #---------Hour values
       
        if($currentHour < 10 && $currentHour >= 6 && strpos(date("A"), 'PM') !== false){
                $hourVal = 3;        
        }
        else if (intval($currentHour) < 2 &&  intval($currentHour) > 12 && strpos($currentTime, 'pm') !== false){
                $hourVal = 2;
        }
        
        else{
                $hourVal = 1;
        }
        
        #---------Day Values
        switch($dayofWeek){
            case "Monday":
                $dayVal = 1;
                break;
            case "Sunday":
                $dayVal = 2;
                break;
            case "Friday":
                $dayVal = 3;
            case "Saturday":
                $dayVal = 3;
            default:
                $dayVal = 1;
        }
        $waitTime = ($dayVal*$hourVal) + 2;
        return $waitTime;
    }
  
  
  
