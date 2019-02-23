<?php
App::uses('BaseAuthenticate', 'Controller' . DS . 'Component' . DS . 'Auth');

/**
 * OAuth2 Authenticate
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
 * @subpackage  OAuth2.Component.Auth
 * @copyright   Copyright (c) WizeHive, Inc. (http://www.wizehive.com)
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 */
class OAuth2Authenticate extends BaseAuthenticate {
	
	/**
	 * Settings
	 * 
	 * @since   0.1
	 * @var	    array
	 */
	public $settings = array(
		'token_type' => 'bearer',
		'access_lifetime' => 3600, // 1 hour
		'refresh_token_lifetime' => 1209600, // 14 days
		'www_realm' => 'Service',
		'token_param_name' => 'access_token',
		'token_bearer_header_name' => 'Bearer',
		'enforce_state' => true,
		'allow_implicit' => true,
		'userModel' => 'User',
		'fields' => array(
			'username' => 'username',
			'password' => 'password'
		),
		'scope' => array(),
		'recursive' => 0,
		'contain' => null
	);
	
	/**
	 * Abort request
	 *
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	string	$message Abort message. 
	 * @return	void
	 */
	protected function _abort($message = '') {
		
		die($message);
		
	}
	
	/**
	 * Constructor
	 *
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since	0.1
	 * @param	ComponentCollection	$collection	The Component collection used on this request.
	 * @param	array				$settings	Array of settings to use.
	 * @return	void
	 */
	public function __construct(ComponentCollection $Collection, $settings = array()) {
		
		$this->Controller = $Collection->getController();
		
		$this->settings = Hash::merge($this->settings, $settings);
		
		// This needs to be called here instead in the top of this file
		App::uses('OAuth2Component', 'OAuth2.Controller' . DS . 'Component');
		
		$this->OAuth2Component = new OAuth2Component($Collection, $this->settings);
		$this->OAuth2Component->initialize($this->Controller);
		
	}
	
	/**
	 * authenticate
	 * 
	 * Verifies an access token and returns the associated `User`
	 * 
	 * Note that because of how the vendor library works, failure responses are sent
	 * back directly from within this method.
	 *
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	CakeRequest		$request	The request that contains login information.
	 * @param	CakeResponse	$response	Unused response object.
	 * @return	mixed			False on login failure. An array of User data on success.
	 */
	public function authenticate(CakeRequest $request, CakeResponse $response) {
		
		return $this->getUser($request);
		
	}
	
	/**
	 * Get user
	 *
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	CakeRequest		$request	The request that contains login information.
	 * @return	mixed			False on login failure. An array of User data on success.
	 */
	public function getUser(CakeRequest $request) {
		
		if (!$this->OAuth2Component->verifyAccessRequest()) {
			$this->OAuth2Component->getResponse()->send();
			$this->_abort();
			return false;
		}
		
		if (!isset($this->OAuth2AccessToken)) {
			$this->OAuth2AccessToken = ClassRegistry::init('OAuth2.OAuth2AccessToken');
		}
		
		if (!isset($this->Bearer)) {
			$this->Bearer = new OAuth2_TokenType_Bearer($this->settings);
		}
		
		$access_token = $this->Bearer->getAccessTokenParameter($this->OAuth2Component->Request);
		
		if (empty($access_token)) {
			$this->OAuth2Component
				->errorResponse(400, 'invalid_request', 'Token not found')
				->send();
			$this->_abort();
			return false;
		}
		
		$user_id = $this->OAuth2AccessToken->field('user_id', array(
			'access_token' => $access_token
		));
		
		if (!empty($user_id)) {
			$user = $this->_findUser(array($this->settings['userModel'] . '.id' => $user_id));
		}
		
		if (empty($user_id) || empty($user)) {
			$this->OAuth2Component
				->errorResponse(400, 'invalid_request', 'User not found')
				->send();
			$this->_abort();
			return false;
		}
		
		return $user;
		
	}
	
}
