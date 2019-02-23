<?php
App::uses('OAuth2AppController', 'OAuth2.Controller');
App::uses('OAuth2Component', 'OAuth2.Controller');
App::uses('Component', 'Controller');
App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('RequestHandlerComponent', 'Controller' . DS . 'Components');
App::uses('AuthComponent', 'Controller' . DS . 'Components');
App::uses('SessionComponent', 'Controller' . DS . 'Components');
App::uses('CakeRoute', 'Routing/Route');
App::uses('Router', 'Routing');
App::uses('OAuth2Controller', 'OAuth2.Controller');

/**
 * OAuth2 Controller Tests
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
 * @subpackage  OAuth2.Test.Case.Controller
 * @copyright   Copyright (c) WizeHive, Inc. (http://www.wizehive.com)
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 */
class OAuth2ControllerTest extends CakeTestCase {

	/**
	 * Setup
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function setUp() {

		parent::setUp();
		
		$this->Controller = $this->getMock('Controller');
		
		$this->Controller->request = $this->getMock('CakeRequest');
		
		$this->Controller->response = $this->getMock('CakeResponse');
		
		$this->ComponentCollection = $this->getMock(
			'ComponentCollection',
			array('getController')
		);

		$this->ComponentCollection->expects($this->any())
			->method('getController')
			->will($this->returnValue($this->Controller));

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

		$this->OAuth2Controller = new OAuth2Controller();
		
		$this->assertInstanceOf('OAuth2AppController', $this->OAuth2Controller);
		
	}

	/**
	 * Test Authorize Action Settings Property
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testAuthorizeActionSettingsProperty() {
		
		$this->OAuth2Controller = new OAuth2Controller();
		
		$authorizeActionSettings = array(
			'userIdKey',
			'loginUrl' => array(
				'controller',
				'action',
				'plugin'
			)
		);
		
		foreach ($authorizeActionSettings as $key => $setting) {
			
			if (is_array($setting)) {
				foreach ($setting as $level2) {
					$this->assertArrayHasKey(
						$level2,
						$this->OAuth2Controller->authorizeActionSettings[$key]
					);
				}
			} else {
				$this->assertArrayHasKey(
					$setting,
					$this->OAuth2Controller->authorizeActionSettings
				);
			}
			
		}
		
	}
	
	/**
	 * Test Construct Classes
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testConstructClasses() {
		
		$this->OAuth2Controller = $this->getMock(
			'OAuth2Controller',
			array('_mergeControllerVars')
		);
		
		$this->OAuth2Controller->Components = $this->getMock(
			'ComponentCollection',
			array('init')
		);
		
		$this->OAuth2Controller
			->expects($this->once())
			->method('_mergeControllerVars')
			->with();
			
		$this->OAuth2Controller->Components
			->expects($this->once())
			->method('init')
			->with($this->OAuth2Controller);
			
		$this->OAuth2Controller->uses = array(
			'Model1',
			'Model2'
		);
			
		$this->assertTrue($this->OAuth2Controller->constructClasses());
		
		$this->assertEquals('Model1', $this->OAuth2Controller->modelClass);
		
	}
	
	/**
	 * Test Before Render With Action Authorize
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testBeforeRenderWithActionAuthorize() {
		
		$response = null;
		
		$this->OAuth2Controller = new OAuth2Controller();
		
		$this->OAuth2Controller->OAuth2 = $this->getMock(
			'OAuth2Component',
			array('getResponse'),
			array($this->ComponentCollection)
		);
		
		$this->OAuth2Controller->OAuth2
			->expects($this->once())
			->method('getResponse')
			->with()
			->will($this->returnValue($response));
			
		$this->OAuth2Controller->request = $this->Controller->request;
		
		$this->Controller->request
			->expects($this->once())
			->method('is')
			->with('get')
			->will($this->returnValue(true));
			
		$this->Controller->request->params['action'] = 'authorize';
		
		$this->assertTrue($this->OAuth2Controller->beforeRender());
		
	}
	
	/**
	 * Test Before Render
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testBeforeRender() {
		
		$http_headers = array(
			'header1' => 'value1',
			'header2' => 'value2'
		);
		
		$response = $this->getMock('OAuth2_Response', array(
			'getStatusCode',
			'getHttpHeaders',
			'getParameters'
		));
		
		$this->OAuth2Controller = $this->getMock(
			'OAuth2Controller',
			array('__parentBeforeRender')
		);
		
		$this->OAuth2Controller->OAuth2 = $this->getMock(
			'OAuth2Component',
			array('getResponse'),
			array($this->ComponentCollection)
		);
		
		$this->OAuth2Controller->OAuth2
			->expects($this->once())
			->method('getResponse')
			->with()
			->will($this->returnValue($response));
			
		$this->OAuth2Controller->request = $this->Controller->request;
		
		$this->Controller->request
			->expects($this->never())
			->method('is');
			
		$this->Controller->request->params['action'] = 'view';
		
		$this->OAuth2Controller->RequestHandler = $this->getMock(
			'RequestHandlerComponent',
			array('responseType')
		);
		
		$this->OAuth2Controller->RequestHandler
			->expects($this->once())
			->method('responseType')
			->with()
			->will($this->returnValue(null));
			
		$this->OAuth2Controller->response = $this->Controller->response;
		
		$status_code = 200;
		
		$this->OAuth2Controller->response
			->expects($this->once())
			->method('statusCode')
			->with($status_code);
		
		$response
			->expects($this->once())
			->method('getStatusCode')
			->with()
			->will($this->returnValue($status_code));
			
		$response
			->expects($this->once())
			->method('getHttpHeaders')
			->with()
			->will($this->returnValue($http_headers));
			
		$this->OAuth2Controller->response
			->expects($this->at(1))
			->method('header')
			->with('header1', 'value1');
			
		$this->OAuth2Controller->response
			->expects($this->at(2))
			->method('header')
			->with('header2', 'value2');
			
		$get_parameters_return = array(
			'data' => array('key1' => 'value1'),
			'test' => uniqid()
		);
		
		$response
			->expects($this->once())
			->method('getParameters')
			->with()
			->will($this->returnValue($get_parameters_return));

		$this->OAuth2Controller
			->expects($this->once())
			->method('__parentBeforeRender')
			->with()
			->will($this->returnValue(true));
			
		$this->assertTrue($this->OAuth2Controller->beforeRender());
		
		$expected = array(
			'data' => $get_parameters_return,
			'_serialize' => 'data'
		);
		
		$this->assertEquals($expected, $this->OAuth2Controller->viewVars);
		
	}
	
	/**
	 * Test Authorize - With Empty User Id
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testAuthorizeWithEmptyUserId() {
		
		$this->OAuth2Controller = $this->getMock(
			'OAuth2Controller',
			array('redirect')
		);
		
		$this->OAuth2Controller->request = $this->Controller->request;
		
		$this->OAuth2Controller->request->params['action'] = 'view';
		
		$this->OAuth2Controller->Session = $this->getMock(
			'SessionComponent',
			array(
				'read',
				'write'
			)
		);
		
		$this->OAuth2Controller->Session
			->expects($this->once())
			->method('read')
			->with('Auth.' . $this->OAuth2Controller->authorizeActionSettings['userIdKey'])
			->will($this->returnValue(null));
			
		$this->OAuth2Controller->Session
			->expects($this->once())
			->method('write');
			
		$expected = uniqid();
		
		$this->OAuth2Controller
			->expects($this->once())
			->method('redirect')
			->with($this->OAuth2Controller->authorizeActionSettings['loginUrl'], 401)
			->will($this->returnValue($expected));
		
		$this->assertEquals($expected, $this->OAuth2Controller->authorize());
		
	}
	
	/**
	 * Test Authorize - With Empty Client Data
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testAuthorizeWithEmptyClientData() {
		
		$user_id = 1;
		
		$client_data = null;
		
		$this->OAuth2Controller = new OAuth2Controller();
		
		$this->OAuth2Controller->request = $this->Controller->request;
		
		$this->OAuth2Controller->request->params['action'] = 'view';
		
		$this->OAuth2Controller->Session = $this->getMock(
			'SessionComponent',
			array(
				'read',
				'write'
			)
		);
		
		$this->OAuth2Controller->Session
			->expects($this->once())
			->method('read')
			->with('Auth.' . $this->OAuth2Controller->authorizeActionSettings['userIdKey'])
			->will($this->returnValue($user_id));
			
		$this->OAuth2Controller->OAuth2 = $this->getMock(
			'OAuth2Component',
			array('validateAuthorizeRequest'),
			array($this->ComponentCollection)
		);
		
		$this->OAuth2Controller->OAuth2
			->expects($this->once())
			->method('validateAuthorizeRequest')
			->with()
			->will($this->returnValue($client_data));
		
		$this->assertFalse($this->OAuth2Controller->authorize());
		
	}
	
	/**
	 * Test Authorize - Show Permissions Page
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testAuthorizeShowPermissionsPage() {
		
		$user_id = 1;
		
		$client_data = array(
			'id' => 1,
			'client_id' => uniqid(),
			'user_id' => 1,
			'api_secret' => uniqid(),
			'app_name' => 'Test',
			'redirect_uri' => 'http://www.wizehive.com'
		);
		
		$this->OAuth2Controller = $this->getMock(
			'OAuth2Controller',
			array('set')
		);
		
		$this->OAuth2Controller->request = $this->Controller->request;
		
		$this->OAuth2Controller->request->params['action'] = 'view';
		
		$this->OAuth2Controller->Session = $this->getMock(
			'SessionComponent',
			array(
				'read',
				'write'
			)
		);
		
		$this->OAuth2Controller->Session
			->expects($this->once())
			->method('read')
			->with('Auth.' . $this->OAuth2Controller->authorizeActionSettings['userIdKey'])
			->will($this->returnValue($user_id));
			
		$this->OAuth2Controller->OAuth2 = $this->getMock(
			'OAuth2Component',
			array('validateAuthorizeRequest'),
			array($this->ComponentCollection)
		);
		
		$this->OAuth2Controller->OAuth2
			->expects($this->once())
			->method('validateAuthorizeRequest')
			->with()
			->will($this->returnValue($client_data));
			
		$this->OAuth2Controller->OAuth2Authorization = $this->getMock(
			'OAuth2Authorization',
			array('getExisting')
		);
		
		$this->OAuth2Controller->OAuth2Authorization
			->expects($this->once())
			->method('getExisting')
			->with(
				$client_data['client_id'],
				$user_id,
				null
			)
			->will($this->returnValue(null));
			
		$this->Controller->request
			->expects($this->at(0))
			->method('is')
			->with('get')
			->will($this->returnValue(true));

		$this->Controller->request
			->expects($this->at(1))
			->method('is')
			->with('post')
			->will($this->returnValue(false));
			
		$this->OAuth2Controller
			->expects($this->once())
			->method('set')
			->with('client', $client_data);
		
		$this->assertNull($this->OAuth2Controller->authorize());
		
	}
	
	/**
	 * Test Authorize - Authorization And Response False
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testAuthorizeWithAuthorizationAndResponseFalse() {
		
		$user_id = 1;
		
		$client_data = array(
			'id' => 1,
			'client_id' => uniqid(),
			'user_id' => 1,
			'api_secret' => uniqid(),
			'app_name' => 'Test',
			'redirect_uri' => 'http://www.wizehive.com'
		);
		
		$this->OAuth2Controller = $this->getMock(
			'OAuth2Controller',
			array('redirect')
		);
		
		$this->OAuth2Controller->request = $this->Controller->request;
		
		$this->OAuth2Controller->request->params['action'] = 'view';
		
		$this->OAuth2Controller->Session = $this->getMock(
			'SessionComponent',
			array(
				'read',
				'write'
			)
		);
		
		$this->OAuth2Controller->Session
			->expects($this->once())
			->method('read')
			->with('Auth.' . $this->OAuth2Controller->authorizeActionSettings['userIdKey'])
			->will($this->returnValue($user_id));
			
		$this->OAuth2Controller->OAuth2 = $this->getMock(
			'OAuth2Component',
			array(
				'validateAuthorizeRequest',
				'handleAuthorizeRequest'
			),
			array($this->ComponentCollection)
		);
		
		$this->OAuth2Controller->OAuth2
			->expects($this->once())
			->method('validateAuthorizeRequest')
			->with()
			->will($this->returnValue($client_data));
			
		$this->OAuth2Controller->OAuth2Authorization = $this->getMock(
			'OAuth2Authorization',
			array('getExisting')
		);
		
		$this->OAuth2Controller->OAuth2Authorization
			->expects($this->once())
			->method('getExisting')
			->with(
				$client_data['client_id'],
				$user_id,
				null
			)
			->will($this->returnValue(true));
			
		$this->OAuth2Controller->OAuth2
			->expects($this->once())
			->method('handleAuthorizeRequest')
			->with(true, $user_id)
			->will($this->returnValue(false));
		
		$this->assertFalse($this->OAuth2Controller->authorize());
		
	}
	
	/**
	 * Test Authorize - Authorization And Redirect
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testAuthorizeWithAuthorizationAndRedirect() {
		
		$user_id = 1;
		
		$client_data = array(
			'id' => 1,
			'client_id' => uniqid(),
			'user_id' => 1,
			'api_secret' => uniqid(),
			'app_name' => 'Test',
			'redirect_uri' => 'http://www.wizehive.com'
		);
		
		$response = $this->getMock('OAuth2_Response');
		
		$http_header = 'http://www.wizehive.com';
		
		$status_code = 301;
		
		$this->OAuth2Controller = $this->getMock(
			'OAuth2Controller',
			array('redirect')
		);
		
		$this->OAuth2Controller->request = $this->Controller->request;
		
		$this->OAuth2Controller->request->params['action'] = 'view';
		
		$this->OAuth2Controller->Session = $this->getMock(
			'SessionComponent',
			array(
				'read',
				'write'
			)
		);
		
		$this->OAuth2Controller->Session
			->expects($this->once())
			->method('read')
			->with('Auth.' . $this->OAuth2Controller->authorizeActionSettings['userIdKey'])
			->will($this->returnValue($user_id));
			
		$this->OAuth2Controller->OAuth2 = $this->getMock(
			'OAuth2Component',
			array(
				'validateAuthorizeRequest',
				'handleAuthorizeRequest'
			),
			array($this->ComponentCollection)
		);
		
		$this->OAuth2Controller->OAuth2
			->expects($this->once())
			->method('validateAuthorizeRequest')
			->with()
			->will($this->returnValue($client_data));
			
		$this->OAuth2Controller->OAuth2Authorization = $this->getMock(
			'OAuth2Authorization',
			array('getExisting')
		);
		
		$this->OAuth2Controller->OAuth2Authorization
			->expects($this->once())
			->method('getExisting')
			->with(
				$client_data['client_id'],
				$user_id,
				null
			)
			->will($this->returnValue(true));
			
		$this->OAuth2Controller->OAuth2
			->expects($this->once())
			->method('handleAuthorizeRequest')
			->with(true, $user_id)
			->will($this->returnValue($response));
			
		$response
			->expects($this->once())
			->method('getHttpHeader')
			->with('Location')
			->will($this->returnValue($http_header));
			
		$response
			->expects($this->once())
			->method('getStatusCode')
			->with()
			->will($this->returnValue($status_code));
		
		$expected = uniqid();
			
		$this->OAuth2Controller
			->expects($this->once())
			->method('redirect')
			->with($http_header, $status_code)
			->will($this->returnValue($expected));
		
		$this->assertEquals($expected, $this->OAuth2Controller->authorize());
		
	}
	
	/**
	 * Test Grant
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testGrant() {
		
		$this->OAuth2Controller = new OAuth2Controller();
		
		$this->OAuth2Controller->OAuth2 = $this->getMock(
			'OAuth2Component',
			array('handleGrantRequest'),
			array($this->ComponentCollection)
		);
		
		$this->OAuth2Controller->OAuth2
			->expects($this->once())
			->method('handleGrantRequest');
			
		$this->assertNull($this->OAuth2Controller->grant());
		
	}
	
	/**
	 * Test Token - With Empty Response
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testTokenWithEmptyResponse() {
		
		$response = null;
		
		$this->OAuth2Controller = $this->getMock(
			'OAuth2Controller',
			array('set')
		);
		
		$this->OAuth2Controller->OAuth2 = $this->getMock(
			'OAuth2Component',
			array('getAccessTokenData'),
			array($this->ComponentCollection)
		);
		
		$this->OAuth2Controller->OAuth2
			->expects($this->once())
			->method('getAccessTokenData')
			->with()
			->will($this->returnValue($response));
			
		$this->assertFalse($this->OAuth2Controller->token());
		
	}
	
	/**
	 * Test Token
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testToken() {
		
		$response = uniqid();
		
		$this->OAuth2Controller = $this->getMock(
			'OAuth2Controller',
			array('set')
		);
		
		$this->OAuth2Controller->OAuth2 = $this->getMock(
			'OAuth2Component',
			array('getAccessTokenData'),
			array($this->ComponentCollection)
		);
		
		$this->OAuth2Controller->OAuth2
			->expects($this->once())
			->method('getAccessTokenData')
			->with()
			->will($this->returnValue($response));
			
		$this->OAuth2Controller
			->expects($this->once())
			->method('set')
			->with($response);
			
		$this->assertNull($this->OAuth2Controller->token());
		
	}
	
}
