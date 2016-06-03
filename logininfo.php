<?php
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
    
 ?>