<?php
App::uses('Component', 'Controller');
App::uses('OAuth2Storage', 'OAuth2.Lib');

/**
 * OAuth2 Component
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
 * @since       1.0
 * @package     OAuth2
 * @subpackage  OAuth2.Controller.Component
 * @copyright   Copyright (c) WizeHive, Inc. (http://www.wizehive.com)
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 */
class OAuth2Component extends Component {
	
	/**
	 * Response
	 *
	 * @since   0.1
	 * @var	    mixed 
	 */
	private $__response = null;
	
	/**
	 * Available methods
	 *
	 * @since   0.1
	 * @var	    array 
	 */
	public $availableMethods = array();
	
	/**
	 * Call
	 *
	 * All methods in this component should actually come from `$this->Server`
	 *
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	1.0
	 * @param	string	$name
	 * @param	array	$arguments
	 * @return  void
	 */
	public function __call($name, $arguments) {
		if (array_key_exists($name, $this->availableMethods)) {
			if ($this->availableMethods[$name] === 'request') {
				array_unshift($arguments, $this->Request);
			}
			return call_user_func_array(array($this->Server, $name), $arguments);
		}
	}
	
	/**
	 * Constructor
	 *
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @since	0.1
	 * @param	ComponentCollection	$collection	The Component collection used on this request.
	 * @param	array				$settings	Array of settings to use.
	 * @return	void
	 */
	public function __construct(ComponentCollection $collection, $settings = array()) {
		
		parent::__construct($collection, $settings);
		
		$this->settings = array_merge(
			array(
				'token_type' => 'bearer',
				'access_lifetime' => 3600, // 1 hour
				'refresh_token_lifetime' => 1209600, // 14 days
				'www_realm' => 'Service',
				'token_param_name' => 'access_token',
				'token_bearer_header_name' => 'Bearer',
				'enforce_state' => true,
				'allow_implicit' => true
			),
			$this->settings
		);
		
	}
	
	/**
	 * Initialize
	 *
	 * @since	0.1
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @param	Controller	$controller	A reference to the instantiating controller object
	 * @return	void
	 */
	public function initialize(Controller $controller) {
		
		parent::initialize($controller);
		
		if (!isset($this->Storage)) {
			$this->Storage = new OAuth2Storage($this->settings);
		}
		if (!isset($this->Server)) {
			$this->Server = new OAuth2_Server(
				$this->Storage,
				$this->settings,
				array(
					new OAuth2_GrantType_AuthorizationCode($this->Storage),
					new OAuth2_GrantType_RefreshToken($this->Storage, array(
						'always_issue_new_refresh_token' => false
					))
				)
			);
		}
		if (!isset($this->Request)) {
			$this->Request = new OAuth2_Request($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
		}
		if (!isset($this->ServerReflection)) {
			$this->ServerReflection = new ReflectionClass('OAuth2_Server');
		}
		
		$methods = $this->ServerReflection->getMethods();
		
		foreach ($methods as $method) {
			
			$this->availableMethods[$method->name] = null;
			
			$MethodReflection = new ReflectionMethod('OAuth2_Server', $method->name);
			
			$params = $MethodReflection->getParameters();
			
			if (
				!empty($params) && 
				!empty($params[0]->name) && 
				$params[0]->name === 'request'
			) {
				$this->availableMethods[$method->name] = 'request';
			}
			
		}
		
	}
	
	/**
	 * Error response
	 * 
	 * Generate a response object suited for errors.
	 *
	 * @since	0.1
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @param	integer	$statusCode
	 * @param	string	$error
	 * @param	string	$errorDescription
	 * @param	string	$errorUri
	 * @return	OAuth2_Response_Error
	 */
	public function errorResponse($statusCode, $error, $errorDescription, $errorUri = null) {
		
		return new OAuth2_Response_Error($statusCode, $error, $errorDescription, $errorUri = null);
		
	}
	
	/**
	 * Get Client ID from query string
	 * 
	 * If no Client ID can be found, `$this->_response` is populated with an error response object.
	 *
	 * @since	0.1
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @return	integer|null
	 */
	public function getClientId() {
		
        if (!is_null($this->Request->headers('PHP_AUTH_USER'))) {
            return $this->Request->headers('PHP_AUTH_USER');
        }

        // This method is not recommended, but is supported by specification
        if (!is_null($this->Request->request('client_id'))) {
            return $this->Request->request('client_id');
        }

        if (!is_null($this->Request->query('client_id'))) {
            return $this->Request->query('client_id');
        }

        $this->__response = $this->errorResponse(
			400, 
			'invalid_client', 
			'Client credentials were not found in the headers or body'
		);
		
        return null;
		
    }
	
	/**
	 * Check the validity of a client's credentials
	 *
	 * @since	0.1
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @param	string	$client_id
	 * @param	string	$client_secret
	 * @return	boolean
	 */
	public function checkClientCredentials($client_id, $client_secret = null) {
		
		return $this->Storage->checkClientCredentials($client_id, $client_secret);
		
	}
	
	/**
	 * Get the stateful response object
	 *
	 * @since	0.1
	 * @author	Anthony Putignano <anthony@wizehive.com>
	 * @return	OAuth2Response
	 */
	public function getResponse() {
		
		if (!empty($this->__response)) {
			$response = $this->__response;
			$this->__response = null;
			return $response;
		} else {
			return $this->Server->getResponse();
		}
		
	}
	
}
