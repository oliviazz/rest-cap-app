 <?php 

  $file = "dotInfo.txt";
 file_put_contents($file, "hello");  
 $text =  file_get_contents($file);
 echo ($text);

?>