<?php
try {
    /******************************************
    * Create databases and  open connections*
    ******************************************/

    // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:db/pins.db');

    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE,
                            PDO::ERRMODE_EXCEPTION);

    echo("Opened or connected to Pins database <br />");


    //Attributes: latitude DECIMAL, longitude DECIMAL, Name, StreetName, DesiredDestination, Author, Photo, Description, bool appendable
    //geoloc FLOAT

    //create the table userAccounts
    $theQuery = 'CREATE TABLE pin (pieceID INTEGER PRIMARY KEY NOT NULL, image TEXT, latitude DECIMAL, longitude DECIMAL, name TEXT, streetName TEXT, destination TEXT, author TEXT, description TEXT, append TEXT)';
    //name, streetName, destination, author, description

        $file_db ->exec($theQuery);   //exec runs a query

    // if everything executed error less we will arrive at this statement
        echo ("Table pins created successfully <br \>");

          // Close file db connection
           $file_db = null;
   }
catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
  }


  ?>
