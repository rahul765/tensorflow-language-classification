<?php

include("dbConfig.php");

$obj = new genFunction(true);
$paramArray = $obj->getRestArray();
$return_arr = array();
$obj->uploadImage("suggested_image");
$curDate = getDefaultDate();
$paramArray = $obj->getRestArray();

mysql_query("INSERT INTO suggestions( item_id, user_id, suggested_image, suggested_comment, post_time)
 VALUES(" . $paramArray['item_id'] . "," . $paramArray['user_id'] . ",'" . $paramArray['suggested_image'] . "',
 '" . $paramArray['suggested_comment'] . "', '" . $curDate . "'  )", $con) or die(mysql_error());

mysql_query("update order_items set is_suggested=1 where `item_id`=" . $paramArray['item_id']
    . " and `order_id`=" . $paramArray['order_id'], $con) or die(mysql_error());

$return_arr['status'] = "true";
$return_arr['code'] = "P008";
$return_arr['msg'] = "Suggestion added";

$return_arr['Post'] = array();
echo json_encode($return_arr);
?>

<?php

class genFunction
{

    private $getRestAPI = array();

    function __construct($Method = false)
    { // $Method must be true if using post
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
            $img_path = "../CMS/app/webroot/suggestion_images/" . $pic;
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