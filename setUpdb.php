<?php //Creates first instance of database and gets handle
    //this file will be responsible for creating the tables and formatting the bare bones info
    
   

    function getDBHandle($dbFileName) // opens a connection to the SQL database in $dbFileName
    {   $servername = "restcapapp.database.windows.net;
        $user = "oliviaazhang";
        $password = "2Papayas!";
        $sqliteName = "sqlite:$dbFileName";
        try
        {
            $dbh = new PDO($sqliteName, $user, $password);
            echo('Success!');
        }
        catch(PDOException $e)
        {
            die("Connection to $sqliteName; " . $e->getMessage());
        }
    }

    function createMissingTables($dbh, $aTables, $createSpecFile) // create those tables listed in array $aTables
    {
        if(!sizeof($aTables))
        {
            return;
        }
        include_once($createSpecFile);
        foreach($aTables as $tbl)
        {
            if(!array_key_exists($tbl=strtolower($tbl), $aTableSpec))
            {
                continue;
            }
            $query = $aTableSpec[$tbl];
            try
            {
                $dbh->exec($query);
            }
            catch(PDOException $e)
            {
                die("Failed to create table $tbl<br>" . $e->getMessage());
            }
        }
    }
    $dbname = "restapp.db";
    getDBHandle($dbname); 
 ?>
