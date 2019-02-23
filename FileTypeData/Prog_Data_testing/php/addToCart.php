<?php
include("dbConfig.php");

$obj = new genFunction(true);
$paramArray = $obj->getRestArray();
$curDate = getDefaultDate();
$return_arr = array();
$itemsList = str_replace("\"", "", $paramArray['dataList']);
$items = explode(",", $itemsList);


$lastRecordItemID;
if (count($items) > 0) {
    $data = explode("*", $items[0]);

    $curDate = getDefaultDate();


    mysql_query("Insert into orders (user_id, status,total_amount,rest_id,table_id,order_date) values (" . $data[0] . ",1,0," . $data[5] . "," . $data[6] . ",'" . $curDate . "')") or die(mysql_error());
    $lastOrderId = mysql_insert_id();

    for ($i = 0; $i < count($items); $i++) {

        $data = explode("*", $items[$i]);
//echo "Insert into order_items (order_id, item_id, quantity,price) 
        //	select ".$lastOrderId.",".$data[2].",".$data[1].",i.price from items i where i.id=".$data[2]." limit 1";

        mysql_query("Insert into order_items (order_id, item_id, quantity,price, item_choice_id, comment)
		select " . $lastOrderId . "," . $data[2] . "," . $data[1] . ",i.price,$data[3],'" . $data[4] . "' from items i where i.id=" . $data[2] . " limit 1
", $con) or die(mysql_error());

        $lastRecordItemID = $data[2];
    }

    mysql_query("update orders set total_amount=(select ifnull(sum(price*quantity),0) from order_items
				where order_id=" . $lastOrderId . ")  where id=" . $lastOrderId) or die(mysql_error());

    $return_arr['status'] = "true";
    $return_arr['code'] = "P005";
    $return_arr['msg'] = "item added to cart.";
} else {
    $return_arr['status'] = "false";
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