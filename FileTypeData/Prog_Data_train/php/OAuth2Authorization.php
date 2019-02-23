<?php
App::uses('OAuth2AppModel', 'OAuth2.Model');

/**
 * OAuth2 Authorization Model
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
 * @subpackage  OAuth2.Model
 * @copyright   Copyright (c) WizeHive, Inc. (http://www.wizehive.com)
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 */
class OAuth2Authorization extends OAuth2AppModel {
	
	/**
	 * Required model
	 *
	 * @since   0.1
	 * @var	    string 
	 */
	public $useTable = 'oauth2_authorizations';
	
	/**
	 * Belongs to associations
	 *
	 * @since   0.1
	 * @var	    array 
	 */
	public $belongsTo = array(
		'OAuth2Client' => array(
			'className' => 'OAuth2.OAuth2Client',
			'foreignKey' => 'oauth2_client_id'
		)
	);
	
	/**
	 * Parent before delete callback
	 * 
	 * De-coupled for easier unit testing
	 * 
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @param   boolean $cascade
	 * @return  boolean
	 */
	public function __parentBeforeDelete($cascade) {
		return parent::beforeDelete($cascade);
	}
	
	/**
	 * Parent after delete callback
	 * 
	 * De-coupled for easier unit testing
	 * 
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  boolean
	 */
	public function __parentAfterDelete() {
		return parent::afterDelete();
	}
	
	/**
	 * Before delete callback
	 * 
	 * Store authorization which is about to be deleted for later
	 * 
	 * @author  Anthony Putignano <anthony@wizehive.com>
	 * @since   0.1
	 * @param   boolean $cascade
	 * @return  boolean
	 */
	public function beforeDelete($cascade = true) {
		
		$authorization = $this->find('first', array(
			'conditions' => array(
				$this->alias . '.id' => $this->id
			),
			'fields' => array(
				'oauth2_client_id',
				'user_id'
			),
			'contain' => false
		));
		
		if (!empty($authorization)) {
			$this->__oldData = $authorization;
		}
		
		return $this->__parentBeforeDelete($cascade);
		
	}
	
	/**
	 * After delete callback
	 * 
	 * Expire all auth codes & tokens related to this authorization
	 * 
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @return	boolean 
	 */
	public function afterDelete() {
		
		if (empty($this->__oldData)) {
			return $this->__parentAfterDelete();
		}
		
		$result = true;
		
		foreach (array(
			'OAuth2AuthCode',
			'OAuth2AccessToken',
			'OAuth2RefreshToken'
		) as $model) {
			if (!isset($this->{$model})) {
				$this->{$model} = ClassRegistry::init('OAuth2.' . $model);
			}
			if (!$this->{$model}->updateAll(array(
				$model . '.expires' => 0
			), array(
				$model . '.oauth2_client_id' => $this->__oldData[$this->alias]['oauth2_client_id'],
				$model . '.user_id' => $this->__oldData[$this->alias]['user_id']
			))) {
				$result = false;
			}
		}
		
		unset($this->__oldData);
		
		if (!$result) {
			return $result;
		}
		
		return $this->__parentAfterDelete();
		
	}
	
	/**
	 * Determine if there is an existing authorization for an API Key and User ID combination
	 * 
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	string	$api_key
	 * @param	integer	$user_id
	 * @param	string	$scope
	 * @return	boolean
	 */
	public function getExisting($api_key, $user_id, $scope = null) {
		
		$preauthorized_api_keys = Configure::read('oAuth2.preauthorizedApiKeys');
		if (empty($preauthorized_api_keys)) {
			$preauthorized_api_keys = array();
		}
		
		if (in_array($api_key, $preauthorized_api_keys)) {
			
			$existing_authorization = true;
			
		} else {

			$client_id = $this->OAuth2Client->field('id', array(
				'api_key' => $api_key
			));
			
			if (empty($client_id)) {
				$existing_authorization = false;
			} else {
				$existing_authorization = $this->field('id', array(
					'oauth2_client_id' => $client_id,
					'user_id' => $user_id,
					'scope' => $scope
				));
			}
		
		}
		
		return (bool) $existing_authorization;
		
	}
	
	/**
	 * Create an authorization if one does not already exist, update if it does
	 * 
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	integer	$client_id
	 * @param	integer	$user_id
	 * @param	string	$scope
	 * @return	boolean
	 */
	public function upsert($client_id, $user_id, $scope = null) {
		
		$this->id = $this->field('id', array(
			'oauth2_client_id' => $client_id,
			'user_id' => $user_id
		));
		
		$result = $this->save(array(
			$this->alias => array(
				'oauth2_client_id' => $client_id,
				'user_id' => $user_id,
				'scope' => $scope
			)
		));
		
		return (bool) $result;
		
	}
	
}
