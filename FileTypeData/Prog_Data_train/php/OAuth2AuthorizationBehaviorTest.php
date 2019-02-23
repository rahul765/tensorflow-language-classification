<?php
App::uses('AppModel', 'Model');
App::uses('ModelBehavior', 'Model');
App::uses('OAuth2AuthorizationBehavior', 'OAuth2.Model/Behavior');

/**
 * OAuth2 Authorization Behavior Double
 */
class OAuth2AuthorizationBehaviorDouble extends OAuth2AuthorizationBehavior {
	
	public $_settings = array();
	
}

/**
 * OAuth2 Authorization Behavior Test Model
 */
class OAuth2AuthorizationBehaviorTestModel extends AppModel {}

/**
 * OAuth2 Authorization Behavior Tests
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
 * @subpackage  OAuth2.Test.Case.Model.Behavior
 * @copyright   Copyright (c) WizeHive, Inc. (http://www.wizehive.com)
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 */
class OAuth2AuthorizationBehaviorTest extends CakeTestCase {

	/**
	 * Setup
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function setUp() {

		parent::setUp();

		$this->Model = $this->getMock('OAuth2AuthorizationBehaviorTestModel');
		
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
	 * Test Setup
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testSetup() {

		$settings = array(
			'fields' => array(
				'user_id' => 'test_id'
			)
		);
		
		$this->OAuth2AuthorizationBehavior = new OAuth2AuthorizationBehaviorDouble();
		
		$this->OAuth2AuthorizationBehavior->setup($this->Model, $settings);
		
		$this->assertEquals(
			$settings['fields']['user_id'],
			$this->OAuth2AuthorizationBehavior->_settings[$this->Model->alias]['fields']['user_id']
		);
		
	}
	
	/**
	 * Test After Save - Empty Client Id
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testAfterSaveEmptyClientId() {
		
		$data = array(
			$this->Model->alias => array()
		);
		
		$this->Model->data = $data;
			
		$this->OAuth2AuthorizationBehavior = new OAuth2AuthorizationBehavior();
		
		$this->OAuth2AuthorizationBehavior->setup($this->Model);
		
		$this->assertTrue($this->OAuth2AuthorizationBehavior->afterSave($this->Model));
		
	}
	
	/**
	 * Test After Save - Empty User Id
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testAfterSaveEmptyUserId() {
		
		$data = array(
			$this->Model->alias => array(
				'oauth2_client_id' => uniqid()
			)
		);
		
		$this->Model->data = $data;
			
		$this->OAuth2AuthorizationBehavior = new OAuth2AuthorizationBehavior();
		
		$this->OAuth2AuthorizationBehavior->setup($this->Model);
		
		$this->assertTrue($this->OAuth2AuthorizationBehavior->afterSave($this->Model));
		
	}
	
	/**
	 * Test After Save - Upsert False
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testAfterSaveUpsertFalse() {
		
		$data = array(
			$this->Model->alias => array(
				'oauth2_client_id' => uniqid(),
				'user_id' => 1,
				'scope' => 'admin'
			)
		);
		
		$this->Model->data = $data;
			
		$this->OAuth2AuthorizationBehavior = new OAuth2AuthorizationBehavior();
		
		$this->OAuth2AuthorizationBehavior->OAuth2Authorization = $this->getMock(
			'OAuth2Authorization',
			array('upsert')
		);
		
		$this->OAuth2AuthorizationBehavior->OAuth2Authorization
			->expects($this->once())
			->method('upsert')
			->with(
				$data[$this->Model->alias]['oauth2_client_id'],
				$data[$this->Model->alias]['user_id'],
				$data[$this->Model->alias]['scope']
			)
			->will($this->returnValue(false));
			
		$this->OAuth2AuthorizationBehavior->setup($this->Model);
		
		$this->assertFalse($this->OAuth2AuthorizationBehavior->afterSave($this->Model));
		
	}
	
	/**
	 * Test After Save
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testAfterSave() {
		
		$data = array(
			$this->Model->alias => array(
				'oauth2_client_id' => uniqid(),
				'user_id' => 1,
				'scope' => 'admin'
			)
		);
		
		$this->Model->data = $data;
			
		$this->OAuth2AuthorizationBehavior = new OAuth2AuthorizationBehavior();
		
		$this->OAuth2AuthorizationBehavior->OAuth2Authorization = $this->getMock(
			'OAuth2Authorization',
			array('upsert')
		);
		
		$this->OAuth2AuthorizationBehavior->OAuth2Authorization
			->expects($this->once())
			->method('upsert')
			->with(
				$data[$this->Model->alias]['oauth2_client_id'],
				$data[$this->Model->alias]['user_id'],
				$data[$this->Model->alias]['scope']
			)
			->will($this->returnValue(true));
			
		$this->OAuth2AuthorizationBehavior->setup($this->Model);
		
		$this->assertTrue($this->OAuth2AuthorizationBehavior->afterSave($this->Model));
		
	}
	
}
