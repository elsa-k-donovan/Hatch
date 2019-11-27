<?php

// put required html mark up
echo"<html>\n";
echo"<head>\n";
echo"<title> View Pin Info </title> \n";
//include CSS Style Sheet
echo "<link rel='stylesheet' type='text/css' href='css/login.css'>";
echo"</head>\n";
// start the body ...
echo"<body>\n";
// place body content here ...

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

    //echo("Opened");
     $sql_select='SELECT * FROM pin';
    //  $sql_select = "SELECT * FROM artCollection WHERE creationDate >=Date('2002-01-01')";
    //  $sql_select_B = "SELECT * FROM artCollection WHERE creationDate >=Date('2002-01-01') AND artist = 'Sarah'";
    //  $sql_selectCol = "SELECT pieceID, title, creationDate FROM artCollection";
    //  $sql_selectColDis = "SELECT DISTINCT artist FROM artCollection";
    //  $sql_orderBy = "SELECT creationDate, artist FROM artCollection WHERE artist = 'Harold' OR artist = 'Sarah' ORDER BY creationDate";

     $c1 =  "SELECT COUNT(*) FROM pin";
    // $c2 = "SELECT COUNT(*) FROM artCollection WHERE creationDate >=Date('2000-01-01')";
    // $c3 = "SELECT artist, COUNT(*) FROM artCollection GROUP BY artist";
    // $c4 = "SELECT artist, geoLoc, COUNT(*) FROM artCollection GROUP BY artist, geoLoc";


      // the result set
       $result = $file_db->query($sql_select);
       if (!$result) die("Cannot execute query.");

      // fetch first row ONLY...
       //$row = $result->fetch(PDO::FETCH_ASSOC);
       //var_dump($row);

        //echo "<h3> User Results:::</h3>";
        echo "<div id='back'>";


        // let img = $("<img>");
        // $(img).attr('src',currentObject["image"]);
        // $(img).appendTo(contentContainer);
        //  $(contentContainer).appendTo("#result");

        // get a row...
        while($row = $result->fetch(PDO::FETCH_ASSOC))
        {
           echo "<div class ='outer'>";
           echo "<div class ='content'>";
           // go through each column in this row
           // retrieve key entry pairs
           foreach ($row as $key=>$entry)
           {
             //if the column name is not 'image'
              if($key!="image")
              {
                // echo the key and entry
                  echo "<h4>".$key.": ".$entry."</h4>";
              }
              else {
                  echo "<img src=".$entry." />";
              }

           }
           echo "<br />";

          // put image in last
            echo "</div>";
            // access by key
            //$imagePath = $row["image"];
            //echo "<img src = $imagePath \>";
            echo "</div>";
        }//end while
        echo"</div>";


  }
  catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
  }


echo"</body>\n";
echo"</html>\n";

?>

<style>

img {
  height: 10%;
  width: 10%;
}
</style>
