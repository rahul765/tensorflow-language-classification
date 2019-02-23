<?php

App::import('Vendor', 'imagetransform');
App::import('Vendor', 'PHPExcel');

class RestaurantsController extends AppController {

    var $name = 'Restaurants';
    public $helpers = array('QrCode');
    public $components = array('Paginator');
var $uses = array('Category','Item','Restaurant','Qrcode','User');
var $paginate = array(
        'paramType' => 'querystring',
        'limit' => 5,
        'maxLimit' => 1000
    );
    /**
     * @method index
     * @uses It's use to display all Restaurants.
     */
    public function index() {
     
             $user_type = $this->UserAuth->getUser();
        $user_type = $user_type['UserGroup']['name'];
            $this->set('title_for_layout', 'restaurant');
            $user_id = $this->UserAuth->getUserId();
            $conditions = array();
            if($user_type != 'Admin'){
                 $conditions[] = array('OR' => array('Restaurant.user_id =' => $user_id));
            }
            //whenever user can search any value that times this conditions will satisfied.
            if (($this->request->is('post')) || ($this->request->is('put'))) {
            
                
                if (!empty($this->request->data['Search']['name'])) {
                    $conditions[] = array('OR' => array('Restaurant.name LIKE' => '%' . $this->request->data['Search']['name'] . '%'));
                }
                                 $this->set('flag','true');
                //pr($conditions);
            }
//               $this->set('data', $this->Section->findAllThreaded(null, null, 'name')); 
//            $restaurant = $this->Restaurant->find('all', array(
//                'conditions' => $conditions));
//           
//          
//            $this->set('restaurants', $restaurant);
               $this->Paginator->settings = $this->paginate;
     $restaurant = $this->Paginator->paginate('Restaurant',$conditions);
    $this->set('restaurants', $restaurant);
       
    }
    /**
     * @method add
     * @uses It's use to create Restaurant.
     */
    public function add() {
        $this->set('title_for_layout', 'restaurant add');
        $user_id = $this->UserAuth->getUserId();
        
             $user_type1 = $this->UserAuth->getUser();
        $user_type = $user_type1['UserGroup']['name'];
        $grp_id = $user_type1['UserGroup']['id'];
//        pr($user_type);
//        exit();
             $restaurant = array();
        if ($this->request->is('post')) {
           if($user_type != 'Admin'){
                $restaurant['Restaurant']['user_id'] =$user_id;
             
           }
 else{
        $restaurant['Restaurant']['user_id'] = $this->request->data['Restaurant']['user_id'];
              
 }
            
           
            $restaurant['Restaurant']['name'] = $this->request->data['Restaurant']['name'];
            $restaurant['Restaurant']['address'] = $this->request->data['Restaurant']['address'];
            $restaurant['Restaurant']['area'] = $this->request->data['Restaurant']['area'];
            $restaurant['Restaurant']['city'] = $this->request->data['Restaurant']['city'];
            $restaurant['Restaurant']['email'] = $this->request->data['Restaurant']['email'];
            $restaurant['Restaurant']['contact_no'] = $this->request->data['Restaurant']['contact_no'];
          
            if ($this->Restaurant->save($restaurant)) {
                $restaurant_id = $this->Restaurant->getLastInsertId();
                $this->save_attachment($restaurant_id, $this->request->data['Restaurant']['image']);            
                $this->Session->setFlash(__('Your restaurant successfully created.'));
                $this->redirect(array('controller' => 'Restaurants', 'action' => 'index'));
            }
        }
//        $user = $this->User->find('list', array('fields' => array('id', 'username')));
        $user = $this->User->find('list', array('fields' => array('id', 'username'),'conditions'=>array('User.user_group_id'=>4)));
            $this->set('user',$user);
           
        }
        
        
     public function save_attachment($rest_id, $attachment_data) {
         $rest_array=array();
       if(!empty($attachment_data['name']))
                {
         
                        $file = $attachment_data; //put the data into a var for easy use

                        $ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
                        $arr_ext = array('jpg', 'jpeg', 'gif','png'); //set allowed extensions

                        //only process if the extension is valid
                        if(in_array($ext, $arr_ext))
                        {
                            $filenm=$rest_id .'_'. $file['name'];
                         
                                //do the actual uploading of the file. First arg is the tmp name, second arg is 
                                //where we are putting it
                              if(move_uploaded_file($file['tmp_name'], WWW_ROOT . 'restaurant_images/' .$filenm));
{
    
                                //prepare the filename for database entry
                                $this->Restaurant->saveField('image',  $filenm);
//                                $this->data['User']['image'] = $file['name'];
}
                        }
                }

       
    }
        /**
     * @method edit
     * @uses It's use to edit Restaurants.
     */
    public function edit($restaurant_id = null) {
        $this->set('title_for_layout', 'restaurant edit');
        $user_id = $this->UserAuth->getUserId();
        $this->Restaurant->id = $restaurant_id;
         $user_type = $this->UserAuth->getUser();
        $user_type = $user_type['UserGroup']['name'];
        if ($this->Restaurant->exists()) {
            if ($this->request->is('post')) {
                   $restaurant = array();
          if($user_type == 'Admin'){
              
              $restaurant['Restaurant']['user_id'] = $this->request->data['Restaurant']['user_id'];
          }
          
          
            $restaurant['Restaurant']['name'] = $this->request->data['Restaurant']['name'];
            $restaurant['Restaurant']['address'] = $this->request->data['Restaurant']['address'];
            $restaurant['Restaurant']['area'] = $this->request->data['Restaurant']['area'];
            $restaurant['Restaurant']['city'] = $this->request->data['Restaurant']['city'];
            $restaurant['Restaurant']['email'] = $this->request->data['Restaurant']['email'];
            $restaurant['Restaurant']['contact_no'] = $this->request->data['Restaurant']['contact_no'];
           
            if ($this->Restaurant->save($restaurant)) {               
                $this->save_attachment($restaurant_id, $this->request->data['Restaurant']['image']); 
               
                
                $this->Session->setFlash(__('Your restaurant successfully edited.'));
                $this->redirect(array('controller' => 'Restaurants', 'action' => 'index'));
            }
              //get all restaurant.
       
    
           
            }
            $user = $this->User->find('list', array('fields' => array('id', 'username')));
            $this->set('user',$user); 
                $restaurant1 = $this->Restaurant->findById($restaurant_id);
            $this->set('restaurant', $restaurant1);
         } else {
            $this->Session->setFlash(__('restaurant has not found.'));
            $this->redirect(array('action' => 'index'));
        }
    }
      /**
     * @method delete
     * @param integer $restaurant_id
     * @return void
     */
    public function delete($restaurant_id = null) {
        $this->set('title_for_layout', 'restaurant delete');
        $user_id = $this->UserAuth->getUserId();
        $this->Restaurant->id = $restaurant_id;
        if ($this->Restaurant->exists()) {
            if ($this->Restaurant->delete($restaurant_id)) {
                $this->Session->setFlash(__('restaurant has successfully deleted.'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('restaurant not deleted. please, try an again.'));
                $this->redirect(array('action' => 'index'));
            }
        } else {
            $this->Session->setFlash(__('restaurant has not found.'));
            $this->redirect(array('action' => 'index'));
        }
    }
    
       /**
     * @method restaurant_builk_delete
     * @return boolean
     * @uses It's use to delete multiple tickets.
     */
    public function restaurant_builk_delete() {
        $this->layout = 'ajax';
      
        $restaurant_ids = $this->request->data['restaurant_ids'];
        $response = array();
        if (!empty($restaurant_ids)) {
            $restaurant_id_array = explode(',', $restaurant_ids);
            foreach ($restaurant_id_array as $restaurant_id) {
                //check restaurant is exist or not.
                $this->Restaurant->id = $restaurant_id;
                if ($this->Restaurant->exists()) {
                    $this->Restaurant->delete($restaurant_id);
                }
            }
            $response = array('is_deleted' => 1);
        } else {
            $response = array('is_deleted' => 0);
        }
        echo json_encode($response);
        exit;
    }

    /* @method view restaurant
     * @param $id(restaurant_id)
     * @uses Get information of particiular restaurant
     */

    public function view_restaurant($id){
        $conditions=array('OR'=>array('Restaurant.id'=>$id));
        $restaurant = $this->Restaurant->find('first', array(
                'conditions' => $conditions));
//        pr($restaurant);
//        exit;
        $html='';
            $html .= '<div class="modal-body">

                            <div class="form-horizontal">
                              <div class="form-group">
                                <label class="col-md-3 control-label">Restaurant Name</label>
                                <div class="col-md-9">'.
                                $restaurant['Restaurant']['name'];
                             $html .= '</div>
                              </div>
                              <div class="form-group">
                                <label class="col-md-3 control-label">Address</label>
                                <div class="col-md-9">'.
                                  $restaurant['Restaurant']['address'];
                                $html .= '</div>
                              </div>
                              <div class="form-group">
                              <label class="col-md-3 control-label">City</label>
                                <div class="col-md-9">'.
                                 $restaurant['Restaurant']['city'];
                                 $html .= '</div>
                              </div>
                              <div class="form-group">
                              <label class="col-md-3 control-label">Email</label>
                                <div class="col-md-9">'.
                                  $restaurant['Restaurant']['email'];
                                 $html .= '</div>
                              </div>
                              <div class="form-group">
                              <label class="col-md-3 control-label">Contact No</label>
                                <div class="col-md-9">'.
                                  $restaurant['Restaurant']['contact_no'];
                                 $html .= '</div>
                              </div>
                              <div class="form-group">
                              <label class="col-md-3 control-label">Image</label>
                               <div class="col-md-9"><img height="80" width="80" src="'.SITE_URL . 'restaurant_images/'. $restaurant['Restaurant']['image'].'"/>';
                                $html .= '</div>
                                        </div></div>
                              </div>';
                                 echo $html;
        exit;

                           
    }
    public function view_menu($id){
        $rest=$this->Restaurant->findById($id);
        $this->set('restaurant',$rest);
//        $cat=  $this->Category->findByRestId($id);
//        $this->set('category',$cat);
        $conditions = array('Category.rest_id'=>$id);
         $this->Paginator->settings = $this->paginate;
     $cat = $this->Paginator->paginate('Category',$conditions);
    $this->set('category', $cat);
//    pr($cat);
//        pr($rest);
//        foreach($rest['Category'] as $category)        {
//            echo 'category';
//            pr($category);
//            foreach ($rest['Item'] as $item){
//                if($category['id']==$item['category_id']){
//                    echo 'item';
//                    pr($item);
//                }
//            }
//        }
//        exit();
//        pr($rest);
        
    }
        }

?>