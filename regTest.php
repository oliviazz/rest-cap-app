<?php 

include 'yelpOriginal2.php';
$string = printResults("French", "Alexandria");
 
        $getName = "/(?<=\"name\": \").*/";
        
        echo $string;
    
        $getName =  "/(?<=\"name\": \").*(?=\", \"rating_img_url_small)/";

        preg_match($getName, $string, $allRestNames);
        print_r($allRestNames);
        echo $allRestNames[0], "hello";


//print_r($output_array);
echo($output_array[0]);




?>