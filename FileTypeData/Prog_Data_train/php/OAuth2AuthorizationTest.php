<?php
App::uses('OAuth2AppModel', 'OAuth2.Model');
App::uses('OAuth2AuthCode', 'OAuth2.Model');
App::uses('OAuth2AccessToken', 'OAuth2.Model');
App::uses('OAuth2RefreshToken', 'OAuth2.Model');
App::uses('OAuth2Authorization', 'OAuth2.Model');

/**
 * OAuth2 Authorization Model Tests
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
 * @subpackage  OAuth2.Test.Case.Model
 * @copyright   Copyright (c) WizeHive, Inc. (http://www.wizehive.com)
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 */
class OAuth2AuthorizationTest extends CakeTestCase {

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

		$this->OAuth2Authorization = new OAuth2Authorization();
		
		// Test Required Model
		
		$this->assertEquals(
			'oauth2_authorizations',
			$this->OAuth2Authorization->useTable
		);
		
		// Test Belongs To Associations
		
		$this->assertArrayHasKey(
			'OAuth2Client',
			$this->OAuth2Authorization->belongsTo
		);
		
		$this->assertEquals(
			'OAuth2.OAuth2Client',
			$this->OAuth2Authorization->belongsTo['OAuth2Client']['className']
		);
		
		$this->assertEquals(
			'oauth2_client_id',
			$this->OAuth2Authorization->belongsTo['OAuth2Client']['foreignKey']
		);
		
	}
	
	/**
	 * Test Before Delete
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testBeforeDelete() {
		
		$id = 1;
		
		$this->OAuth2Authorization = $this->getMock(
			'OAuth2Authorization',
			array(
				'find',
				'__parentBeforeDelete'
			)
		);
		
		$find_options = array(
			'conditions' => array(
				$this->OAuth2Authorization->alias . '.id' => $id
			),
			'fields' => array(
				'oauth2_client_id',
				'user_id'
			),
			'contain' => false
		);
		
		$find_results = array(
			'id' => rand(1,99),
			'oauth2_client_id' => uniqid(),
			'user_id' => $id,
			'scope' => 'admin',
			'created' => date('Y-m-d H:i:s'),
			'modified' => date('Y-m-d H:i:s')
		);
		
		$this->OAuth2Authorization
			->expects($this->once())
			->method('find')
			->with('first', $find_options)
			->will($this->returnValue($find_results));
			
		$this->OAuth2Authorization
			->expects($this->once())
			->method('__parentBeforeDelete')
			->with(true)
			->will($this->returnValue(true));
			
		$this->OAuth2Authorization->id = $id;
		
		$this->assertTrue($this->OAuth2Authorization->beforeDelete());
		
		$this->assertEquals(
			$find_results,
			$this->OAuth2Authorization->__oldData
		);
		
	}
	
	/**
	 * Test Before Delete - Empty Old Data
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testAfterDeleteEmptyOldData() {
		
		$this->OAuth2Authorization = $this->getMock(
			'OAuth2Authorization',
			array(
				'__parentAfterDelete'
			)
		);
		
		$this->OAuth2Authorization
			->expects($this->once())
			->method('__parentAfterDelete')
			->with()
			->will($this->returnValue(true));
		
		$this->assertTrue($this->OAuth2Authorization->afterDelete());
		
	}
	
	/**
	 * Test Before Delete - Empty Result
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testAfterDeleteEmptyResult() {
		
		$old_data = array(
			'id' => rand(1,99),
			'oauth2_client_id' => uniqid(),
			'user_id' => 1,
			'scope' => 'admin',
			'created' => date('Y-m-d H:i:s'),
			'modified' => date('Y-m-d H:i:s')
		);
		
		$this->OAuth2Authorization = $this->getMock(
			'OAuth2Authorization',
			array(
				'__parentAfterDelete'
			)
		);
		
		$this->OAuth2Authorization->__oldData = array(
			$this->OAuth2Authorization->alias => $old_data
		);
		
		$models = array(
			'OAuth2AuthCode',
			'OAuth2AccessToken',
			'OAuth2RefreshToken'
		);
		
		foreach ($models as $model) {
			
			$this->OAuth2Authorization->{$model} = $this->getMock(
				$model,
				array('updateAll')
			);
			
			$find_arg1 = array($model . '.expires' => 0);
			
			$find_arg2 = array(
				$model . '.oauth2_client_id' => $old_data['oauth2_client_id'],
				$model . '.user_id' => $old_data['user_id']
			);
			
			$find_results = ($model === 'OAuth2RefreshToken') ? false : true;
			
			$this->OAuth2Authorization->{$model}
				->expects($this->once())
				->method('updateAll')
				->with($find_arg1, $find_arg2)
				->will($this->returnValue($find_results));
		
		}
		
		$this->OAuth2Authorization
			->expects($this->never())
			->method('__parentAfterDelete');
		
		$this->assertFalse($this->OAuth2Authorization->afterDelete());
		
	}
	
	/**
	 * Test After Delete
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testAfterDelete() {
		
		$old_data = array(
			'id' => rand(1,99),
			'oauth2_client_id' => uniqid(),
			'user_id' => 1,
			'scope' => 'admin',
			'created' => date('Y-m-d H:i:s'),
			'modified' => date('Y-m-d H:i:s')
		);
		
		$this->OAuth2Authorization = $this->getMock(
			'OAuth2Authorization',
			array(
				'__parentAfterDelete'
			)
		);
		
		$this->OAuth2Authorization->__oldData = array(
			$this->OAuth2Authorization->alias => $old_data
		);
		
		$models = array(
			'OAuth2AuthCode',
			'OAuth2AccessToken',
			'OAuth2RefreshToken'
		);
		
		foreach ($models as $model) {
			
			$this->OAuth2Authorization->{$model} = $this->getMock(
				$model,
				array('updateAll')
			);
			
			$find_arg1 = array($model . '.expires' => 0);
			
			$find_arg2 = array(
				$model . '.oauth2_client_id' => $old_data['oauth2_client_id'],
				$model . '.user_id' => $old_data['user_id']
			);
			
			$find_results = true;
			
			$this->OAuth2Authorization->{$model}
				->expects($this->once())
				->method('updateAll')
				->with($find_arg1, $find_arg2)
				->will($this->returnValue($find_results));
		
		}
		
		$this->OAuth2Authorization
			->expects($this->once())
			->method('__parentAfterDelete')
			->with()
			->will($this->returnValue(true));
		
		$this->assertTrue($this->OAuth2Authorization->afterDelete());
		
	}
	
	/**
	 * Test Get Existing - With Pre Authorized Api Keys
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testGetExistingWithPreAuthorizedApiKeys() {
		
		$api_key = uniqid();
		
		$user_id = 1;
		
		$scope = 'root';
		
		$preauthorized_api_keys = array($api_key);
		
		$this->OAuth2Authorization = new OAuth2Authorization();
		
		Configure::write('oAuth2.preauthorizedApiKeys', $preauthorized_api_keys);
		
		$this->assertTrue($this->OAuth2Authorization->getExisting(
			$api_key,
			$user_id,
			$scope
		));
		
	}
	
	/**
	 * Test Get Existing - Fail
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testGetExistingFail() {
		
		$api_key = uniqid();
		
		$user_id = 1;
		
		$scope = 'root';
		
		$this->OAuth2Authorization = $this->getMock(
			'OAuth2Authorization',
			array('field')
		);
		
		$this->OAuth2Authorization->OAuth2Client = $this->getMock(
			'OAuth2Client',
			array('field')
		);
		
		$this->OAuth2Authorization
			->expects($this->never())
			->method('field');
			
		$this->OAuth2Authorization->OAuth2Client
			->expects($this->once())
			->method('field')
			->with('id', compact('api_key'))
			->will($this->returnValue(false));
			
		$this->assertFalse($this->OAuth2Authorization->getExisting(
			$api_key,
			$user_id,
			$scope
		));
		
	}
	
	/**
	 * Test Get Existing - Success
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testGetExistingSuccess() {
		
		$api_key = uniqid();
		
		$user_id = 1;
		
		$scope = 'root';
		
		$client_id = 2;
		
		$this->OAuth2Authorization = $this->getMock(
			'OAuth2Authorization',
			array('field')
		);
		
		$this->OAuth2Authorization->OAuth2Client = $this->getMock(
			'OAuth2Client',
			array('field')
		);
		
		$this->OAuth2Authorization->OAuth2Client
			->expects($this->once())
			->method('field')
			->with('id', compact('api_key'))
			->will($this->returnValue($client_id));

		$field_options = array(
			'oauth2_client_id' => $client_id,
			'user_id' => $user_id,
			'scope' => $scope
		);
		
		$field_results = array(
			'id' => rand(1,99),
			'oauth2_client_id' => uniqid(),
			'user_id' => 1,
			'scope' => 'admin',
			'created' => date('Y-m-d H:i:s'),
			'modified' => date('Y-m-d H:i:s')
		);
		
		$this->OAuth2Authorization
			->expects($this->once())
			->method('field')
			->with('id', $field_options)
			->will($this->returnValue($field_results));
		
		$this->assertTrue($this->OAuth2Authorization->getExisting(
			$api_key,
			$user_id,
			$scope
		));
		
	}
	
	/**
	 * Test Upsert - Fail
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testUpsertFail() {
		
		$client_id = 1;
		
		$user_id = 2;
		
		$scope = 'root';
		
		$this->OAuth2Authorization = $this->getMock(
			'OAuth2Authorization',
			array(
				'field',
				'save'
			)
		);
		
		$field_options = array(
			'oauth2_client_id' => $client_id,
			'user_id' => $user_id
		);
		
		$field_results = 999;
		
		$this->OAuth2Authorization
			->expects($this->once())
			->method('field')
			->with('id', $field_options)
			->will($this->returnValue($field_results));
			
		$save_options = array(
			$this->OAuth2Authorization->alias => array(
				'oauth2_client_id' => $client_id,
				'user_id' => $user_id,
				'scope' => $scope
			)
		);
		
		$save_results = false;
		
		$this->OAuth2Authorization
			->expects($this->once())
			->method('save')
			->with($save_options)
			->will($this->returnValue($save_results));
			
		$this->assertFalse($this->OAuth2Authorization->upsert(
			$client_id,
			$user_id,
			$scope
		));
		
	}
	
	/**
	 * Test Upsert - Success
	 *
	 * @author  Everton Yoshitani <everton@wizehive.com>
	 * @since   0.1
	 * @return  void
	 */
	public function testUpsertSuccess() {
		
		$client_id = 1;
		
		$user_id = 2;
		
		$scope = 'root';
		
		$this->OAuth2Authorization = $this->getMock(
			'OAuth2Authorization',
			array(
				'field',
				'save'
			)
		);
		
		$field_options = array(
			'oauth2_client_id' => $client_id,
			'user_id' => $user_id
		);
		
		$field_results = 999;
		
		$this->OAuth2Authorization
			->expects($this->once())
			->method('field')
			->with('id', $field_options)
			->will($this->returnValue($field_results));
			
		$save_options = array(
			$this->OAuth2Authorization->alias => array(
				'oauth2_client_id' => $client_id,
				'user_id' => $user_id,
				'scope' => $scope
			)
		);
		
		$save_results = array(
			'id' => rand(1,99),
			'oauth2_client_id' => $client_id,
			'user_id' => $user_id,
			'scope' => $scope,
			'created' => date('Y-m-d H:i:s'),
			'modified' => date('Y-m-d H:i:s')
		);
		
		$this->OAuth2Authorization
			->expects($this->once())
			->method('save')
			->with($save_options)
			->will($this->returnValue($save_results));
			
		$this->assertTrue($this->OAuth2Authorization->upsert(
			$client_id,
			$user_id,
			$scope
		));
		
	}

}
