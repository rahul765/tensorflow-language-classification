<?php

App::import('Vendor', 'imagetransform');
App::import('Vendor', 'PHPExcel');

class SuggestionsController extends AppController {

    var $name = 'Suggestions';
//    public $helpers = array('PhpExcel');
    public $components = array('Paginator');
    var $uses = array('Category', 'Item', 'RestItemMapping', 'Restaurant', 'User', 'ItemChoice','Suggestion');
var $paginate = array(
        'paramType' => 'querystring',
        'limit' => 5,
        'maxLimit' => 1000
    );
    /**
     * @method index
     * @uses It's use to display all items.
     */
    public function index($id) {
                $this->set('title_for_layout', 'Suggestion');
        $user_id = $this->UserAuth->getUserId();
        $user = $this->UserAuth->getUser();
        $user_type = $user['UserGroup']['name'];
            $grp_id = $user['UserGroup']['id'];
//        $rest = $this->User->findById($user_id, array(
//            'fields' => 'rest_id'));
//        $rest_id = $rest['User']['rest_id'];
      $rest_id = $this->User->findById($user_id);
   
        $conditions = array();
        $conditions = array('OR'=>array('Suggestion.item_id'=>$id));
        $today = date('Y/m/d');
  
//        $suggestion = $this->Suggestion->find('all', array(
//            'conditions' => $conditions
//        ));
//        $this->set('suggestions', $suggestion);
        $this->Paginator->settings = $this->paginate;

    // similar to findAll(), but fetches paged results
//        pr($conditions);
    $suggestion = $this->Paginator->paginate('Suggestion',$conditions);
    $this->set('suggestions', $suggestion);

    }

    /**
     * @method delete
     * @param integer $suggestion_id
     * @return void
     */
    public function delete($suggestion_id = null) {
        $this->set('title_for_layout', 'suggestion delete');
        $user_id = $this->UserAuth->getUserId();
        $this->Suggestion->id = $suggestion_id;
//        $sid= $this->Suggestion->findById($suggestion_id);
        if ($this->Suggestion->exists()) {
            if ($this->Suggestion->delete($suggestion_id)) {
                $this->Session->setFlash(__('Suggestion has successfully deleted.'));
                $this->redirect(array('action' => 'report_abused'));
            } else {
                $this->Session->setFlash(__('Suggestion not deleted. please, try an again.'));
                $this->redirect(array('action' => 'report_abused'));
            }
        } else {
            $this->Session->setFlash(__('Suggestion has not found.'));
            $this->redirect(array('action' => 'report_abused'));
        }
    }

    /**
     * @method item_builk_delete
     * @return boolean
     * @uses It's use to delete multiple items.
     */
    public function suggestion_builk_delete() {
        $this->layout = 'ajax';
        $suggestion_ids = $this->request->data['suggestion_ids'];
        $response = array();
        if (!empty($suggestion_ids)) {
            $sggestion_id_array = explode(',', $suggestion_ids);
            foreach ($sggestion_id_array as $suggestion_id) {
                //check item is exist or not.
                $this->Suggestion->id = $suggestion_id;
                if ($this->Suggestion->exists()) {
                    $this->Suggestion->delete($suggestion_id);
                }
            }
            $response = array('is_deleted' => 1);
        } else {
            $response = array('is_deleted' => 0);
        }
        echo json_encode($response);
        exit;
    }
    
    public function report_abused() {
                $this->set('title_for_layout', 'Suggestion');
        $user_id = $this->UserAuth->getUserId();
        $user = $this->UserAuth->getUser();
        $user_type = $user['UserGroup']['name'];
            $grp_id = $user['UserGroup']['id'];
//        $rest = $this->User->findById($user_id, array(
//            'fields' => 'rest_id'));
//        $rest_id = $rest['User']['rest_id'];
      $rest_id = $this->User->findById($user_id);
   
        $conditions = array();
         $conditions = array('OR'=>array('Suggestion.is_abused'=>1));
          if (($this->request->is('post')) || ($this->request->is('put'))) {

            if (!empty($this->request->data['Search']['name'])) {
                $conditions[] = array('OR' => array('Item.name LIKE' => '%' . $this->request->data['Search']['name'] . '%'));
            }
            $this->set('flag', 'true');
//            pr($conditions);
        }
       
        $today = date('Y/m/d');
  
//        $suggestion = $this->Suggestion->find('all', array(
//            'conditions' => $conditions
//        ));
//        $this->set('suggestions', $suggestion);
        $this->Paginator->settings = $this->paginate;

    // similar to findAll(), but fetches paged results
//        pr($conditions);
    $suggestion = $this->Paginator->paginate('Suggestion',$conditions);
    $this->set('suggestions', $suggestion);
//pr($suggestion);
//exit();
    }
    
    
        /**
     * @method change_active_status
     * @uses It's use to change active status.
     * @param int $item_id,varchar $st.
     */
    public function change_abused_status($sug_id = null) {

        $this->Suggestion->id = $sug_id;
        if ($this->Suggestion->exists()) {
            
                $this->Suggestion->saveField('is_abused', 0);
            
            $this->redirect(array('action' => 'report_abused'));
        } else {
            $this->Session->setFlash(__('Suggestion not found.'));
            $this->redirect(array('action' => 'report_abused'));
        }
    }

}

?>