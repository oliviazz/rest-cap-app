<!DOCTYPE html>
<html>
    <head>
    <title>Restaurant Cap App</title>
    <link rel="stylesheet" href="index.css" type="text/css">
        <?php 
            
            include('database.php');
            $loggedIn = false;
            $username = $_GET["username"];
            if(strlen($username)!== 0){
                $loggedIn = true;
                echo("<div id = username style = display:none>".$username."</div>");
                $_POST["myName"] = $username;
            }
        ?>
    </head>
    <body>
         <br>
        <h1 id = "title">Find Food, Fast. </h1> 
        <div id = "mssg">
        <h4>Real time traffic and restaurant capacity data for an easier meal out.</h4>
            </div>
       
    <div id = "centerDiv" align = "center">
 <form action= "rstart.php" method="post" >
    Location: <input type="text" name="location"/><br><br>
    Type: &nbsp; &nbsp;&nbsp; <select name = "foodType" >
        <option value="Cafe">Cafe</option>
        <option value="French">French</option>
        <option value="Mexican">Mexican</option>
        <option value="Chinese">Chinese</option>
        <option value="Sushi">Sushi</option>
        <option value="Italian">Italian</option>
     <option value="American">American</option>
        <option value="Steak">Steakhouse</option>
        </select>
     <br><br>
      User: &nbsp; &nbsp;<input id = namefield type="text" name="myName" /><br>
      <?php 
        if(!$loggedIn){
            echo("<i><font color = lightgrey size = 3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Currently using as Guest.<a href = input.php> Log In here.</font> </a></i>");
        }
        else{
            
            echo("<i><font color = lightgrey size = 3><a href = input.php?username='$username'>Submit Capacity Report </a> or <a href = index.php?username= style = color:red;> Log Out</font> </a> </i>");
        }
        ?>

     <br><br>
     <input id = "sBut" type="submit" accesskey="s" name="submit" value = "Search" onClick = "showLoad()">

        <div id = "loadPic" style = "visibility:hidden">
            <img src= 30.gif  ><br><font color = red> Loading Results . . . </font>
        </div>  
     </input>
     <br>
</form>
        </div>  
          
    </body>
    <script>
    function showLoad(){
    
        document.getElementById('loadPic').style = "visibility: visible;";
        document.getElementById('sBut').style = "display:none;";

    }
        
    
        document.getElementById('namefield').value = document.getElementById('username').innerHTML;
    </script>
</html>
