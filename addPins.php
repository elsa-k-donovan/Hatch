<?php

//check if there has been something posted to the server to be processed
if($_SERVER['REQUEST_METHOD'] == 'POST')
{

 $latitude = $_POST['a_lat'];
 $longitude = $_POST['a_long'];

 $namePin = $_POST['a_name'];
 $streetName = $_POST['a_street'];
 $destination = $_POST['a_destination'];
 $author = $_POST['a_author'];
 $description = $_POST['a_description'];
 $append = $_POST['a_append'];

  /*PROBLEM CHILD HERE*/
 $fname = $_FILES['filename']['name'];

  move_uploaded_file($_FILES['filename']['tmp_name'], "image/".$fname);




 //$imagePath = $_POST['a_image'];

//COPIED FROM EXAMPLES
// $fname = $_FILES['filename']['name'];


//WHATS THE PURPOSE OF THIS IF FILES STATEMENT
 //if($_FILES)
  //{

      //$fname = $_FILES['filename']['name'];
    //  move_uploaded_file($_FILES['filename']['tmp_name'], "images/".$fname);
      // the file name with correct path

   try {
       /**************************************
       * Create databases and                *
       * open connections                    *
       **************************************/

       // Create (connect to) SQLite database in file
       $file_db = new PDO('sqlite:db/pins.db');

       // Set errormode to exceptions
       /* .. */
       $file_db->setAttribute(PDO::ATTR_ERRMODE,
                               PDO::ERRMODE_EXCEPTION);

        /*  $user_es =$file_db->quote($user);
        $password_es = $file_db->quote($password);*/

        $namePin_es = $file_db->quote($namePin);
        $streetName_es = $file_db->quote($streetName);
        $destination_es = $file_db->quote($destination);
        $author_es = $file_db->quote($author);
        $description_es = $file_db->quote($description);
        $latitude_es = $file_db->quote($latitude);
        $longitude_es = $file_db->quote($longitude);
        $append_es = $file_db->quote($append);

        /*PROBLEM CHILD HERE*/
        $imagePath_es= "image/".$fname;

        //DO THIS INSTEAD WITH LATITUDE AND LONGITUDE
        $sql_select="SELECT COUNT (*) FROM pin WHERE name = $namePin_es";

        $result = $file_db->query($sql_select);
        $var1 = $result->fetch(PDO::FETCH_NUM);

        if (intval($var1[0]) > 0){
          echo("That Pin Name is already in use. Please choose another.");
        }
        else{

        // Attribute Names in pin TABLE
        // name TEXT, streetName TEXT, destination TEXT, author TEXT, image TEXT, description TEXT, append TEXT
        // $namePin_es, $streetName_es, $destination_es, $author_es, $imagePath_es, $description_es, $append_es
        $queryInsert = "INSERT INTO pin(image, latitude, longitude, name, streetName, destination, author, description, append) VALUES ('$imagePath_es', $latitude_es, $longitude_es, $namePin_es, $streetName_es, $destination_es, $author_es, $description_es, $append_es)";
        $file_db->exec($queryInsert);
        echo("Valid Entry.");
        }

     }
     catch(PDOException $e) {
       // Print PDOException message
       echo $e->getMessage();
     }

     exit;


  // } //FILES
 }//POST
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Pin</title>
<!-- get JQUERY -->
  <script src = "jquery/jquery-3.4.1.js"></script>
<!--set some style properties::: -->
<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>

<div class= "formContainer">
<!--form done using more current tags... -->
<form id="insertDatabase" action="" enctype ="multipart/form-data">

<fieldset>
  <button onclick="location.href='map_mobile.php';" id = "x"><p>✖</p></button>

<h5 id="result"> </h5>
<h6 id = "log"> </h6>
<h3>Add Pin</h3>

<p id = "latLabel"> </p>
<p id = "lngLabel"> </p>

<!--accept="image/*" capture="camera" -->

<!-- <p><label> </label><input type = "text" name = "a_lat"  disabled></p>
<p><label> </label><input type = "text" name = "a_long" disabled></p> -->

<!-- capture = "camera" -->

<p><label>Camera</label><input type="file" capture="camera" name="filename" />
<p><label> </label><input type = "text" placeholder="Pin Name" name = "a_name" required></p>
<p><label> </label><input type=  "text"  placeholder="Street Intersection" name = "a_street" required></p>
<p><label> </label><input type = "text" placeholder="Desired Destination" name = "a_destination" required></p>

<!-- <select name="cars">
  <option value="volvo">Volvo</option>
  <option value="saab">Saab</option>
  <option value="fiat">Fiat</option>
  <option value="audi">Audi</option>
</select> -->

<p><label> </label><input type = "text" placeholder="Your Name" name = "a_author" required></p>
<p><label> </label><input type = "text" placeholder="Description" name = "a_description" required></p>
<p> Appendable? </p>
<p><label>Yes</label><input value="yes" type = "radio" placeholder="" size="24" maxlength = "40" name = "a_append" required></p>
<p><label>No</label><input value="no" type = "radio" placeholder="" size="24" maxlength = "40" name = "a_append" required></p>



<!--<p><label>Upload Image:</label> <input type ="file" name = 'filename' size=10 required/></p> -->

<!--
$namePin = $_POST['a_name'];
$streetName = $_POST['a_street'];
$destination = $_POST['a_destination'];
$author = $_POST['a_author'];
$description = $_POST['a_description'];
$append = $_POST['a_append'];

$imagePath = $_POST['filename'];
-->

<!-- <div id="result"> </div> -->
<a href = "viewPins.php"> View Pins</a>

<p class = "sub"><input type = "submit" name = "submit" value = "Submit" id ="buttonS" /></p>

 </fieldset>

 </form>

</div> <!--formContainer-->


<!-- <div id="result"></div>
<div id = "view">
<p> <a href = "viewUsersEx.php"> View Users</a> </p>
</div> -->
<script>

// here we put our JQUERY
$(document).ready (function(){


  console.log(localStorage.getItem('currentMarkerLat'));
  console.log(localStorage.getItem('currentMarkerLong'));

  document.getElementById('latLabel').innerHTML = '<input type = "text" name="a_lat" value = "'+ localStorage.getItem('currentMarkerLat') +'" readonly>';
  document.getElementById('lngLabel').innerHTML = '<input type = "text" name="a_long" value = "'+ localStorage.getItem('currentMarkerLong') +'" readonly>';



    $("#insertDatabase").submit(function(event) {
       //stop submit the form, we will post it manually. PREVENT THE DEFAULT behaviour ...
      event.preventDefault();
     console.log("button clicked");
     $("#log").text("Button clicked.");
     let form = $('#insertDatabase')[0];
     let data = new FormData(form);
    // Display the key/value pairs
    // to access the data in the formData Object ... (not this is ALL TEXT ... )
    // as key -value pairs
    //Object.entries() method in JavaScript returns an array consisting of
    //enumerable property [key, value] pairs of the object.


  //  for (let valuePairs of data.entries()) {
  //  console.log(valuePairs[0]+ ', ' + valuePairs[1]);
  //  }

    $.ajax({
              type: "POST",
              enctype: 'multipart/form-data',
              url: "addPins.php",
              data: data,
              processData: false,//prevents from converting into a query string
              contentType: false,
              cache: false,
              timeout: 600000,
              success: function (response) {
              //reponse is a STRING (not a JavaScript object -> so we need to convert)
              console.log("we had success!");
              console.log(response);
              //use the JSON .parse function to convert the JSON string into a Javascript object
              //let parsedJSON = JSON.parse(response);
              //console.log(parsedJSON);
              //displayResponse(parsedJSON);
               $("#result").text(response);

               //location.reload();

             },
             error:function(){
            console.log("error occurred");
          }
        });

   });

});

</script>
</body>
</html>
