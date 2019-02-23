<?php

App::import('Vendor', 'imagetransform');
App::import('Vendor', 'PHPExcel');

class OrdersController extends AppController {

    var $name = 'Orders';
//    public $helpers = array('PhpExcel');
    public $components = array('Paginator');
    var $uses = array('Order', 'Item', 'Restaurant', 'Qrcode', 'User','ItemChoice');
var $paginate = array(
        'paramType' => 'querystring',
        'limit' => 5,
        'maxLimit' => 1000
    );
    /**
     * @method index
     * @uses It's use to display all tickets.
     */
    public function index() {

        $user = $this->UserAuth->getUser();
        $user_type = $user['UserGroup']['name'];
        $grp_id = $user['UserGroup']['id'];

        $this->set('title_for_layout', 'Order');
        $user_id = $this->UserAuth->getUserId();
        $rest_id = $this->User->findById($user_id);
        $conditions = array();
//            if($grp_id == 5){
        if ($grp_id == 5) {
            $conditions[] = array('OR' => array('Order.rest_id =' => $rest_id['User']['rest_id']));
        }
        if ($grp_id == 4) {
            $conditions[] = array('OR' => array('Restaurant.user_id =' => $user_id));
        }
//             if($user_type != 'Admin'){
//            }
        if (!empty($this->request->query['status'])) {
            $conditions[] = array('OR' => array('Order.status =' => $this->request->query['status']));
        }
        if (($this->request->is('post')) || ($this->request->is('put'))) {

           $date=$this->request->data['Search']['order_date'];
           $datearray = explode('-', $date);
           
//            pr($datearray);
            $date_Array=array(date('Y-m-d', strtotime($datearray[0])), date('Y-m-d', strtotime( $datearray[1])));
//            pr($date_Array);
//            exit();
            if ((!empty($this->request->data['Search']['order_date'])) || (!empty($this->request->data['Search']['date_to']))) {
//                $conditions[] = array('OR' => array('DATE_FORMAT(Order.order_date,"%Y-%m-%d") BETWEEN ? and ?' => array(date('Y-m-d', strtotime(str_replace("/", "-", $this->request->data['Search']['date_from']))), date('Y-m-d', strtotime(str_replace("/", "-", $this->request->data['Search']['date_to']))))));
                $conditions[] = array('OR' => array('DATE_FORMAT(Order.order_date,"%Y-%m-%d") BETWEEN ? and ?' => array(date('Y-m-d', strtotime($datearray[0])), date('Y-m-d', strtotime( $datearray[1])))));
            }
            if (!empty($this->request->data['Search']['rest_name'])) {
                $conditions[] = array('OR' => array('Restaurant.name LIKE' => '%' . $this->request->data['Search']['rest_name'] . '%'));
            }
            if (!empty($this->request->data['Search']['table_name'])) {
                $conditions[] = array('OR' => array('RestaurantTable.name LIKE' => '%' . $this->request->data['Search']['table_name'] . '%'));
            }
            if (!empty($this->request->data['Search']['user_name'])) {
                $conditions[] = array('OR' => array('User.first_name LIKE' => '%' . $this->request->data['Search']['user_name'] . '%'));
            }
//            if (!empty($this->request->data['Search']['order_date'])) {
//                $conditions[] = array('OR' => array('Order.order_date LIKE' => '%' . $this->request->data['Search']['order_date'] . '%'));
//            }
//            pr($conditions);
//            exit();
            $this->set('flag', 'true');      
        }
//        $order = $this->Order->find('all', array(
//            'conditions' => $conditions));
//        $this->set('orders', $order);
         $this->Paginator->settings = $this->paginate;
     $order = $this->Paginator->paginate('Order',$conditions);
    $this->set('orders', $order);
        $this->set('grps', $grp_id);
    }

    /**
     * @method delete
     * @param integer $order_id
     * @return void
     */
    public function delete($order_id = null) {
        $this->set('title_for_layout', 'order delete');
        $user_id = $this->UserAuth->getUserId();
        $this->Order->id = $order_id;
        if ($this->Order->exists()) {
            if ($this->Order->delete($order_id)) {
                $this->Session->setFlash(__('Order has successfully deleted.'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Order not deleted. please, try an again.'));
                $this->redirect(array('action' => 'index'));
            }
        } else {
            $this->Session->setFlash(__('Order has not found.'));
            $this->redirect(array('action' => 'index'));
        }
    }

    /**
     * @method order_builk_delete
     * @return boolean
     * @uses It's use to delete multiple orders.
     */
    public function order_builk_delete() {
        $this->layout = 'ajax';

        $order_ids = $this->request->data['order_ids'];
        $response = array();
        if (!empty($order_ids)) {
            $order_id_array = explode(',', $order_ids);
            foreach ($order_id_array as $order_id) {
                //check order is exist or not.
                $this->Order->id = $order_id;
                if ($this->Order->exists()) {
                    $this->Order->delete($order_id);
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
     * @method change_active_status
     * @uses It's use to change active status.
     * @param int $item_id,varchar $st.
     */
    public function change_status($order_id = null, $st) {
        echo 'hii';
        exit();
        $this->Order->id = $order_id;
        $orderItem = $this->Order->findById($order_id);
        if ($this->Order->exists()) {
            if ($st == 0) {
                $this->Order->saveField('status', 0);
            } else if ($st == 2) {
                $this->Order->saveField('status', 2);
            }
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash(__('OrderItem not found.'));
            $this->redirect(array('action' => 'index'));
        }
    }
    
        public function running_index() {

        $user = $this->UserAuth->getUser();
        $user_type = $user['UserGroup']['name'];
        $grp_id = $user['UserGroup']['id'];

        $this->set('title_for_layout', 'Order');
        $user_id = $this->UserAuth->getUserId();
        $rest_id = $this->User->findById($user_id);
        $conditions = array();
        if ($grp_id == 5) {
            $conditions[] = array('OR' => array('Order.rest_id =' => $rest_id['User']['rest_id'],));
        }               
         $conditions[] = array('OR' => array('Order.status =' => 1));
        if (($this->request->is('post')) || ($this->request->is('put'))) {
            if ((!empty($this->request->data['Search']['date_from'])) || (!empty($this->request->data['Search']['date_to']))) {
                $conditions[] = array('OR' => array('DATE_FORMAT(Order.order_date,"%Y-%m-%d") BETWEEN ? and ?' => array(date('Y-m-d', strtotime(str_replace("/", "-", $this->request->data['Search']['date_from']))), date('Y-m-d', strtotime(str_replace("/", "-", $this->request->data['Search']['date_to']))))));
            }
            if (!empty($this->request->data['Search']['rest_name'])) {
                $conditions[] = array('OR' => array('Restaurant.name LIKE' => '%' . $this->request->data['Search']['rest_name'] . '%'));
            }
            if (!empty($this->request->data['Search']['table_name'])) {
                $conditions[] = array('OR' => array('RestaurantTable.name LIKE' => '%' . $this->request->data['Search']['table_name'] . '%'));
            }
            if (!empty($this->request->data['Search']['user_name'])) {
                $conditions[] = array('OR' => array('User.first_name LIKE' => '%' . $this->request->data['Search']['user_name'] . '%'));
            }
            if (!empty($this->request->data['Search']['order_date'])) {
                $conditions[] = array('OR' => array('Order.order_date LIKE' => '%' . $this->request->data['Search']['order_date'] . '%'));
            }
            $this->set('flag', 'true');      
        }
        $order = $this->Order->find('all', array(
            'fields' => array('Category.*','Order.*','ItemChoice.*','Item.*','OrderItem.*,RestaurantTable.*'),
            'joins' => array(
               
              array(
                    'table' => 'categories',
                    'alias' => 'Category',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array('Item.category_id = Category.id')
                ),
              array(
                    'table' => 'item_choices',
                    'alias' => 'ItemChoice',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array('ItemChoice.item_id = Item.id')
                ),
              array(
                    'table' => 'order_items',
                    'alias' => 'OrderItem',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array('OrderItem.order_id = Order.id')
                )  , array(
                    'table' => 'items',
                    'alias' => 'Item',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array('OrderItem.item_id = Item.id')
                ) 
                
        ),
            'conditions' => $conditions));
        $this->set('orders', $order);
        pr($order);
        exit();
        $this->set('grps', $grp_id);    
    }

}

?>