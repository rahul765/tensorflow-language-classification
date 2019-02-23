<?php
Yii::import('ext.yiiext.behaviors.NestedSetBehavior');
Yii::import('ext.seo.components.SeoRecordBehavior');
class AdController extends Controller
{
	
        /**
	 * @property string the name of the default action
	 */
	public $defaultAction = 'default';
        
         public function behaviors(){
             return array(
                'seo'=>array('class'=>'ext.seo.components.SeoControllerBehavior'),
             );
        }
	
	public function filters(){
          return array(
                array('ext.seo.components.SeoFilter + detail'), // apply the filter to the view-action
          );
        }
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
				'testLimit' => 1
			),
                         'gmap.'  =>  array(
                                            'class'=>'classifieds.extensions.LocationPicker.LocationWidget',
                                            'defaultLocation'=>'Hyderabad, India'
                                          ),
                         'siteMap'=> array('class'=>'classifieds.components.actions.siteMap'),
			  'saveImageAttachment' => 'ext.imageAttachment.ImageAttachmentAction',
		);
	}

	
	public function actionDefault(){
         // $this->layout = 'application.views.layouts.adview';
	   $this->render('default');
	}
	
	public function actionPublish(){
		
	//$this->layout = 'application.views.layouts.adview';
		
	$this->render('publish_tpl');
}
		
	public function actionIndex()
	{    $this->layout = 'column2';
		//get incoming params
		$cid = isset($_GET['cid']) ? $_GET['cid'] : null;
		$lid = isset(Yii::app()->session['lid']) ? Yii::app()->session['lid'] : null;
		
		//create criteria object
		$criteria = new CDbCriteria();
		$criteriaParams = array();
		
		//init vars
		$whereArray 	= array();
		$pagerParams 	= array();
		$breadcrump		= array();
		
		//check incoming params
		if(!empty($cid) && is_numeric($cid)){
			$categoryInfo = Category::model()->findByPk( $cid );
			
			$inWhereArray = array($cid);

			//check for category childs
			$childs = $categoryInfo->childs;
			if(!empty($childs)){
				$this->view->childs = $childs;
				foreach ($childs as $k){
					$inWhereArray[] = $k->category_id;
				}
			}
			
			$criteria->addInCondition('category_id', $inWhereArray);
			
			//set params to pager
			$pagerParams['cid'] = $cid;
			
			//set breadcrump
			if($parents_array = Category::model()->getParentRecursive($cid)){
				$parents_array = array_reverse($parents_array, true);
				foreach ($parents_array as $k => $v){
					$breadcrump[] = CHtml::link($v, array('ad/index', 'name' => DCUtil::getSeoTitle($v), 'cid' => $k));
				}
			} 
			$breadcrump[] = $categoryInfo->name;
			
			//set category title in to the view
			$this->view->name = $categoryInfo->name;
		}
		
		//check incoming params
		if(!empty($lid) && is_numeric($lid)){
			$criteria->addCondition('t.location_id = :lid');
			$criteriaParams[':lid'] = $lid;
			$pagerParams['lid'] = $lid;
		}
		
		//set order
		$criteria->order = 't.ad_vip DESC, t.ad_id DESC';
		
		if(!empty($criteriaParams)){
			$criteria->params = array_merge($criteria->params, $criteriaParams);
		}
		
		//get ad count that match criteria
		$cache_key_name = md5(json_encode($criteria->toArray()));
		if(!$count = Yii::app()->cache->get( $cache_key_name )) {
	    	$count=Ad::model()->count($criteria);
	    	Yii::app()->cache->set($cache_key_name , $count);
		}
		
	    //create pagination object
	    $pages=new CPagination($count);
	
	    //init pagination object
	    $pages->pageSize = NUM_CLASSIFIEDS_ON_PAGE;
	    $pages->applyLimit($criteria);
	    if(!empty($pagerParams)){
	    	$pages->params = $pagerParams;
	    }
	    
	    //get classifieds
	    $cache_key_name = md5(json_encode($criteria->toArray()));
		if(!$adList = Yii::app()->cache->get( $cache_key_name )) {
	    	$adList = Ad::model()->findAll($criteria);
	    	Yii::app()->cache->set($cache_key_name , $adList);
		}	
	    
	    //inject classifieds data into the view
	    $this->view->adList = $adList;
	    
	    if(!empty($breadcrump)){
	    	$this->view->breadcrump = $breadcrump;
	    }
	    
	    if(isset($this->view->name)){
		    $this->view->pageTitle 			= $this->view->name;
		    $this->view->pageDescription 	= $this->view->name;
		    $this->view->pageKeywords 		= $this->view->name;
	    }
    
	    //render view
	    $this->render('index_tpl', array('pages' => $pages));	
	}
        
        		
	public function actionDetail(){
            
                 $this->layout = 'column1';
		$contact_model=new ContactForm;
		$contact_model->scenario = 'registerwcaptcha';
		
		       $adId = isset($_GET['adid']) ? $_GET['adid']: null;
		        if(empty($adId) || (int)$adId == 0){
			       $this->redirect(Yii::app()->createUrl('/classifieds'));
			}
		         
		if(!empty( $adId ) && is_numeric( $adId )){
			//get classified info
			if(!$adInfo = Yii::app()->cache->get( 'ad_info_' . $adId )) {
				//!$adInfo = Ad::model()->with( 'location','category', 'pics')->findByPk( $adId );
				$adInfo = Ad::model()->with( 'category', 'pics')->findByPk( $adId );
				Yii::app()->cache->set('ad_info_' . $adId , $adInfo);	
			}
			$this->view->adInfo = $adInfo;
				
			//get similar classifieds info
			$similarAdsOptions = array('location' 	=> $adInfo->location, 
									   'search_string'	=> $adInfo->ad_title,
									   'where'			=> 'CA.ad_id <> ' . $adId,
									   'offset' 		=> 0,
									   'limit' 			=> 10);
                        
			$cache_key_name = 'similarAds_' . md5(json_encode($similarAdsOptions));
			if(!$similarAds = Yii::app()->cache->get( $cache_key_name )) {									   
				$similarAds = Ad::model()->getSearchList( $similarAdsOptions );
				Yii::app()->cache->set($cache_key_name , $similarAds);	
			}
			$this->view->similarAds = $similarAds;
		
			
			/**
			 * handle classified contact
			 */
			$adContactModel = new ContactForm();
			$this->view->adContactModel = $adContactModel;
			$this->view->showContactForm = 1;
								
			if(isset($_POST['ContactForm'])){
			       $adContactModel->attributes = $_POST['ContactForm'];
				if($adContactModel->validate()){	
				     $adContactModel->body = nl2br(DCUtil::sanitize($adContactModel->body));
					$this->_sendMails($adInfo,$adContactModel,'','ad_contact');
					$this->view->showContactForm = 0;
				        
				
				}
			}	
			//define error array
			$errorArray = array();
			
			//$mail = new AdMail($this->view->adInfo, AdMail::ADCONTACT);
	               // $mail->send();		
			
			$this->view->defaultFormArray 	= $defaultFormArray;	
			$this->view->errorArray 	= $errorArray;
			/**
			 * end of classified contact 
			 */
		//}
		
		//$this->view->breadcrump 		= array(stripslashes($adInfo->ad_title));
            //    $this->view->pageTitle 			= stripslashes($adInfo->ad_title);
		//$this->view->pageDescription 	= stripslashes(DCUtil::getShortDescription(stripslashes($adInfo->ad_title)));
		//$this->view->pageKeywords 		= stripslashes($adInfo->ad_title);
		}  //else not ajax request
		
		$this->render('detail_tpl',array('contact_model'=>$adContactModel));	
	}
	
 
	public function actionDelete()
	{
		//init ad model
		$adModel = new Ad();
		
		//is there ad parameter
		$adId = isset($_GET['id']) ? $_GET['id']: null;
		if(empty($adId) || !is_numeric($adId) ||(int)$adId == 0){
			$this->redirect(array('/site/index'));
		}
		
		//is there ad with this id
		if(!$adModel->getAdById($adId)){
			$this->redirect(array('/site/index'));
		}
		
		//create delete form model
		$adDeleteModel = new AdDeleteForm();
		$this->view->showDeleteForm = 1;
							
		//is the form submitted
		if(isset($_POST['AdDeleteForm'])){
			$adDeleteModel->attributes = $_POST['AdDeleteForm'];
			
			//validate form and delete code
			if($adDeleteModel->validate() && $code_valid = $adModel->getAdByIdAndCode( $adId, $adDeleteModel->code)){
			try{
				//delete all tags for this ad
				$adTagModel = new AdTag();
				$adData = $adModel->findByPk( $adId );
				$adTagModel->removeTags( $adTagModel->string2array( $adData->ad_tags) );
						
				//delete all pics for this ad
				$adPicModel = new AdPic;
				$adPicArray = $adPicModel->findAll('ad_id = :ad_id', array(':ad_id' => $adId));
				if(!empty($adPicArray)){
					foreach ($adPicArray as $k => $v){
						@unlink(PATH_UF_CLASSIFIEDS . $v['ad_pic_path']);
						@unlink(PATH_UF_CLASSIFIEDS . 'small-' . $v['ad_pic_path']);
					}
					$adPicModel->deleteAll('ad_id = :ad_id', array(':ad_id' => $adId));
				}
						
				$adModel->ad_id = $adId;
				$adModel->setIsNewRecord( false );
				$adModel->delete();
					
				Yii::app()->cache->flush();
			} catch (Exception $e){}
				
				//do not show form in the view
				$this->view->showDeleteForm = 0;
			} else {
				if (!$code_valid){
					$adDeleteModel->addError('code', Yii::t('delete_page_v2', 'Delete code is invalid'));
				}
			}
		}//check if form is submitted
		
		
		//set data to view
		$this->view->adDeleteModel = $adDeleteModel;

		$this->view->breadcrump 		= array(Yii::t('delete_page', 'pageTitle'));
		$this->view->pageTitle 			= Yii::t('delete_page', 'pageTitle');
		$this->view->pageDescription 	= Yii::t('delete_page', 'pageDescription');
		$this->view->pageKeywords 		= Yii::t('delete_page', 'pageKeywords');

		$this->render('delete_tpl');	

	}
	
	public function actionLocation()
	{
		$lid = isset($_GET['lid']) ? $_GET['lid'] : null;
		if(!empty($lid) && is_numeric($lid)){
			Yii::app()->session['lid'] = $lid;
		} else {
			Yii::app()->session['lid'] = '';
		}
		
		Yii::app()->cache->flush();		
		$this->redirect( array('/ad/index') );
	}
	
	
  
}