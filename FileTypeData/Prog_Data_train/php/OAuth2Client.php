<?php
App::uses('OAuth2AppModel', 'OAuth2.Model');

/**
 * OAuth2 Client Model
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
class OAuth2Client extends OAuth2AppModel {
	
	/**
	 * Required model
	 *
	 * @since   0.1
	 * @var	    string 
	 */
	public $useTable = 'oauth2_clients';
	
	/**
	 * Behaviors
	 *
	 * @since   0.1
	 * @var	    array 
	 */
	public $actsAs = array(
		'OAuth2.OAuth2Hash' => array(
			'fields' => array('api_secret')
		)
	);
	
	/**
	 * Has many associations
	 * 
	 * @since   0.1
	 * @var	    array
	 */
	public $hasMany = array(
		'OAuth2Authorization' => array(
			'className' => 'OAuth2.OAuth2Authorization',
			'foreignKey' => 'oauth2_client_id',
			'dependent' => true
		),
		'OAuth2AuthCode' => array(
			'className' => 'OAuth2.OAuth2AuthCode',
			'foreignKey' => 'oauth2_client_id',
			'dependent' => true
		),
		'OAuth2AccessToken' => array(
			'className' => 'OAuth2.OAuth2AccessToken',
			'foreignKey' => 'oauth2_client_id',
			'dependent' => true
		),
		'OAuth2RefreshToken' => array(
			'className' => 'OAuth2.OAuth2RefreshToken',
			'foreignKey' => 'oauth2_client_id',
			'dependent' => true
		)
	);
	
	/**
	 * Validation rules
	 *
	 * @since   0.1
	 * @var	    array
	 */
	public $validate = array(
		'app_name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'allowEmpty' => false,
				'required' => 'create',
				'last' => true,
				'message' => 'Please enter a valid name'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 50),
				'allowEmpty' => false,
				'message' => 'Name has a max length of 50 characters',
			)
		),
		'redirect_uri' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'allowEmpty' => false,
				'required' => 'create',
				'last' => true,
				'message' => 'Please enter a valid redirect URI'
			),
			'url' => array(
				'rule' => array('url', true),
				'allowEmpty' => false,
				'message' => 'Please enter a valid redirect URI'
			)
		)
	);
	
	/**
	 * Parent before save callback
	 * 
	 * De-coupled for easier unit testing
	 * 
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @param   array $options
	 * @return  boolean
	 */
	public function __parentBeforeSave($options) {
		return parent::beforeSave($options);
	}
	
	/**
	 * Parent after save callback
	 * 
	 * De-coupled for easier unit testing
	 * 
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @param   boolean $created
	 * @return  boolean
	 */
	public function __parentAfterSave($created) {
		return parent::afterSave($created);
	}
	
	/**
	 * Before save callback
	 * 
	 * If the client is being created, add the `api_key` and `api_secret`, plus
	 * make sure they are hashed.
	 * 
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	array	$options
	 * @return	boolean 
	 */
	public function beforeSave($options = array()) {
		
		if (empty($this->id)) {
			
			if (!empty($this->whitelist)) {
				foreach (array('api_key', 'api_secret') as $field) {
					if (!in_array($field, $this->whitelist)) {
						$this->whitelist[] = $field;
					}
				}
			}
			
			$this->data[$this->alias]['api_key'] = $this->generateRandomString(36);
			$this->__api_secret = $this->generateRandomString(64);
			$this->data[$this->alias]['api_secret'] = $this->oAuth2Hash($this->__api_secret);
			
		}
		
		return $this->__parentBeforeSave($options);
		
	}
	
	/**
	 * After save callback
	 * 
	 * Insert the un-hashed `api_secret` back into the data.
	 * 
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	boolean	$created
	 * @return	boolean
	 */
	public function afterSave($created = false) {
		
		$return = $this->__parentAfterSave($created);
		
		if ($created && !empty($this->__api_secret)) {
			$this->data[$this->alias]['api_secret'] = $this->__api_secret;
		}
		
		return $return;
		
	}
	
	/**
	 * Get the last un-hashed `api_secret` which was saved
	 * 
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @return	string
	 */
	public function getLastApiSecret() {
		
		return !empty($this->__api_secret) ? $this->__api_secret : null;
		
	}
	
	/**
	 * Get a random string
	 * 
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	integer	$length
	 * @return	string
	 */
	public function generateRandomString($length = 36) {
		
		if (file_exists('/dev/urandom')) { // Get 100 bytes of random data
			$randomData = file_get_contents('/dev/urandom', false, null, 0, 100) . uniqid(mt_rand(), true);
		} else {
			$randomData = mt_rand() . mt_rand() . mt_rand() . mt_rand() . microtime(true) . uniqid(mt_rand(), true);
		}
		return substr(hash('sha512', $randomData), 0, $length);
		
	}
	
	/**
	 * Make sure that the client credentials is valid.
	 *
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	string	$client_id		Client identifier to be check with.
	 * @param	string	$client_secret	(optional) If a secret is required, check that they've given the right one.
	 * @return	boolean	TRUE if the client credentials are valid, and MUST return FALSE if it isn't.
	 *
	 * @see http://tools.ietf.org/html/rfc6749#section-3.1
	 *
	 * @ingroup oauth2_section_3
	 */
	public function checkClientCredentials($client_id, $client_secret = null) {
		
		$conditions = array('api_key' => $client_id);
		if (!is_null($client_secret)) {
			$conditions['api_secret'] = $client_secret;
		}
		
		$client = $this->field('id', $conditions);
		
		return (bool) $client;
		
	}
	
	/**
	 * Get client details corresponding client_id.
	 *
	 * OAuth says we should store request URIs for each registered client.
	 * Implement this function to grab the stored URI for a given client id.
	 *
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	string	$client_id	Client identifier to be check with.
	 * @return	array	Client details. Only mandatory item is the "registered redirect URI", and MUST
	 *					return FALSE if the given client does not exist or is invalid.
	 *
	 * @ingroup oauth2_section_4
	 */
	public function getClientDetails($client_id) {
		
		$data = $this->find('first', array(
			'conditions' => array(
				$this->alias . '.api_key' => $client_id
			),
			'contain' => false
		));
		
		if (empty($data[$this->alias]['id'])) {
			return false;
		}
		
		return array(
			'client_id' => $data[$this->alias]['api_key'],
			'user_id' => $data[$this->alias]['user_id'],
			'app_name' => $data[$this->alias]['app_name'],
			'redirect_uri' => $data[$this->alias]['redirect_uri']
		);
		
	}
	
}
