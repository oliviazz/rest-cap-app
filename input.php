<!DOCTYPE html>



<html>
    <head>
    <title>Input Real Time Restaurant Data</title>
    <link rel="stylesheet" href="CSS/input.css" type="text/css">
    </head>
    <body>
         <br>
<?php 

  
//    $loggedIn = false;
//echo($_POST['logout']);
    //If username is entered, or if the logout button is clicked
   if(strlen($_POST['username']) != 0 ){
       $username = $_POST['username'];
       echo( $_POST['username']);
       $loggedIn = true;
       echo('<div id = "mssg">
        <h4>Welcome, User!'.$username.'</h4>
        </div>
        <form  method="post" action="input.php" >
        <input class = mini type = "text" name = "logout" value = "yes" style = "display:none; width = 2px;">
        <input id = "sBut" type="submit" accesskey="l" name="logout" value = "Log Out"> 
        </form>
        <br><br>');
   }
    else {
    echo('<div id = "mssg">
            <form  method="post" action="input.php" >
                Username:<input class = mini type="text" name="username">
                Password:<input class = mini type="password" name="password">
                <input class = mini type = "text" name = "loggedIn" style = "display:none; ">
                <input id = "loginBut" type="submit" accesskey="l" name="submit" value = "Log In"> 
            </form>
        </div><br>
        <a href = "newUser.php">Create New Account</a><br><br>');
    }
?>

    <div id = "cDiv" align = "center">
 <form action= "input.php" method="post" >
     <br>
    Restaurant: <input type="text" name="restaurant"/><br><br>
    Current Capacity: &nbsp; &nbsp;&nbsp; <select name = "curCapacity" >
        <option value=1>1</option>
        <option value=2>2</option>
        <option value=3>3</option>
        <option value=4>4</option>
        <option value=5>5</option>
        </select>
     <br><br>
     Date/Time: &nbsp; &nbsp;<input type="text" name="timestamp" /><br /><br>
     <input id = "sBut" type="submit" accesskey="s" name="submit" value = "Submit"> 
     <br>
</form>
        </div>  
      
    </body>
</html>
<?php 
    class MyDB extends SQLite3
    {
       function __construct()
       {
          $this->open('restapp.db');
       }
    }
    $db = new MyDB();

    //Assume that no message = successful database opening. 
    if(!$db)
    {
       echo $db->lastErrorMsg();
    }
//    else{
//       //echo("Success!"); 
//    }



    if(isset($_POST['username'], $_POST['password']) && !$loggedIn){
    
           
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $mylocation = $_POST["location"];

    //$db->query('DELETE FROM UserInfo');   

    $results = $db->query('SELECT * FROM UserInfo'); 
   

    //Select column from table
    $userFound = false;
    while ($row = $results->fetchArray()) {
        //var_dump($row);
        if(strcasecmp($username, $row['Username']) == 0 && strcasecmp($password, $row['Password']) == 0){
                echo("Success! ". $username." is logged in.");
                $userFound = true;
                $_POST['loggedIn'] = $username;
                echo($_POST['loggedIn']);
                
                break;
        }    
    }
    if(!$userFound)
        echo("User ".$username.'" not found. Create <a href = newUser.php > new account </a> for '.$username.' instead? ');
        
    }
     print("<div> <br><br><a href = \"index.php?username=".$username."\" class = \"back\">Back to Main Page</a>  </div>");
?>