<?php
/**
 * Created by ka
 * User: c86
 * Date: 19/11/14
 * Time: 09:59 AM
 */

function getCategory()
{
    $queryToUser = "Select * from categories_master";

    $resToUser = mysql_query($queryToUser) or $errorMsg = mysql_error();
    $status = 0;
    if (mysql_num_rows($resToUser)) {

        while ($post = mysql_fetch_assoc($resToUser)) {

            $Arrayssubcategory = Array();

            $querySubcategory = "Select * from sub_categories_master where fk_category_id= (select id from categories_master where id='" . $post['id'] . "')";

            $resTosubcategory = mysql_query($querySubcategory) or $errorMsg = mysql_error();

            while ($subcategoryItem = mysql_fetch_assoc($resTosubcategory)) {

                $Arrayssubcategory_item = Array();

                $querySubcategory_item = "Select * from subcategory_item_master as sub where `id`= (select id from sub_categories_master where id='" . $subcategoryItem['id'] . "')";

                $resTosubcategory_item = mysql_query($querySubcategory_item) or $errorMsg = mysql_error();
                while ($subcategoryItem_list = mysql_fetch_assoc($resTosubcategory_item)) {

                    $Arrayssubcategory_item[] = $subcategoryItem_list;
                    $Arrayssubcategory[] = $subcategoryItem;

                }
                $Arrayssubcategory['subcategory_item'] = $Arrayssubcategory_item;

            }
            $post['subcategory'] = $Arrayssubcategory;
            $posts[] = $post;
        }
        $status = 1;
    } else {
        $status = 0;
    }

    $data ['Result']['status'] = $status;
    $data['Result']['category'] = $posts;

    return $data;
}

function registerUser($userData)
{
    $fb_id = validateValue($userData['fb_id'], "");
    $email_id = validateValue($userData['email_id'], "");
    $first_name = validateValue($userData['FirstName'], "N/A");
    $last_name = validateValue($userData['LastName'], "N/A");
    $phone_no = validateValue($userData['phone_no'], "");
    $profile_photo = validateValue($userData['profile_photo'], "");
    $created_time = validateValue($userData['created_time'], "");
    $modification_time = validateValue($userData['modification_time'], "");
    $deviceid = validateValue($userData['deviceid'], "");
    $Visibility = validateValue($userData['Visibility'], "");
    $Followers = validateValue($userData['Followers'], "");
    $Followings = validateValue($userData['Followins'], "");
    $TotalFeeds = validateValue($userData['TotalFeeds'], "");

    if (isset($userData['profile_photo'])) {
        $imageData = base64_decode($userData['$profile_photo']);
        $source = imagecreatefromstring($imageData);
        $destdir = $_SERVER['DOCUMENT_ROOT'] . '/Foodorder/Images/';

        $length = 8;
        $ProfileImagename = $userData->fb_id . "_" . substr(str_shuffle(md5(time())), 0, $length) . ".png";
        $imageSaved = imagejpeg($source, $destdir . $ProfileImagename, 100);
        echo "imgestrorede " . $imageSaved;
    } else {
        $ActivityImagename = "";
    }

    $posts = array();
    $errorMsg = "";


    $insertFields = "fb_id, email_id,first_name,last_name,phone_no,profile_photo,created_time,modification_time
        , deviceid,Visibility,Followers,Followings,TotalFeeds";
    $valuesFields = "'" . addslashes($fb_id . "'" . $email_id . "','" . $first_name . "','" . $last_name . "','" . $phone_no . "','" . $ProfileImagename . "','" . $created_time . "'
        ,'" . $modification_time . "','" . $deviceid . "','" . $Visibility . "','" . $Followers . "','" . $Followings . "','" . $TotalFeeds . "'");

    $query = "Insert into user_master(" . $insertFields . ") values(" . $valuesFields . ")";
    $res1 = mysql_query($query) or $error = mysql_error();


    if ($res1) {
        $queryToUser = "Select * from user_master where emailId = '" . $email_id . "'";
        $resToUser = mysql_query($queryToUser) or $errorMsg = mysql_error();
        $userId = "";
        if (mysql_num_rows($resToUser)) {
            $status = 1; //success
        }
    } else {
        $status = 0;
        $posts['User'] = null;
    }


    $data['status'] = $status;
    $data['errorMsg'] = $errorMsg;
    $data['data'] = $posts;

    return $data;
}


function checkIfDirectoryExist($fName)
{
    if (!file_exists($fName)) {
        mkdir($fName, 0777);
    }
}

?>