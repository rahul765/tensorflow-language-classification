<?php
/**
 * Image behavior class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @since 1.1.0
 */
class ImgRecordBehavior extends CBehavior
{
	/**
	 * @property string the image id attribute. Defaults to "imageId".
	 * @since 1.2.0
	 */
	public $attribute='imageId';

	/**
	 * Saves the image for the owner of this behavior.
	 * @param string $name the image name.
	 * @param string $path the path for saving the image.
	 * @param CUploadedFile $file the uploaded file.
	 */
	public function saveImage($file,$name=null,$path=null,$id = null)
	{
		$image=Yii::app()->image->save($file,$name,$path,$id);

		if($image!==null && $this->owner->hasAttribute($this->attribute))
			$this->owner->{$this->attribute}=$image->id;
	}

	/**
	 * Renders the image for the owner of this behavior.
	 * @param string $version the name of the image version.
	 * @param string $alt the image alternative text.
	 * @param array $htmlOptions the additional HTML options.
	 */
	public function renderImage($version,$alt='',$htmlOptions=array())
	{
		if($this->owner->hasAttribute($this->attribute))
		{
			$image=Yii::app()->image->loadModel($this->owner->{$this->attribute});

			if($image!==null)
				$image->render($version,$alt,$htmlOptions);
		}
	}
}
