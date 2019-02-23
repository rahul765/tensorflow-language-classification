<?php
App::uses('OAuth2AuthCode', 'OAuth2.Model');
App::uses('OAuth2AccessToken', 'OAuth2.Model');
App::uses('OAuth2Client', 'OAuth2.Model');
App::uses('OAuth2RefreshToken', 'OAuth2.Model');
App::uses('OAuth2Storage', 'OAuth2.Lib');

/**
 * OAuth2 Storage Double
 */
class OAuth2StorageDouble extends OAuth2Storage {
	
	public $_settings = array();
	
}

/**
 * OAuth2 Storage Utility Tests
 *
 * PHP 5
 *
 * Copyright (c) WizeHive, Inc. (http://www.wizehive.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @author      Everton Yoshitani <everton@wizehive.com>
 * @since       0.1
 * @package     OAuth2
 * @subpackage  OAuth2.Test.Case.Lib
 * @copyright   Copyright (c) WizeHive, Inc. (http://www.wizehive.com)
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 */
class OAuth2StorageTest extends CakeTestCase {

	/**
	 * Setup
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function setUp() {

		parent::setUp();

	}

	/**
	 * Tear Down
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function tearDown() {

		parent::tearDown();

		ClassRegistry::flush();
		
	}

	/**
	 * Test Instance Setup
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testInstanceSetup() {

		$settings = array('key' => 'value');
		
		$this->OAuth2Storage = new OAuth2StorageDouble($settings);
		
		$this->assertEquals($settings, $this->OAuth2Storage->_settings);
		
		$implements = class_implements(new OAuth2Storage());

		$interfaces = array(
			'OAuth2_Storage_AuthorizationCodeInterface',
	 		'OAuth2_Storage_AccessTokenInterface',
	 		'OAuth2_Storage_ClientCredentialsInterface',
			'OAuth2_Storage_ClientInterface',
	 		'OAuth2_Storage_RefreshTokenInterface',
	 		'OAuth2_Storage_UserCredentialsInterface'
		);
		
		foreach ($interfaces as $interface) {
			$this->assertArrayHasKey($interface, $implements);
		}
		
	}
	
	/**
	 * Test Model - Initialization
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testModelInitialization() {
		
		$this->OAuth2Storage = new OAuth2Storage();
		
		$model_name = 'Client';
		
		$results = $this->OAuth2Storage->model($model_name);
		
		$this->assertInstanceOf('OAuth2Client', $results);
		
	}
	
	/**
	 * Test Get Authorization Code
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testGetAuthorizationCode() {
		
		$expected = uniqid();
		
		$this->OAuth2Storage = $this->getMock(
			'OAuth2Storage',
			array('model')
		);
		
		$this->OAuth2Storage->OAuth2AuthCode = $this->getMock(
			'OAuth2AuthCode',
			array('getAuthorizationCode')
		);
		
		$this->OAuth2Storage
			->expects($this->once())
			->method('model')
			->with('AuthCode')
			->will($this->returnValue($this->OAuth2Storage->OAuth2AuthCode));
			
		$this->OAuth2Storage->OAuth2AuthCode
			->expects($this->once())
			->method('getAuthorizationCode')
			->with($expected)
			->will($this->returnValue($expected));
			
		$this->assertEquals(
			$expected,
			$this->OAuth2Storage->getAuthorizationCode($expected)
		);
		
	}
	
	/**
	 * Test Set Authorization Code
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testSetAuthorizationCode() {
		
		$expected 
			= $code 
			= $client_id 
			= $user_id 
			= $redirect_uri 
			= $expires 
			= $scope 
			= uniqid();
		
		$this->OAuth2Storage = $this->getMock(
			'OAuth2Storage',
			array('model')
		);
		
		$this->OAuth2Storage->OAuth2AuthCode = $this->getMock(
			'OAuth2AuthCode',
			array('setAuthorizationCode')
		);
		
		$this->OAuth2Storage
			->expects($this->once())
			->method('model')
			->with('AuthCode')
			->will($this->returnValue($this->OAuth2Storage->OAuth2AuthCode));
			
		$this->OAuth2Storage->OAuth2AuthCode
			->expects($this->once())
			->method('setAuthorizationCode')
			->with(
				$code,
				$client_id,
				$user_id,
				$redirect_uri,
				$expires,
				$scope
			)
			->will($this->returnValue($expected));
			
		$this->assertEquals(
			$expected,
			$this->OAuth2Storage->setAuthorizationCode(
				$code,
				$client_id,
				$user_id,
				$redirect_uri,
				$expires,
				$scope
			)
		);
		
	}
	
	/**
	 * Test Expire Authorization Code
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testExpireAuthorizationCode() {
		
		$expected = uniqid();
		
		$this->OAuth2Storage = $this->getMock(
			'OAuth2Storage',
			array('model')
		);
		
		$this->OAuth2Storage->OAuth2AuthCode = $this->getMock(
			'OAuth2AuthCode',
			array('expireAuthorizationCode')
		);
		
		$this->OAuth2Storage
			->expects($this->once())
			->method('model')
			->with('AuthCode')
			->will($this->returnValue($this->OAuth2Storage->OAuth2AuthCode));
			
		$this->OAuth2Storage->OAuth2AuthCode
			->expects($this->once())
			->method('expireAuthorizationCode')
			->with($expected)
			->will($this->returnValue($expected));
			
		$this->assertEquals(
			$expected,
			$this->OAuth2Storage->expireAuthorizationCode($expected)
		);
		
	}
	
	/**
	 * Test Get Access Token
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testGetAccessToken() {
		
		$expected = uniqid();
		
		$this->OAuth2Storage = $this->getMock(
			'OAuth2Storage',
			array('model')
		);
		
		$this->OAuth2Storage->OAuth2AccessToken = $this->getMock(
			'OAuth2AccessToken',
			array('getAccessToken')
		);
		
		$this->OAuth2Storage
			->expects($this->once())
			->method('model')
			->with('AccessToken')
			->will($this->returnValue($this->OAuth2Storage->OAuth2AccessToken));
			
		$this->OAuth2Storage->OAuth2AccessToken
			->expects($this->once())
			->method('getAccessToken')
			->with($expected)
			->will($this->returnValue($expected));
			
		$this->assertEquals(
			$expected,
			$this->OAuth2Storage->getAccessToken($expected)
		);
		
	}
	
	/**
	 * Test Set Access Token
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testSetAccessToken() {
		
		$expected 
			= $oauth_token
			= $client_id
			= $user_id
			= $expires
			= $scope
			= uniqid();
		
		$this->OAuth2Storage = $this->getMock(
			'OAuth2Storage',
			array('model')
		);
		
		$this->OAuth2Storage->OAuth2AccessToken = $this->getMock(
			'OAuth2AccessToken',
			array('setAccessToken')
		);
		
		$this->OAuth2Storage
			->expects($this->once())
			->method('model')
			->with('AccessToken')
			->will($this->returnValue($this->OAuth2Storage->OAuth2AccessToken));
			
		$this->OAuth2Storage->OAuth2AccessToken
			->expects($this->once())
			->method('setAccessToken')
			->with(
				$oauth_token,
				$client_id,
				$user_id,
				$expires,
				$scope
			)
			->will($this->returnValue($expected));
			
		$this->assertEquals(
			$expected,
			$this->OAuth2Storage->setAccessToken(
				$oauth_token,
				$client_id,
				$user_id,
				$expires,
				$scope
			)
		);
		
	}
	
	/**
	 * Test Check Client Credentials
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testCheckClientCredentials() {
		
		$expected = uniqid();
		
		$this->OAuth2Storage = $this->getMock(
			'OAuth2Storage',
			array('model')
		);
		
		$this->OAuth2Storage->OAuth2Client = $this->getMock(
			'OAuth2Client',
			array('checkClientCredentials')
		);
		
		$this->OAuth2Storage
			->expects($this->once())
			->method('model')
			->with('Client')
			->will($this->returnValue($this->OAuth2Storage->OAuth2Client));
			
		$this->OAuth2Storage->OAuth2Client
			->expects($this->once())
			->method('checkClientCredentials')
			->with($expected)
			->will($this->returnValue($expected));
			
		$this->assertEquals(
			$expected,
			$this->OAuth2Storage->checkClientCredentials($expected)
		);
		
	}

	/**
	 * Test Check Restricted Grant Type
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testCheckRestrictedGrantType() {
		
		$client_id = $grant_type = uniqid();
		
		$this->OAuth2Storage = new OAuth2Storage();
		
		$this->assertTrue($this->OAuth2Storage->checkRestrictedGrantType(
			$client_id,
			$grant_type
		));
		
	}
	
	/**
	 * Test Get Client Details
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testGetClientDetails() {
		
		$expected = uniqid();
		
		$this->OAuth2Storage = $this->getMock(
			'OAuth2Storage',
			array('model')
		);
		
		$this->OAuth2Storage->OAuth2Client = $this->getMock(
			'OAuth2Client',
			array('getClientDetails')
		);
		
		$this->OAuth2Storage
			->expects($this->once())
			->method('model')
			->with('Client')
			->will($this->returnValue($this->OAuth2Storage->OAuth2Client));
			
		$this->OAuth2Storage->OAuth2Client
			->expects($this->once())
			->method('getClientDetails')
			->with($expected)
			->will($this->returnValue($expected));
			
		$this->assertEquals(
			$expected,
			$this->OAuth2Storage->getClientDetails($expected)
		);
		
	}
	
	/**
	 * Test Get Refresh Token
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testGetRefreshToken() {
		
		$expected = uniqid();
		
		$this->OAuth2Storage = $this->getMock(
			'OAuth2Storage',
			array('model')
		);
		
		$this->OAuth2Storage->OAuth2RefreshToken = $this->getMock(
			'OAuth2Client',
			array('getRefreshToken')
		);
		
		$this->OAuth2Storage
			->expects($this->once())
			->method('model')
			->with('RefreshToken')
			->will($this->returnValue($this->OAuth2Storage->OAuth2RefreshToken));
			
		$this->OAuth2Storage->OAuth2RefreshToken
			->expects($this->once())
			->method('getRefreshToken')
			->with($expected)
			->will($this->returnValue($expected));
			
		$this->assertEquals(
			$expected,
			$this->OAuth2Storage->getRefreshToken($expected)
		);
		
	}
	
	/**
	 * Test Set Refresh Token
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testSetRefreshToken() {
		
		$expected 
			= $refresh_token
			= $client_id
			= $user_id
			= $expires
			= $scope
			= uniqid();
		
		$this->OAuth2Storage = $this->getMock(
			'OAuth2Storage',
			array('model')
		);
		
		$this->OAuth2Storage->OAuth2RefreshToken = $this->getMock(
			'OAuth2Client',
			array('setRefreshToken')
		);
		
		$this->OAuth2Storage
			->expects($this->once())
			->method('model')
			->with('RefreshToken')
			->will($this->returnValue($this->OAuth2Storage->OAuth2RefreshToken));
			
		$this->OAuth2Storage->OAuth2RefreshToken
			->expects($this->once())
			->method('setRefreshToken')
			->with(
				$refresh_token,
				$client_id,
				$user_id,
				$expires,
				$scope
			)
			->will($this->returnValue($expected));
			
		$this->assertEquals(
			$expected,
			$this->OAuth2Storage->setRefreshToken(
				$refresh_token,
				$client_id,
				$user_id,
				$expires,
				$scope
			)
		);
		
	}
	
	/**
	 * Test Unset Refresh Token
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testUnsetRefreshToken() {
		
		$expected = uniqid();
		
		$this->OAuth2Storage = $this->getMock(
			'OAuth2Storage',
			array('model')
		);
		
		$this->OAuth2Storage->OAuth2RefreshToken = $this->getMock(
			'OAuth2Client',
			array('unsetRefreshToken')
		);
		
		$this->OAuth2Storage
			->expects($this->once())
			->method('model')
			->with('RefreshToken')
			->will($this->returnValue($this->OAuth2Storage->OAuth2RefreshToken));
			
		$this->OAuth2Storage->OAuth2RefreshToken
			->expects($this->once())
			->method('unsetRefreshToken')
			->with($expected)
			->will($this->returnValue($expected));
			
		$this->assertEquals(
			$expected,
			$this->OAuth2Storage->unsetRefreshToken($expected)
		);
		
	}
	
	/**
	 * Test Check User Credentials
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testCheckUserCredentials() {
		
		$expected = $username = $password = uniqid();
		
		$test_model = 'TestModel';
		
		$settings = array('userModel' => $test_model);
		
		$this->OAuth2Storage = new OAuth2Storage($settings);
		
		$this->OAuth2Storage->{$test_model} = $this->getMock(
			$test_model,
			array('authenticate')
		);
		
		$this->OAuth2Storage->{$test_model}
			->expects($this->once())
			->method('authenticate')
			->with($username, $password)
			->will($this->returnValue(true));
			
		$this->assertTrue($this->OAuth2Storage->checkUserCredentials(
			$username,
			$password
		));
		
	}
	
	/**
	 * Test Get User Details - Empty User Id
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testGetUserDetailsEmptyUserId() {
		
		$expected = $username = uniqid();
		
		$user = null;
		
		$test_model = 'TestModel';
		
		$settings = array('userModel' => $test_model);
		
		$this->OAuth2Storage = new OAuth2Storage($settings);
		
		$this->OAuth2Storage->{$test_model} = $this->getMock(
			$test_model,
			array('getUserIdByUsername')
		);
		
		$this->OAuth2Storage->{$test_model}
			->expects($this->once())
			->method('getUserIdByUsername')
			->with($username)
			->will($this->returnValue($user));
			
		$this->assertEquals(
			array(),
			$this->OAuth2Storage->getUserDetails(
				$username
			)
		);
		
	}
	
	/**
	 * Test Get User Details
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testGetUserDetails() {
		
		$expected = $username = uniqid();
		
		$test_model = 'TestModel';
		
		$settings = array('userModel' => $test_model);
		
		$this->OAuth2Storage = new OAuth2StorageDouble($settings);
		
		$this->OAuth2Storage->{$test_model} = $this->getMock(
			$test_model,
			array('getUserIdByUsername')
		);
		
		$get_user_details_results = 1;
		
		$this->OAuth2Storage->{$test_model}
			->expects($this->once())
			->method('getUserIdByUsername')
			->with($username)
			->will($this->returnValue($get_user_details_results));
			
		$this->assertEquals(
			array('user_id' => $get_user_details_results),
			$this->OAuth2Storage->getUserDetails(
				$username
			)
		);
		
	}
	
}
