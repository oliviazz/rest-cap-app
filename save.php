<!DOCTYPE html>
<html>
    <body>
  
<?php  


     $selected = $_REQUEST['selected'];
     $dotLoc = $_REQUEST['dotLoc'];
     $text = $dotLoc.",-".$selected;
     echo $text;
     $file = fopen("dotInfo.txt","w");
     fwrite($file, $text);
     fclose($file);
  
 
    
?></body>

    </html>
