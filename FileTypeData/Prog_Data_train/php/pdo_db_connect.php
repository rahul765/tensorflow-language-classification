<?php
/*
 * this file contains all methods to access the gamehoarder db
 */
//session_start();
if(isset($_GET['insertuser']) && isset($_GET['insertgame'])) {
    $conn = func_connect_db("gamehoarder");
    $user_record['name'] = $_GET['insertuser'];
    $user_record['game'] = $_GET['insertgame'];
    $user_record['date'] = date('Y-m-d');
    func_insert_game_user($conn, $user_record);
}

/*
* Code for deleting an entry from game-user relation.
*/
if(isset($_GET['deleteuser']) && isset($_GET['deletegame'])) {
    $conn = func_connect_db("gamehoarder");
    $user_record['name'] = $_GET['deleteuser'];
    $user_record['game'] = $_GET['deletegame'];
    func_delete_game_user($conn, $user_record);
    echo $user_record['game'];
}

/**
 * helper function to add end date for a game
 */
function func_update_game_enddate($conn,$username, $game) {
    // assoc array passed as input
    if ($conn) {
        try {
            $enddate = date('Y-m-d');
            $s = $conn->prepare("UPDATE OwnsGames SET enddate = '$enddate' WHERE username = '$username' AND game = '$game'");
            $s->execute();
        } catch (PDOException $e) {
            echo "Could not insert to DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
}
/**
 * helper function to add start date for a game
 */
function func_update_game_startdate($conn,$username, $game) {
    // assoc array passed as input
    if ($conn) {
        try {
            $startdate = date('Y-m-d');
            $s = $conn->prepare("UPDATE OwnsGames SET startdate = '$startdate' WHERE username = '$username' AND game = '$game'");
            $s->execute();
        } catch (PDOException $e) {
            echo "Could not insert to DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
}

/**
 * Function to insert entry into game-user relation
 *
 * @param
 * $conn - PHP connection Object for current DB
 * $user_record - user record containing the username and game of the entry to insert
 */
function func_insert_game_user($conn, $user_record) {
    // assoc array passed as input
    if ($conn) {
        try {
            $s = $conn->prepare("INSERT INTO OwnsGames (username, game, adddate) value (:name, :game, :date)");
            $s->execute($user_record);
            $username = $user_record['name'];
            $currdate = $user_record['date'];
            $game = $user_record['game'];
            $s1 = $conn->prepare("INSERT INTO UserHistory (eventtime, username, game, action, date) value (NOW(), '$username', '$game', 0, '$currdate')");
            $s1->execute();
        } catch (PDOException $e) {
            echo "Could not insert to DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
}

/*
* Function to insert entry into game-user relation
*
* @param
* $conn - PHP Connection Object for current DB
* $user_record - user record containing the username and game of the entry to delete
*/
function func_delete_game_user($conn, $user_record) {
    if ($conn) {
        try {
            $stmt = $conn->prepare("DELETE FROM OwnsGames WHERE username=:name AND game=:game");
            $stmt->execute($user_record);
            $username = $user_record['name'];
            $currdate = date('Y-m-d');
            $game = $user_record['game'];
            // log delete event in UserHistory
            $s1 = $conn->prepare("INSERT INTO UserHistory (eventtime, username, game, action, date) value (NOW(), '$username', '$game', 4, '$currdate')");
            $s1->execute();
        } catch (PDOException $e) {
            echo "Could not delete record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
}

/**
 * this is a helper fucntion that returns the top most trending
 * games, based on number of users who have the game in their repo
 */
function func_getTopGamesByUsers($conn, $username, $count) {
    // assoc array passed as input
    $result=NULL;
    if ($conn) {
        try {
            // get top $count most trending games. Trend by user count
            $stmt = $conn->prepare("select game, count(*) as usercount from OwnsGames where game not in (select game from OwnsGames where username = '$username') group by game order by usercount desc limit $count");
            $stmt->execute();
            $result=$stmt->fetchAll();
            $count=0;
            foreach($result as $row) {
                $op[$count]['name'] = $row[0];
                $op[$count]['count'] = $row[1];
                $count+=1;
            }
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    return $op;

}

function func_getRecommendations($conn, $username, $count) {
    // assoc array passed as input
    $result=NULL;
    if ($conn) {
        try {
            // 
            $stmt = $conn->prepare("SELECT Pop.name as game, (Gen.gencount + Pop.usercount) as score
                FROM
                (SELECT Games.genre as genre, COUNT(Games.name) as gencount
                FROM OwnsGames, Games
                WHERE OwnsGames.username = '$username' AND OwnsGames.game = Games.name
                GROUP BY genre) as Gen,

                (SELECT OwnsGames.game as name, Games.genre as genre, COUNT(*) as usercount
                FROM OwnsGames, Games
                WHERE OwnsGames.game = Games.name
                GROUP BY OwnsGames.game) as Pop
                
                WHERE Gen.genre = Pop.genre AND Pop.name NOT IN (select OwnsGames.game FROM OwnsGames where OwnsGames.username = '$username')
                ORDER BY score desc
                LIMIT $count");
            $stmt->execute();
            $result=$stmt->fetchAll();
            $count=0;
            foreach($result as $row) {
                $op[$count]['name'] = $row['game'];
                $op[$count]['score'] = $row['score'];
                $count+=1;
            }
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    return $op;

}

/**
 * this is a generic function that returns games by its current status.
 * status can be inrepo(0), started(1), finished(2)
 */
function func_getGamesByStatus($conn, $username, $status) {
    // assoc array passed as input
    $result=NULL;
    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT game FROM OwnsGames WHERE username='$username' AND status='$status'");
            $stmt->execute();
            $result=$stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    return $result;

}

/**
 * This is a helper fucntion to get user history
 */
function func_getUserHistory($conn, $username) {
    // assoc array passed as input
    $result=NULL;
    if ($conn) {
        try {
            // get top $count most trending games. Trend by user count
            $stmt = $conn->prepare("select * from UserHistory where username = '$username' order by eventtime");
            $stmt->execute();
            $result=$stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    return $result;

}
/**
 * this is a helper function which returns
 * all the date specific stats about games
 * in a user's repo
 * code snppet to access the data on the other side

 * foreach ($game_list as $row) {
 *     echo $row['game']. $row['adddate']. "<br>";
 * }
 */
function func_getGamesDateStats($conn, $username) {
    $result=NULL;
    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT game, adddate, startdate, enddate FROM OwnsGames WHERE username='$username'");
            $stmt->execute();
            $result=$stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    return $result;

}
/*
* Function to fetch all games belonging to a specific user
*
* @param
* $conn - PHP Data Object for current DB
* $username - username of user whose games to fetch
*
* @return
* $result - array of all games belonging to a specific user
*/
function func_getGamesUser($conn, $username) {
    // assoc array passed as input
    $result=NULL;
    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT game FROM OwnsGames WHERE username='$username'");
            $stmt->execute();
            $result=$stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    return $result;
}

/**
 * Function to connect to a database
 *
 * @param
 * $db - name of database to connect to
 * @return
 * $conn - PDO associated with database
 */
function func_connect_db($db) {
    try {
		// set host, dbname, based on given input
        if ($db == "gamehoar_games") {
            $host   = "engr-cpanel-mysql.engr.illinois.edu";
            $user = "gamehoar_db";
            $pass = "gamehoarder411";
        } else {
            $user = "root";
            $pass = "root";
            $host = "localhost";
        }
		
		// user with access / modify privileges to database
        $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        ///echo "connected to db<br>";
    } catch (PDOException $e) {
        echo "Could not connect to DB.\n";
        echo "getMessage(): " . $e->getMessage () . "\n";
        $conn = NULL;
    }
    return $conn;
}

/*
* Function to check if a specific user exists
*
* @param
* $conn - PHP Connection Object for current DB
* $username - username that needs to be checked in database
*
* @return
* $ret - true if user exists, false if user doesn't exist
*/
function func_is_user_exists($conn, $username) {
    if ($conn == NULL) {
        $ret = false;
        goto end;
    }
    try {
        $stmt = $conn->prepare('SELECT password FROM Users WHERE username = :uname');
        $stmt->execute(array('uname' => $username));
        $result = $stmt->fetchAll();
        if (count($result)) {
            $ret = true;
        } else {
        $ret = false;
        }
    } catch (PDOException $e) {
        echo "Could not connect to DB.\n";
        echo "getMessage(): " . $e->getMessage () . "\n";
        $conn = NULL;
    }
end:
    return $ret;
}

function func_insert_new_user($conn, $user_record) {
    // assoc array passed as input
    if ($conn) {
        try {
            $s = $conn->prepare("INSERT INTO Users (username, password, email) value (:name, :pass, :email)");
            $s->execute($user_record);
            $username = $user_record['name'];
            $currdate = date('Y-m-d');
            $s1 = $conn->prepare("INSERT INTO UserHistory (eventtime, username, action, date) value (NOW(), '$username', 3, '$currdate')");
            $s1->execute();
        } catch (PDOException $e) {
            echo "Could not insert to DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
}

//TBD: pass by reference
function func_closeDbConection($conn) {
    if ($conn != NULL) {
       $conn = NULL; 
    } else {
        echo "conn handle is NULL";
    }
}

/*
* Function to return a specific user's name, email, and password
*
* @param
* $conn - PHP Connection Object for current DB
* $username - username whose info the function should return
*
* @return
* $op - user's name, email, and password
*/
function func_getUserCredential($conn, $username) {
    $op=NULL;
    if ($conn == NULL) {
        return NULL;
    } else {
        try {
            // uname is a primary key, so atmost one row expected
            $stmt = $conn->prepare("SELECT * FROM Users WHERE username=:name");
            $stmt->execute(array('name' => $username));
            $result = $stmt->fetchAll();
            foreach($result as $row) {
                $op['name'] = $row[0];
                $op['passwd'] = $row[1];
                $op['email'] = $row[2];
            }
        } catch (PDOException $e) {
            echo "Could not connect to DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
            $op = NULL;
        }
        return $op;
    }
}

/*
* Function to return a change user's password
*
* @param
* $conn - PHP Data Object for current DB
* $username - username whose password needs to be changes
* $newPassword - password to change to
*/
function func_changePassword($conn, $username, $newPassword) {
    if ($conn == NULL) {
        //$op = NULL;
        return NULL;    
    } else {
        try {
            $stmt = $conn->prepare("UPDATE Users SET password='$newPassword' WHERE username='$username'");
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Could not change password.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
            $op = NULL;
        }
    }
}

function func_getGames($conn, $search) {
    $op=NULL;
    if ($conn == NULL) {
        return NULL;
    } else {
        try {
            // uname is a primary key, so atmost one row expected
            $stmt = $conn->prepare("SELECT * FROM Games WHERE name LIKE ?");
            $like="%$search%";
            $stmt->execute(array($like));
            $result = $stmt->fetchAll();
            $count=0;
            foreach($result as $row) {
                $op[$count]['name'] = $row[0];
                $op[$count]['rating'] = $row[1];
                $op[$count]['genre'] = $row[2];
                $op[$count]['year'] = $row[3];
                $count+=1;
            }
        } catch (PDOException $e) {
            echo "Could not connect to DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
            $op = NULL;
        }
        return $op;
    }
}

function func_getGamesByDev($conn, $search) {
    //returns an assoc array of username password and email
    //echo "get dev called for". $search;
    $op=NULL;
    if ($conn == NULL) {
        //$op = NULL;
        return NULL;
    } else {
        try {
            // uname is a primary key, so atmost one row expected
            $stmt = $conn->prepare("select Games.name, Games.rating, Games.genre, Games.year from Develops,Games where Games.name = Develops.game and Develops.developer like ?");
            $like="$search%";
            $stmt->execute(array($like));
            $result = $stmt->fetchAll();
            $count=0;
            foreach($result as $row) {
                $op[$count]['name'] = $row[0];
                $op[$count]['rating'] = $row[1];
                $op[$count]['genre'] = $row[2];
                $op[$count]['year'] = $row[3];
                $count+=1;
            }
        } catch (PDOException $e) {
            echo "Could not connect to DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
            $op = NULL;
        }
        return $op;
    }
}

function func_getGameImage($conn, $search) {
    //returns the url of the cover art image for the given game
    //returns default thumbnail if image could not be retrieved
    if ($conn == NULL) {
        return "images/thumb.jpg";
    } else {
        try {
            //echo "SELECT wiki_img FROM GamesMeta WHERE game = '$search'";
            //return "images/thumb.jpg";
            $search = addslashes($search);
            $stmt = $conn->prepare("SELECT wiki_img FROM GamesMeta WHERE game = '$search'");
            $stmt->execute();
            $result = $stmt->fetchAll();
            if (sizeof($result) > 0) {
                if (!($result[0][0] == "null")) {
                    return $result[0][0];
                } else {
                    return "images/thumb.jpg";
                }
            } else {
                return "images/thumb.jpg";
            }
        } catch (PDOException $e) {
            echo "Could not connect to DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
        return "images/thumb.jpg";
    }
}

/*
* Helper method for populating DB
* This prepare statement, once created, 
* can be used multiple times
*/
function func_prepareGamesPDO($conn, $flag) {
    if (strcmp($flag, "INSERT") == 0) {
        echo "prep";
        $s = $conn->prepare("INSERT INTO Games (name, rating, genre, year) value (:NAME, :RATING, :GENRE, :YEAR)");
    } else if (strcmp($flag, "UPDATE") == 0) {
    }
    return $s; 
}
/*
* Helper method for populating DB
*/
function func_populate($conn, $pdo, $arr) {
    if ($conn == NULL || $arr == NULL || $pdo == NULL) return;
    else {
        try {
            /*foreach ($arr as $key => $val) {
                echo "<br>" . $key . "=>" . $val . "</br>";
            }*/
            $pdo->execute($arr);
        } catch (PDOException $e) {
            echo "Could not insert to GamesDB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
}

/*
* Helper method for populating DB
* This prepare statement, once created, 
* can be used multiple times
*/
function func_prepareDeveloperPDO($conn, $flag) {
    if (strcmp($flag, "INSERT") == 0) {
        echo "prep";
        $s = $conn->prepare("INSERT INTO Developers (name, country) value (:NAME, :COUNTRY)");
    } else if (strcmp($flag, "UPDATE") == 0) {
    }
    return $s; 
}
/*
* Helper method for populating DB
* This prepare statement, once created, 
* can be used multiple times
*/
function func_prepareDevelopsPDO($conn, $flag) {
    if (strcmp($flag, "INSERT") == 0) {
        echo "prep";
        $s = $conn->prepare("INSERT INTO Develops (developer, game) value (:DEV, :GAME)");
    } else if (strcmp($flag, "UPDATE") == 0) {
    }
    return $s; 
}

/*
* Helper method for populating DB
* This prepare statement, once created, 
* can be used multiple times
*/
function func_preparePublisherPDO($conn, $flag) {
    if (strcmp($flag, "INSERT") == 0) {
        echo "prep";
        $s = $conn->prepare("INSERT INTO Publishers (name, country) value (:NAME, :COUNTRY)");
    } else if (strcmp($flag, "UPDATE") == 0) {
    }
    return $s; 
}
/*
* Helper method for populating DB
* This prepare statement, once created, 
* can be used multiple times
*/
function func_preparePublishesPDO($conn, $flag) {
    if (strcmp($flag, "INSERT") == 0) {
        echo "prep";
        $s = $conn->prepare("INSERT INTO Publishes (publisher, game) value (:PUB, :GAME)");
    } else if (strcmp($flag, "UPDATE") == 0) {
    }
    return $s; 
}
?>