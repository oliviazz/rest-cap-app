<!DOCTYPE html>
 <html>
 <head>
  <title>Restaurant Search Results</title>
      <link rel="stylesheet" href="CSS/rstart.css" type="text/css">
 </head>
 <body>
    <br><br>
    <?php  
    
        if (isset($_POST['location'], $_POST['foodType']) ){
            session_start();
        
     
            $my_location = $_POST['location']; 
            $name = $_POST['name'];
            $term = $_POST['foodType'];
            $numResults = intval($_POST['numResults']);
            $startLoc = $_POST['startLoc'];
        
            if(strlen($startLoc) == 0){
                $startLoc = $my_location;  
            }

        if($numResults == 0){
            $numResults = 10;
        }
    ?>
     <div id = "name">
         Hi, <?php echo $_POST['name']; ?>.
         Here are <?php echo $_POST['foodType']; ?> restaurants 
         in <?php echo $_POST['location'];?>:
         <br>
     </div>
     
     <form action = "rstart.php" method = "post" id = "form2">
        Show
        <select name = "numResults" id = "num">
            <option value = "3" id = 3>3</option>
            <option value = "5" selected = "selected"id = 5>5</option>
            <option value = "10"  id = 10>10</option>
            <option value = "15" id = all>All</option>
         </select>
         Results &nbsp;&nbsp;&nbsp;
            <input type = "hidden" id = "loc" name = "location" value = <?php echo $_POST['location']?>>
            <input type = "hidden" id = "na" name = "name" value = <?php echo $_POST['name']?>>
            <input type = "hidden" id = "ft" name = "foodType" value = <?php echo $_POST['foodType']?> >
         Start Location:
         &nbsp;&nbsp;&nbsp;<input name = "startLoc" id = "startLocBox" type = text placeholder = "TJHSST">
          <button id = "sBut" type="submit" accesskey="s" name="submit"><u>R</u>ecalculate</button>
         <br><br>
     </form>
     
     <br>
     <div id = "searchResults">
        <table>        
        <?php
             
            function unescapeUTF8EscapeSeq($str) {
                return preg_replace_callback("/\\\u([0-9a-f]{4})/i",
                create_function('$matches',
                'return html_entity_decode(\'&#x\'.$matches[1].\';\', ENT_QUOTES, \'UTF-8\');'
                ), $str);
            }
        
            include 'yelpOriginal2.php';
            $searchResults = printResults($term, $my_location, $numResults);
            $getName =  "/(?<=\"name\": \").{3,50}(?=\", \"rating_img_url_small)/";
            $getLat = "/(?<=\"latitude\":).{1,30}(?=, \"longitude\")/";
            $getLong = "/(?<=\"longitude\":).{1,30}(?=},)/";
            $getRating = "/(?<=\"rating\":).{1,5}(?=, \"mobile_url\")/";
            $getURL = "/(?<=\"url\": \").{1,300}(?=\", \"categories\")/";   
            //$isClosed = "/(?<=\"is_closed\":).{2,6}(?=, \"location)/";
        
            preg_match_all($getName, $searchResults, $allRestNames);
            preg_match_all($getLat, $searchResults, $lat);
            preg_match_all($getLong, $searchResults, $long);
            preg_match_all($getRating, $searchResults, $rating);
            preg_match_all($getURL, $searchResults, $url);
        
            if(sizeof($allRestNames[0]) < $numResults){
                $numResults = sizeof($allRestNames[0]);
            }
        
            echo("Showing &nbsp; ".$numResults . " Results " );
            echo("<div id = hiddennumber style = \"display:none\">".$numResults."</div>");   
        
            $apikey = "AIzaSyDRkicInDSoOxevtYZpe1QxOpIAox9j6mk";

            for ($i = 0; $i < $numResults; $i++){
                $restName = preg_replace('/\s+/', '_', $allRestNames[0][$i]);
                $restNameFormat = unescapeUTF8EscapeSeq(preg_replace('/\s+/', '&nbsp;', $allRestNames[0][$i]));
                $restLat = trim($lat[0][$i]);
                $restLong = trim($long[0][$i]);
            
                $service_url = 'https://maps.googleapis.com/maps/api/directions/json?origin=Fairfax&destination='.$restLat.",".$restLong."&key=".$apikey;
                $routes=json_decode(file_get_contents($service_url))->routes[0];
                $myDis = ($routes->legs[0]->distance->text);
                $myTime = ($routes->legs[0]->duration->text);
            
                $openTable_url = 'http://opentable.herokuapp.com/api/restaurants?country=US&name='.$restName.'';
                $price = json_decode(file_get_contents($openTable_url))->restaurants[0]->price;
             
                print("<tr><td align = \"center\" class = blackname>".$restNameFormat." </td>");
                print('<td><a href = '.$openTable_url .' target=\"_blank\>');
                if(!(is_null($price) != null)){
                    for($i = 0; $i<intval($price); $i++){
                     print('$');
                    }  
                }
                else{
                    print('$');
                }
                print('</td>');
                print("<td><a href = ".$url[0][$i]." target=\"_blank\" class = yelp>Yelp: [".$rating[0][$i]." / 5 ]</a> </td>");
            

            echo($service_url.' ');      
            print('<td><a href=trafficPage.php?name='.$restName.'&long='.$restLong.'&lat='.$restLat.'>'.$myTime." ( ".$myDis.' ) </a></td>');
             
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
            print("<div> <a href = \"index.html\" class = \"back\">Back to Main Page</a>   </div>");
        }
     
        else{
            print("<br><br><br><br><font size = 6 ><a href = \"index.html\" class = error>No values entered.<br>To view results, <i><u>return</u></i> to main page <br> and complete required fields</font></a>"); 
            print("<br><br><br><br><br><br><br><br><br>");  
        }
        ?>
   
    
    
 </body>
<script>
        document.getElementById('sBut').style.width = "200px";
        document.getElementById('num').style.width = "60px";
        var number = document.getElementById('hiddennumber').innerHTML;
        document.getElementById('num').value = number;
        
        
</script>
     
</html>    
  
  
  
