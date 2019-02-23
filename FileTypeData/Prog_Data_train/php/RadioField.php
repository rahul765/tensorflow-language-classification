<?php

namespace App\Source\ModelFieldBuilder;

class RadioField extends AField
{
	protected $allowTypes = ['radio'];
	protected $defaultType = 'radio';
	public $values = [];

	public function __construct(\stdClass $obj){
		if( $obj->values )
			$obj->values = array_merge($this->values, (array)$obj->values);
		
		parent::__construct($obj);
	}

	public function __toString(){
		if( !$this->visible || $this->name=='default' )
			return '';

		$options = '';
		foreach ($this->values as $value=>$name) {
			$str = '<div class="radio"><label>';
			$str .= sprintf('<input type="%s" value="%s" name="%s" #> %s', $this->type, $value, $this->name, $name);

			if( $this->value!==null && (string)$this->value==(string)$value )
				$str = str_replace("#", "# checked=\"checked\"", $str);
			if( $this->value===null && (string)$this->default==(string)$value )
				$str = str_replace("#", "# checked=\"checked\"", $str);

			$options .= $str.'</label></div>';
		}

		$this->className = str_replace("form-control", "", $this->className);

		return $this->toString($options);
	}
}