<html>
 <head>
  <title>Result Page</title>
      <link rel="stylesheet" href="CSS/index.css" type="text/css">
 </head>
 <body>
     
  <?php  
     $my_location = $_POST['location']; 
     $name = $_POST['name'];
     $term = $_POST['foodType'];
     include 'rstart.php';
     $requestInfo = returnData($my_location, $name, $term);
echo $requestInfo;
     echo gettype($requestInfo);
 
  ?>
     
 </body>
</html>
     
     
     