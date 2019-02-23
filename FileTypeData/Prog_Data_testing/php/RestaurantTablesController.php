<?php

App::import('Vendor', 'imagetransform');
App::import('Vendor', 'PHPExcel');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::import('Vendor', 'MPDF/mpdf');

class RestaurantTablesController extends AppController {

    var $name = 'RestaurantTables';
    public $helpers = array('QrCode');

  public $components = array('Paginator');
var $uses = array('Category','Item','Qrcode','RestaurantTable');
var $paginate = array(
        'paramType' => 'querystring',
        'limit' => 5,
        'maxLimit' => 1000
    );
    /**
     * @method index
     * @uses It's use to display all RestaurantTables.
     */
    public function index($id) {

        $this->set('title_for_layout', 'restaurant_table');
        $user_id = $this->UserAuth->getUserId();
        $conditions = array();
        //whenever user can search any value that times this conditions will satisfied.
        if (($this->request->is('post')) || ($this->request->is('put'))) {


            if (!empty($this->request->data['Search']['name'])) {
                $conditions[] = array('OR' => array('RestaurantTable.name LIKE' => '%' . $this->request->data['Search']['name'] . '%'));
            }
            $this->set('flag', 'true');
            //pr($conditions);
        }
        $conditions[] = array('OR' => array('RestaurantTable.rest_id =' => $id));
//               $this->set('data', $this->Section->findAllThreaded(null, null, 'name')); 
//        $restaurant_table = $this->RestaurantTable->find('all', array(
//            'conditions' => $conditions));
//         $this->set('restaurantTables', $restaurant_table);
////           pr($conditions);
//            pr($restaurant_table);
//            exit();
        $this->set('restid', $id);    
        $this->Paginator->settings = $this->paginate;
     $restaurant_table = $this->Paginator->paginate('RestaurantTable',$conditions);
    $this->set('restaurantTables', $restaurant_table);
      $this->set('restaurantaName', $restaurant_table[0]['Restaurant']['name']);
//    echo $restaurant_table[0]['Restaurant']['name'];
//    exit();   
    }

    /**
     * @method add
     * @uses It's use to create table.
     */
    public function add($id) {

        $this->set('title_for_layout', 'restaurantTable add');
//        $user_id = $this->UserAuth->getUserId();
        $lastCreated = $this->RestaurantTable->find('first', array(
            'order' => array('RestaurantTable.seq_id' => 'desc'),
            'conditions' => array('RestaurantTable.rest_id =' => $id),
            'fields' => array('RestaurantTable.seq_id')
        ));
//        $lastCreated = $this->RestaurantTable->find('first');
        $this->set('rid',$id);
        $lastseqid = $lastCreated['RestaurantTable']['seq_id'];
        if ($this->request->is('post')) {
            $table_ids= $this->request->data['RestaurantTable']['name'];
          if (strpos($table_ids, '-') !== false){
            $table_id_array1 = explode('-', $table_ids);
            $first=$table_id_array1[0];
            $last=$table_id_array1[1];
            $table_id_array=array();
            foreach (range($first, $last) as $table_id ) {
  array_push($table_id_array, $table_id);
}
          }
else{
             $table_id_array = explode(',', $table_ids);
}

          $tbl=array();
              foreach ($table_id_array as $table_id) {
            $lastseqid = $lastseqid + 1;
            $restaurant_table = array();
            $restaurant_table['RestaurantTable']['rest_id'] = $id;
            $restaurant_table['RestaurantTable']['name'] = $table_id;
            $restaurant_table['RestaurantTable']['seq_id'] = $lastseqid;
            $restaurant_table['RestaurantTable']['qrcode'] = 'table_' . $lastseqid . '.png';
            
            
             $this->RestaurantTable->create();
            if ($this->RestaurantTable->save($restaurant_table)) {         
               
                $restaurant_table_id = $this->RestaurantTable->getLastInsertId();             

            array_push($tbl, $lastseqid);
                $dir = WWW_ROOT . '/qrcode_images/' . $id;
                if (!file_exists($dir))
                    mkdir($dir);
//                    
//                $this->redirect(array('controller' => 'Qrcodes', 'action' => 'view',$restaurant_table_id));
//                $ar1=array('controller' => 'Qrcodes', 'action' => 'view',$restaurant_table_id);
//                $this->QrCode->url($ar1,$qid); 
//                
//               
//                $img = $this->QrCode->text($ar1);
//                echo $img;
//                exit();
//                $this->save_attachment($restaurant_table_id, $this->request->data['restaurant_table']['icon']);


            }
                        }

                $this->Session->setFlash(__('Your restaurant_table successfully created.'));
              $this->Session->write('last', $tbl);  
                $this->redirect(array('controller' => 'RestaurantTables', 'action' => 'index/' . $id . '?sid=1' ));
              
        }
    }

//           
//     public function save_attachment($restaurant_table_id, $attachment_data) {
//         $cat_array=array();
//       if(!empty($attachment_data['name']))
//                {
//           
//                        $file = $attachment_data; //put the data into a var for easy use
//
//                        $ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
//                        $arr_ext = array('jpg', 'jpeg', 'gif'); //set allowed extensions
//
//                        //only process if the extension is valid
//                        if(in_array($ext, $arr_ext))
//                        {
//                            $filenm=$restaurant_table_id .'_'. $file['name'];
//                         
//                                //do the actual uploading of the file. First arg is the tmp name, second arg is 
//                                //where we are putting it
//                              if(move_uploaded_file($file['tmp_name'], WWW_ROOT . 'restaurant_table_images/' .$filenm));
//{
//    
//                                //prepare the filename for database entry
//                                $this->restaurant_table->saveField('icon',  $filenm);
////                                $this->data['User']['image'] = $file['name'];
//}
//                        }
//                }
//
//       
//    }

    /**
     * @method edit
     * @uses It's use to edit Restaurants.
     */
    public function edit($restaurant_table_id = null) {
        $this->set('title_for_layout', 'restaurant_table edit');
        $user_id = $this->UserAuth->getUserId();
        $res_tbl = $this->RestaurantTable->findAllById($restaurant_table_id);

        $this->RestaurantTable->id = $restaurant_table_id;
        $rid = $res_tbl[0]['RestaurantTable']['rest_id'];
        $this->set('rest_id',$rid);
//        $sid=$res_tbl[0]['RestaurantTable']['seq_id'];
        if ($this->RestaurantTable->exists()) {
            if ($this->request->is('post')) {
                $restaurant_table = array();

                $restaurant_table['RestaurantTable']['name'] = $this->request->data['RestaurantTable']['name'];


                if ($this->RestaurantTable->save($restaurant_table)) {
                    $this->Session->setFlash(__('Your restaurant_table successfully created.'));
                    $this->redirect(array('controller' => 'RestaurantTables', 'action' => 'index/' . $rid));
                }
                //get all restaurant.
            }
            $restaurant1 = $this->RestaurantTable->findById($restaurant_table_id);
            $this->set('restaurant', $restaurant1);
        } else {
            $this->Session->setFlash(__('restaurant table has not found.'));
            $this->redirect(array('action' => 'index/' . $rid));
        }
    }

    /**
     * @method delete
     * @param integer $restaurant_table_id
     * @return void
     */
    public function delete($restaurant_table_id = null) {
        $this->set('title_for_layout', 'restaurant_table delete');
        $user_id = $this->UserAuth->getUserId();
        $res_tbl = $this->RestaurantTable->findAllById($restaurant_table_id);

        $this->RestaurantTable->id = $restaurant_table_id;
        $rid = $res_tbl[0]['RestaurantTable']['rest_id'];
        $sid = $res_tbl[0]['RestaurantTable']['seq_id'];


        if ($this->RestaurantTable->exists()) {
            if ($this->RestaurantTable->delete($restaurant_table_id)) {

                $pth = WWW_ROOT . 'qrcode_images/' . $rid . '/table_' . $sid . '.png';
                if (file_exists($pth)) {
                    unlink($pth);
                }

                $this->Session->setFlash(__('restaurant_table has successfully deleted.'));
                $this->redirect(array('controller' => 'RestaurantTables', 'action' => 'index/' . $rid));
            } else {
                $this->Session->setFlash(__('restaurant_table not deleted. please, try an again.'));
                $this->redirect(array('controller' => 'RestaurantTables', 'action' => 'index/' . $rid));
            }
        } else {
            $this->Session->setFlash(__('restaurant_table has not found.'));
            $this->redirect(array('controller' => 'RestaurantTables', 'action' => 'index/' . $rid));
        }
    }

    public function viewpdf($id) {
        //Import /app/Vendor/Fpdf
        App::import('Vendor', 'Fpdf', array('file' => 'fpdf/fpdf.php'));
        $conditions[] = array('OR' => array('RestaurantTable.rest_id =' => $id));
//               $this->set('data', $this->Section->findAllThreaded(null, null, 'name')); 
        $restaurant_table = $this->RestaurantTable->find('all', array(
            'conditions' => $conditions));
        $this->set('restaurant_table', $restaurant_table);
//    $dir = new Folder('/app/webroot/qrcode_images/1/');
//$files = $dir->find('.*\.png');
//pr($files);
//exit();
//    $records = $this->Audio->find('all', array('conditions' => array('rest_id' => $this->Auth->user('idUser'))));
//    $files = array();
//    foreach ($records as $record) {
//        $files[] = WWW_ROOT . 'qrcode_images/'.$rid;
//    }
//    $this->set('files', $files);
        //Assign layout to /app/View/Layout/pdf.ctp
        $this->layout = 'pdf'; //this will use the pdf.ctp layout
        //Set fpdf variable to use in view
        $this->set('fpdf', new FPDF('P', 'mm', 'A4'));
        //pass data to view
        $this->set('data', 'Hello, PDF world');
        //render the pdf view (app/View/[view_name]/pdf.ctp)
        $this->render('pdf');
    }

    public function viewpdf1($id) {
         $conditions[] = array('OR' => array('RestaurantTable.rest_id =' => $id));
//               $this->set('data', $this->Section->findAllThreaded(null, null, 'name')); 
        $restaurant_table = $this->RestaurantTable->find('all', array(
            'conditions' => $conditions));
        $this->set('restaurant_table', $restaurant_table);
       
        $mpdf = new mPDF('', 'A4', '', '', 10, 20, 10, 20, 16, 13, 'L');
        $mpdf->SetTitle("Counselling Details-" . $id);
        $mpdf->SetHTMLFooter('<div style="width:100%;text-align:center;margin:0 auto;">Page {PAGENO} of {nb}</div>');
        $html= "<html>
                <head>
                    <title></title>
                    <style>
                      body{
                             font-family: 'Open Sans', sans-serif;
                        }
                    </style>
                 </head>
                 <body>".
                 
	    "<h1 style='text-align:center;margin-bottom:20px'>Table QRcodes</h1>";
             
        $cnt=0;
            if (!empty($restaurant_table)) {
            foreach ($restaurant_table as $restaurantTable) {
                $cnt = $cnt+1;
                $html .= 
                       "<div style='float:left;width:160px;border:1px solid black;border-radius:10px;text-align:center;margin-left:10px;'><h2>Order!</h2> ".
                       
                        "<img src='".WWW_ROOT . 'qrcode_images/'.$id."/".$restaurantTable['RestaurantTable']['qrcode']."'/>
                            
                         <div> Table-".$restaurantTable['RestaurantTable']['name']."</div>";
                       
$html .="</div>
                   ";
if($cnt >3){
    $cnt=0;
    $html .="<div style='clear:both;height:10px;'></div>";
}

//                else{
//                    $html.="<tr> ";
//                    $html .= "<td>
//                       <div style='border:1px solid black;'><h2>Order!</h2> ".
//                        "<img src='".WWW_ROOT . 'qrcode_images/'.$id."/".$restaurantTable['RestaurantTable']['qrcode']."'/>
//                             <div> table_".$restaurantTable['RestaurantTable']['seq_id']."</div>";
//                       
//$html .="</div>
//                    </td></tr>";
//                }
            }
            }
                
    $html .="</body></html>";

//       $html='<img src="'.WWW_ROOT . 'qrcode_images/'.$id.'/table_1.png"/>';
        $mpdf->WriteHTML($html);
      

        $name = $restaurant_table[0]['Restaurant']['name'].'restaurants \' tables.pdf';
        $mpdf->Output($name, "D");
//     $this->Mpdf->init();
//    $this->Mpdf->setFilename('file.pdf');
//    $this->Mpdf->setOutput('D');
//      $html="gghfgfg";
//     $mpdf->WriteHTML($html);
//    // can call any mPDF method via $this->Mpdf->pdf
//    $this->Mpdf->pdf->SetWatermarkText("Draft");
        exit;
    }
     /**
     * @method table_builk_delete
     * @return boolean
     * @uses It's use to delete multiple tickets.
     */
    public function restaurantTable_builk_delete() {
        $this->layout = 'ajax';
     
        $restauranttable_ids = $this->request->data['restaurantTable_ids'];
    
        $response = array();
        if (!empty($restauranttable_ids)) {
            $table_id_array = explode(',', $restauranttable_ids);
           
            foreach ($table_id_array as $table_id) {
                //check restaurant is exist or not.
                
                $res_tbl = $this->RestaurantTable->findAllById($table_id);
//print_r($res_tbl);
//exit;

       $this->RestaurantTable->id = $table_id;
        $rid = $res_tbl[0]['RestaurantTable']['rest_id'];
        $sid = $res_tbl[0]['RestaurantTable']['seq_id'];
                if ($this->RestaurantTable->exists()) {
                    $this->RestaurantTable->delete($table_id);
                     $pth = WWW_ROOT . 'qrcode_images/' . $rid . '/table_' . $sid . '.png';
                     
                if (file_exists($pth)) {
                    unlink($pth);
                }
                }
            }
            $response = array('is_deleted' => 1);
        } else {
            $response = array('is_deleted' => 0);
        }
        echo json_encode($response);
        exit;
    }
}

?>