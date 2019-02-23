<?php
App::uses('ModelBehavior', 'Model');
App::uses('AppModel', 'Model');
App::uses('SpecialFieldsBehavior', 'SpecialFields.Model' . DS . 'Behavior');

/**
 * Special Fields Model
 *
 */
class SpecialFieldsModel extends AppModel {	
	
	public function timeSinceModified($results, $timeUnit = 'seconds') {
		$modified = time() - 60;
		$timediff = time() - $modified;
		
		$timeUnits = array ('seconds' => 1, 'minutes' => 60, 'hours' => 3600);
		$formattedTimeDiff = floor($timediff / $timeUnits[$timeUnit]);
		return $formattedTimeDiff . ' ' . $timeUnit . ' ago';
	}
	
}

/**
 * Special Fields Behavior Test
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
 * @package     SpecialFields
 * @subpackage  SpecialFields.Test.Case.Model.Behavior
 * @copyright   Copyright (c) WizeHive, Inc. (http://www.wizehive.com)
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 */
class SpecialFieldsBehaviorTest extends CakeTestCase {
	
	/**
	 * Setup
	 * 
	 * @author	Paul Smith <paul@wizehive.com>
	 * @since	1.0
	 * @return	void 
	 */
	public function setUp() {
		
		parent::setUp();
		
		$this->SpecialFieldsBehavior = new SpecialFieldsBehavior();
		
		$this->SpecialFieldsModel = $this->getMock(
			'SpecialFieldsModel',
			array('attributes', 'field', 'getVirtualField')
		);
		
		$this->SpecialFieldsModel
			->expects($this->any())
			->method('getVirtualField')
			->will($this->returnCallback(function(){
				$args = func_get_args();
				if (empty($args[0])) return array('testVirtual' => 'CONCAT(description, " ", "Fields")');
				if ($args[0] == 'testVirtual') return 'CONCAT(description, " ", "Fields")';
				return null;
			}));
				
		$this->SpecialFieldsModel
			->expects($this->any())
			->method('attributes')
			->with()
			->will($this->returnValue(
				array(
					'optionsAttr.option1' => array(
						'field' => 'options.option1', 
						'type' => 'string'
					), 
					'optionsAttr.optionTwo' => array(
						'field' => 'options.option2', 
						'type' => 'string'
					),
					'optionsAttr.optionThree' => array(
						'field' => 'options.option3.suboption', 
						'type' => 'string'
					),
					'sOptionsAttr.option1' => array(
						'field' => 's_options.option1', 
						'type' => 'string'
					), 
					'sOptionsAttr.optionTwo' => array(
						'field' => 's_options.option2', 
						'type' => 'string'
					),
					'sOptionsAttr.optionThree' => array(
						'field' => 's_options.option3.suboption', 
						'type' => 'string'
					),
					'testCallback' => array(
						'field' => '',
						'type' => 'string',
						'special' => 'callback',
						'callbackFunction' => 'timeSinceModified',
						'callbackParams' => array(
							'seconds'
						)
					),
					'testVirtual' => array(
						'field' => '',
						'type' => 'string',
						'special' => 'virtual'
					)
				)
			));
		
		$this->SpecialFieldsBehavior->setup(
			$this->SpecialFieldsModel,
			array(
				'json' => array(
					'options' => 'optionsAttr'
				),
				'serialized' => array(
					's_options' => 'sOptionsAttr'
				),
				'virtual' => true,
				'callback' => true
			)
		);
		
	}
	
	/**
	 * Teardown
	 * 
	 * @author	Paul Smith <paul@wizehive.com>
	 * @since	1.0
	 * @return	void 
	 */
	public function tearDown () {
		
		parent::tearDown();

		unset($this->SpecialFieldsBehavior);
		
		ClassRegistry::flush();
		
	}
	
	/**
	 * Test Instance Setup
	 * 
	 * @author	Paul Smith <paul@wizehive.com>
	 * @since	1.0
	 * @return	void 
	 */
	public function testInstanceSetup() {
		
		$this->assertIsA($this->SpecialFieldsModel, 'Model');
		
		$expected = array(
			$this->SpecialFieldsModel->alias => array(
				'json' => array(
					'options' => array(
						'option1' => array(
							'field' => 'options.option1',
							'type' => 'string'
						),
						'optionTwo' => array(
							'field' => 'options.option2',
							'type' => 'string'
						),
						'optionThree' => array(
							'field' => 'options.option3.suboption', 
							'type' => 'string'
						)
					)
				),
				'serialized' => array(
					's_options' => array(
						'option1' => array(
							'field' => 's_options.option1',
							'type' => 'string'
						),
						'optionTwo' => array(
							'field' => 's_options.option2',
							'type' => 'string'
						),
						'optionThree' => array(
							'field' => 's_options.option3.suboption', 
							'type' => 'string'
						)
					)
				),
				'virtual' => true,
				'callback' => true,
				'callbackFields' => array(
					'testCallback' => array(
						'field' => '',
						'type' => 'string',
						'special' => 'callback',
						'callbackFunction' => 'timeSinceModified',
						'callbackParams' => array('seconds')
					)
				)
			)
		);

		$this->assertEquals($expected, $this->SpecialFieldsBehavior->settings);
	}
	
	/**
	 * Test Before Save - Json Encode
	 * 
	 * @author	Paul Smith <paul@wizehive.com>
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	1.0
	 * @return	void 
	 */
	public function testBeforeSaveJson() {
		
		$data = array(
			'options' => array(
				'option1' => 'value1',
				'option2' => 'value2'
			),
			'nonencoded.option3' => 'value3',
			'nonencoded.option4' => 'value4',
			'regularfield' => 'value5'
		);
		
		$this->SpecialFieldsModel->data[$this->SpecialFieldsModel->alias] = $data;
		
		$encoded = json_encode(array('option1' => 'value1', 'option2' => 'value2'));
		
		$result = $this->SpecialFieldsBehavior->beforeSave($this->SpecialFieldsModel, array());
		
		$this->assertTrue($result);
		
		$result = $this->SpecialFieldsModel->data[$this->SpecialFieldsModel->alias];
		
		$this->assertInternalType('string', $result['options']);
		$this->assertEquals($result['options'], $encoded);
		// 'nonencoded' fields should be unchanged
		$this->assertArrayHasKey('nonencoded.option3', $result);
		$this->assertArrayHasKey('nonencoded.option4', $result);
		$this->assertArrayHasKey('regularfield', $result);
		$this->assertEquals('value3', $result['nonencoded.option3']);
		$this->assertEquals('value4', $result['nonencoded.option4']);
		$this->assertEquals('value5', $result['regularfield']);
		
	}
	
	/**
	 * Test Before Save - Serialize
	 * 
	 * @author	Paul Smith <paul@wizehive.com>
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	1.0
	 * @return	void 
	 */
	public function testBeforeSaveSerialized() {
		
		$data = array(
			's_options' => array(
				'option1' => 'value1',
				'option2' => 'value2'
			),
			'nonencoded.option3' => 'value3',
			'nonencoded.option4' => 'value4',
			'regularfield' => 'value5'
		);
		
		$this->SpecialFieldsModel->data[$this->SpecialFieldsModel->alias] = $data;
		
		$serialized = serialize(array('option1' => 'value1', 'option2' => 'value2'));
		
		$result = $this->SpecialFieldsBehavior->beforeSave($this->SpecialFieldsModel, array());
		
		$this->assertTrue($result);
		
		$result = $this->SpecialFieldsModel->data[$this->SpecialFieldsModel->alias];
		
		$this->assertInternalType('string', $result['s_options']);
		$this->assertEquals($result['s_options'], $serialized);
		
		// 'nonencoded' fields should be unchanged
		$this->assertArrayHasKey('nonencoded.option3', $result);
		$this->assertArrayHasKey('nonencoded.option4', $result);
		$this->assertArrayHasKey('regularfield', $result);
		$this->assertEquals('value3', $result['nonencoded.option3']);
		$this->assertEquals('value4', $result['nonencoded.option4']);
		$this->assertEquals('value5', $result['regularfield']);
		
	}
	
	/**
	 * Test Before Find - Remove JSON Fields
	 * 
	 * @author	Paul Smith <paul@wizehive.com>
	 * @since	1.0
	 * @return	void 
	 */
	public function testBeforeFindJsonFields() {
		$query = array(
			'fields' => array(
				$this->SpecialFieldsModel->alias . '.field1',
				$this->SpecialFieldsModel->alias . '.field2',
				$this->SpecialFieldsModel->alias . '.options.option1',
				$this->SpecialFieldsModel->alias . '.options.option2'
			)
		);
		
		$expected = array(
			'fields' => array(
				$this->SpecialFieldsModel->alias . '.field1',
				$this->SpecialFieldsModel->alias . '.field2',
				$this->SpecialFieldsModel->alias . '.options'
			)
		);
		
		$result = $this->SpecialFieldsBehavior->beforeFind($this->SpecialFieldsModel, $query);
		
		$this->assertEquals($result, $expected);
		
	}
	
	/**
	 * Test Before Find - Remove Serialized Fields
	 * 
	 * @author	Paul Smith <paul@wizehive.com>
	 * @since	1.0
	 * @return	void 
	 */
	public function testBeforeFindSerializedFields() {
		$query = array(
			'fields' => array(
				$this->SpecialFieldsModel->alias . '.field1',
				$this->SpecialFieldsModel->alias . '.field2',
				$this->SpecialFieldsModel->alias . '.s_options.option1',
				$this->SpecialFieldsModel->alias . '.s_options.option2'
			)
		);
		
		$expected = array(
			'fields' => array(
				$this->SpecialFieldsModel->alias . '.field1',
				$this->SpecialFieldsModel->alias . '.field2',
				$this->SpecialFieldsModel->alias . '.s_options'
			)
		);
		
		$result = $this->SpecialFieldsBehavior->beforeFind($this->SpecialFieldsModel, $query);
		$this->assertEquals($result, $expected);
	}
	
	/**
	 * Test Before Find - Callback Fields
	 * 
	 * @author	Paul Smith <paul@wizehive.com>
	 * @since	1.0
	 * @return	void 
	 */
	public function testBeforeFindCallbackFields() {
		$query = array(
			'fields' => array(
				$this->SpecialFieldsModel->alias . '.field1',
				$this->SpecialFieldsModel->alias . '.field2',
				$this->SpecialFieldsModel->alias . '.testCallback'
			)
		);
		
		$expected = array(
			'fields' => array(
				$this->SpecialFieldsModel->alias . '.field1',
				$this->SpecialFieldsModel->alias . '.field2'
			)
		);
		
		$result = $this->SpecialFieldsBehavior->beforeFind($this->SpecialFieldsModel, $query);
		$this->assertEquals($result, $expected);
	}
	
	/**
	 * Test Before Find - Virtual Fields
	 * 
	 * @author	Paul Smith <paul@wizehive.com>
	 * @since	1.0
	 * @return	void 
	 */
	public function testBeforeFindVirtualFields() {
		$query = array(
			'fields' => array(
				$this->SpecialFieldsModel->alias . '.field1',
				$this->SpecialFieldsModel->alias . '.field2',
				$this->SpecialFieldsModel->alias . '.testVirtual'
			)
		);
		
		$expected = array(
			'fields' => array(
				$this->SpecialFieldsModel->alias . '.field1',
				$this->SpecialFieldsModel->alias . '.field2',
				$this->SpecialFieldsModel->alias . '.testVirtual'
			)
		);
		
		$result = $this->SpecialFieldsBehavior->beforeFind($this->SpecialFieldsModel, $query);
		$this->assertEquals($result, $expected);
	}
	
	/**
	 * Test Get Special Field Data - Parse JSON, Serialized And Virtual Fields
	 * 
	 * @author	Paul Smith <paul@wizehive.com>
	 * @since	1.0
	 * @return	void 
	 */
	public function testGetSpecialFieldData() {
		
		$sampleData = array(
			$this->SpecialFieldsModel->alias => array(
				'field1' => 'value1',
				'field2' => 'value2',
				'options' => '{"option1":"optionvalue1","option2":"optionvalue2","option3":{"suboption":"optionvalue3"},"option4":"optionvalue4"}',
				's_options' => 'a:4:{s:7:"option1";s:12:"optionvalue1";s:7:"option2";s:12:"optionvalue2";s:7:"option3";a:1:{s:9:"suboption";s:12:"optionvalue3";}s:7:"option4";s:12:"optionvalue4";}'
			)
		);
		
		$result = $this->SpecialFieldsBehavior->getSpecialFieldData($this->SpecialFieldsModel, $sampleData);
		
		$this->assertRegExp('/\A[\d]{1,8}? seconds ago\z/', $result['testCallback']);
		
		unset($result['testCallback']);
		
		$expected = array(
			'options' => array(
				'option1' => 'optionvalue1',
				'option2' => 'optionvalue2',
				'option3' => array(
					'suboption' => 'optionvalue3'
				)
			),
			's_options' => array(
				'option1' => 'optionvalue1',
				'option2' => 'optionvalue2',
				'option3' => array(
					'suboption' => 'optionvalue3'
				)
			)
		);
		
		$this->assertEquals($expected, $result);
		
	}
	
	/**
	 * Test Get Validation Field Names - Simple Array Of JSON And Serialized Field Names
	 * 
	 * @author	Paul Smith <paul@wizehive.com>
	 * @since	1.0
	 * @return	void
	 */
	public function testGetValidationFieldNames() {
		$expected = array(
			'options' => 'options',
			's_options' => 's_options'
		);
		$actual = $this->SpecialFieldsBehavior->getValidationFieldNames($this->SpecialFieldsModel);
		
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * Test After Find
	 * 
	 * @author	Paul Smith <paul@wizehive.com>
	 * @since	1.0
	 * @return  void
	 */
	public function testAfterFind() {
		
		$sampleResults = array(
			0 => array(
				$this->SpecialFieldsModel->alias => array(
					'field1' => 'value1',
					'field2' => 'value2',
					'options' => '{"option1":"optionvalue1","option2":"optionvalue2","option4":"optionvalue4"}',
					's_options' => 'a:2:{s:7:"option1";s:12:"optionvalue1";s:7:"option2";s:12:"optionvalue2";}'
				)
			)
		);
		
		$result = $this->SpecialFieldsBehavior->afterFind($this->SpecialFieldsModel, $sampleResults);
		
		$this->assertRegExp('/\A[\d]{1,8}? seconds ago\z/', $result[0][$this->SpecialFieldsModel->alias]['testCallback']);
		
		unset($result[0][$this->SpecialFieldsModel->alias]['testCallback']);
		
		$expected = array(
			0 => array(
				$this->SpecialFieldsModel->alias => array(
					'field1' => 'value1',
					'field2' => 'value2',
					'options' => array(
						'option1' => 'optionvalue1',
						'option2' => 'optionvalue2'
					),
					's_options' => array(
						'option1' => 'optionvalue1',
						'option2' => 'optionvalue2'
					)
				)
			)
		);
		
		$this->assertEquals($expected, $result);
		
	}
	
	/**
	 * Test Before Validate - Empty Model Data
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @return  void
	 */
	public function testBeforeValidateEmptyModelData() {
		
		$data = array(
			'id' => 1,
			'options' => array(
				'type' => 1,
				'frequency' => '2 mins'
			)
		);
		
		$this->SpecialFieldsModel->data = $data;
		
		$this->assertTrue($this->SpecialFieldsBehavior->beforeValidate($this->SpecialFieldsModel));
		
		$this->assertEquals($data, $this->SpecialFieldsModel->data);
		
	}
	
	/**
	 * Test Before Validate - Empty Special Fields
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @return  void
	 */
	public function testBeforeValidateEmptySpecialFields() {
		
		$data = array(
			'id' => 1,
			'options' => array(
				'type' => 1,
				'frequency' => '2 mins'
			)
		);
		
		$this->SpecialFieldsModel->data = array(
			$this->SpecialFieldsModel->alias => $data
		);
		
		$this->SpecialFieldsBehavior = $this->getMock('SpecialFieldsBehavior', array(
			'getValidationFieldNames'
		));
		
		$this->SpecialFieldsBehavior
			->expects($this->once())
			->method('getValidationFieldNames')
			->with($this->SpecialFieldsModel)
			->will($this->returnValue(array()));
			
		$this->assertTrue($this->SpecialFieldsBehavior->beforeValidate($this->SpecialFieldsModel));
		
		$this->assertEquals($data, $this->SpecialFieldsModel->data[$this->SpecialFieldsModel->alias]);
		
	}
	
	/**
	 * Test Before Validate - Flatten Special Fields Data
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @return  void
	 */
	public function testBeforeValidate() {
		
		$data = array(
			'id' => 1,
			'options' => array(
				'type' => 1,
				'frequency' => '2 mins',
				'move_to' => array(
					'dataFilterId' => 'userId'
				)
			),			
			'this_is_not_going_to_be_flattened' => array(
				'key1' => 'value1',
				'key2' => 'value2'
			)
		);
		
		$this->SpecialFieldsModel->data = array(
			$this->SpecialFieldsModel->alias => $data
		);
		
		$this->SpecialFieldsBehavior = $this->getMock('SpecialFieldsBehavior', array(
			'getValidationFieldNames',
			'getSpecialFieldValidateFields'
		));
		
		$validation_field_names = array(
			'actions',
			'options'
		);
		
		$this->SpecialFieldsBehavior
			->expects($this->once())
			->method('getValidationFieldNames')
			->with($this->SpecialFieldsModel)
			->will($this->returnValue($validation_field_names));

		$this->SpecialFieldsBehavior
			->expects($this->at(1))
			->method('getSpecialFieldValidateFields')
			->with($this->SpecialFieldsModel, 'actions')
			->will($this->returnValue(array()));
						
		$special_field_validate_fields = array(
			'options.type',
			'options.frequency',
			'options.move_to'
		);
		
		$this->SpecialFieldsBehavior
			->expects($this->at(2))
			->method('getSpecialFieldValidateFields')
			->with($this->SpecialFieldsModel, 'options')
			->will($this->returnValue($special_field_validate_fields));
			
		$this->assertTrue($this->SpecialFieldsBehavior->beforeValidate($this->SpecialFieldsModel));
		
		$expected = array(
			'id' => 1,
			'this_is_not_going_to_be_flattened' => array(
				'key1' => 'value1',
				'key2' => 'value2'
			),
			'options.type' => 1,
			'options.frequency' => '2 mins',
			'options.move_to' => array(
				'dataFilterId' => 'userId'
			)
		);
		
		$results = $this->SpecialFieldsModel->data[$this->SpecialFieldsModel->alias];
		
		$this->assertEquals(
			$expected,
			$results 
		);
		
	}
	
	/**
	 * Test Before Validate - Ignore Special Field Name With Declared Validation Rules
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @return  void
	 */
	public function testBeforeValidateIgnoreSpecialFieldNameWithDeclaredValidationRules() {
		
		$data = array(
			'id' => 1,
			'options' => array(
				'type' => 1,
				'frequency' => '2 mins'
			),
			'filter' => array(
				'prefix' => 'test',
				'attribute' => 'test',
				'value' => 'test'
			),
			'this_is_not_going_to_be_flattened' => array(
				'key1' => 'value1',
				'key2' => 'value2'
			)
		);
		
		$this->SpecialFieldsModel->validate['filter'] = array(
			'test' => array(
				'rule' => 'validateTest',
				'allowEmpty' => true,
				'required' => 'create',
				'last' => true,
				'message' => 'Please specify valid filters'
			)
		);
		
		$this->SpecialFieldsModel->data = array(
			$this->SpecialFieldsModel->alias => $data
		);
		
		$this->SpecialFieldsBehavior = $this->getMock('SpecialFieldsBehavior', array(
			'getValidationFieldNames',
			'getSpecialFieldValidateFields'
		));
		
		$validation_field_names = array(
			'actions',
			'options',
			'filter'
		);
		
		$this->SpecialFieldsBehavior
			->expects($this->once())
			->method('getValidationFieldNames')
			->with($this->SpecialFieldsModel)
			->will($this->returnValue($validation_field_names));
			
		$this->SpecialFieldsBehavior
			->expects($this->at(1))
			->method('getSpecialFieldValidateFields')
			->with($this->SpecialFieldsModel, 'actions')
			->will($this->returnValue(array()));
						
		$special_field_validate_fields = array(
			'options.type',
			'options.frequency'
		);
		
		$this->SpecialFieldsBehavior
			->expects($this->at(2))
			->method('getSpecialFieldValidateFields')
			->with($this->SpecialFieldsModel, 'options')
			->will($this->returnValue($special_field_validate_fields));
		
		$this->SpecialFieldsBehavior
			->expects($this->at(3))
			->method('getSpecialFieldValidateFields')
			->with($this->SpecialFieldsModel, 'filter')
			->will($this->returnValue(array()));
			
		$this->assertTrue($this->SpecialFieldsBehavior->beforeValidate($this->SpecialFieldsModel));
		
		$expected = array(
			'id' => 1,
			'filter' => array(
				'prefix' => 'test',
				'attribute' => 'test',
				'value' => 'test'
			),
			'options.type' => 1,
			'options.frequency' => '2 mins',
			'this_is_not_going_to_be_flattened' => array(
				'key1' => 'value1',
				'key2' => 'value2'
			)
		);
		
		$this->assertEquals(
			$expected,
			$this->SpecialFieldsModel->data[$this->SpecialFieldsModel->alias]
		);
		
	}
	
	/**
	 * Test After Validate - Empty Model Data
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @return  void
	 */
	public function testAfterValidateEmptyModelData() {
		
		$data = array(
			'id' => 1,
			'options.type' => 1,
			'options.frequency' => '2 mins'
		);
		
		$this->SpecialFieldsModel->data = $data;
		
		$this->SpecialFieldsBehavior->afterValidate($this->SpecialFieldsModel);
		
		$expected = $data;
		
		$this->assertEquals(
			$expected,
			$this->SpecialFieldsModel->data
		);
		
	}
	
	/**
	 * Test After Validate - Empty Special Fields
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @return  void
	 */
	public function testAfterValidateEmptySpecialFields() {
		
		$data = array(
			'id' => 1,
			'options.type' => 1,
			'options.frequency' => '2 mins'
		);
		
		$this->SpecialFieldsModel->data = array(
			$this->SpecialFieldsModel->alias => $data
		);
		
		$this->SpecialFieldsBehavior = $this->getMock('SpecialFieldsBehavior', array(
			'getValidationFieldNames'
		));
		
		$validation_field_names = array();
		
		$this->SpecialFieldsBehavior
			->expects($this->once())
			->method('getValidationFieldNames')
			->with($this->SpecialFieldsModel)
			->will($this->returnValue($validation_field_names));
			
		$this->SpecialFieldsBehavior->afterValidate($this->SpecialFieldsModel);
		
		$expected = $data;
		
		$this->assertEquals(
			$expected,
			$this->SpecialFieldsModel->data[$this->SpecialFieldsModel->alias]
		);
		
	}
	
	/**
	 * Test Before Validate - Unflatten Special Fields Data
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @return  void
	 */
	public function testAfterValidate() {
		
		$data = array(
			'id' => 1,
			'options.type' => 1,
			'options.frequency' => '2 mins'
		);
		
		$this->SpecialFieldsModel->data = array(
			$this->SpecialFieldsModel->alias => $data
		);
		
		$this->SpecialFieldsBehavior = $this->getMock('SpecialFieldsBehavior', array(
			'getValidationFieldNames'
		));
		
		$validation_field_names = array('options');
		
		$this->SpecialFieldsBehavior
			->expects($this->once())
			->method('getValidationFieldNames')
			->with($this->SpecialFieldsModel)
			->will($this->returnValue($validation_field_names));
		
		$this->SpecialFieldsBehavior->afterValidate($this->SpecialFieldsModel);
		
		$expected = Hash::expand($data);
		
		$this->assertEquals(
			$expected,
			$this->SpecialFieldsModel->data[$this->SpecialFieldsModel->alias]
		);
		
	}
	
	/**
	 * Test Filter Special Field Data - Empty Prefix
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @return  void
	 */
	public function testFilterSpecialFieldDataEmptyPrefix() {
		
		$prefix = null;
		
		$whitelist = array(
			'options.key1',
			'options.key2'
		);
		
		$this->SpecialFieldsModel->data = array(
			$this->SpecialFieldsModel->alias => array(
				'id' => 1,
				'type' => 'test',
				'options.key1' => 'value1',
				'options.key2' => 'value2',
				'options.key3' => 'value3'
			)
		);
		
		$this->assertFalse($this->SpecialFieldsBehavior->filterSpecialFieldData(
			$this->SpecialFieldsModel,
			$prefix,
			$whitelist
		));
		
	}
	
	/**
	 * Test Filter Special Field Data - Empty Model Data
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @return  void
	 */
	public function testFilterSpecialFieldDataEmptyModelData() {
		
		$prefix = 'options';
		
		$whitelist = array(
			'options.key1',
			'options.key2'
		);
		
		$this->SpecialFieldsModel->data = array();
		
		$this->assertFalse($this->SpecialFieldsBehavior->filterSpecialFieldData(
			$this->SpecialFieldsModel,
			$prefix,
			$whitelist
		));
		
	}
	
	/**
	 * Test Filter Special Field Data - Single Value Whitelist
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @return  void
	 */
	public function testFilterSpecialFieldDataSingleValueWhitelist() {
		
		$prefix = 'options';
		
		$whitelist = 'options.key1';
		
		$this->SpecialFieldsModel->data = array(
			$this->SpecialFieldsModel->alias => array(
				'id' => 1,
				'type' => 'test',
				'options.key1' => 'value1',
				'options.key2' => 'value2',
				'options.key3' => 'value3'
			)
		);
		
		$this->assertTrue($this->SpecialFieldsBehavior->filterSpecialFieldData(
			$this->SpecialFieldsModel,
			$prefix,
			$whitelist
		));
		
		$expected = array(
			$this->SpecialFieldsModel->alias => array(
				'id' => 1,
				'type' => 'test',
				'options.key1' => 'value1'
			)
		);
		
		$this->assertEquals($expected, $this->SpecialFieldsModel->data);
		
	}
	
	/**
	 * Test Filter Special Field Data
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @return  void
	 */
	public function testFilterSpecialFieldData() {
		
		$prefix = 'options';
		
		$whitelist = array(
			'options.key1',
			'options.key2'
		);
		
		$this->SpecialFieldsModel->data = array(
			$this->SpecialFieldsModel->alias => array(
				'id' => 1,
				'type' => 'test',
				'options.key1' => 'value1',
				'options.key2' => 'value2',
				'options.key3' => 'value3'
			)
		);
		
		$this->assertTrue($this->SpecialFieldsBehavior->filterSpecialFieldData(
			$this->SpecialFieldsModel,
			$prefix,
			$whitelist
		));
		
		$expected = array(
			$this->SpecialFieldsModel->alias => array(
				'id' => 1,
				'type' => 'test',
				'options.key1' => 'value1',
				'options.key2' => 'value2',
			)
		);
		
		$this->assertEquals($expected, $this->SpecialFieldsModel->data);
		
	}
	
	/**
	 * Test Get Special Field Validate Fields - Empty Prefix
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @return  void
	 */
	public function testGetSpecialFieldValidateFieldsEmptyPrefix() {
		
		$prefix = null;
		
		$this->assertEquals(
			array(),
			$this->SpecialFieldsBehavior->getSpecialFieldValidateFields(
				$this->SpecialFieldsModel, $prefix
			)
		);
		
	}
	
	/**
	 * Test Get Special Field Validate Fields - Empty Model Validate Array
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @return  void
	 */
	public function testGetSpecialFieldValidateFieldsEmptyModelValidateArray() {
		
		$prefix = 'options';
		
		$this->SpecialFieldsModel->validate = array();
		
		$this->assertEquals(
			array(),
			$this->SpecialFieldsBehavior->getSpecialFieldValidateFields(
				$this->SpecialFieldsModel, $prefix
			)
		);
		
	}
	
	/**
	 * Test Get Special Field Validate Fields
	 * 
	 * @author	Everton Yoshitani <everton@wizehive.com>
	 * @since	1.0
	 * @return  void
	 */
	public function testGetSpecialFieldValidateFields() {
		
		$prefix = 'options';
		
		$this->SpecialFieldsModel->validate = array(
			'workspace_id' => array(
				'foreignID' => array(
					'rule' => array('validateForeignID'),
					'allowEmpty' => false,
					'required' => 'create',
					'last' => true,
					'message' => 'Please enter a valid workspace ID'
				)
			),
			'action_type' => array(
				'inOptionsList' => array(
					'rule' => array('inOptionsList', 'action_type'),
					'allowEmpty' => false,
					'required' => 'create',
					'last' => true,
					'message' => 'Please specify a valid type'
				)
			),
			'options.days' => array(
				'option' => array(
					'rule' => 'validateCreateTaskDaysOption',
					'allowEmpty' => false,
					'required' => 'create',
					'last' => true,
					'message' => 'Please specify a valid option'
				)
			),
			'options.task' => array(
				'option' => array(
					'rule' => 'validateCreateTaskTaskOption',
					'allowEmpty' => false,
					'required' => 'create',
					'last' => true,
					'message' => 'Please specify a valid option'
				)
			)
		);
		
		$expected = array('options.days', 'options.task');
		
		$this->assertEquals(
			$expected,
			$this->SpecialFieldsBehavior->getSpecialFieldValidateFields(
				$this->SpecialFieldsModel, $prefix
			)
		);
		
	}
	
}
