<html>
    <head>
    <title>Web Application Development Form (Lab 8)</title>
    <link rel="stylesheet" href="css/Formindex.css" type="text/css">
    </head>
    <body>
        <h2> PHP Form: </h2>
 <form action="webForm.php" method="post">
    Name:  <input type="text" name="fname" placeholder ="First"><input type="text" name="mname" placeholder ="Middle">
     <input type="text" name="lname" placeholder ="Last"><br />
    Phone: <input type="text" name="phone" placeholder = "Ex. 888-888-888" /><br />
    Birthdate: <input type="text" name="bday" placeholder = "MM/DD/YYYY" /><br />
    Least Favorite Vegetable: <select name = "veggie" >
        <option value="Broccoli">Broccoli</option>
        <option value="Potato">Potato</option>
        <option value="Radish">Radish</option>
        <option value="Beet">Beet</option>
        <option value="Brussel Sprouts">Brussel Sprouts</option>
        <option value="Celery">Celery</option>
        <option value="Cabbage">Cabbage</option>
        </select>
     <br><br>
     <button type="submit" accesskey="s" name="submit"><u>S</u>ubmit</button>
</form>
        <hr>
    </body>
</html>

<?php 
if (isset($_POST['submit'])){
     $my_location = $_REQUEST['location']; 
     $fname = $_REQUEST['fname'];
     $mname= $_REQUEST['mname'];
     $lname = $_REQUEST['lname']; 
     $dob = $_REQUEST['bday'];
     $phone = $_REQUEST['phone'];
     $myVeg = $_REQUEST['veggie'];
     date_default_timezone_set("America/New_York");
     echo "<br>";
     echo "<b>FORM SUBMISSION AT </b>",  date("h:i:sa"), " on ", date("Y/m/d");
     echo "<br><br>";
     echo "<u>Full Name:</u> ";
     echo  $fname, " ", $mname, " ", $lname;
     echo "<br>";
     echo "<u>Date of Birth:</u> ", $dob;
     echo "<br>";
     echo  "<u>Phone number:</u> ",$phone;
     echo "<br>";
     echo "<u>Most Hated Vegetable:</u> ", $myVeg, "<br>";
    
}

?>