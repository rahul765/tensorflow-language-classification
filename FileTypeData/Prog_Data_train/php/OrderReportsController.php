<?php

App::import('Vendor', 'imagetransform');
App::import('Vendor', 'PHPExcel');
App::uses('CakeEmail', 'Network/Email');
App::uses('Paypal', 'Paypal.Lib');

class OrderReportsController extends AppController {

    var $name = 'OrderReports';
    public $helpers = array('Number', 'Form');
//    public $Utilities = array('Number','Form');
    public $components = array('Paginator');
    var $uses = array('paymentNotify', 'OrderItem', 'Item', 'Restaurant', 'Qrcode', 'User', 'Category', 'RestaurantTable', 'Order', 'Suggestion', 'Like', 'PaymentUpdate');
    var $paginate = array(
        'paramType' => 'querystring',
        'limit' => 5,
        'maxLimit' => 1000,
        'fields' => array('Order.*', 'ItemChoice.*', 'Item.*', 'OrderItem.*', 'RestaurantTable.*', 'User.*', 'Restaurant.*', 'UserSetting.*', '(UserSetting.bill_rate * Order.total_amount)/100 As total_com', 'count(OrderItem.id)'),
        'joins' => array(
            array(
                'table' => 'restaurant_tables',
                'alias' => 'RestaurantTable',
                'type' => 'inner',
                'foreignKey' => false,
                'conditions' => array('RestaurantTable.id = Order.table_id')
            ), array(
                'table' => 'restaurants',
                'alias' => 'Restaurant',
                'type' => 'inner',
                'foreignKey' => false,
                'conditions' => array('Order.rest_id = Restaurant.id')
            ), array(
                'table' => 'users',
                'alias' => 'User',
                'type' => 'inner',
                'foreignKey' => false,
                'conditions' => array('User.id = Order.user_id')
            ), array(
                'table' => 'user_settings',
                'alias' => 'UserSetting',
                'type' => 'inner',
                'foreignKey' => false,
                'conditions' => array('Restaurant.user_id = UserSetting.user_id')
            )
        )
    );

    public function item_report($id) {
        $user_type = $this->UserAuth->getUser();
        $user_type1 = $user_type['UserGroup']['name'];
        $user_grp_id = $user_type['UserGroup']['id'];
        $this->set('title_for_layout', 'OrderItem');
        $user_id = $this->UserAuth->getUserId();
        $rest_id = $this->User->findById($user_id);
        $conditions1 = array();
        $conditions = array();
//        if($user_grp_id == 5){
//         $conditions[] = array('AND' => array('Order.rest_id =' => $rest_id['User']['rest_id'],'OrderItem.status ' => 1));
//        }
        $rst_id = array();
        if (!empty($this->request->query['user'])) {
//            $rest =  $this->Restaurant->find('all',array('conditions'=>array('Restaurant.user_id'=>$this->request->query['user'])));
//         foreach ($rest as $rests ) {
//  array_push($rst_id, $rests['Restaurant']['id']);
//}
            $conditions[] = array('OR' => array('Restaurant.user_id' => $this->request->query['user']));
        }
        $conditions[] = array('OR' => array('Order.rest_id' => $id));
        if (($this->request->is('post')) || ($this->request->is('put'))) {

            $date = $this->request->data['Search']['order_date'];
            $datearray = explode('-', $date);

//            pr($datearray);
            $date_Array = array(date('Y-m-d', strtotime($datearray[0])), date('Y-m-d', strtotime($datearray[1])));
//            pr($date_Array);
//            exit();
            if ((!empty($this->request->data['Search']['order_date'])) || (!empty($this->request->data['Search']['date_to']))) {
//                $conditions[] = array('OR' => array('DATE_FORMAT(Order.order_date,"%Y-%m-%d") BETWEEN ? and ?' => array(date('Y-m-d', strtotime(str_replace("/", "-", $this->request->data['Search']['date_from']))), date('Y-m-d', strtotime(str_replace("/", "-", $this->request->data['Search']['date_to']))))));
                $conditions[] = array('OR' => array('DATE_FORMAT(Order.order_date,"%Y-%m-%d") BETWEEN ? and ?' => array(date('Y-m-d', strtotime($datearray[0])), date('Y-m-d', strtotime($datearray[1])))));
            }
            $this->set('flag', 'true');
            $this->set('ids', $id);
        }

//        exit();
        $this->Paginator->settings = $this->paginate;
        $orderItem = $this->Paginator->paginate('OrderItem', $conditions);
        $this->set('orderItems', $orderItem);
//        pr($orderItem);
//        exit();
//        pr($orderItem);


        $conditions1 = array('OR' => array('User.user_group_id ' => 4));

        $user = $this->User->find('list', array(
            'fields' => array('User.id', 'User.username'),
            'conditions' => $conditions1
        ));
        $this->set('users', $user);
    }

    public function bus_report() {
        $user_type = $this->UserAuth->getUser();
        $user_type1 = $user_type['UserGroup']['name'];
        $user_grp_id = $user_type['UserGroup']['id'];
        $this->set('title_for_layout', 'OrderItem');
        $user_id = $this->UserAuth->getUserId();
        $rest_id = $this->User->findById($user_id);
        $conditions = array();

        $rst_id = array();


//            $conditions[] = array('OR' => array('Restaurant.user_id' => $this->request->query['user']));
        if (($this->request->is('post')) || ($this->request->is('put'))) {
            if ((!empty($this->request->data['Search']['date_from'])) || (!empty($this->request->data['Search']['date_to']))) {
                pr($this->request->data['Search']);
                echo date('Y-m-d', str_replace("/", "-", $this->request->data['Search']['date_from']));
                echo date('Y-m-d', strtotime(str_replace("/", "-", $this->request->data['Search']['date_to'])));
                exit();
                $conditions[] = array('OR' => array('DATE_FORMAT(Order.order_date,"%Y-%m-%d") BETWEEN ? and ?' => array(date('Y-m-d', str_replace("/", "-", $this->request->data['Search']['date_from'])), date('Y-m-d', strtotime(str_replace("/", "-", $this->request->data['Search']['date_to']))))));
//                pr($conditions);
//                exit();
            }

            $this->set('flag', 'true');
        }
        $orderItem1 = $this->OrderItem->find('all', array(
            'fields' => array('Category.*', 'Order.*', 'ItemChoice.*', 'Item.*', 'OrderItem.*', 'RestaurantTable.*', 'User.*', 'Restaurant.*', '(UserSetting.bill_rate * Order.total_amount)/100 As total_com'),
            'joins' => array(
                array(
                    'table' => 'categories',
                    'alias' => 'Category',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array('Category.id = Item.category_id')
                ), array(
                    'table' => 'restaurant_tables',
                    'alias' => 'RestaurantTable',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array('RestaurantTable.id = Order.table_id')
                ), array(
                    'table' => 'restaurants',
                    'alias' => 'Restaurant',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array('Order.rest_id = Restaurant.id')
                ), array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array('User.id = Order.user_id')
                ), array(
                    'table' => 'user_settings',
                    'alias' => 'UserSetting',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array('Restaurant.user_id = UserSetting.user_id')
                )
            ),
            'conditions' => $conditions,
        ));
//            pr($orderItem1);
//            exit();
//        $this->Paginator->settings = $this->paginate;
//     $orderItem = $this->Paginator->paginate('OrderItem',$conditions);
        $this->set('orderItems', $orderItem1);
//        pr($orderItem);


        $conditions1 = array('OR' => array('User.user_group_id ' => 4));
//          $this->Paginator->settings = $this->paginate;
//     $user = $this->Paginator->paginate('User',$conditions1);
        $user = $this->User->find('all', array(
            'fields' => array('User.id', 'User.username'),
            'conditions' => $conditions1
        ));
        $this->set('users', $user);
    }

    public function restaurant_report($id) {
//           $this->layout = 'ajax';
        $this->paginate['fields'] = array('sum((Order.total_amount*user_settings.bill_rate)/100) As total_cum ', 'Restaurant.*', 'sum(Order.total_amount) As total');
        $this->paginate['joins'] = array();
        $this->paginate['group'] = array('Order.rest_id');
        $conditions = array('Restaurant.user_id' => $id);
        $this->Paginator->settings = $this->paginate;
        $res_ords = $this->Paginator->paginate('Order', $conditions);
//        $res_ord = $this->Order->find('all', array(
//            'conditions' => array('Restaurant.user_id' => $id),
//            'fields' => array('sum((Order.total_amount*user_settings.bill_rate)/100) As total_cum ','Restaurant.*','sum(Order.total_amount) As total'),
//            'group' => 'Order.rest_id'
//        ));
//        $this->set('res_ords', $res_ord);

        $html1 = '<div class="table-responsive">
                         <table class="table table-bordered table-hover" id="list">
                            <thead>
                                <tr>                                
                                    <th>ID</th>
                                    <th>Restaurant Name</th>
                                    <th>Restaurant Total</th>              
                                    <th>Total Commision</th>
                                    <th>Total</th>              

                                </tr>
                            </thead>
                            <tbody>';
        $total = array();
        $total['bus_total'] = 0;
        $total['com_total'] = 0;
        $total['final_total'] = 0;
        App::uses('CakeNumber', 'Utility');
        if (!empty($res_ords)) {
            foreach ($res_ords as $res_ords) {

                $html1 .= ' <tr>
                                         
                                            <td>' . $res_ords['Restaurant']['id'] . '</td>              
                                            <td>' . $res_ords['Restaurant']['name'] . '</td>   
                                            <td>' .
                        CakeNumber::currency($res_ords[0]['total'], 'USD') . '</td>';
                $total['bus'] = $res_ords[0]['total'];
                $total['com'] = $res_ords[0]['total_cum'];
                $html1 .= '<td>' . CakeNumber::currency($res_ords[0]['total_cum'], 'USD') . '</td>';

                $total['final'] = $total['bus'] - $total['com'];
                $html1 .= '<td>' . CakeNumber::currency($total['final'], 'USD')
                        . ' </td> </tr> ';

                $total['bus_total'] += $total['bus'];
                $total['com_total'] += $total['com'];
                $total['final_total'] += $total['final'];
            }
            $html1 .=' <tr>
                <td></td>
                <td class="fc-header-right"><b>Total:</b></td>
                <td>' . CakeNumber::currency($total['bus_total'], 'USD') . '</td>
                <td>' . CakeNumber::currency($total['com_total'], 'USD') . '</td>
                <td>' . CakeNumber::currency($total['final_total'], 'USD') . '</td>                
            </tr>';
        } else {
            $html1 .=' <tr><td colspan="5" class="fc-header-center">No Data Found</td></tr>';
        }
        $html1.='</tbody>
                        </table>                 
                    </div><div class="clearfix"></div>';
//        $html='';
        echo $html1;
        exit;
    }

    public function buisness_report() {

        $this->paginate['fields'] = array('User.id', 'User.username');
        $this->paginate['joins'] = array();
        if (($this->request->is('post')) || ($this->request->is('put'))) {
            if ((!empty($this->request->data['Search']['order_date']))) {
                $date = $this->request->data['Search']['order_date'];
                $datearray = explode('-', $date);

//            pr($datearray);
                $date_Array = array(date('Y-m-d', strtotime($datearray[0])), date('Y-m-d', strtotime($datearray[1])));
                $conditions[] = array('OR' => array('DATE_FORMAT(Order.order_date,"%Y-%m-%d") BETWEEN ? and ?' => $date_Array));
//                pr($conditions);
//                exit();
            }

            $this->set('flag', 'true');
        }
        $conditions1 = array('OR' => array('User.user_group_id ' => 4));
        $this->Paginator->settings = $this->paginate;
        $user = $this->Paginator->paginate('User', $conditions1);
        $this->set('users', $user);
        $orderItem1 = $this->Order->find('all', array(
            'fields' => array('sum((Order.total_amount*user_settings.bill_rate)/100) As total_cum ', 'UserSetting.user_id', 'sum(Order.total_amount) As total'),
            'group' => 'Restaurant.user_id'
        ));
        $this->set('orderItems', $orderItem1);
//        pr($orderItem1);
//        exit();
//         $user = $this->User->find('all', array(
//            'fields' => array('User.id', 'User.username'),
//            'conditions' => $conditions1
//        ));

        $this->set('users', $user);
//                $res_ord = $this->Order->find('all', array(
//            'conditions' => array('Restaurant.user_id' => $id),
//            'fields' => array('sum((Order.total_amount*user_settings.bill_rate)/100) As total_cum ','Restaurant.*','sum(Order.total_amount) As total'),
//            'group' => 'Order.rest_id'
//        ));
//
//        $this->set('res_ords', $res_ord);
    }

    public function res_report() {
        $grp_id = $this->UserAuth->getGroupId();
//           $this->layout = 'ajax';
        $user_id = $this->UserAuth->getUserId();
        $conditions = array();
        $conditions1 = array();
        $cnd = '';
        $this->Order->virtualFields = array(
            'total_order' => 'ifnull(sum(Order.total_amount),0)',
            'total_cum' => 'sum((Order.total_amount*user_settings.bill_rate)/100)'
        );
        if ($grp_id == 4) {
            $conditions = array('OR' => array('Restaurant.user_id' => $user_id));
        } else if ($grp_id == 5) {
            $conditions = array('OR' => array('Order.status' => 1));
        }

        if (($this->request->is('post')) || ($this->request->is('put'))) {

//           $date=$this->request->data['Search']['order_date'];
//           $datearray = explode('-', $date);
//            pr($datearray);
//            $date_Array=array(date('Y-m-d', strtotime($datearray[0])), date('Y-m-d', strtotime( $datearray[1])));
            if (!empty($this->request->data['Search']['name'])) {
                $conditions[] = array('OR' => array('Restaurant.name LIKE' => '%' . $this->request->data['Search']['name'] . '%'));
            }
            if (!empty($this->request->data['Search']['year']) && $this->request->data['Search']['mon'] == null && $this->request->data['Search']['day'] == null) {

                $conditions[] = array('OR' => array('Order.order_date LIKE' => '%' . $this->request->data['Search']['year'] . '%'));
            }
            if (!empty($this->request->data['Search']['year']) && !empty($this->request->data['Search']['mon']) && $this->request->data['Search']['day'] == null) {

                $conditions[] = array('OR' => array('Order.order_date LIKE' => '%' . $this->request->data['Search']['year'] . '-' . $this->request->data['Search']['mon'] . '%'));
            }
            if (!empty($this->request->data['Search']['year']) && !empty($this->request->data['Search']['mon']) && !empty($this->request->data['Search']['day'])) {

                $conditions[] = array('OR' => array('Order.order_date LIKE' => '%' . $this->request->data['Search']['year'] . '-' . $this->request->data['Search']['mon'] . '-' . $this->request->data['Search']['day'] . '%'));
            }
            if (!empty($this->request->data['Search']['famt']) && !empty($this->request->data['Search']['lamt'])) {
                $cnd = 'Having Order__total_order BETWEEN ' . $this->request->data['Search']['famt'] . ' and ' . $this->request->data['Search']['lamt'];
//                $conditions[] = array('OR' => array('Order.total_order BETWEEN ? and ?' => array($this->request->data['Search']['famt'], $this->request->data['Search']['lamt'])));
//                $this->paginate['group']=array('sum((Order.total_amount*user_settings.bill_rate)/100)');
            }
//            pr($date_Array);
//            exit();
//            if ((!empty($this->request->data['Search']['order_date'])) || (!empty($this->request->data['Search']['date_to']))) {
////                $conditions[] = array('OR' => array('DATE_FORMAT(Order.order_date,"%Y-%m-%d") BETWEEN ? and ?' => array(date('Y-m-d', strtotime(str_replace("/", "-", $this->request->data['Search']['date_from']))), date('Y-m-d', strtotime(str_replace("/", "-", $this->request->data['Search']['date_to']))))));
//                $conditions[] = array('OR' => array('DATE_FORMAT(Order.order_date,"%Y-%m-%d") BETWEEN ? and ?' => array(date('Y-m-d', strtotime($datearray[0])), date('Y-m-d', strtotime( $datearray[1])))));
//            }
            $this->set('flag', 'true');
        }
        $this->paginate['fields'] = array('Usersetting.*', 'Restaurant.*', 'Order.*', 'User.*', 'User1.*');
        $this->paginate['joins'] = array(array(
                'table' => 'users',
                'alias' => 'User1',
                'type' => 'inner',
                'foreignKey' => false,
                'conditions' => array('User1.id = Restaurant.user_id')
        ));
        $this->paginate['group'] = array('Order.rest_id ' . $cnd);
//        $this->paginate['having'] = $conditions1;


        $this->Paginator->settings = $this->paginate;
        $res_ords = $this->Paginator->paginate('Order', $conditions);
        $this->set('res_ords', $res_ords);
//        pr($res_ords);
//        exit();      
        $year = array();
        $day = array();
//        $years=array();
        foreach (range(1, 31) as $days) {
//  array_push($day, $days);
            $day[$days] = $days;
        }
        $mon = array('01' => 'jan', '02' => 'feb', '03' => 'mar', '04' => 'apr', '05' => 'may', '06' => 'june', '07' => 'jul', '08' => 'aug', '09' => 'sept', '10' => 'oct', '11' => 'nov', '12' => 'dec');
        $first_year = '2010';
        $last_year = date('Y');
//         $test_arr = array();
        foreach (range($first_year, $last_year) as $years) {
            $year[$years] = $years;
        }
        $last_mon = date('Y-m', strtotime('last month'));
        $this->set('last_mon', $last_mon);
        if ($grp_id == 4) {


            $conditions_pay = array('OR' => array('DATE_FORMAT(Order.order_date ,"%Y-%m")' => $last_mon));
            $conditions_pay[] = array('OR' => array('Restaurant.user_id' => $user_id));
            $ord = $this->Order->find('first', array('group' => array('Restaurant.user_id'),
                'conditions' => $conditions_pay));
            $this->set('payment', $ord);
        }
//        pr($ord);
//        exit();
        $this->set('day', $day);
        $this->set('year', $year);
        $this->set('mon', $mon);
        /* for pay now button */
$con=array();
        $con[] = array('OR' => array('PaymentUpdate.payment_status' => 'Completed',array('AND' => array('PaymentUpdate.payment_status' => 'Pending', 'PaymentUpdate.pending_reason' => 'paymentreview'))));

//        $con['AND'] = array('OR' => array());
        $con[] = array('AND' => array('PaymentUpdate.user_id' => $user_id,'PaymentUpdate.payment_mon_year' => $last_mon));
     
        $pay = $this->PaymentUpdate->find('all', array('conditions' => $con));
       $this->set('paybtn',$pay);
    }

    public function item_stat($id) {
        $user_type = $this->UserAuth->getUser();
        $user_type1 = $user_type['UserGroup']['name'];
        $user_grp_id = $user_type['UserGroup']['id'];
        $this->set('title_for_layout', 'OrderItem');
        $user_id = $this->UserAuth->getUserId();
        $rest_id = $this->User->findById($user_id);
        $conditions1 = array();
        $conditions = array();
        $cnd = '';
        $this->Order->virtualFields = array(
            'total_order' => 'ifnull(sum(Order.total_amount),0)',
            'total_cum' => 'sum((Order.total_amount*Usersetting.bill_rate)/100)'
        );
//        if($user_grp_id == 5){
//         $conditions[] = array('AND' => array('Order.rest_id =' => $rest_id['User']['rest_id'],'OrderItem.status ' => 1));
//        }
        $conditions[] = array('Category.rest_id' => $id);
        $rst_id = array();
        if (!empty($this->request->query['status'])) {
//            $rest =  $this->Restaurant->find('all',array('conditions'=>array('Restaurant.user_id'=>$this->request->query['user'])));
//         foreach ($rest as $rests ) {
//  array_push($rst_id, $rests['Restaurant']['id']);
//}
            $conditions[] = array('AND' => array('Order.status' => $this->request->query['status']));
        }
        if (($this->request->is('post')) || ($this->request->is('put'))) {

//           $date=$this->request->data['Search']['order_date'];
//           $datearray = explode('-', $date);
//            pr($datearray);
//            $date_Array=array(date('Y-m-d', strtotime($datearray[0])), date('Y-m-d', strtotime( $datearray[1])));
            if (!empty($this->request->data['Search']['name'])) {
                $conditions[] = array('OR' => array('Restaurant.name LIKE' => '%' . $this->request->data['Search']['name'] . '%'));
            }
            if (!empty($this->request->data['Search']['year']) && $this->request->data['Search']['mon'] == null && $this->request->data['Search']['day'] == null) {
                $syear = $this->request->data['Search']['year'];
                $conditions[] = array('OR' => array('DATE_FORMAT(Order.order_date ,"%Y")' => $syear));
//                pr($conditions);
//                exit();
            }
            if (!empty($this->request->data['Search']['year']) && !empty($this->request->data['Search']['mon']) && $this->request->data['Search']['day'] == null) {
                $smonyear = $this->request->data['Search']['year'] . '-' . $this->request->data['Search']['mon'];
                $conditions[] = array('OR' => array('DATE_FORMAT(Order.order_date ,"%Y-%m")' => array(date('Y-m', strtotime($smonyear)))));
            }
            if (!empty($this->request->data['Search']['year']) && !empty($this->request->data['Search']['mon']) && !empty($this->request->data['Search']['day'])) {
                $date1 = $this->request->data['Search']['year'] . '-' . $this->request->data['Search']['mon'] . '-' . $this->request->data['Search']['day'];
                $conditions[] = array('OR' => array('DATE_FORMAT(Order.order_date ,"%Y-%m-%d")' => array(date('Y-m-d', strtotime($date1)))));
            }
            if (!empty($this->request->data['Search']['famt']) && !empty($this->request->data['Search']['lamt'])) {
                $cnd = 'Having Order__total_order BETWEEN ' . $this->request->data['Search']['famt'] . ' and ' . $this->request->data['Search']['lamt'];
//                $conditions[] = array('OR' => array('sum((Order.total_amount*Usersetting.bill_rate)/100) BETWEEN ? and ?' => array($this->request->data['Search']['famt'], $this->request->data['Search']['lamt'])));
            }
//            pr($conditions);
//            exit();
//            if ((!empty($this->request->data['Search']['order_date'])) || (!empty($this->request->data['Search']['date_to']))) {
////                $conditions[] = array('OR' => array('DATE_FORMAT(Order.order_date,"%Y-%m-%d") BETWEEN ? and ?' => array(date('Y-m-d', strtotime(str_replace("/", "-", $this->request->data['Search']['date_from']))), date('Y-m-d', strtotime(str_replace("/", "-", $this->request->data['Search']['date_to']))))));
//                $conditions[] = array('OR' => array('DATE_FORMAT(Order.order_date,"%Y-%m-%d") BETWEEN ? and ?' => array(date('Y-m-d', strtotime($datearray[0])), date('Y-m-d', strtotime( $datearray[1])))));
//            }
            $this->set('flag', 'true');
        }
        $i = $this->OrderItem->find('all', array(
            'fields' => array('OrderItem.item_id', 'Item.*', 'Category.*', 'Order.*', 'Usersetting.*', 'Restaurant.*'
                , 'COUNT(OrderItem.id) AS total_ord', 'sum((Order.total_amount*Usersetting.bill_rate)/100) As total_cum '),
            'joins' => array(array(
                    'table' => 'restaurants',
                    'alias' => 'Restaurant',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array('Order.rest_id = Restaurant.id')
                ), array(
                    'table' => 'user_settings',
                    'alias' => 'UserSetting',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array('Restaurant.user_id = UserSetting.user_id')
                )
            ),
            'group' => array('Item.id'),
            'conditions' => $conditions
        ));


        $this->paginate['fields'] = array('Item.category_id', 'Category.*', 'Order.*', 'Usersetting.*', 'Restaurant.*'
            , 'COUNT(OrderItem.id) AS total_ord', 'sum((Order.total_amount*Usersetting.bill_rate)/100) As total_cum ');
        $this->paginate['joins'] = array(array(
                'table' => 'restaurants',
                'alias' => 'Restaurant',
                'type' => 'inner',
                'foreignKey' => false,
                'conditions' => array('Order.rest_id = Restaurant.id')
            ), array(
                'table' => 'user_settings',
                'alias' => 'UserSetting',
                'type' => 'inner',
                'foreignKey' => false,
                'conditions' => array('Restaurant.user_id = UserSetting.user_id')
            )
        );
        $this->paginate['group'] = array('Item.category_id');


        $this->Paginator->settings = $this->paginate;
        $c = $this->Paginator->paginate('OrderItem', $conditions);

        // for count photos
        $s = $this->Suggestion->find('all', array('fields' => array('COUNT(Suggestion.id) As tot_sug', 'Suggestion.Item_id'),
            'group' => array('Suggestion.Item_id'),
            'conditions' => array('not' => array('Suggestion.suggested_image' => ''))));
        $sc = $this->Suggestion->find('all', array('fields' => array('COUNT(Suggestion.id) As tot_sug', 'Item.category_id'),
            'joins' => array(array('table' => 'categories',
                    'alias' => 'Category',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array('Category.id = Item.category_id'))),
            'group' => array('Item.category_id'),
            'conditions' => array('not' => array('Suggestion.suggested_image' => ''))));

        // for count status update 
        $update_item = $this->Suggestion->find('all', array('fields' => array('COUNT(Suggestion.id) As tot_update', 'Suggestion.Item_id'),
            'group' => array('Suggestion.Item_id'),
            'conditions' => array('Suggestion.suggested_image' => '')));
        $update_category = $this->Suggestion->find('all', array('fields' => array('COUNT(Suggestion.id) As tot_update', 'Item.category_id'),
            'joins' => array(array('table' => 'categories',
                    'alias' => 'Category',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array('Category.id = Item.category_id'))),
            'group' => array('Item.category_id'),
            'conditions' => array('Suggestion.suggested_image' => '')));
        // for count like
        $l = $this->Like->find('all', array('fields' => array('COUNT(Like.id) As tot_like', 'Suggestion.Item_id'),
            'group' => array('Suggestion.Item_id'),
            'conditions' => array('Like.is_like' => 1)));
        $lc = $this->Like->find('all', array('fields' => array('COUNT(Like.id) As tot_like', 'Item.category_id', 'Suggestion.Item_id'),
            'joins' => array(array('table' => 'categories',
                    'alias' => 'Category',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array('Category.id = Item.category_id'))),
            'group' => array('Item.category_id'),
            'conditions' => array('Like.is_like' => 1)));
        $this->set('cat', $c);
        $this->set('item', $i);
        $this->set('sug', $s);
        $this->set('sugcat', $sc);
        $this->set('like', $l);
        $this->set('likecat', $lc);
        $this->set('updateitem', $update_item);
        $this->set('updatecategory', $update_category);
        $this->set('rid', $id);

        $year = array();
        $day = array();
//        $years=array();
        foreach (range(1, 31) as $days) {
//  array_push($day, $days);
            $day[$days] = $days;
        }
        $mon = array('01' => 'jan', '02' => 'feb', '03' => 'mar', '04' => 'apr', '05' => 'may', '06' => 'june', '07' => 'jul', '08' => 'aug', '09' => 'sept', '10' => 'oct', '11' => 'nov', '12' => 'dec');
        $first_year = '2010';
        $last_year = date('Y');
//         $test_arr = array();
        foreach (range($first_year, $last_year) as $years) {
            $year[$years] = $years;
        }

        $this->set('day', $day);
        $this->set('year', $year);
        $this->set('mon', $mon);
    }

    /* this function not use */

    public function paypal_index() {
        $user_id = $this->UserAuth->getUserId();
        $last_mon = date('Y-m', strtotime('last month'));
//          exit();
        $this->Order->virtualFields = array(
            'total_order' => 'ifnull(sum(Order.total_amount),0)',
            'total_cum' => 'sum((Order.total_amount*Usersetting.bill_rate)/100)'
        );
        $conditions = array('OR' => array('DATE_FORMAT(Order.order_date ,"%Y-%m")' => $last_mon));
        $conditions[] = array('OR' => array('Restaurant.user_id' => $user_id));
        $ord = $this->Order->find('first', array('group' => array('Restaurant.user_id'),
            'conditions' => $conditions));
//$ord=  $this->Order->find('all');
// pr($ord);
// exit();

        if (($this->request->is('post')) || ($this->request->is('put'))) {
//            pr($this->request->data);
            $this->request->data['user_id'] = $user_id;
            $this->request->data['payment_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['payment_date']));
            $this->request->data['payment_mon_year'] = $last_mon;
            pr($this->request->data);
            $this->PaymentUpdate->save($this->request->data);
        }
        $this->set('order', $ord);
        pr($ord);
    }

    public function ord_mail() {


        $today = date('Y-m-d');
        $payment_date = date('Y-m-t');

        if ($payment_date == $today) {
            $users = $this->User->find('all', array('conditions' => array('User.user_group_id' => 4)));
            $email = new CakeEmail('gmail');
            $email->from('tem.narola@narolainfotech.com');
            $email->to('dc.narola@narolainfotech.com');
//            $email->to($pays['User']['email']);
            $email->subject('Pay monthly revenue for orders');
            //$email->transport('Debug');

            $html = 'Hello ' . $usr['User']['first_name'] . ' ' . $usr['User']['last_name'] . ' user please pay revenue of order for ' . $last_mon . ' month';

            try {
                $result = $email->send($html);
                echo "Email Sent";
            } catch (Exception $ex) {
                // we could not send the email, ignore it
                echo $result = "Could not send registration email to userid-";
            }
        } else {
            $last_mon = date('Y-m', strtotime('last month'));
            $con = array('OR' => array('PaymentUpdate.payment_status' => 'Completed'));
            $con['OR'] = array('AND' => array('PaymentUpdate.payment_status' => 'Pending', 'PaymentUpdate.pending_reason' => 'paymentreview'));
            $con['OR'] = array('OR' => array('PaymentUpdate.payment_mon_year' => $last_mon));
            $pay = $this->PaymentUpdate->find('all', array('fields' => 'DISTINCT PaymentUpdate.user_id', 'conditions' => $con));
            $useridarr = array();
            if (!empty($pay)) {
                foreach ($pay as $pays) {

                    array_push($useridarr, $pays['PaymentUpdate']['user_id']);
                }
//    pr($useridarr);
            }
            $users = $this->User->find('all', array('conditions' => array('User.user_group_id' => 4)));
            foreach ($users as $usr) {
                if (!in_array($usr['User']['id'], $useridarr)) {

                    echo $usr['User']['id'];
                    $email = new CakeEmail('gmail');
                    $email->from('tem.narola@narolainfotech.com');
                    $email->to($usr['User']['email']);
//            $email->to($pays['User']['email']);
                    $email->subject('Pay monthly revenue for orders');
                    //$email->transport('Debug');

                    $html = 'Hello ' . $usr['User']['first_name'] . ' ' . $usr['User']['last_name'] . ' user please pay revenue of order for ' . $last_mon . ' month';


                    try {
                        $result = $email->send($html);
//                echo "Email Sent";
                    } catch (Exception $ex) {
                        // we could not send the email, ignore it
                        echo $result = "Could not send registration email to userid-";
                    }
                }
            }
        }
    }

    public function exp_check() {
        $user_id = $this->UserAuth->getUserId();
        $last_mon = date('Y-m', strtotime('last month'));
//          exit();
        $this->Order->virtualFields = array(
            'total_order' => 'ifnull(sum(Order.total_amount),0)',
            'total_cum' => 'sum((Order.total_amount*Usersetting.bill_rate)/100)'
        );
        $conditions = array('OR' => array('DATE_FORMAT(Order.order_date ,"%Y-%m")' => $last_mon));
        $conditions[] = array('OR' => array('Restaurant.user_id' => $user_id));
        $ord = $this->Order->find('first', array('group' => array('Restaurant.user_id'),
            'conditions' => $conditions));
//        echo $ord['Order']['total_cum'];
//        exit();
        $this->Paypal = new Paypal(array(
            'sandboxMode' => true,
            'nvpUsername' => 'suv.narola_api1.narolainfotech.com',
            'nvpPassword' => '4V36KYLEQDZCLEGM',
            'nvpSignature' => 'Abls.48hZS6VhoR3RvDyw0BriH5sAK0ZhKW4u3MDYGNjiTf1WavThNgp'
        ));

        $order = array(
            'description' => 'Your Payment to Website\'s admininstartor',
            'currency' => 'EUR',
            'return' => 'http://clientapp.narolainfotech.com/PG/Foodorder/CMS/OrderReports/return_data',
            'cancel' => 'http://clientapp.narolainfotech.com/PG/Foodorder/CMS/OrderReports/cancel',
            'shipping' => '10.00',
//      'total'=>intval($ord['Order']['total_cum'])
            'items' => array(
                0 => array(
                    'name' => 'Blue shoes',
                    'description' => 'Your payment for ' . $last_mon . ' month',
                    'subtotal' => round($ord['Order']['total_cum'], 2)
                )
            )
        );

        $token = $this->Paypal->setExpressCheckout($order);
        header("Location: " . $token);
        exit();
    }

    public function notify() {
        $data = serialize($this->request->data);

        $this->paymentNotify->save($data);
        exit();
    }

    public function cancel() {
        $data = serialize($this->request->data);

        $this->paymentNotify->save($data);
        exit();
    }

    public function return_data() {
        $user_id = $this->UserAuth->getUserId();
        $last_mon = date('Y-m', strtotime('last month'));
//          exit();
        $this->Order->virtualFields = array(
            'total_order' => 'ifnull(sum(Order.total_amount),0)',
            'total_cum' => 'sum((Order.total_amount*Usersetting.bill_rate)/100)'
        );
        $conditions = array('OR' => array('DATE_FORMAT(Order.order_date ,"%Y-%m")' => $last_mon));
        $conditions[] = array('OR' => array('Restaurant.user_id' => $user_id));
        $ord = $this->Order->find('first', array('group' => array('Restaurant.user_id'),
            'conditions' => $conditions));
        $this->Paypal = new Paypal(array(
            'sandboxMode' => true,
            'nvpUsername' => 'suv.narola_api1.narolainfotech.com',
            'nvpPassword' => '4V36KYLEQDZCLEGM',
            'nvpSignature' => 'Abls.48hZS6VhoR3RvDyw0BriH5sAK0ZhKW4u3MDYGNjiTf1WavThNgp'
        ));
        $order = array(
            'description' => 'Your Payment to Website\'s admininstartor',
            'currency' => 'EUR',
            'return' => 'http://clientapp.narolainfotech.com/PG/Foodorder/CMS/OrderReports/return_data',
            'cancel' => 'http://clientapp.narolainfotech.com/PG/Foodorder/CMS/OrderReports/cancel',
            'shipping' => '10.00',
//      'total'=>intval($ord['Order']['total_cum'])
            'items' => array(
                0 => array(
                    'name' => 'Blue shoes',
                    'description' => 'Your payment for ' . $last_mon . ' month',
                    'subtotal' => round($ord['Order']['total_cum'], 2)
                )
            )
        );

        if (isset($_REQUEST['token'])) {
            echo $token = $_REQUEST['token'];
        } else {
            echo 'hii';
        }
        $data = $this->Paypal->getExpressCheckoutDetails($token);

        $data1 = $this->Paypal->doExpressCheckoutPayment($order, $data['TOKEN'], $data['PAYERID']);
        if ($data1['ACK'] == 'Success') {
            $send_data['user_id'] = $user_id;
            $send_data['payment_mon_year'] = $last_mon;
            $send_data['payment_date'] = $data1['TIMESTAMP'];
            $send_data['payment_status'] = $data1['PAYMENTINFO_0_PAYMENTSTATUS'];
            $send_data['payment_type'] = $data1['PAYMENTINFO_0_PAYMENTTYPE'];
            $send_data['pending_reason'] = $data1['PAYMENTINFO_0_PENDINGREASON'];
            $send_data['receipt_id'] = $data1['PAYMENTINFO_0_TRANSACTIONID'];
            $send_data['payment_gross'] = $data1['PAYMENTINFO_0_SETTLEAMT'];
            $send_data['mc_currency'] = $data1['PAYMENTINFO_0_CURRENCYCODE'];
            if ($this->PaymentUpdate->save($send_data)) {
//       $this->session->setflash("Sucessfully paymment");
            } else {
                $fail_data = serialize($data1);
                $this->PaymentNotify->save($fail_data);
//      $this->Session->setFlash(__('Not Sucessfully Payment'));
            }
        } else {
            $fail_data = serialize($data1);
            $this->PaymentNotify->save($fail_data);
        }
//$data = serialize($data1);
//$this->
//$data2=  unserialize($data);
//pr($data2);
        pr($data1);
//     pr($data);
        exit();
    }

}

?>
