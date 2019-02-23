<?php
App::uses('OAuth2AppModel', 'OAuth2.Model');

/**
 * OAuth2 Refresh Token Model
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
class OAuth2RefreshToken extends OAuth2AppModel {
	
	/**
	 * Required model
	 *
	 * @since   0.1
	 * @var	    string
	 */
	public $useTable = 'oauth2_refresh_tokens';
	
	/**
	 * Behaviors
	 *
	 * @since   0.1
	 * @var	    array 
	 */
	public $actsAs = array(
		'OAuth2.OAuth2Hash' => array(
			'fields' => array('refresh_token')
		),
		'OAuth2.OAuth2Authorization' => array(
			'fields' => array(
				'client_id' => 'oauth2_client_id',
				'user_id' => 'user_id',
				'scope' => 'scope'
			)
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
	 * Grant refresh access tokens.
	 *
	 * Retrieve the stored data for the given refresh token.
	 *
	 * Required for OAuth2::GRANT_TYPE_REFRESH_TOKEN.
	 *
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	string	$refresh_token	Refresh token to be check with.
	 * @return	An associative array as below, and NULL if the refresh_token is
	 *			invalid:
	 *			- client_id: Stored client identifier.
	 *			- expires: Stored expiration unix timestamp.
	 *			- scope: (optional) Stored scope values in space-separated string.
	 *
	 * @see http://tools.ietf.org/html/rfc6749#section-6
	 *
	 * @ingroup oauth2_section_6
	 */
	public function getRefreshToken($refresh_token) {
		
		$data = $this->find('first', array(
			'conditions' => array(
				$this->alias . '.refresh_token' => $refresh_token
			),
			'fields' => array(
				'user_id',
				'expires',
				'scope'
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
		
		return array(
			'refresh_token' => $refresh_token,
			'client_id' => $data['OAuth2Client']['api_key'],
			'user_id' => $data[$this->alias]['user_id'],
			'expires' => $data[$this->alias]['expires'],
			'scope' => $data[$this->alias]['scope']
		);
		
	}
	
	/**
	 * Take the provided refresh token values and store them somewhere.
	 *
	 * This function should be the storage counterpart to getRefreshToken().
	 *
	 * If storage fails for some reason, we're not currently checking for
	 * any sort of success/failure, so you should bail out of the script
	 * and provide a descriptive fail message.
	 *
	 * Required for OAuth2::GRANT_TYPE_REFRESH_TOKEN.
	 *
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	string	$refresh_token	Refresh token to be stored.
	 * @param	string	$client_id		Client identifier to be stored.
	 * @param	integer	$expires		expires to be stored.
	 * @param	string	$scope			(optional) Scopes to be stored in space-separated string.
	 * @return	boolean
	 * 
	 * @ingroup oauth2_section_6
	 */
	public function setRefreshToken($refresh_token, $client_id, $user_id, $expires, $scope = null) {
		
		$client_id = $this->OAuth2Client->field('id', array('api_key' => $client_id));
		
		if (empty($client_id)) {
			return false;
		}
		
		$this->create(false);
		if (!$this->save(array(
			$this->alias => array(
				'refresh_token' => $refresh_token,
				'oauth2_client_id' => $client_id,
				'user_id' => $user_id,
				'expires' => $expires,
				'scope' => $scope
			)
		))) {
			return false;
		}
		
		return true;
		
	}
	
	/**
	 * Expire a used refresh token.
	 *
	 * This is not explicitly required in the spec, but is almost implied.
	 * After granting a new refresh token, the old one is no longer useful and
	 * so should be forcibly expired in the data store so it can't be used again.
	 *
	 * If storage fails for some reason, we're not currently checking for
	 * any sort of success/failure, so you should bail out of the script
	 * and provide a descriptive fail message.
	 *
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	string	$refresh_token	Refresh token to be expired.
	 * @return	boolean
	 * 
	 * @ingroup oauth2_section_6
	 */
	public function unsetRefreshToken($refresh_token) {
		
		$result = $this->updateAll(array(
			$this->alias . '.expires' => 0,
			$this->alias . '.modified' => "'" . date('Y-m-d H:i:s') . "'"
		), array(
			$this->alias . '.refresh_token' => $refresh_token
		));
		
		return (bool) $result;
		
	}
	
}
