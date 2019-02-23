<?php
//Yii::import('ext.EGeoNameService.EGeoNameService');
class SuggestTag extends CAction{

/**
	 * @var string name of the model class.
	 */
	public $modelName ;
	/**
	 * @var string name of the method of model class that returns data.
	 */
	public $methodName = "suggest";
	/**
	 * @var integer maximum number of rows to be returned
	 */
	public $limit=20;
	
        protected $controller;  

/**
	 * Suggests models based on the current user input.
	 */
	public function run()
	{
		if(isset($_GET['term'])&&($keyword=trim($_GET['term']))!=='')
		{       $model = Skills::model();
			$suggest=$model->{$this->methodName}($keyword, $this->limit, $_GET);
			//print_r($suggest[0]);
			echo CJSON::encode($suggest); Yii::app()->end();
                       /* if($suggest!==array())
				echo implode("\n",$suggest);*/
                        
                        /*
                        if(isset($_GET['q']) && ($keyword=trim($_GET['q']))!==''){
			$tags=Skills::model()->suggestTags($keyword);
			if($tags!==array())
				echo implode("\n",$tags);
		         }  */
		}
	}

/**
	 * @return CActiveRecord
	 */
	protected function getModel() {
		return CActiveRecord::model($this->modelName);
	}

}

