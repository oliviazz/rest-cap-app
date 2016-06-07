<?php  //Accesses existing database and creates tables
    include_once('setUpdb.php');
 
    // class MyDB extends SQLite3
    // {
    //   function __construct()
    //   {
    //       $this->open('restapp.db');
    //   }
    // }
    

    //$db = new MyDB();
    $db = getDBHandle('restapp.db');
    if(!$db)
    {
       echo $db->lastErrorMsg();
    }
    else
    {
       echo "Opened database successfully\n";
    }
    $sqltxt = "
        CREATE TABLE UserInfo
        (
        Username  Text PRIMARY KEY NOT NULL,
        Password  Text NOT NULL,
        Email Text,
        FirstName Text,
        LastName Text,
        HomeLocation Text,
        Reviews Text);
        ";

    $sqltxt2 = "
        CREATE TABLE CapacityReviews
        (
        Number INTEGER PRIMARY KEY NOT NULL,
        Restaurant   Text NOT NULL,
        Time Text NOT NULL,
        Capacity INTEGER NOT NULL);
        ";

    $sqltxt3 = "
        CREATE TABLE RestaurantData
        (
        Name Text PRIMARY KEY NOT NULL,
        Location   Text NOT NULL,
        YelpLink Text NOT NULL,
        Lat REAL NOT NULL,
        Long REAL NOT NULL,
        Reviews Text);
        ";
    
 

     $ret = $db->exec($sqltxt);
     $ret2 = $db->exec($sqltxt2);
     $ret3 = $db->exec($sqltxt3);
 echo('potato');
     if(!$ret)
     {
         echo $db->lastErrorMsg();
     }
     else
     {
         echo "Table created successfully\n";
     }
     if(!$ret2||!$ret3)
     {
         echo $db->lastErrorMsg();
     }
     else
     {
         echo "Table2 created successfully\n";
     }
     echo('e');
     $db->close();
 ?>
