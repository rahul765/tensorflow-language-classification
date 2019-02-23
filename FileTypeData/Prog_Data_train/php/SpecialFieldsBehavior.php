<?php
App::uses('ModelBehavior', 'Model');
App::uses('Hash', 'Utility');

/**
 * Special Fields Behavior
 *
 * JSON encoded, virtual fields, and callback fields
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
 * @package     SpecialFields
 * @subpackage  SpecialFields.Model.Behavior
 * @copyright   Copyright (c) WizeHive, Inc. (http://www.wizehive.com)
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 */
class SpecialFieldsBehavior extends ModelBehavior {
	
	/**
	 * Sets up field settings based on model attributes
	 * 
	 * @author  Paul Smith <paul@wizehive.com>
	 * @since   1.0
	 * @param   type $Model
	 * @param   type $specialType
	 * @return  void
	 */
	private function __setupSpecialFields($Model, $specialType) {
		
		$modelFields = $Model->attributes();
		
		foreach ($modelFields as $field => $fieldAttrs) {
			
			if (isset($fieldAttrs['special']) && $fieldAttrs['special'] == $specialType) {
				$this->settings[$Model->alias][$specialType . 'Fields'][$field] = $fieldAttrs;
			}
			
		}
		
	}
	
	/**
	 * Setup
	 *
	 * Sets up the configuration for the model
	 *
	 * @author  Paul Smith <paul@wizehive.com>
	 * @since   1.0
	 * @param   Model $model
	 * @param   array $config
	 * @return  void
	 */
	public function setup(Model $Model, $settings = array()) {
		
		foreach (array('json', 'serialized') as $type) {
			
			if (isset($settings[$type])) {
				
				// Automatically parse json & serialized field structure from model attributes
				$settings[$type] = Hash::normalize($settings[$type]);
				
				foreach ($settings[$type] as $field => $attribute) {
					
					if (empty($attribute)) {
						$settings[$type][$field] = $attribute = $field;
					}
					
				}
				
				$modelAttributes = Hash::expand($Model->attributes());
				
				foreach ($settings[$type] as $field => $attribute) {
					
					$match = Hash::extract($modelAttributes, $attribute);
					
					if (!empty($match)) {
						$this->settings[$Model->alias][$type][$field] = $match;
					}
					
				}
				
			} else {
				$this->settings[$Model->alias][$type] = array();
			}
			
		}
		$this->settings[$Model->alias]['virtual'] = (isset($settings['virtual'])) ? $settings['virtual'] : false;
		$this->settings[$Model->alias]['callback'] = (isset($settings['callback'])) ? $settings['callback'] : false;
		
		// Set up callback fields for this model
		if ($this->settings[$Model->alias]['callback']) {
			$this->__setupSpecialFields($Model, 'callback');
		}
		
	}
	
	/**
	 * Before Find Callback
	 *
	 * Prevent Special Fields from Being Searched in the Database
	 * 
	 * @author  Paul Smith <paul@wizehive.com>
	 * @since   1.0
	 * @param   Model $Model
	 * @param   array $query
	 * @return  array
	 */
	public function beforeFind(Model $Model, $query) {
		
		// Remove special fields from the query, since they don't come from the DB
		if (is_array($query['fields'])) {
			$fields = Hash::expand(array_flip($query['fields']));
			foreach (array('json', 'serialized') as $type) {
			foreach ($this->settings[$Model->alias][$type] as $fieldName => $fieldSubFields) {
				// Remove subfields, but keep the 'master' field so we pull it from the DB.
				// Only do this if the master field is present in the query to begin with.
				if (isset($fields[$Model->alias][$fieldName])) {
					$fields[$Model->alias][$fieldName] = array();
				}
			}
			}
			$fields = Hash::flatten($fields);
			// Remove any Callback fields completely
			if (isset($this->settings[$Model->alias]['callbackFields'])) {
				foreach ($this->settings[$Model->alias]['callbackFields'] as $fieldName => $fieldSubfields) {
					unset($fields[$Model->alias . '.' . $fieldName]);
				}
			}

			$query['fields'] = array_keys($fields);
		}
		
		if (!empty($query['callbackFields'])) {
			$this->_callbackFields = $query['callbackFields'];
		}
		
		return $query;
		
	}
	
	/**
	 * Before Save Callback
	 *
	 * Encode JSON/Serialized fields before saving
	 * 
	 * @author  Paul Smith <paul@wizehive.com>
	 * @author  Anthony Putignano <anthony@wizehive.com>
	 * @since   1.0
	 * @param   Model $Model
	 * @param   array $options
	 * @return  boolean
	 */
	public function beforeSave(Model $Model, $options) {
		
		foreach (array('json', 'serialized') as $type) {
			
			foreach ($this->settings[$Model->alias][$type] as $fieldName => $fieldSubFields) {
				
				if (Hash::check($Model->data[$Model->alias], $fieldName)) {
					
					if ($type === 'json') {
						$encoded = json_encode(Hash::extract($Model->data[$Model->alias], $fieldName));
					} else {
						$encoded = serialize(Hash::extract($Model->data[$Model->alias], $fieldName));
					}
					
					$Model->data[$Model->alias][$fieldName] = $encoded;
				}
				
			}
			
		}
		
		return true;
		
	}
	
	/**
	 * Before Validate Callback
	 *
	 * Flatten Special Fields Data For Validation
	 * 
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   1.0
	 * @param   Model $Model
	 * @param   array $options
	 * @return  boolean
	 */
	public function beforeValidate(Model $Model, $options = array()) {
		
		if (!empty($Model->data[$Model->alias])) {
			
			$fields = $this->getValidationFieldNames($Model);
		
			if (!empty($fields)) {
			
				foreach($fields as $field) {
			
					$validation_fields = $this->getSpecialFieldValidateFields($Model, $field);

					if (empty($Model->data[$Model->alias][$field])) {
						continue;
					}
					
					// There is no validation rules for this special field
					if (empty($validation_fields)) {
						continue;
					}

					// Ignore if there is a validation rule with the exact special `$field` name
					if (!empty($Model->validate[$field])) {
						continue;
					}
					
					// Set special fields again in dot notation
					foreach ($validation_fields as $validation_field) {
						
						if (Hash::check($Model->data[$Model->alias], $validation_field)) {

							$value = Hash::get($Model->data[$Model->alias], $validation_field);

							$Model->data[$Model->alias] = Hash::remove($Model->data[$Model->alias], $validation_field);

							$Model->data[$Model->alias][$validation_field] = $value;

						}
						
					}

					unset($Model->data[$Model->alias][$field]);
				
				}
		
			}
		
		}
		
		return true;
		
	}
	
	/**
	 * After Validate Callback
	 *
	 * Expand Special Fields Data Flattened Before Validation
	 * 
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   1.0
	 * @param   Model $Model
	 * @return  void
	 */
	public function afterValidate(Model $Model) {
		
		if (!empty($Model->data[$Model->alias])) {
			
			$fields = $this->getValidationFieldNames($Model);
		
			if (!empty($fields)) {
				$Model->data[$Model->alias] = Hash::expand($Model->data[$Model->alias]);
			}
			
		}
		
	}
	
	/**
	 * Get validation field names
	 *
	 * Allows validation errors for top-level JSON/Serialized fields to show
	 * 
	 * @author  Paul Smith <paul@wizehive.com>
	 * @since   1.0
	 * @param   Model $Model
	 * @return  array
	 */
	public function getValidationFieldNames(Model $Model) {
		
		$return = array();
		
		foreach (array('json', 'serialized') as $type) {
			
			foreach (array_keys($this->settings[$Model->alias][$type]) as $fieldName) {
				$return[$fieldName] = $fieldName;
			}
			
		}
		
		return $return;
		
	}
	
	/**
	 * After Find Callback
	 *
	 * Retrieve a Set of Special Fields That Fit Within the Given Field Map
	 *
	 * Decode JSON fields before returning
	 *
	 * @author  Anthony Putignano <anthony@wizehive.com>
	 * @since   1.0
	 * @param   Model $Model
	 * @param   array $results
	 * @param   boolean $primary
	 * @return  array
	 */
	public function afterFind(Model $Model, $results, $primary = false) {
		
		if (empty($results)) {
			return $results;
		}
		
		foreach ($results as $key => $row) {
			
			if (empty($row[$Model->alias])) {
				continue;
			}
			
			$results[$key][$Model->alias] = Hash::merge(
				$row[$Model->alias],
				$this->getSpecialFieldData($Model, $row)
			);
			
		}
		
		unset($this->_callbackFields);
		
		return $results;
		
	}
	
	/**
	 * Retrieve Data for Special Fields
	 * 
	 * @author  Paul Smith <paul@wizehive.com>
	 * @since   1.0
	 * @param   Model $Model
	 * @param   array $result
	 * @return  array
	 */
	public function getSpecialFieldData(Model $Model, $result) {
		
		$special_field_data = array();
		
		// Fill out JSON/Serialized fields as appropriate
		foreach (array('json', 'serialized') as $type) {
			
			if (!empty($this->settings[$Model->alias][$type])) {
			
				foreach ($this->settings[$Model->alias][$type] as $field => $sub_attributes) {
				
					if (array_key_exists($field, $result[$Model->alias])) {
					
						if ($type === 'json') {
							$decoded = json_decode($result[$Model->alias][$field], true);
						} else {
							$decoded = unserialize($result[$Model->alias][$field]);
						}
					
						if (empty($decoded)) {
							
							$special_field_data[$field] = null;
						
						} elseif ( // one-dimensional
							array_key_exists('field', $sub_attributes) &&
							array_key_exists('type', $sub_attributes)
						) {
							
							$special_field_data[$field] = $decoded;
						
						} else {
							
							$decoded = Hash::flatten($decoded);
							
							foreach ($decoded as $key => $value) {
								
								foreach ($sub_attributes as $sub_attribute) {
									
									$strlen = strlen($sub_attribute['field']) + 1;
									
									if (
										$sub_attribute['field'] == $field . '.' . $key ||
										$sub_attribute['field'] . '.' == substr($field . '.' . $key, 0, $strlen)
									) {
										$special_field_data[$field][$key] = $value;
									}
									
								}
								
							}
							
							if (!empty($special_field_data[$field])) {
								$special_field_data[$field] = Hash::expand($special_field_data[$field]);
							}
							
						}
					
					}
				
				}
			
			}
			
		}
		
		// Get callback field values
		if (!empty($this->settings[$Model->alias]['callbackFields'])) {
			
			foreach ($this->settings[$Model->alias]['callbackFields'] as $callbackFieldName => $callbackFieldAttrs) {
				
				if (
					!empty($this->_callbackFields) && 
					!in_array($callbackFieldName, $this->_callbackFields)
				) {
					continue;
				}
				
				$function = $callbackFieldAttrs['callbackFunction'];
				
				if (!empty($callbackFieldAttrs['field'])) {
					$callbackFieldName = $callbackFieldAttrs['field'];
				}
				
				if (method_exists($this, $function)) {
					// If function is defined within this behavior, use that
					$handler = array($this, $function);
					array_unshift($callbackFieldAttrs['callbackParams'], $Model);
				} elseif (method_exists($Model, $function)) {
					// Otherwise use the function within the model itself
					$handler = array($Model, $function);
				}
				
				$callback_params = array_merge(
					compact('result'),
					$callbackFieldAttrs['callbackParams']
				);
				
				if (is_callable($handler)) {
					$special_field_data[$callbackFieldName] = call_user_func_array($handler, $callback_params);
				}
				
			}
			
		}
		
		return $special_field_data;
		
	}
	
	/**
	 * Filter Special Fields Data
	 * 
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   1.0
	 * @param   Model $Model
	 * @param   string $prefix
	 * @param   array $whitelist
	 * @return  boolean
	 */
	public function filterSpecialFieldData(Model $Model, $prefix = null, $whitelist = array()) {
		
		if (empty($prefix)) {
			return false;
		}
		
		if (empty($Model->data[$Model->alias])) {
			return false;
		}
		
		if (!is_array($whitelist)) {
			$whitelist = array($whitelist);
		}

		foreach($Model->data[$Model->alias] as $data_field => $data_value) {
			
			if (strpos($data_field, $prefix .'.') !== false) {
				
				if (in_array($data_field, $whitelist)) {
					continue;
				}
				
				unset($Model->data[$Model->alias][$data_field]);
				
			}
			
		}
		
		return true;
		
	}
	
	/**
	 * Get Special Field Validate Fields
	 * 
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   1.0
	 * @param   Model $Model
	 * @param   string $prefix
	 * @return  array
	 */
	public function getSpecialFieldValidateFields(Model $Model, $prefix) {
		
		$list = array();
		
		if (empty($prefix)) {
			return $list;
		}
		
		if (empty($Model->validate)) {
			return $list;
		}
		
		foreach($Model->validate as $validation_field => $validation_rules) {
			
			if (strpos($validation_field, $prefix .'.') === 0) {
				$list[] = $validation_field;
			}
			
		}
		
		return $list;
		
	}
	
}
