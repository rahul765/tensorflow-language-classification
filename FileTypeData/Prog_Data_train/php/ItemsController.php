<?php

App::import('Vendor', 'imagetransform');
App::import('Vendor', 'PHPExcel');

class ItemsController extends AppController {

    var $name = 'Items';
//    public $helpers = array('PhpExcel');
    public $components = array('Paginator');
    var $uses = array('Category', 'Item', 'RestItemMapping', 'Restaurant', 'User', 'ItemChoice', 'Suggestion');
    var $paginate = array(
        'paramType' => 'querystring',
        'limit' => 5,
        'maxLimit' => 1000
    );

    /**
     * @method index
     * @uses It's use to display all items.
     */
    public function index() {
        
        $this->set('title_for_layout', 'Item');
//        phpinfo();exit();
        
        $user_id = $this->UserAuth->getUserId();
        $user = $this->UserAuth->getUser();
        $user_type = $user['UserGroup']['name'];
        $grp_id = $this->UserAuth->getGroupId();
//        $rest = $this->User->findById($user_id, array(
//            'fields' => 'rest_id'));
//        $rest_id = $rest['User']['rest_id'];
        $rest_id = $this->User->findById($user_id);

        $conditions = array();
        $today = date('Y-m-d');
        if ($user_type != 'Admin') {
            if ($grp_id == '5') {
//                echo $rest_id['User']['rest_id'];
                $conditions[] = array('OR' => array('Item.rest_id =' => $rest_id['User']['rest_id']));
            } else {
                $conditions[] = array('OR' => array('Item.user_id =' => $user_id));
            }
//            $conditions[] = array('OR' => array('Item.user_id =' => $user_id));
        }
//    $conditions[] = array('OR' => array('Item.id' => array($i_id)));
//        $con = array('AND' => array('Item.start_date <> ' => $today, 'Item.end_date <> ' => $today));
//        $con = array('AND' => array('Item.start_date =< ' => $today, 'Item.end_date >= ' => $today));  
//        $conditions[] =array('AND' => array('Item.start_date >= ' => $today, 'Item.end_date =< ' => $today));
        if (($this->request->is('post')) || ($this->request->is('put'))) {

            if (!empty($this->request->data['Search']['name'])) {
                $conditions[] = array('OR' => array('Item.name LIKE' => '%' . $this->request->data['Search']['name'] . '%'));
            }
            $this->set('flag', 'true');
            //pr($conditions);
        }
        $this->Paginator->settings = $this->paginate;
        $item = $this->Paginator->paginate('Item', $conditions);
        $this->set('items', $item);

//        $item = $this->Item->find('all', array(
//            'conditions' => $conditions
//        ));
//        $this->set('items', $item);
//         pr($item);
//    exit();
        $this->set('user_types', $user_type);

        //   get all category.
        $categors = $this->Category->find('list', array(
            'fields' => array('Category.id', 'Category.name'),
            'recursive' => false
        ));
        $this->set('ctys', $categors);
        $this->set('grp_id', $grp_id);
    }

    /**
     * @method add
     * @uses It's use to create items.
     */
    public function add() {

        $grp_id = $this->UserAuth->getGroupId();
        $user_id = $this->UserAuth->getUserId();
        $user_type = $this->UserAuth->getUser();

        $rest = $this->User->findById($user_id);
        $this->set('title_for_layout', 'Item add');
        $conditions = array();
        $conditions1 = array();
        if ($grp_id != "1") {

            $conditions[] = array('OR' => array('Restaurant.user_id =' => $user_id));
        }
        if ($grp_id != '1') {
            if ($grp_id == '5') {
//                echo $rest_id['User']['rest_id'];
                $conditions1[] = array(array('Category.rest_id =' => $rest['User']['rest_id'], 'Category.parent_id' => 0));
            } else {
                $conditions1[] = array(array('Category.user_id =' => $user_id, 'Category.parent_id' => 0));
            }
//         $conditions[] = array('OR' => array('Category.rest_id =' => $rest_id['User']['rest_id']));
        }
//        $user_id = $this->UserAuth->getUserId();
        if ($this->request->is('post')) {
//            $category = $this->Category->findById($this->request->data['Item']['category_id']);
//            $rest_id = $category['Category']['rest_id'];
            $rest_id = $this->request->data['Item']['rest_id'];
            $userid = $this->Restaurant->findById($rest_id);
            $item = array();
            if ($grp_id != '1') {
                $item['Item']['user_id'] = $user_id;
            } else {
                $item['Item']['user_id'] = $userid['Restaurant']['user_id'];
            }
 if ($grp_id == '5') {
                 $item['Item']['rest_id'] = $rest['User']['rest_id'];
                
            }
            else{
               
                  $item['Item']['rest_id'] = $this->request->data['Item']['rest_id'];
            }
            $itemchoice = array();
          
            $date = $this->request->data['Item']['date'];
              if(!empty($date)){
            $datearray = explode('-', $date);
              
//            pr($datearray);
            $date_Array = array(date('Y-m-d', strtotime($datearray[0])), date('Y-m-d', strtotime($datearray[1])));
             $item['Item']['start_date'] = $date_Array[0];
            $item['Item']['end_date'] = $date_Array[1];
            }
            $item['Item']['category_id'] = $this->request->data['Item']['category_id'];
            $item['Item']['name'] = $this->request->data['Item']['name'];
            $item['Item']['description'] = $this->request->data['Item']['description'];
            $item['Item']['price'] = $this->request->data['Item']['price'];
           
//            $item['Item']['user_id'] = $user_id;
//            $item['Item']['rest_id'] = $this->request->data['Item']['rest_id'];



            if ($this->Item->save($item)) {
                if (!empty($this->request->data['ItemChoice'])) {
                    $itemchoice = $this->request->data['ItemChoice'];
                    $item_choice = array();
                    $item_id = $this->Item->id;
                    $cnt = count($itemchoice['name']);

                    for ($i = 0; $i < $cnt; $i++) {
                        $item_choice['ItemChoice']['choice_name'] = $itemchoice['name'][$i];
                        $item_choice['ItemChoice']['description'] = $itemchoice['description'][$i];
                        $item_choice['ItemChoice']['price'] = $itemchoice['price'][$i];
                        $item_choice['ItemChoice']['item_id'] = $item_id;
                        $this->ItemChoice->create();
                        $this->ItemChoice->save($item_choice);
                    }
                }
                $this->Session->setFlash(__('Your Item successfully created.'));
                $this->redirect(array('controller' => 'items', 'action' => 'index'));
            }
        }

            //get all category.
            $cats = $this->Category->find('all', array(
//                'fields' => array('Category.id', 'Category.name'),
//                'recursive' => false, 
                'conditions' => $conditions1
            ));
//            pr($cats);
            $lists=array();
              foreach ($cats as $row) {
            if (!empty($row['sub_category'])) {
                $lists[$row['Category']['id']] = $row['Category']['name'];
            }
        }
//        pr($lists);
            $this->set('ctys', $lists);
//exit();

        //get all Restaurant.
        $rest = $this->Restaurant->find('all', array(
            'fields' => array('Restaurant.id', 'Restaurant.name'),
//            'recursive' => false,
            'conditions' => $conditions
        ));
        $options=array();
        foreach ($rest as $row) {
            if (!empty($row['Category'])) {
                $options[$row['Restaurant']['id']] = $row['Restaurant']['name'];
            }
        }
//        $options[] = $temp;
   
        $this->set('options', $options);
        $this->set('rest', $rest);
        $this->set('grp_id', $grp_id);
    }

    /**
     * @method edit
     * @uses It's use to edit items.
     */
    public function edit($item_id = null) {
        $this->set('title_for_layout', 'Item edit');
        $grp_id = $this->UserAuth->getGroupId();
        $user_id = $this->UserAuth->getUserId();
        $user_type = $this->UserAuth->getUser();
        $user_type = $user_type['UserGroup']['name'];
            $rest = $this->User->findById($user_id);
            $conditions1=array();
             if ($grp_id != "1") {

            $conditions1[] = array('OR' => array('Restaurant.user_id =' => $user_id));
        }
        $this->Item->id = $item_id;
        if ($this->Item->exists()) {
            if ($this->request->is('post')) {
                 $item = array();
                $date = $this->request->data['Item']['date'];
             
                if(!empty($date)){
                $datearray = explode('-', $date);
//                pr($datearray);
                 $date_Array = array(date('Y-m-d', strtotime($datearray[0])), date('Y-m-d', strtotime($datearray[1])));
            
                  $item['Item']['start_date'] = $date_Array[0];
                $item['Item']['end_date'] = $date_Array[1];
                }
               
                $category = $this->Category->findById($this->request->data['Item']['category_id']);
                $rest_id = $category['Category']['rest_id'];
             
                if ($user_type != 'Admin') {
                    $item['Item']['user_id'] = $user_id;
                } else {
                    $item['Item']['user_id'] = $category['Category']['user_id'];
                }
                if ($grp_id == '5') {
                 $item['Item']['rest_id'] = $rest['User']['rest_id'];
                
            }
            else{
               
                  $item['Item']['rest_id'] = $this->request->data['Item']['rest_id'];
            }

                $item['Item']['category_id'] = $this->request->data['Item']['category_id'];
                $item['Item']['name'] = $this->request->data['Item']['name'];
                $item['Item']['description'] = $this->request->data['Item']['description'];
                $item['Item']['price'] = $this->request->data['Item']['price'];
//                $item['Item']['start_date'] = $date_Array[0];
//                $item['Item']['end_date'] = $date_Array[1];
//                $item['Item']['user_id'] = $user_id;
//                $item['Item']['rest_id'] = $this->request->data['Item']['rest_id'];
                   
                if ($this->Item->save($item)) {
                    $itemchoice = $this->request->data['ItemChoice'];
                    if (isset($itemchoice)) {
                        $item_choice = array();
//               $item_id = $item_id;
                        $cnt = count($itemchoice['name']);

                        for ($i = 0; $i < $cnt; $i++) {
                            $item_choice['ItemChoice']['choice_name'] = $itemchoice['name'][$i];
                            $item_choice['ItemChoice']['description'] = $itemchoice['description'][$i];
                            $item_choice['ItemChoice']['price'] = $itemchoice['price'][$i];
                            $item_choice['ItemChoice']['item_id'] = $item_id;
                            $this->ItemChoice->create();
                            $this->ItemChoice->save($item_choice);
                        }
                    }
//                    $this->redirect(array('controller' => 'RestItemMappings', 'action' => 'edit', $item_id, $this->request->data['Item']['category_id']));
                    $this->Session->setFlash(__('Your Item successfully created.'));

                    $this->redirect(array('controller' => 'items', 'action' => 'index'));
                }
            }
            //get particular item detail
            $item = $this->Item->findById($item_id);
            $this->set('item', $item);
            $restuarant_id = $item['Item']['rest_id'];
//            pr($item);
            //get all category.
            $cats = $this->Category->find('all', array(
//                'fields' => array('Category.id', 'Category.name'),
//                'recursive' => false, 
                'conditions' => array(
                    'Category.rest_id' => $restuarant_id,
                     'Category.parent_id' => 0  )
            ));
              foreach ($cats as $row) {
            if (!empty($row['sub_category'])) {
                $lists[$row['Category']['id']] = $row['Category']['name'];
            }
        }
            $this->set('ctys', $lists);
//            pr($items);
            //get subcategory
                  $sub_cat= $this->Category->find('list', array(
//                'fields' => array('Category.id', 'Category.name'),
//                'recursive' => false, 
                'conditions' => array(                   
                     'Category.parent_id' => $item['Category']['parent_id'] )
            ));
            $this->set('sub_cat',$sub_cat);
            $conditions = array();
            $conditions = array('OR' => array('ItemChoice.item_id' => $item_id));
            //get all item choice.
            $items_choice = $this->ItemChoice->find('all', array(
                'conditions' => $conditions
            ));
            $this->set('item_choice', $items_choice);

         
             //get all Restaurant.
        $rest = $this->Restaurant->find('all', array(
            'fields' => array('Restaurant.id', 'Restaurant.name'),
//            'recursive' => false,
            'conditions' => $conditions1
        ));
        $options=array();
        foreach ($rest as $row) {
            if (!empty($row['Category'])) {
                $options[$row['Restaurant']['id']] = $row['Restaurant']['name'];
            }
        }
//        $options[] = $temp;
     $this->set('grp_id', $grp_id);
        $this->set('options', $options);
        } else {
            $this->Session->setFlash(__('Item has not found.'));
            $this->redirect(array('action' => 'index'));
        }
    }

    /**
     * @method delete
     * @param integer $item_id
     * @return void
     */
    public function delete($item_id = null) {
        $this->set('title_for_layout', 'item delete');
        $user_id = $this->UserAuth->getUserId();
        $this->Item->id = $item_id;
        if ($this->Item->exists()) {
            if ($this->Item->delete($item_id)) {
                $this->Session->setFlash(__('Item has successfully deleted.'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Item not deleted. please, try an again.'));
                $this->redirect(array('action' => 'index'));
            }
        } else {
            $this->Session->setFlash(__('Item has not found.'));
            $this->redirect(array('action' => 'index'));
        }
    }

    /**
     * @method item_builk_delete
     * @return boolean
     * @uses It's use to delete multiple items.
     */
    public function item_builk_delete() {
        $this->layout = 'ajax';

        $item_ids = $this->request->data['item_ids'];
        $response = array();
        if (!empty($item_ids)) {
            $item_id_array = explode(',', $item_ids);
            foreach ($item_id_array as $item_id) {
                //check item is exist or not.
                $this->Item->id = $item_id;
                if ($this->Item->exists()) {
                    $this->Item->delete($item_id);
                     
                }
            }
//             $this->redirect(array('action' => 'index'));
            $response = array('is_deleted' => 1);
        } else {
            $response = array('is_deleted' => 0);
        }
        echo json_encode($response);
        exit;
    }

    /**
     * @method change_active_status
     * @uses It's use to change active status.
     * @param int $item_id,varchar $st.
     */
    public function change_active_status($item_id = null, $st = null) {

        $this->Item->id = $item_id;
        if ($this->Item->exists()) {
            if ($st == 'active') {
                $this->Item->saveField('is_active', 0);
            } elseif ($st == 'inactive') {
                $this->Item->saveField('is_active', 1);
            }
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash(__('Item not found.'));
            $this->redirect(array('action' => 'index'));
        }
    }

    public function edit_item_choice($item_id) {
        $this->layout = 'ajax';
        $this->set('title_for_layout', 'Item edit');
        $user_id = $this->UserAuth->getUserId();
        $user_type = $this->UserAuth->getUser();
        $user_type = $user_type['UserGroup']['name'];
        $this->ItemChoice->id = $item_id;
        if ($this->ItemChoice->exists()) {
            if ($this->request->is('post')) {
                $item_choice = array();

                $item_choice['ItemChoice']['choice_name'] = $this->request->data['data_item'][0];
                $item_choice['ItemChoice']['description'] = $this->request->data['data_item'][1];
                $item_choice['ItemChoice']['price'] = $this->request->data['data_item'][2];

                if ($this->ItemChoice->save($item_choice)) {
                    echo json_encode($item_choice['ItemChoice']);
                    exit;
                }
            }
        } else {
            $this->Session->setFlash(__('Item has not found.'));
            $this->redirect(array('action' => 'index'));
        }
    }

    /**
     * @method delete_choice
     * @param integer $item_id
     * @return void
     */
    public function deletechoice($choice_id = null) {

//        $this->layout = 'ajax';
        $this->set('title_for_layout', 'choice delete');
        $user_id = $this->UserAuth->getUserId();
        $item_choice = $this->ItemChoice->findById($choice_id);
        $this->ItemChoice->id = $choice_id;
        if ($this->ItemChoice->exists()) {
            if ($this->ItemChoice->delete($choice_id)) {
//                echo 'hello';
//               exit();
                $this->Session->setFlash(__('choice has successfully deleted.'));
                $this->redirect($this->referer());
//$this->redirect(array('action' => 'edit',$item_choice['item_id']));
            } else {

                $this->Session->setFlash(__('choice not deleted. please, try an again.'));
                $this->redirect(array('action' => 'edit', $item_choice['item_id']));
            }
        } else {

            $this->Session->setFlash(__('choice has not found.'));
            $this->redirect(array('action' => '/edit/' . $item_choice['item_id']));
        }
    }

    /**
     * @method item_builk_delete
     * @return boolean
     * @uses It's use to delete multiple items.
     */
    public function choice_builk_delete() {
//        $this->layout = 'ajax';


        $choice_ids = $this->request->data['choice_ids'];

        $response = array();
        if (!empty($choice_ids)) {
            $choice_id_array = explode(',', $choice_ids);
            foreach ($choice_id_array as $choice_id) {
                //check item is exist or not.
                $this->ItemChoice->id = $choice_id;
                if ($this->ItemChoice->exists()) {
                    $this->ItemChoice->delete($choice_id);
                }
            }
            $response = array('is_deleted' => 1);
        } else {
            $response = array('is_deleted' => 0);
        }
        echo json_encode($response);
        exit;
    }

    /**
     * @method category_builk_delete
     * @return boolean
     * @uses It's use to delete multiple categories.
     */
    public function category_retaurant() {
        $this->layout = 'ajax';
        $this->autoRender = false;
        $restuarant_id = $this->request->data['rest_id'];
        $con = array();
        $con = array('AND' => array(
                'Category.rest_id' => $restuarant_id,
//                'Category.parent_id' => 0  
            ));
        if (!empty($this->data)) {

            $this->loadModel('Category');
            $category = $this->Category->find('all', array(
                'conditions' => $con));
           
//  foreach ($category as $row) {
//            if (!empty($row['sub_category'])) {
//                $lists[$row['Category']['id']] = $row['Category']['name'];
//            }
//        }
//        pr($lists);
//        exit;
            $cnt = sizeof($category);
            if ($cnt > 0) {
                $HTML = "";
                foreach ($category as $row){
                     if (!empty($row['sub_category'])) {
                    $HTML.="<option value='" . $row['Category']['id'] . "'>" . $row['Category']['name'] . "</option>";
                     }
                     else{
                         $HTML.="no data";
                     }
                }
            }
            else{
                 $HTML.="no data";
            }
        }
        echo $HTML;
    }

    public function report_abused() {
        $this->set('title_for_layout', 'Item');
        $user_id = $this->UserAuth->getUserId();
        $user = $this->UserAuth->getUser();
        $user_type = $user['UserGroup']['name'];
        $grp_id = $user['UserGroup']['id'];
//        $rest = $this->User->findById($user_id, array(
//            'fields' => 'rest_id'));
//        $rest_id = $rest['User']['rest_id'];
        $rest_id = $this->User->findById($user_id);

        $conditions = array();
        $today = date('Y-m-d');
        if ($user_type != 'Admin') {
            if ($grp_id == '5') {
//                echo $rest_id['User']['rest_id'];
                $conditions[] = array('OR' => array('Item.rest_id =' => $rest_id['User']['rest_id']));
            } else {
                $conditions[] = array('OR' => array('Item.user_id =' => $user_id));
            }
//            $conditions[] = array('OR' => array('Item.user_id =' => $user_id));
        }
//    $conditions[] = array('OR' => array('Item.id' => array($i_id)));
//        $con = array('AND' => array('Item.start_date <> ' => $today, 'Item.end_date <> ' => $today));
//        $con = array('AND' => array('Item.start_date =< ' => $today, 'Item.end_date >= ' => $today));  
//        $conditions[] =array('AND' => array('Item.start_date >= ' => $today, 'Item.end_date =< ' => $today));
        if (($this->request->is('post')) || ($this->request->is('put'))) {

            if (!empty($this->request->data['Search']['name'])) {
                $conditions[] = array('OR' => array('Item.name LIKE' => '%' . $this->request->data['Search']['name'] . '%'));
            }
            $this->set('flag', 'true');
            //pr($conditions);
        }

        $this->set('user_types', $user_type);

        //   get all category.
        $categors = $this->Category->find('list', array(
            'fields' => array('Category.id', 'Category.name'),
            'recursive' => false
        ));
        $this->set('ctys', $categors);
        $this->set('grp_id', $grp_id);
        $this->Paginator->settings = $this->paginate;

        // similar to findAll(), but fetches paged results
        $data = $this->Paginator->paginate('Item', $conditions);
        $this->set('items', $data);

//            $this->set(compact('items'));
//            $item = $this->Item->find('all', array(
//            'conditions' => $conditions
//        ));
//        $this->set('items', $item);
    }
    
/**
     * @method category_builk_delete
     * @return boolean
     * @uses It's use to delete multiple categories.
     */
    public function get_sub_category() {
        $this->layout = 'ajax';
        $this->autoRender = false;
        $parent_id = $this->request->data['parent_id'];
        echo $parent_id;
        $con = array();
        $con = array('AND' => array(
                'Category.parent_id' => $parent_id
        ));
        if (!empty($this->data)) {

            $this->loadModel('Category');
            $lists = $this->Category->find('all', array(
                'conditions' => $con));

            $cnt = sizeof($lists);
            if ($cnt > 0) {
                $HTML = "";
                foreach ($lists as $list):
                    $HTML.="<option value='" . $list['Category']['id'] . "'>" . $list['Category']['name'] . "</option>";

                endforeach;
            }
            else{
                $HTML="no data";
            }
        }
        echo $HTML;
    }
    
}

?>