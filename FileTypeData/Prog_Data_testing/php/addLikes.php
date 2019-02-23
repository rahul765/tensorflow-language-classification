<?php
include("dbConfig.php");
$obj = new genFunction(true);
$paramArray = $obj->getRestArray();
$return_arr = array();
$r = mysql_query("select * from likes where user_id=" . $paramArray['user_id'] . " and suggestion_id=" . $paramArray['suggestion_id']);
if (mysql_num_rows($r) > 0) {
    mysql_query("update likes set `is_like`=" . $paramArray['is_like'] . " where user_id=" . $paramArray['user_id'] . "
	and suggestion_id=" . $paramArray['suggestion_id'], $con) or die(mysql_error());
    $return_arr['status'] = "true";
    $return_arr['code'] = "P026";
    $return_arr['msg'] = "likes updated.";
} else {
    mysql_query("INSERT INTO likes(`user_id`,`is_like`,suggestion_id)
	 VALUES('" . $paramArray['user_id'] . "'," . $paramArray['is_like'] . ",'" . $paramArray['suggestion_id'] . "')", $con) or die(mysql_error());
    $return_arr['status'] = "true";
    $return_arr['code'] = "P023";
    $return_arr['msg'] = "likes successfully added.";
}
$row_array = array();

$return_arr['Post'] = $row_array;
echo json_encode($return_arr);
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
}

?>