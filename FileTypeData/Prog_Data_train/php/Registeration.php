<?php
include("dbConfig.php");
include("GCM.php");
$obj = new genFunction(true);
$arr = $obj->getRestArray();
$return_arr = array();
$emailExist = false;


if (strlen(trim($arr['email'])) == 0) {
    $return_arr['status'] = "false";
    $return_arr['code'] = "P006";
    $return_arr['msg'] = "Please enter email.registratiion fail";
} else {
    $result = mysql_query("select * from users where email='" . trim($arr['email']) . "'", $con) or die(mysql_error());


    $userID = "-1";
    if (mysql_num_rows($result) > 0) {
        $emailExist = true;


        while ($row_name = mysql_fetch_array($result)) {

            $userID = $row_name['id'];
            break;
        }
        $user_result = mysql_query("select  user_id from users_friends_mappings where user_id= '$userID'", $con) or die(mysql_error());
        //  echo "update users set deviceid='".$arr['deviceid']."' where email='".$arr['email']."'";
        //  exit;
        $user_regid = mysql_query("update users set deviceid='" . $arr['deviceid'] . "' where email='" . $arr['email'] . "'") or die(mysql_error());
        if (mysql_num_rows($user_result) == 0) {
            $url = "https://graph.facebook.com/" . $arr['fb_id'] . "/?fields=friends.limit(1000000)&access_token=" . $arr['Access_token'];


            $result1 = file_get_contents($url);


            $data = json_decode($result1, true);


            $friends = $data['friends']['data'];

            for ($i = 0; $i < count($friends); $i++) {
                mysql_query("insert into users_friends_mappings
				 (user_id,friend_id,is_follow)
				SELECT '$userID',ID,'0' from users where fb_id='" . $friends[$i]['id'] . "'") or die(mysql_error());
            }
        }

        $result = mysql_query("select * from users where email='" . trim($arr['email']) . "'", $con) or die(mysql_error());


    } else {

//$obj->uploadImage("profile_photo");
//$arr=$obj->getRestArray();

        $emailExist = false;
        mysql_query("INSERT INTO users(user_group_id,fb_id, username, email,first_name,last_name,phone_no
,deviceid,deviceType,address)
 VALUES(2,'" . $arr['fb_id'] . "','" . $arr['username'] . "','"
            . trim($arr['email']) . "','" . $arr['first_name'] . "','" . $arr['last_name'] . "','" . $arr['phone_no'] . "','" . $arr['deviceid'] . "','" . $arr['deviceType'] . "','" . $arr['address'] . "')", $con)
        or die(mysql_error());

        $lastUserID = mysql_insert_id();

# An HTTP GET request example
        $url = "https://graph.facebook.com/" . $arr['fb_id'] . "/?fields=friends.limit(1000000)&access_token=" . $arr['Access_token'];
        $result = file_get_contents($url);
        /*ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec ($ch);
        curl_close ($ch); */

        $data = json_decode($result, true);


        $friends = $data['friends']['data'];

        for ($i = 0; $i < count($friends); $i++) {
            mysql_query("insert into users_friends_mappings
				 (user_id,friend_id,is_follow) 
				SELECT '" . $lastUserID . "',ID,'0' from users where fb_id='" . $friends[$i]['id'] . "'") or die(mysql_error());

        }

        for ($i = 0; $i < count($friends); $i++) {

            mysql_query("insert into users_friends_mappings
				 (friend_id,user_id,is_follow) 
				SELECT '" . $lastUserID . "',ID,'0' from users where fb_id='" . $friends[$i]['id'] . "'") or die(mysql_error());
        }


        $regIds = getFriendIDs();


        if ($regIds != "") {

            $post = getUser($lastUserID);
            $userName = $post['username'];

            $msg = "your facebook friend " . $userName . " started using FoodOrder App.";

            $gcmData["key"] = "user";
            $gcmData["message"] = $msg;

            sendNotification($regIds, $gcmData);
        }

        $return_arr = array();
        $row_array = array();
        $status = "";
        $code = "";
        $msg = "";
        $result = mysql_query("select * from users where id=" . $lastUserID);

    }


    if (mysql_num_rows($result) > 0) {


        while ($row = mysql_fetch_array($result)) {
            $row_array['id'] = $row['id'];
            $ro_Count = mysql_query("select COUNT(s.user_id) AS CountRecord FROM suggestions s where user_id=" . $row['id']);
            if (mysql_num_rows($ro_Count) > 0) {
                while ($rows = mysql_fetch_array($ro_Count)) {
                    $row_array['user_suggested'] = $rows['CountRecord'];
                }
            }
            $row_array['fb_id'] = $row['fb_id'];
            $row_array['username'] = $row['username'];
            $row_array['email'] = $row['email'];
            $row_array['first_name'] = $row['first_name'];
            $row_array['last_name'] = $row['last_name'];
            $row_array['phone_no'] = $row['phone_no'];
            $row_array['email_verified'] = $row['email_verified'];
            $row_array['active'] = $row['active'];
            $row_array['privacy'] = $row['privacy'];
            $row_array['deviceType'] = $row['deviceType'];
            $row_array['address'] = $row['address'];

            $totalFollowingCount = mysql_query("select count(u.id) as 'totalFollowingCount' from `users_friends_mappings`  um
								inner join users u on (u.id=um.friend_id)
								where user_id=" . $row['id'] . " and is_follow=true");
            $totalFollowing = "";
            while ($row3 = mysql_fetch_array($totalFollowingCount)) {
                $totalFollowing = $row3['totalFollowingCount'];
            }

            $totalFollowersCount = mysql_query("select count(u.id) as 'totalFollowerCount' from `users_friends_mappings` um
								inner join users u on (u.id=um.user_id)
								where friend_id=" . $row['id'] . " and is_follow=true");


            $totalFollower = "";
            while ($row3 = mysql_fetch_array($totalFollowersCount)) {
                $totalFollower = $row3['totalFollowerCount'];
            }


            $row_array['totalFollowingCount'] = $totalFollowing;
            $row_array['totalFollowerCount'] = $totalFollower;

            if ($emailExist == false) {
                $return_arr['status'] = "true";
                $return_arr['code'] = "P001";
                $return_arr['msg'] = "Registration Success";
            } else {
                $return_arr['status'] = "false";
                $return_arr['code'] = "P002";
                $return_arr['msg'] = "Already register with this email address.";
            }
        }
    } else {
        $return_arr['status'] = "false";
        $return_arr['code'] = "P006";
        $return_arr['msg'] = "Registration Fail";
    }
}
$return_arr['Post'][] = $row_array;
echo json_encode($return_arr);


function getUser($userId)
{
    $queryToUser = "Select * from users where id = '" . $userId . "'";
    $resToUser = mysql_query($queryToUser) or $errorMsg = mysql_error();

    if (mysql_num_rows($resToUser)) {
        while ($post = mysql_fetch_assoc($resToUser)) {
            return $post;
        }
    }
}


function getFriendIDs($userId)
{


    //$userId = 61;

    $queryToUser = "Select friend_id from users_friends_mappings where user_id = '" . $userId . "'";
    $resToUser = mysql_query($queryToUser) or $errorMsg = mysql_error();


    $friendIDs = array();

    if (mysql_num_rows($resToUser)) {
        while ($post = mysql_fetch_assoc($resToUser)) {
            $friendIDs[] = $post['friend_id'];
        }
    }


    $ids = implode(",", $friendIDs);


    $query = "Select deviceid from users where id IN  (" . $ids . ")";
    $res = mysql_query($query) or $errorMsg = mysql_error();


    $deveIdsArr = array();

    if (mysql_num_rows($res)) {
        while ($post = mysql_fetch_assoc($res)) {
            $deveIdsArr[] = $post['deviceid'];
        }
    }

    $deviceIds = implode(",", $deveIdsArr);

    return $deviceIds;

}


function sendNotification($regId, $msg)
{
    $gcm = new GCM();

    $registatoin_ids = array($regId);
    $message = array("message" => $msg);

    $gcm->send_notification($registatoin_ids, $message);
}


?>

<?php

class genFunction
{
    private $getRestAPI = array();

    function __construct($Method = false) // $Method must be true if using post
    {
        if ($Method === false) {

            if (isset($_GET) && is_array($_GET)) {
                foreach ($_GET as $key => $val) {
                    $this->getRestAPI[$key] = $val;
                }
            }
        } else {
            if (isset($_POST) && is_array($_POST)) {
                foreach ($_POST as $key => $val) {
                    $this->getRestAPI[$key] = $val;
                }
            }
        }
    }

    public function getRestArray()
    {
        return $this->getRestAPI;
    }

    function uploadImage($KeyName)
    {
        if (empty($this->getRestAPI[$KeyName]) == false) {
            $imageKeyName = $this->getRestAPI[$KeyName];
            $ext = "png";
            $image = base64_decode($imageKeyName);
            $pic = md5(microtime()) . "." . $ext;
            $img = imagecreatefromstring($image);
            $img_path = "./usersProfile/" . $pic;
            if ($img !== false) {
                imagepng($img, $img_path);
            }
            $this->getRestAPI[$KeyName] = $pic;
        } else {
            $pic = "";
            $this->getRestAPI[$KeyName] = $pic;
        }
    }
}

?>