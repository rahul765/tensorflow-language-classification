<?php

App::import('Vendor', 'imagetransform');
App::import('Vendor', 'PHPExcel');

class RestItemMappingsController extends AppController {

    var $name = 'RestItemMappings';
//    public $helpers = array('PhpExcel');
    public $components = array();
    var $uses = array('Category', 'Item', 'Restaurant', 'Qrcode', 'User','RestItemMapping');

    /**
     * @method index
     * @uses It's use to display all tickets.
     */
   /* public function index() {
        $user_type = $this->UserAuth->getUser();
        $user_type = $user_type['UserGroup']['name'];

        $this->set('title_for_layout', 'Category');
        $user_id = $this->UserAuth->getUserId();
        $conditions = array();

        if ($user_type != 'Admin') {
            $conditions[] = array('OR' => array('Category.user_id =' => $user_id));
        }
        if (($this->request->is('post')) || ($this->request->is('put'))) {


            if (!empty($this->request->data['Search']['name'])) {
                $conditions[] = array('OR' => array('Category.name LIKE' => '%' . $this->request->data['Search']['name'] . '%'));
            }
            $this->set('flag', 'true');
        }
        $category = $this->Category->find('all', array(
            'conditions' => $conditions));


        $this->set('categories', $category);
    }*/

    /**
     * @method add
     * @uses It's use to create category.
     */
    

    /**
     * @method edit
     * @uses It's use to edit categories.
     */
    public function edit($item_id,$category_id) {
      
        $this->set('title_for_layout', 'Category edit');
        $user_id = $this->UserAuth->getUserId();
        $user_id = $this->UserAuth->getUserId();

        $user_type = $this->UserAuth->getUser();
        $user_type = $user_type['UserGroup']['name'];

       
         $rest_id = $this->RestItemMapping->findByItemId($item_id);
       
 $id=$rest_id['RestItemMapping']['id'];
        $this->RestItemMapping->id = $id;
         $category = $this->Category->findById($category_id);
$rest_map['RestItemMapping']['rest_id']=$category['Category']['rest_id'];
if($this->RestItemMapping->save($rest_map)){
$this->Session->setFlash(__('Your Item successfully edited.'));
                    $this->redirect(array('controller' => 'items', 'action' => 'index'));
    }}

 
}