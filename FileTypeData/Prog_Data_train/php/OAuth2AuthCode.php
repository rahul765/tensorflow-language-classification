<?php
App::uses('OAuth2AppModel', 'OAuth2.Model');

/**
 * OAuth2 Auth Code Model
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
class OAuth2AuthCode extends OAuth2AppModel {
	
	/**
	 * Required model
	 *
	 * @since   0.1
	 * @var	    string 
	 */
	public $useTable = 'oauth2_auth_codes';
	
	/**
	 * Behaviors
	 *
	 * @since   0.1
	 * @var	    array 
	 */
	public $actsAs = array(
		'OAuth2.OAuth2Hash' => array(
			'fields' => array('auth_code')
		)
	);
	
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
	 * Fetch authorization code data (probably the most common grant type).
	 *
	 * Retrieve the stored data for the given authorization code.
	 *
	 * Required for OAuth2::GRANT_TYPE_AUTH_CODE.
	 *
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	string	$code	Authorization code to be check with.
	 * @return	array	An associative array as below, and NULL if the code is invalid:
	 *					- client_id: Stored client identifier.
	 *					- redirect_uri: Stored redirect URI.
	 *					- expires: Stored expiration in unix timestamp.
	 *					- scope: (optional) Stored scope values in space-separated string.
	 *
	 * @see http://tools.ietf.org/html/rfc6749#section-4.1
	 *
	 * @ingroup oauth2_section_4
	 */
	public function getAuthorizationCode($code) {
		
		$data = $this->find('first', array(
			'conditions' => array(
				'auth_code' => $code
			),
			'fields' => array(
				'redirect_uri',
				'expires',
				'user_id'
			),
			'contain' => array(
				'OAuth2Client' => array(
					'fields' => array(
						'api_key'
					)
				)
			)
		));
		
		if ( 
			empty($data[$this->alias]) ||
			empty($data['OAuth2Client']['api_key'])
		) {
			return null;
		}
		
		return array_merge(
			array('client_id' => $data['OAuth2Client']['api_key']),
			$data[$this->alias]
		);
		
	}
	
	/**
	 * Take the provided authorization code values and store them somewhere.
	 *
	 * This function should be the storage counterpart to getAuthCode().
	 *
	 * If storage fails for some reason, we're not currently checking for
	 * any sort of success/failure, so you should bail out of the script
	 * and provide a descriptive fail message.
	 *
	 * Required for OAuth2::GRANT_TYPE_AUTH_CODE.
	 *
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	string	$code			Authorization code to be stored.
	 * @param	string	$client_id		Client identifier to be stored.
	 * @param	integer	$user_id		User identifier to be stored.
	 * @param	string	$redirect_uri	Redirect URI to be stored.
	 * @param	integer	$expires		Expiration to be stored.
	 * @param	string	$scope			(optional) Scopes to be stored in space-separated string.
	 * @return	boolean
	 * 
	 * @ingroup oauth2_section_4
	 */
	public function setAuthorizationCode($code, $client_id, $user_id, $redirect_uri, $expires, $scope = null) {
		
		$client_id = $this->OAuth2Client->field('id', array('api_key' => $client_id));
		
		if (empty($client_id)) {
			return false;
		}
		
		$this->create(false);
		if (!$this->save(array(
			$this->alias => array(
				'auth_code' => $code,
				'oauth2_client_id' => $client_id,
				'user_id' => $user_id,
				'redirect_uri' => $redirect_uri,
				'expires' => $expires,
				'scope' => $scope
			)
		))) {
			return false;
		}
		
		return true;
		
	}
	
	/**
	 * Once an Authorization Code is used, it must be exipired
	 *
	 * @see http://tools.ietf.org/html/rfc6749#section-4.1.2
	 *
	 *    The client MUST NOT use the authorization code
	 *    more than once.  If an authorization code is used more than
	 *    once, the authorization server MUST deny the request and SHOULD
	 *    revoke (when possible) all tokens previously issued based on
	 *    that authorization code
	 *
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	string	$code		Authorization code to expire.
	 * @return	boolean
	 * 
	 */
	public function expireAuthorizationCode($code) {
		
		$result = $this->updateAll(array(
			$this->alias . '.expires' => 0,
			$this->alias . '.modified' => "'" . date('Y-m-d H:i:s') . "'"
		), array(
			$this->alias . '.auth_code' => $code
		));
		
		return (bool) $result;
		
	}
	
}
