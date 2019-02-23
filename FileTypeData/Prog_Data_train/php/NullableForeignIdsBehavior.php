<?php
App::uses('ModelBehavior', 'Model');

/**
 * Nullable Foreign Ids Behavior
 *
 * Handle empty/false/0 values for foreign ID fields by making them null when appropriate
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
 * @package     NullableForeignIds
 * @subpackage  NullableForeignIds.Model.Behavior
 * @copyright   Copyright (c) WizeHive, Inc. (http://www.wizehive.com)
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 */
class NullableForeignIdsBehavior extends ModelBehavior {
	
	/**
	 * Set/limit the nullable foreign id fields for this model.
	 * If empty, all nullable id fields as determined from the DB will be used
	 *
	 * @param Model $model
	 * @param array $settings
	 * @return void
	 */
	public function setup(Model $Model, $settings = null) {
		$this->settings[$Model->alias]['nullableForeignIds'] = $settings;
		$this->settings[$Model->alias]['schemaNullable'] = null;
	}
	
	/**
	 * Get default array for this model of field names that are foreign ids and are allowed to be null 
	 * 
	 * @param	Model $Model
	 * @return	array
	 */
	public function getDefaultNullableForeignIds(Model $Model) {
		return $this->settings[$Model->alias]['nullableForeignIds'];
	}
	
	/**
	 * Get an array of field names that are foreign ids and are allowed to be null
	 * 
	 * @param	Model $Model
	 * @return	array
	 */
	public function getNullableForeignIds(Model $Model) {
		$returnFields = $this->getDefaultNullableForeignIds($Model);
		if (!empty($returnFields)) return $returnFields;
		if ($this->settings[$Model->alias]['schemaNullable'] !== null) return $this->settings[$Model->alias]['schemaNullable'];
		
		$schema = $Model->schema();
		if (empty($schema)) return $returnFields;
		foreach ($schema as $fieldName => $fieldAttrs) {
			if (substr($fieldName, -3) == '_id' 
				&& isset($fieldAttrs['null']) && $fieldAttrs['null'] == true 
				&& isset($fieldAttrs['type']) && $fieldAttrs['type'] == 'integer') {
				$returnFields[] = $fieldName;
			}
		}
		return $this->settings[$Model->alias]['schemaNullable'] = $returnFields;
	}
	
	/**
	 * Replace empty/0/etc. foreign ID values with null if this model hasn't already been nullified
	 * 
	 * @param Model $Model
	 * @return void
	 */
	private function nullify(Model $Model) {
		if (empty($Model->data)) return;
		// Find all fields that are foreign ids and allow null
		$nullableForeignIds = $this->getNullableForeignIds($Model);
		foreach ($Model->data[$Model->alias] as $field => $value) {
			if (in_array($field, $nullableForeignIds)) {
				if (empty($value)) {
					$Model->data[$Model->alias][$field] = null;
				}
			}
		}
	}
	
	/**
	 * Before Save Callback - Nullify foreign ID values before saving
	 * 
	 * @param	Model $Model
	 * @param	array $options
	 * @return	boolean
	 */
	public function beforeSave(Model $Model) {		
		$this->nullify($Model);
		return true;
	}
	
	/**
	 * Before Validate Callback - Nullify foreign ID values before validating
	 * 
	 * @param	Model $Model
	 * @return	boolean
	 */
	public function beforeValidate(Model $Model) {
		$this->nullify($Model);
		return true;
	}
	
}
