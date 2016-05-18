<?php //Accesses existing database and adds user info into database
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
       echo "Opened database successfully\n";
    }
    //$db->query('DELETE FROM UserInfo');   

    $results = $db->query('SELECT * FROM UserInfo'); 
    $db->exec('alter table UserInfo add column Email Text');
    $username = $_GET["username"];
    $password = $_GET["password"];
   

    //Select column from table
   echo("Entered:".$username.$password);
    $userFound = false;
    while ($row = $results->fetchArray()) {
        //var_dump($row);
        if(strcasecmp($username, $row['Username']) == 0 && strcasecmp($password, $row['Password']) == 0){
                echo( $row['Password']. "Password");
                echo($username." is logged in.");
                $userFound = true;
                break;
        }    
    }
    if(!$userFound)
        echo("User not found. Create new account instead? ");
//// if(strlen($username)>0 && strlen($password)>0){
//        
//        $sqltxt = "
//        INSERT INTO UserInfo ( Username, Password )
//        VALUES ('$username',  '$password');";
//    
//        echo ($sqltxt);
//        $ret = $db->exec($sqltxt);
//
//        if(!$ret)
//        {
//         echo $db->lastErrorMsg();
//       
//        }
//        else
//        {
//        echo "Records created successfully\n";
//        }
//        print("Result Entered! <br>");
//    }
////
    
   

    // set the resulting array to associative

    $db->close();
 ?>
