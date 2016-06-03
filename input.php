<!DOCTYPE html>
<html>
    <head>
        <title>Input Real Time Restaurant Data</title>
        <link rel="stylesheet" href="input.css" type="text/css">
        </script><script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
        <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
    </head>
    <body>
        <br>
        <?php 
           
            date_default_timezone_set('America/New_York');
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
       
    
        if(isset($_POST['submit']) && strlen($_POST['restaurant']) > 0){
                $curCapacity = $_POST["curCapacity"];
                $restaurant = $_POST["restaurant"];
                $restName = str_replace("'","`",$restName);
                $day = $_POST["day"];
                $hour = $_POST["hour"];
                $sqltxt = "
                    INSERT INTO CapacityReviews ( Number, Restaurant, Time, Capacity)
                    VALUES (NULL,  '$restaurant', '$day.$hour', '$curCapacity');";

                $ret = $db->exec($sqltxt);
                if(!$ret){
                    echo $db->lastErrorMsg();
                }
                echo('Capacity Report Submitted!');    
               
        }
    
        if(strlen($_GET['username']) > 0)
            showUser($_GET['username']);
                
            


        
    if(isset($_POST['username'], $_POST['password']) && strlen($_POST['username']) > 0 && strlen($_POST['password']) >0){
          
                $username = $_POST["username"];
                $password = $_POST["password"];    
                $results = $db->query('SELECT * FROM UserInfo'); 
                $userFound = false;
                while ($row = $results->fetchArray()) {
                    
                    if(strcasecmp($username, $row['Username']) == 0 && strcasecmp($password, $row['Password']) == 0){
                        $userFound = true;
                        $_POST['loggedIn'] = $username;
                        break;
                    }    
                }
                #No username found - show link to make new account
                if(!$userFound){
                     echo('<div id = "mssg">
                        <form  method="post" action="input.php" >
                        Username:<input class = mini type="text" name="username">
                        Password:<input class = mini type="password" name="password">
                        <input class = mini type = "text" name = "loggedIn" style = "display:none; ">
                        <input id = "loginBut" type="submit" accesskey="l" name="submit" value = "Log In"> 
                        </form>
                        </div><br>
                        <font color = red> Login for "'.$username.'" failed. Would you like to create a <a href = newUser.php style = "color:red; background:none; border:none; text-shadow: 1px 1px #000000;"><b>  new account </a></b> for '.$username.' instead?</font><br><br>
                        <i><h4 style = "color:deepskyblue; text-shadow: 1px 1px #000000;" >Log in to submit capacity reports</h4></i>
                         ');
                
                  
                }
            else{
                showUser($username);
            }
    }
    #Not logged in initially
    else if(!$userFound){
        echo('<div id = "mssg">
                <form  method="post" action="input.php" >
                Username:<input class = mini type="text" name="username">
                Password:<input class = mini type="password" name="password">
                <input class = mini type = "text" name = "loggedIn" style = "display:none; ">
                <input id = "loginBut" type="submit" accesskey="l" name="submit" value = "Log In"> 
                </form>
                </div><br>
                <a href = "newUser.php">{ Create New Account }</a><br><br>
                <i><h1 style = "color:white; ">Log in to submit capacity report</h1></i>');
        
       
    }
        
    #Yes match
    function showUser($username){
        echo('<div id = "mssg">
            <h2>Welcome, User  '.$username.'!</h2>
            </div>
            <form  method="post" action="input.php?username=\"'.$username.'" >
            <input class = mini type = "text" name = "logout" value = "yes" style = "display:none; width = 2px;">
            <input id = "sBut" type="submit" accesskey="l" name="logout" value = "Log Out"> 
            </form>
            <br><br>
            <div id = messageboard></div>
        
        <div id = "cDiv" align = "center">
            
            <div align = "center" style = align-content:center>
            <form action= "input.php" method="post"  ><br>
                
                Restaurant: <input id="restaurant" name = "restaurant" autocomplete = on type = "text"><br><br>
                Current Capacity: <select name = "curCapacity" >
                    <option value=1>1</option>
                    <option value=2>2</option>
                    <option value=3>3</option>
                    <option value=4>4</option>
                    <option value=5>5</option>
                </select><br><br>
                Day: <input type="text" id = "day" name = "day" style = "color:black:"> 
                Hour: <input type="text"  name = "hour" id = "hour"style = "color:black:" ><br><br>
                <input type = "text" name = "username" style = "display:none" value = '.$username.'>
           
        </div>
                <input id = "sBut" type="submit" accesskey="s" name="submit" value = "Submit" onclick = show()> <br> 
            </form>
        </div>
        <div id = hday style = display:none >'.date("l").'</div>
        <div id = hhour style = display:none >'.date("ga").'</div>'
            );
    }
        
    ?>
        
    </body>
    <script> 
        document.getElementById('messageboard').innerHTML = "";
        document.getElementById('day').value = document.getElementById('hday').innerHTML;
        document.getElementById('hour').value = document.getElementById('hhour').innerHTML;
        
        $(function() {
            $( "#restaurant" ).autocomplete({
            source: <?php 
                 $searchTerm = $_GET['term'];
                 $results = $db->query("SELECT * FROM RestaurantData WHERE Name LIKE '%".$searchTerm."%' ORDER BY Name ASC");
                 while ($row =  $results->fetchArray()) {
                        $data[] = str_replace('&nbsp;', ' ', $row['Name']);
                 }
                 echo json_encode($data); ?>
            });
        }); 
    </script>

</html>

<?php 
     print("<div> <br><br><a id = back style = color:blue; href = \"index.php?username=".$username."\" class = \"back\">{ Back to Main Page} </a> <a href = databaseInfo.php target=_blank>View Databases</a><br><br></div>");

?>