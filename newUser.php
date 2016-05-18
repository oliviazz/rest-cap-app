<!DOCTYPE html>



<html>
    <head>
    <title>Create New Account</title>
    <link rel="stylesheet" href="CSS/newUser.css" type="text/css">
    </head>
    <body>
         <br><br>
        
<?php

    $loggedIn = $_POST["loggedIn"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $mylocation = $_POST["location"];

    
//    if(strlen($username) > 0){
//        $loggedIn = TRUE;
//        echo("New Account Created!");
//        echo("<h4>Welcome, ".$username."</h4>");
//        sleep(3);
//        //header('Location: input.php');
//    }

   if(!$loggedIn || strlen($_POST["loggedIn"]) == 0){
       ?>
        <h3>Create New Account</h3>
        <div id = "cDiv" align = "center">
            
            <form  method="post" action="newUser.php" >
                Username:<input  type="text" name="username"><br>
                Email:<input  type="text" name="email"><br>
                Password:<input type="password" name="password"><br>
                <br><i>Optional:</i><br>
                First Name:<input  type="text" name="firstname"><br>
                Last Name:<input   type="text" name="lastname"><br>
                Home Location:<input type="text" name="location"><br>
                <input id = "createAcctBut" type="submit" accesskey="l" name="submit" value = "Sign Up"> 
            </form>
        </div><br><br><br>
        
<?php }
    else{
    ?>
        
        <input id = "sBut" type="submit" accesskey="l" name="logout" value = "Log Out" onClick = "window.location.href = 'input.php'>" 
        <br><br><br>
        
<?php 
    }
   
      class MyDB extends SQLite3
    {
       function __construct()
       {
          $this->open('restapp.db');
       }
    }
    $db = new MyDB();
    if(!$db)
    {
       echo $db->lastErrorMsg();
    }
    else
    {
       //
    }
    //$db->query('DELETE FROM UserInfo');   
        echo('Current Users: ');
        echo('<table width = "40%" align = "center">');
        $results = $db->query('SELECT * FROM UserInfo'); 
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
        echo('</table>');
 
    
    
    

    //$db->query('DELETE FROM UserInfo');   

    $results = $db->query('SELECT * FROM UserInfo'); 
    $userFound = false;
    while ($row = $results->fetchArray()) {
        //var_dump($row);
        if(strcasecmp($username, $row['Username']) == 0 && strcasecmp($password, $row['Password']) == 0){
                
                $userFound = true;
                break;
        }    
    }

    if($userFound)
        echo("User ".$username.'" already exists. Try another usename');
    
    else{ 
        if(strlen($username)>0 && strlen($password)>0){
        
        $sqltxt = "
        INSERT INTO UserInfo ( Username, Password, Email, FirstName, LastName)
        VALUES ('$username',  '$password', '$email','$firstname', '$lastname');";
    
        echo ($sqltxt);
        $ret = $db->exec($sqltxt);

        if(!$ret)
        {
         echo $db->lastErrorMsg();
       
        }
        else
        {
        echo "Records created successfully\n";
        }
        print("Result Entered! <br>");
        $_POST['loggedIn'] = true;
        sleep(4);  
     header('Location: input.php');
        }
        }
//
    
   

    // set the resulting array to associative

    $db->close();
        
        
        ?>

      
    </body>
</html>