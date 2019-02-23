<?php

    /**
    *Format (tab separated): 
    Name	Year	Genre	Developer_name	Publisher_name	Systems	Regions	Rating
    */
    $GAMENAME = 0;
    $YEAR = 1;
    $GENRE = 2;
    $DEV = 3;
    $PUB = 4;
    $SYS = 5;
    $REG = 6;
    $RATING = 7; 
    $TOTAL_COLS = 8; 
    echo"<h1>populate DB</h1>";
    // read file
    $file_handle = fopen("games.txt", "r");
    if ($file_handle) {
        require_once('pdo_db_connect.php');
        $conn = func_connect_db("gamehoarder");
        echo"file handle obtained";
        $flag = false;
        while(!feof($file_handle)) {
            $line = fgets($file_handle);
            //echo "<p>" . $line . "</p>";
            if (strpos($line,'START') !== false) {
                echo "found start";
                $flag = true;
                break;
            }
        }
        $count_nonum = 0;
        $invalid = 0;
        if ($flag) {
            $count = 0;
            // prepare your pdo here
            $pdo_Games = func_prepareGamesPDO($conn, "INSERT");
            $pdo_Developers = func_prepareDeveloperPDO($conn, "INSERT");
            $pdo_Develops = func_prepareDevelopsPDO($conn, "INSERT");
            while(!feof($file_handle)) {
                $line = fgets($file_handle);
                $assoc_line = explode("\t", $line);
                if (count($assoc_line) == $TOTAL_COLS) {
                    $input['NAME'] = $assoc_line[$GAMENAME];
                    $input['RATING'] = (empty($assoc_line[$RATING])) ? NULL : $assoc_line[$RATING];
                    $input['GENRE'] = (empty($assoc_line[$GENRE])) ? NULL : $assoc_line[$GENRE];
                    // insert rows with valid number of columns
                    if (is_numeric($assoc_line[$YEAR]) == false) {
                        $int = filter_var($assoc_line[1], FILTER_SANITIZE_NUMBER_INT);
                        //echo $assoc_line[1] . "----------";
                        //echo "year ". $int . "</br>";
                        //TBD: extract the first year
                        $input['YEAR'] = NULL;
                    } else {
                        $input['YEAR'] = $assoc_line[$YEAR];
                    }
                    func_populate($conn, $pdo_Games, $input);
                    
                    // populate developer
                    // TBD: country field
                    $dev['NAME'] = $assoc_line[$DEV];
                    $dev['COUNTRY'] = NULL;
                    echo $dev['NAME']. "<br>";
                    func_populate($conn, $pdo_Developers, $dev);

                    // populate Developes(developer, game) relation
                    $develops['DEV'] = $assoc_line[$DEV];
                    $develops['GAME'] = $assoc_line[$GAMENAME];
                    func_populate($conn, $pdo_Develops, $develops);
                }
            }
        }
    } else {
        echo("error");
    }
    fclose($file_handle);
    $conn = NULL;
?>
