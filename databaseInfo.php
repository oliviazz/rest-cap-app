 <?php
 
 class MyDB extends SQLite3
            {
                function __construct(){
                    $this->open('restapp.db');
                }
            }
            $db = new MyDB();
            if(!$db){
                echo $db->lastErrorMsg();
            }
       
 
 $results = $db->query('SELECT * FROM CapacityReviews'); 
            echo('<p align = center>CapacityReviews</p>');

            echo('<table width = "70%" align = "center">');
            while ($row = $results->fetchArray()) {
                            echo('<tr>');
                            echo('<td> ' . $row['Number'] . '</td>');
                            echo('<td> ' . $row['Restaurant'] . '</td>');
                            echo('<td>' . $row['Capacity'] . '</td>');
                            echo('<td>' . $row['Time'] . '</td>');
                            echo('</tr>');  

                        }
                        echo('</table><hr>');


$results = $db->query('SELECT * FROM UserInfo'); 

        echo('<p align = center>UserInfo</p>');
        echo('<table width = "70%" align = "center">');
        $i = 0;
        while ($row = $results->fetchArray()) {
            echo('<tr>');
            echo('<td>' . $i. '</td>');
            echo('<td>' . $row['Username'] . '</td>');
            echo('<td>' . $row['Password'] . '</td>');
            echo('<td>' . $row['Email'] . '</td>');
            echo('</tr>');  
            $i++;
             
        }
        echo('</table><hr>');
 
$results = $db->query('SELECT * FROM RestaurantData'); 
            echo('<p align = center>RestaurantData</p>');
            echo('<table width = "70%" align = "center">');
            
            $i = 0;
            while ($row = $results->fetchArray()) {
                echo('<tr>');
                echo('<td>' . $i. '</td>');
                echo('<td>' . $row['Name'] . '</td>');
                echo('<td>' . $row['Location'] . '</td>');
                
                echo('</tr>');  
                $i++;
             
            }
        echo('</table>');
?>