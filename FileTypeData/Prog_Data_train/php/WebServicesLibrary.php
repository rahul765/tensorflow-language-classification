<?php

class WebserviceLibrary
{
    private $jsonarr = array();

    const SUCCESSSTATUS = "true";
    const ERRORSTATUS = "false";
    const SUCCESSCODE = "SUC001";
    const RegistrationSuccessCode = "SUC001";
    const ERRORCODE = "ERR001";
    const SUCCESSMESSAGE = "";
    const ERRORMESSAGE = "";
    const STITLE = "";
    private $MethodType = false;
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
        $this->MethodType = $Method;
    }


    function getSelectedParam($ParamArray)
    {
        if ($ParamArray != "" && is_array($ParamArray) == true) {
            $FlipTheArray = array_flip($ParamArray);
            $ArrayValue = array_intersect_key($this->getRestAPI, $FlipTheArray);
            return $ArrayValue;
        }
    }


    function ProcedureParam($procedureName, $OutputParam = false)
    {
        $array = $this->getRestAPI;
        if ($array != "" && is_array($array)) {
            $vals = "";
            $i = 0;
            $OutputParams = "";
            foreach ($array as $key => $val) {
                if ($vals == "") {
                    $vals = "'" . addslashes($val) . "'";
                } else {
                    $vals .= ',' . "'" . addslashes($val) . "'";
                }

                if (is_array($OutputParam) == true && count($OutputParam) >= ($i + 1)) {
                    if ($OutputParams == "") {
                        $OutputParams = "," . $OutputParam[$i];
                    } else {
                        $OutputParams .= "," . $OutputParam[$i];
                    }
                } else {
                    $OutputParams = $OutputParams;
                }
                $i++;
            }
        }

        $AllInputOutput = $OutputParam != "" ? $vals . $OutputParams : $vals;

        $Procedure = " CALL " . $procedureName . "(" . $AllInputOutput . ")";
        //echo $Procedure;
        // exit;
        return $Procedure;
    }


    function getSuccessMessage($SuccessMessage = "")
    {
        $this->jsonarr['status'] = WebserviceLibrary::SUCCESSSTATUS;
        if ($SuccessMessage != "") {
            $this->jsonarr['msg'] = $SuccessMessage;
        }
        return $this->jsonarr;
    }

    function getErrorMessage($ErrorMessage = "")
    {
        $this->jsonarr['status'] = WebserviceLibrary::ERRORSTATUS;
        $this->jsonarr['msg'] = empty($ErrorMessage) == true ? "There was an Error" : $ErrorMessage;
        return $this->jsonarr;
    }


    function uploadImage($KeyName)
    {
        if (empty($this->getRestAPI[$KeyName]) == false) {
            $imageKeyName = $this->getRestAPI[$KeyName];

            //echo $imageKeyName;
            $ext = "png";
            $image = base64_decode($imageKeyName);


            $profile_pics = WebserviceLibrary::STITLE . md5(microtime()) . "." . $ext;
            $img = imagecreatefromstring($image);
            $img_path = "./usersProfile/" . $profile_pics;

            if ($img !== false) {
                imagepng($img, $img_path);
            }

            /*$thumbnail = new thumbnail();
            $thumbnail->load($img_path);
            // $thumbnail->resizeToWidth(100);
            $thumbnail->resize(480,300);
            $thumbnail->save($img_thumb);
            $thumbnail->load($img_thumb);*/
            $this->getRestAPI[$KeyName] = $profile_pics;
        } else {
            $profile_pics = "";
            $this->getRestAPI[$KeyName] = $profile_pics;
        }
    }

    function getStaticJsonResponse($msg = "", $Code = "", $status = "")
    {
        $arr = array();
        $this->jsonarr['status'] = $status;
        $this->jsonarr['msg'] = $msg;
        $this->jsonarr['code'] = $Code;
        $this->jsonarr['post'] = $arr;
        echo json_encode($this->jsonarr);
    }

    function getJsonResponse($ResponseMessage, $KeyName = false, $sort = "", $ImageKeyName = "", $SendEmail = "", $msg = "", $Code = "", $status = "")
    {
        $KeyName = $KeyName === false ? "post" : $KeyName;
        if ($ResponseMessage != "" && is_array($ResponseMessage) == true) {
            //$this->jsonarr['response']['Path'] = $ImageKeyName=="MovieImage"?UPLOAD_MOVIE_IMAGE_THUMB_PATH:($ImageKeyName=="ProfileImage"?UPLOAD_IMAGE_PATH_THUMB:"");

            //$this->getSuccessMessage($msg);
            $i = 0;
            $arr = array();
            $arrt = array();
            foreach ($ResponseMessage as $key => $val) {
                if (is_array($sort) === true && $sort[0] != "") {
                    sort($val[$sort[0]]);
                }
                if (is_array($sort) === true && $sort[1] != "") {
                    sort($val[$sort[1]]);
                }
                $arr[$key] = $val;
                $i++;
            }
            array_push($arrt, $arr);
            $this->jsonarr[$KeyName] = $arrt;
        } else {
            //$this->getErrorMessage($ResponseMessage);
        }

        $this->jsonarr['status'] = $status;
        $this->jsonarr['msg'] = $msg;
        $this->jsonarr['code'] = $Code;

        if ($Code == "P003") {
            //Add total data count
            $SelectedParam = $this->getSelectedParam(array("resturantID"));
            $resturantID = $SelectedParam["resturantID"];
            $objDBFunctions = new dbfunctions();
            $objDBFunctions->Query("select count(distinct(c.id)) as 'TotalRows' from categories_master c
				inner join sub_categories_master sc on (sc.`fk_category_id`=c.`id`)
				inner join subcategory_item_master si on (si.`fk_sub-categoty-id`=sc.`id`)
				inner join `foodItem_Resturant_Mapping` fr on (fr.`foodItemID`=si.`id`)
				where fr.`resturantID`=" . $resturantID);
            $SubData = $objDBFunctions->getFetchArray();
            $keyname1 = array_keys($SubData);
            foreach ($keyname1 as $key1 => $val1) {
                $this->jsonarr['post']['TotalRows'] = trim($SubData[$val1], "\n");
            }
        }

        echo json_encode($this->jsonarr);

    }

    function getAllParam() // $Method must be true if using post
    {
        if ($this->MethodType === false) {
            array_shift($this->getRestAPI);
            // ($this->getRestAPI);
        }
        return $this->getRestAPI;
    }

    public function getPagination()
    {
        $AllParam = $this->getAllParam();
        $limit2 = $AllParam['Limit'];
        $PageNo = $AllParam['Page'];
        if ($PageNo == '1') {
            $limit1 = '0';
        } elseif ($PageNo != '1') {
            $limit1 = ((($PageNo - 1) * $limit2) + 0);
        } else {
            $limit1 = ((($PageNo - 1) * $limit2) + 0);
        }
        $this->getRestAPI['Page'] = $limit1;
        $this->getRestAPI['Limit'] = $limit2;
    }

    public function getCategory()
    {
        $DbObj1 = new dbfunction();
        $this->getPagination();
        $Response = $DbObj1->getCountryArrayData($this->ProcedureParam("getMasterCategory"), "Category");
        return $Response;
    }

}

?>