<?php
/**
 * OAuth2 Hash Behavior
 *
 * PHP 5
 *
 * Copyright (c) WizeHive, Inc. (http://www.wizehive.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @author      Anthony Putignano <anthony@wizehive.com>
 * @since       0.1
 * @package     OAuth2
 * @subpackage  OAuth2.Model.Behavior
 * @copyright   Copyright (c) WizeHive, Inc. (http://www.wizehive.com)
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 */
class OAuth2HashBehavior extends ModelBehavior {
	
	/**
	 * Default settings
	 * 
	 * @since   0.1
	 * @var	    array
	 */
	private $__default_settings = array(
		'type' => 'sha512',
		'fields' => array()
	);

	/**
	 * Settings
	 * 
	 * @since   0.1
	 * @var	    array
	 */
	protected $_settings = array();
		
	/**
	 * Setup callback
	 * 
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	Model	$Model
	 * @param	array	$settings 
	 * @return	void
	 */
	public function setup(&$Model, $settings = array()) {
		
		if (!isset($this->_settings[$Model->alias])) {
			
			$this->_settings[$Model->alias] = $this->__default_settings;
			
		}
		
		if (!is_array($settings)) $settings = array();

		$this->_settings[$Model->alias] = array_merge($this->_settings[$Model->alias], $settings);
		
	}
	
	/**
	 * Create a hash based on the hash `type` defined in the settings
	 * 
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	Model	$Model
	 * @param	string	$string
	 * @return	string	The hashed string
	 */
	public function oAuth2Hash(&$Model, $string = '') {
		
		$hashed = null;
		
		switch ($this->_settings[$Model->alias]['type']) {
			
			case 'sha512':
				
				$string = Configure::read('Security.salt') . $string;
				
				if (function_exists('mhash')) {
					return bin2hex(mhash(MHASH_SHA512, $string));
				}

				if (function_exists('hash')) {
					return hash('sha512', $string);
				}
				
				break;
			
			default:
				
				$hashed = Security::hash($string, $this->_settings[$Model->alias]['type'], true);
				break;
			
		}
		
		return $hashed;
		
	}
	
	/**
	 * Before save callback
	 * 
	 * Hash any fields defined in the `fields` setting
	 * 
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	Model	$Model
	 * @param	array	$options
	 * @return	boolean 
	 */
	public function beforeSave(&$Model, $options = array()) {
		
		if (!empty($this->_settings[$Model->alias]['fields'])) {
			foreach ($this->_settings[$Model->alias]['fields'] as $field) {
				if (!empty($Model->data[$Model->alias][$field])) {
					$Model->data[$Model->alias][$field] = $this->oAuth2Hash($Model, $Model->data[$Model->alias][$field]);
				}
			}
		}
		
		return true;
		
	}
	
	/**
	 * Before find callback
	 * 
	 * Hash any conditions for fields defined in the `fields` setting
	 * 
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	Model	$Model
	 * @param	array	$queryData
	 * @param	array	$queryData
	 */
	public function beforeFind(&$Model, $queryData = array()) {
		
		if (!empty($this->_settings[$Model->alias]['fields'])) {
			foreach ($this->_settings[$Model->alias]['fields'] as $field) {
				if (!empty($queryData['conditions'][$field])) {
					$queryData['conditions'][$field] = $this->oAuth2Hash($Model, $queryData['conditions'][$field]);
				}
				if (!empty($queryData['conditions'][$Model->alias . '.' . $field])) {
					$queryData['conditions'][$Model->alias . '.' . $field] = $this->oAuth2Hash($Model, $queryData['conditions'][$Model->alias . '.' . $field]);
				}
			}
		}
		
		return $queryData;
		
	}
	
}
