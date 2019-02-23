<?php
App::uses('BaseAuthenticate', 'Controller' . DS . 'Component' . DS . 'Auth');

/**
 * OAuth2PublicAuthenticate
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
 * @package     OAuth2
 * @subpackage  OAuth2.Controller.Component.Auth
 * @copyright   Copyright (c) WizeHive, Inc. (http://www.wizehive.com)
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 */
class OAuth2PublicAuthenticate extends BaseAuthenticate {
	
	/**
	 * authenticate
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
	 * getUser
	 *
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	CakeRequest		$request	The request that contains login information.
	 * @return	mixed			False on login failure. An array of User data on success.
	 */
	public function getUser($request) {
		
		return array(
			'id' => 'public',
			'admin_level' => 'public',
			'Role' => array(
				'id' => 'public',
				'slug' => 'public',
				'name' => 'Public'
			)
		);
		
	}
	
}
