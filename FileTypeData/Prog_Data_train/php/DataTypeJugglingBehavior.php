<?php
App::uses('ModelBehavior', 'Model');

/**
 * Data Type Juggling Behavior
 *
 * PHP 5
 *
 * Copyright (c) WizeHive, Inc. (http://www.wizehive.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @since       1.0
 * @package     DataTypeJuggling
 * @subpackage  DataTypeJuggling.Model.Behavior
 * @copyright   Copyright (c) WizeHive, Inc. (http://www.wizehive.com)
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 */
class DataTypeJugglingBehavior extends ModelBehavior {
	
	/**
	 * Column type cache 
	 */
	protected $_column_type_cache = array();
	
	/**
	 * Before Validate Callback
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @param	Model $Model
	 * @return	boolean
	 */
	public function beforeValidate(Model $Model) {
		
		if (empty($Model->data[$Model->alias])) {
			return true;
		}
		
		foreach ($Model->data[$Model->alias] as $field => $value) {
			
			if ($this->isBoolean($Model, $field)) {
				$Model->data[$Model->alias][$field] = $this->convertToBoolean($Model, $value);
			}
			
			if ($value === 'null') {
				$Model->data[$Model->alias][$field] = null;
			}
			
		}
		
		return true;
		
	}
	
	/**
	 * Before Save Callback
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @param	Model $Model
	 * @return	boolean
	 */
	public function beforeSave(Model $Model) {
		
		if (empty($Model->data[$Model->alias])) {
			return true;
		}
		
		foreach ($Model->data[$Model->alias] as $field => $value) {
			
			if ($this->isBoolean($Model, $field)) {
				$Model->data[$Model->alias][$field] = $this->convertToInteger($Model, $value);
			}
			
			if ($value === 'null') {
				$Model->data[$Model->alias][$field] = null;
			}
			
		}
		
		return true;
		
	}

	/**
	 * Before Find Callback
	 *
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @param	Model $Model
	 * @param	array $query
	 * @return	array
	 */
	public function beforeFind(Model $Model, $query) {
		
		if (!empty($query['parseTypes']) && !empty($query['conditions'])) {
			
			foreach($query['conditions'] as $condition => $value) {
				
				if (is_array($value)) {
					continue;
				}
				
				$field = $condition;
				
				if (strpos($condition, '.') !== false) {
					list($alias, $field) = explode('.', $condition, 2);
				}
				
				if ($this->isBoolean($Model, $field)) {
					$query['conditions'][$condition] = $this->convertToBoolean($Model, $value);
				}
				
			}
		
		}
		
		return $query;
		
	}
	
	/**
	 * After Find Callback
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @param	Model $Model
	 * @param	array $results
	 * @param	boolean $primary 
	 * @return	array
	 */
	public function afterFind(Model $Model, $results = array(), $primary = false) {

		if ($primary) {
			
			foreach ($results as $key => $row) {
				
				if (empty($row[$Model->alias])) {
					continue;
				}
				
				if (!empty($row[$Model->alias])) {
				
					foreach($row[$Model->alias] as $column => $value) {
					
						if (is_array($value)) {
							continue;
						}
						
						if ($this->isBoolean($Model, $column)) {
							
							$results[$key][$Model->alias][$column] = $this->convertToBoolean($Model, $value);
						
						} elseif ($this->isString($Model, $column)) {
							
							$results[$key][$Model->alias][$column] = $this->convertToString($Model, $value);
						
						} elseif (!is_null($value) && $this->isNumeric($Model, $column)) {
							
							$results[$key][$Model->alias][$column] = (int)$value;
							
						}
					
					}
					
				}
				
			}
		
		}
		
		return $results;
		
	}
	
	/**
	 * Is Boolean
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @param	Model $Model
	 * @param	string $column
	 * @return	boolean
	 */
	public function isBoolean(Model $Model, $column = null) {
		
		/**
		 * This is repetitive, but it's worth NOT putting in a function since it's called
		 * so much and function calls are expensive. 
		 */
		
		if (
			!array_key_exists($Model->name, $this->_column_type_cache) ||
			!array_key_exists($column, $this->_column_type_cache[$Model->name])
		) {
			$this->_column_type_cache[$Model->name][$column] = $Model->getColumnType($column);
		}
		
		return $this->_column_type_cache[$Model->name][$column] === 'boolean';
		
	} 

	/**
	 * Is String
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @param	Model $Model
	 * @param	string $column
	 * @return	boolean
	 */
	public function isString(Model $Model, $column = null) {
		
		$types = array('string', 'text', 'binary');
		
		/**
		 * This is repetitive, but it's worth NOT putting in a function since it's called
		 * so much and function calls are expensive. 
		 */
		
		if (
			!array_key_exists($Model->name, $this->_column_type_cache) ||
			!array_key_exists($column, $this->_column_type_cache[$Model->name])
		) {
			$this->_column_type_cache[$Model->name][$column] = $Model->getColumnType($column);
		}
		
		return in_array($this->_column_type_cache[$Model->name][$column], $types);
		
	}
	
	/**
	 * Is Numberic
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @param	Model $Model
	 * @param	string $column
	 * @return	boolean
	 */
	public function isNumeric(Model $Model, $column = null) {
		
		$types = array('biginteger', 'integer');
		
		/**
		 * This is repetitive, but it's worth NOT putting in a function since it's called
		 * so much and function calls are expensive. 
		 */
		
		if (
			!array_key_exists($Model->name, $this->_column_type_cache) ||
			!array_key_exists($column, $this->_column_type_cache[$Model->name])
		) {
			$this->_column_type_cache[$Model->name][$column] = $Model->getColumnType($column);
		}
		
		return in_array($this->_column_type_cache[$Model->name][$column], $types);
		
	}
	
	/**
	 * Convert Data Type
	 *
	 * Utility method used with `ApiResourceComponent` this will do conversions
	 * at the Api Layer just before the response in cases like metadata or special fields
	 * that this behavior can't convert on `afterFind`
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @param	Model $Model
	 * @param	string $data
	 * @param   string $type
	 * @return	boolean
	 */
	public function convertDataType(Model $Model, $data = null, $type = null) {
		
		if (empty($type)) {
			return $data;
		}
		
		if ($type === 'int') {
			$type = 'integer';
		}
		
		switch($type) {
			
			case 'integer':
				$data = $this->convertToIntegerExceptNull($Model, $data);
				break;
				
			case 'boolean':
				$data = $this->convertToBoolean($Model, $data);
				break;
				
			case 'string':
				$data = $this->convertToString($Model, $data);
				break;
				
			default:
			
		}
		
		return $data;
		
	}
	
	/**
	 * Convert To Boolean
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @param	Model $Model
	 * @param	mixed $data
	 * @return	boolean
	 */
	public function convertToBoolean(Model $Model, $data = null) {
		
		if ($data == '1') return true;

		if ($data === 'true') return true;
		
		return false;
		
	}
	
	/**
	 * Convert To Integer
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @param	Model $Model
	 * @param	mixed $data
	 * @return	integer
	 */
	public function convertToInteger(Model $Model, $data = null) {

		if ($data === 'false') {
			return 0;
		}

		return (empty($data)) ? 0 : 1;

	}
	
	/**
	 * Convert To Integer Except Null
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @param	Model $Model
	 * @param	mixed $data
	 * @return	integer
	 */
	public function convertToIntegerExceptNull(Model $Model, $data = null) {
		
		if (is_int($data)) return $data;
		
		if (is_null($data)) {
			return $data;
		}
		
		if (is_object($data)) return 1;
		
		if ($data == 'true') return 1;
		
		return (int)$data;
		
	}
	
	/**
	 * Convert To String
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @param	Model $Model
	 * @param	mixed $data
	 * @return	string
	 */
	public function convertToString(Model $Model, $data = null) {
		
		if ($data !== '0' && empty($data)) {
			return null;
		}
		
		return $data;
		
	}
	
	/**
	 * Convert To Null
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @param	Model $Model
	 * @param	mixed $data
	 * @return	string
	 */
	public function convertToNull(Model $Model, $data = null) {
		
		if (empty($data)) {
			return null;
		}
		
		return $data;
		
	}
	
}
