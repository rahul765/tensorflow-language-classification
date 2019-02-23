<?php

App::import('Vendor', 'imagetransform');
App::import('Vendor', 'PHPExcel');

class UserSettingsController extends AppController {

    var $name = 'UserSettings';
//    public $helpers = array('PhpExcel');
    public $components = array('Paginator');
    var $uses = array('Category', 'UserSetting', 'RestUserSettingMapping', 'Restaurant', 'User', 'UserSettingChoice');
var $paginate = array(
        'paramType' => 'querystring',
        'limit' => 5,
        'maxLimit' => 1000
    );
    /**
     * @method index
     * @uses It's use to display all userSettings.
     */
    public function index() {
                $this->set('title_for_layout', 'UserSetting');
        $user_id = $this->UserAuth->getUserId();
        $user = $this->UserAuth->getUser();
        $user_type = $user['UserGroup']['name'];
            $grp_id = $user['UserGroup']['id'];
      $rest_id = $this->User->findById($user_id);
   
        $conditions = array();
        $conditions1 = array();
        $today = date('Y/m/d');
 $conditions1 = array('OR' => array('User.user_group_id '=>4));

        if (($this->request->is('post')) || ($this->request->is('put'))) {
            if (!empty($this->request->data['Search']['name'])) {  
               
                $conditions[] = array('OR' => array('User.username LIKE' => '%' . $this->request->data['Search']['name'] . '%'));
                $conditions1[] = array('OR' => array('User.username LIKE' => '%' . $this->request->data['Search']['name'] . '%'));
            }
            $this->set('flag', 'true');
//            pr($conditions);
        }
        $userSetting = $this->UserSetting->find('all', array(
            'conditions' => $conditions
        ));
       
        $this->set('userSettings', $userSetting);
//        pr($conditions1);
//        exit();
         
          
//    $user = $this->User->find('all', array(
//            'fields' => array('User.id','User.username'),           
//        'conditions' => $conditions1
//        ));
//        $this->set('users', $user);
              $this->Paginator->settings = $this->paginate;
     $user = $this->Paginator->paginate('User',$conditions1);
    $this->set('users', $user);

    }
    /**
     * @method edit
     * @uses It's use to edit userSettings.
     */
    public function setting($userid = null) {
         $this->layout = 'ajax';
        $this->set('title_for_layout', 'UserSetting edit'); 
//        exit();    pr($this->request->data);
    
        $id=$this->UserSetting->findByUserId($userid);     
        if(!empty($id)){
            $this->UserSetting->id=$id['UserSetting']['id'];           
               $this->set('rate',$id);     
        }
              if ($this->request->is('post')) {
                 $settings = array();                 
                $settings['UserSetting']['user_id'] = $userid;
                $settings['UserSetting']['bill_rate'] = $this->request->data['data_item'];
                  if ($this->UserSetting->save($settings)) {
                echo json_encode($settings['UserSetting']);
                exit;
                }
            }
                         
       
    }

}

?>