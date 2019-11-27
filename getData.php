<?php

// clear any sessions ...
session_start();
// remove all session variables
session_unset();
// destroy the session
session_destroy();

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

		// get coordinates from Table pin
		$sql_select = 'SELECT * FROM pin';

		// the result set
		$result = $file_db->query($sql_select);
		if (!$result) die("Cannot execute query.");

//START OF COPIED Code

		// MAKE AN ARRAY::
		$res = array();
		$i=0;
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
      $res[$i] = $row;
       $i++;

			// go through first and second column (so lat and long)
			// retrieve key entry pairs
			 foreach ($row as $key=>$entry)
			 {
				//if the key is latitude
				 if($key=="latitude")
				 {
					 // store entry as $lat
						 $lat=$entry;
						// $res[$i] = $lat;
						// $i++;
						 // echo("<h2>".$lat."</h2>");
				 }
				 else if ($key=="longitude"){
					 //store entry as $long
						 $long=$entry;
						// $res[$i] = $long;
					//	 $i++;
						 // echo("<h2>".$long."</h2>");
				 }


			}

		}//end while

		// endcode the resulting array as JSON
		$myJSONObj = json_encode($res);
		echo $myJSONObj;

		exit;

//END OF COPIED CODE

		// // get a row -- so each entry unit
		// while($row = $result->fetch(PDO::FETCH_ASSOC))
		// {
		// 	 // go through first and second column (so lat and long)
		// 	 // retrieve key entry pairs
		// 	  foreach ($row as $key=>$entry)
		// 	  {
		// 		 //if the key is latitude
		// 			if($key=="latitude")
		// 			{
		// 				// store entry as $lat
		// 					$lat=$entry;
		// 					// echo("<h2>".$lat."</h2>");
		// 			}
		// 			else if ($key=="longitude"){
		// 				//store entry as $long
		// 					$long=$entry;
		// 					// echo("<h2>".$long."</h2>");
		// 			}
		//
		// 	 }
		// }//end while


	}
	catch(PDOException $e) {
		// Print PDOException message
		echo $e->getMessage();
	}

?>
