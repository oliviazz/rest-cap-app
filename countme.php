<html>
<!--

 Olivia Zhang | Web Application Development | 11/23/15
    Countme PHP Lab
        -Keep track of real-time visitor count through php

--!> 




    <head> <title>Visitor Count</title>

    </head>
    <body align = "center" color = "blue">
        <?php

            $file = 'countMe.txt';
            
            $numVisits =  file_get_contents($file);
            
            
            echo("Hello! \n");
            $numVisits++; 
            echo("You are visitor number ");
            echo($numVisits);
    
            file_put_contents('countMe.txt', $numVisits);
           
           
            
            
        ?>
        </body>
</html>
    

<!--getcwd(); determine where u at now 