<?php
Yii::import('application.models.UserGroupsUser');
class Ad extends CActiveRecord
{
	public $pcode;
	public $lat;
	public $lng;
	public $zoom;
	public $location;
	public $images;
	public $category_title;
	public $validacion;
	public $scat_id;

	public $uploadedFiles ;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Ad the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ad';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			
		   array('category_id','required', 'on'=>'category'),
		   array('category_title','safe', 'on'=>'category'),
		   
		   array('ad_title, ad_type_id, ad_description,', 'required','on'=>'general'),
		   array('ad_price, ad_tags, ad_video, ad_link, validacion','safe', 'on'=>'general'),
		  
		   
		   array('ad_email, ad_phone,  ad_address', 'required', 'on'=>'location'),
		   array('ad_publisher_name, ad_skype, ad_lat, ad_lng','safe', 'on'=>'location'),
		   
		    array('ad_id,   ad_email, ad_publish_date, ad_ip, ad_price, ad_phone, ad_title, ad_description, ad_puslisher_name, ad_tags', 'safe', 'on'=>'search'),
		    
		  // array('ad_lat','required','message'=>'Please Specify your location'),
		   array('category_id', 'length', 'max'=>10),
		   array('ad_ip', 'length', 'max'=>20),
		   array('ad_email, ad_price, ad_phone, ad_title, ad_puslisher_name, code, ad_address', 'length', 'max'=>255),
		   array('ad_video', 'match',
			     'pattern' => '/(http:\/\/vimeo.com\/[\d]+)|(http:\/\/(www.)?youtube.com\/watch\?v=[a-zA-Z0-9_]+[^&])/', 'allowEmpty' => 'true',
			     'message' => Yii::t('publish_page_v2', 'Please insert link to youtube or vimeo video.')),
		  // array('ad_publish_date, ad_description, ad_tags, ad_skype, ad_lat,ad_lng', 'safe'),
		   array('ad_email', 'email', 'message' => Yii::t('publish_page', 'Please fill in valid e-mail')),
		   array('ad_price', 'numerical','message' => Yii::t('publish_page', 'Please fill in only digits')),
		   array('ad_link', 'url', 'message' => Yii::t('publish_page_2', 'Please fill in valid url')),
		   array('images', 'file', 'maxFiles' => 1, 'maxSize' => 204800, 'types'=>'jpg, gif, png', 'allowEmpty' => true,'on'=>'photo'),

		   // The following rule is used by search().
			// Please remove those attributes that should not be searched.
		   
		   array(
			      'validacion', 
                              'application.extensions.recaptcha.EReCaptchaValidator', 
                              //'privateKey'=>'6LcNWuISAAAAAE_36j1F3h4ecfwRt_zg7B9yrGL5',
                              //'privateKey'=>'6Lfc_eASAAAAABfBfY11YZbtADJyuYja62AbVaOt',
			       'privateKey' => '6LeSjucSAAAAAP5xic9mM_9PpK-XaLQLygSLYwPG',
			       'on' => 'general'
		   ),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			//'location'	=>array(self::BELONGS_TO, 'Location', 'location_id'),
			'owner' 	=>  array(self::BELONGS_TO, 'UserGroupsUser', 'ug_id'),
			'category'	=>  array(self::BELONGS_TO, 'Category', 'category_id'),
			'pics'		=>  array(self::HAS_MANY, 'AdPic', 'ad_id'),
			'type'		=>  array(self::BELONGS_TO, 'AdType', 'ad_type_id'),
		);
	}
        public function behaviors(){
        
            return array(
       
                  array(
                      'class'=>'ext.seo.components.SeoRecordBehavior',
                      'route'=>'classifieds/ad/detail',
                      'params'=>array( 'adtitle'=>DCUtil::getSeoTitle( stripslashes($k->ad_title)),'adid'=>$this->ad_id),
                    ),
           );
           }
	public function getLink()
	{
                /*
                 return CController::createUrl('/profiles/view', array(
			'id'=>$this->id,
			'title'=>$this->cname,
		));
                */
               // return $this->getUrl(array('adtitle'=>$this->ad_title,'adid'=>$this->ad_id));
	        return CController::createUrl('/ad/detail',array('adtitle' => DCUtil::getSeoTitle( stripslashes($this->ad_title) ), 'adid' => $this->ad_id));
	}   
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
	                'ad_type_id'                   => Yii::t('admin','Ad Type'), 
			'ad_link' 			=> Yii::t('admin_v2', 'Ad Link'),
			'ad_video' 			=> Yii::t('admin_v2', 'Ad Video'),
			'ad_lat' 			=> Yii::t('admin_v2', 'Ad Latitute'),
			'ad_lng' 			=> Yii::t('admin_v2', 'Ad Altitude'),
			
			'images'			=> Yii::t('publish_page', 'Images'),
			'ad_skype' 			=> Yii::t('admin_v2', 'Skype'),
			'ad_address' 		=> Yii::t('admin_v2', 'Address'),
			'ad_valid_id' 		=> Yii::t('admin_v2', 'Ad Valid Days'),
					
			'ad_id' 		=> Yii::t('admin', 'Ad'),
			'category_id' 		=> Yii::t('admin', 'Category'),
			'pcode'                 => Yii::t('admin', 'PIN Code'),
			'location' 		=> Yii::t('admin', 'Location'),
			'ad_email' 		=> Yii::t('admin', 'Ad Email'),
			'ad_publish_date' 	=> Yii::t('admin', 'Ad Publish Date'),
			'ad_ip' 		=> Yii::t('admin', 'Ad Ip'),
			'ad_price' 		=> Yii::t('admin', 'Ad Price'),
			'ad_phone' 		=> Yii::t('admin', 'Ad Phone'),
			'ad_title' 		=> Yii::t('admin', 'Ad Title'),
			'ad_description' 	=> Yii::t('admin', 'Ad Description'),
			'ad_puslisher_name'     => Yii::t('admin', 'Ad Puslisher Name'),
			'ad_tags' 		=> Yii::t('admin', 'Ad Tags'),
			'validacion'            => Yii::t('admin', 'Enter both words separated by a space: '),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('ad_id',$this->ad_id,true);
		$criteria->compare('category_id',$this->category_id,true);
		$criteria->compare('location',$this->location,true);
		$criteria->compare('ad_email',$this->ad_email,true);
		$criteria->compare('ad_publish_date',$this->ad_publish_date,true);
		$criteria->compare('ad_ip',$this->ad_ip,true);
		$criteria->compare('ad_price',$this->ad_price,true);
		$criteria->compare('ad_phone',$this->ad_phone,true);
		$criteria->compare('ad_title',$this->ad_title,true);
		$criteria->compare('ad_description',$this->ad_description,true);
		$criteria->compare('ad_puslisher_name',$this->ad_puslisher_name,true);
		$criteria->compare('ad_tags',$this->ad_tags,true);
		$criteria->compare('ad_link',$this->ad_link,true);
		$criteria->compare('ad_video',$this->ad_video,true);
		$criteria->compare('ad_lat',$this->ad_lat,true);
		$criteria->compare('ad_lng',$this->ad_lng,true);
		$criteria->compare('ad_address',$this->ad_address,true);
		
		$criteria->order = 'ad_id DESC';

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
			'pagination'=>array(
		        'pageSize'=>50,
		    ),
		));
	}
	
	
	protected function beforeSave(){
		$adValidModel 	= new AdValid();
                if(parent::beforeSave()){
			/*if($this->isNewRecord)
			  $this->created=$this->modified=time();
			else
			   $this->modified=time();
                        */
                       // $this->ad_description	= nl2br($this->ad_description);
			$this->ad_publish_date 	= date('Y-m-d');
			$this->ad_ip			= $_SERVER['REMOTE_ADDR'];
			$this->ad_title 		= DCUtil::ucfirst($this->ad_title);
			$this->ad_title 		= mb_substr($this->ad_title, 0, 90, 'utf-8');
				
			//fix tags if any
			if(!empty($this->ad_tag)){
				$this->ad_tags = AdTag::array2string(array_unique(AdTag::string2array($this->ad_tags)));
				$this->ad_tags = $this->ad_tags;
			}
			
			$one_day_in_seconds = 60 * 60 * 24;
				$this->ad_valid_until = date('Y-m-d', time() + ($adValidModel->getDaysById($this->ad_valid_id) * $one_day_in_seconds));
			if(!empty($this->ad_lat)){
					$this->ad_lat = preg_replace('/\(|\)/', '', $this->ad_lat);
				}	
			//generate delete code
			do{
				$code = md5(time());
			} while ($this->isFreeCode( $code ));
				
			$this->code = $code;
			$this->ug_id = Yii::app()->user->id;
			return true;
		}
		else
			return false;
        }
         
        protected function afterSave(){
	
		parent::afterSave(); 
                $tagsArray = AdTag::string2array( $this->ad_tags );
		if(!empty($tagsArray)){
			AdTag::model()->addTags( $tagsArray );
		}
		
		$mail = new AdMail($this, AdMail::NOTIFICATION);
	        $mail->send();
        }
	
	
	/**
	 *
	 *
	 * check if this del code is free for use
	 *
	 * @param string $code
	 * @return boolean
	 */
	public function isFreeCode( $code )
	{
		$ret = 0;
		$res = $this->find("code = '{$code}'");
		if(!empty($res)){
			$ret = 1;
		}
		return $ret;
	}
	
	/**
	 * check if there is ad with this id
	 *
	 * @param integer $ad_id
	 * @return boolean
	 */
	public function getAdById( $ad_id )
	{
		$ret = 0;
		$res = $this->findByPk( $ad_id );
		if(!empty($res)){
			$ret = 1;
		}
		
		return $ret;
	}
	
	/**
	 * check if delete code is for this ad
	 *
	 * @param integer $ad_id
	 * @param string $code
	 * @return boolean
	 */
	public function getAdByIdAndCode( $ad_id, $code )
	{
		$ret = 0;
		$res = $this->find("ad_id = {$ad_id} AND code = '{$code}'");
		if(!empty($res)){
			$ret = 1;
		}
		
		return $ret;
	}
	
	
	public static function resizeAndUploadFiles($uploadedFiles, $adId){
		$adPicModels = Array();
		if(!empty($uploadedFiles) ){
			define('ASIDO_GD_JPEG_QUALITY', 100);
						
			foreach($uploadedFiles as $k => $v){
				$adPicModel = new AdPic();
					
				$fileNameOnServer = $adId . '-classifieds-' . $v->getName();
				$v->saveAs(PATH_UF_CLASSIFIEDS . $fileNameOnServer);
							
				$pic_variations = array('small' => array('name' => 'small-' . $fileNameOnServer, 'width' => 120, 'height' => 90));
							
				Yii::import('application.extensions.asido.*');
				require_once('class.asido.php');
				asido::driver('gd');
							
				//resize images
				foreach ($pic_variations as $k => $v){
				    $img = asido::image(PATH_UF_CLASSIFIEDS . $fileNameOnServer , PATH_UF_CLASSIFIEDS . $v['name']);
				    asido::frame($img, $v['width'], $v['height'], Asido::color(255, 255, 255));
				    $img->save( ASIDO_OVERWRITE_ENABLED );
			        }//end of foreach
							
				//save image in image table
			        $adPicModel->ad_id = $adId;  //$adModel->ad_id;
			        $adPicModel->ad_pic_path = $fileNameOnServer;
			        $adPicModel->save();
			     
				
			
			        unset($adPicModel);
		        }	
		}
		
		
		
	}
        
	public function getAddress()
	{
		              
		//trim($this->location)." - ".
		//"<abbr title='Phone'>P:</abbr>".$this->ad_phone;
                
		$addrArr = explode(",",$this->ad_address);
                foreach($addrArr as $i=>$line)
			if(empty($line))$addrArr[$i] = false;
			else
			  $addrArr[$i] = $addrArr[$i].",<br>";
                $addrArr = array_filter($addrArr);			
		$address = implode("",$addrArr);
		return $address;		
	}
        
	public function getSearchCount( $_options = array() )
	{
		$ret = 0;
		
		$whereArray = array();
		$where = '';
		
		if(isset($_options['location_id'])){
			$whereArray[] = ' CA.location_id = ' . $_options['location_id'];
		}
		
		if(isset($_options['search_string'])){
			$whereArray[] = " MATCH(ad_title, ad_description, ad_tags) AGAINST ('{$_options['search_string']}') ";
		}
		
		if(!empty($whereArray)){
			$where = 'WHERE ' . join(' AND ', $whereArray);
		}
		
		if(!$ret = Yii::app()->cache->get( 'getSearchCount_' . md5($where) )) {
			$sql = "SELECT count(ad_id) AS ad_count
				
					FROM ad AS CA
						
					{$where}
						
					LIMIT 0,1";
			
			$res = Yii::app()->db->createCommand($sql)->queryAll();
			if(!empty($res)){
				$ret = $res[0]['ad_count'];
				Yii::app()->cache->set('getSearchCount_' . md5($where) , $ret);	
			}		
		}
		return $ret;
	}
	
	public function getSearchList( $_options = array() ){
		$ret = 0;
		//print_r($_options); Yii::app()->end();
		$whereArray = array();
		$where = '';
		
		if(isset($_options['location'])){
			$whereArray[] = ' CA.location = ' . $_options['location'];
		}
		
		if(isset($_options['search_string'])){
			$whereArray[] = " MATCH(ad_title, ad_description, ad_tags) AGAINST ('{$_options['search_string']}') ";
		}
		
		if(isset($_options['where'])){
			$whereArray[] = $_options['where'];
		}
		
		if(!empty($whereArray)){
			$where = 'WHERE ' . join(' AND ', $whereArray);
		}
		
		$limit = '';
		if(isset($_options['offset']) && isset($_options['limit'])){
			$limit = 'LIMIT ' . $_options['offset'] . ', ' . $_options['limit'];
		}
		
		$cache_key_name = 'getSearchList_' . md5($where) . '_' . md5($limit);
		if(!$ret = Yii::app()->cache->get( $cache_key_name )) {
			$sql = "SELECT CA.*,  C.name
					FROM ad AS CA
					LEFT JOIN category AS C
					ON C.id = CA.category_id
						
					{$where}
					
					{$limit}";
			
			$res = Yii::app()->db->createCommand($sql)->queryAll();
			if(!empty($res)){
				$ret = $res;
				Yii::app()->cache->set($cache_key_name , $ret);	
			}
		}		
		return $ret;
	}
	
	public function normalizeTags($tags = ''){
		$ret = '';
		if(!empty($tags)){
			$ret = AdTag::string2array($tags);
		}
		return $ret;
	}
}