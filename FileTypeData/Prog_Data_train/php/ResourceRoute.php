<?php
App::uses('Router', 'Routing');

/**
 * A really easy way to create the routes the demo API needs. Your mileage may vary.
 * Feel free to modify or create your own solution for building routes.
 */

class ResourceRoute extends Router {
	
	protected static $_resourceMap = array(
		array('action' => 'index', 'method' => 'GET', 'id' => false),
		array('action' => 'count', 'method' => 'GET', 'id' => false),
		array('action' => 'view', 'method' => 'GET', 'id' => true),
		array('action' => 'add', 'method' => 'POST', 'id' => false),
		array('action' => 'edit', 'method' => 'PUT', 'id' => true),
		array('action' => 'delete', 'method' => 'DELETE', 'id' => true)
	);
	
	public static function mapSubresource($prefix = '', $parentParams = array(), $subresource = '', $subresourceOptions = array()) {

		$strlen = strlen($prefix);
		$prefix = substr($prefix, 0, ($strlen - 4));
		
		$subresourceOptions = array_merge(
			array(
				'controllerClass' => $subresource,
				'parentModelAlias' => $parentParams['modelAlias'],
				'id_format' => self::ID,
				'single' => false
			),
			$subresourceOptions
		);

		foreach (self::$_resourceMap as $mapParams) {

			$url = $prefix . ':parent__' . $subresourceOptions['parentModelAlias'] . '/' . $subresource;
			
			if (
				!empty($subresourceOptions['single']) && 
				!in_array($mapParams['action'], array(
					'view',
					'edit'
				))
			) {
				continue;
			}
			
			if ($mapParams['action'] === 'count') {
				$url .= '/count';
			}
			
			if (empty($subresourceOptions['single']) && !empty($mapParams['id'])) {
				$url .= '/:id';
			}

			self::connect($url, array(
				'plugin' => null,
				'controller' => $subresourceOptions['controllerClass'],
				'action' => $mapParams['action'],
				'[method]' => $mapParams['method'],
				'modelAlias' => !empty($subresourceOptions['modelAlias']) ? $subresourceOptions['modelAlias'] : null,
				'single' => !empty($subresourceOptions['single']) ? true : false
			), array(
				'id' => $subresourceOptions['id_format'], 
				'pass' => array('id')
			));

		}
		
		unset(
			$subresourceOptions['controllerClass'], 
			$subresourceOptions['parentModelAlias'],
			$subresourceOptions['id_format'],
			$subresourceOptions['single'],
			$subresourceOptions['class'],
			$subresourceOptions['query']
		);
		
		$prefix = $url . '/';
		
		self::mapSubresources($prefix, $subresourceOptions);
		
	}
	
	public static function mapSubresources($prefix = '', $subresources = array()) {
		
		if (empty($subresources)) {
			return;
		}
		
		if (array_key_exists('modelAlias', $subresources)) {
			$modelAlias = $subresources['modelAlias'];
		} else {
			$modelAlias = null;
		}
		
		$parentParams = compact('modelAlias');
		
		foreach ($subresources as $subresource => $subresourceOptions) {
			
			if ($subresource === 'controllerClass') {
				continue;
			}
			
			if ($subresource === 'modelAlias') {
				continue;
			}
			
			self::mapSubresource($prefix, $parentParams, $subresource, $subresourceOptions);
			
		}
		
	}
	
	public static function mapAll($resources = array()) {
		
		foreach ($resources as $resource => $subresources) {
		
			$options = array();
			
			if (!empty($subresources['controllerClass'])) {
				$options['controllerClass'] = $subresources['controllerClass'];
			}
			
			self::mapResources($resource, $options);
			
			$prefix = '/' . $resource . '/:id/';

			self::mapSubresources($prefix, $subresources);

		}
		
	}
	
	public static function mapResources($controller = array(), $options = array()) {
		
		$hasPrefix = isset($options['prefix']);
		
		$options = array_merge(
			array(
				'prefix' => '/',
				'id' => self::ID . '|' . self::UUID
			),
			$options
		);

		$prefix = $options['prefix'];

		foreach ((array)$controller as $name) {
			list($plugin, $name) = pluginSplit($name);
			$urlName = Inflector::underscore($name);
			$plugin = Inflector::underscore($plugin);
			if ($plugin && !$hasPrefix) {
				$prefix = '/' . $plugin . '/';
			}

			foreach (self::$_resourceMap as $params) {
				
				if ($params['action'] === 'count') {
					$url = $prefix . $urlName .'/count';
				} else {
					$url = $prefix . $urlName . (($params['id']) ? '/:id' : '');
				}
				
				if (!empty($options['controllerClass'])) {
					$controller = $options['controllerClass'];
				} else {
					$controller = $urlName;
				}
				
				Router::connect($url,
					array(
						'plugin' => $plugin,
						'controller' => $controller,
						'action' => $params['action'],
						'[method]' => $params['method']
					),
					array(
						'id' => $options['id'], 
						'pass' => array('id')
					)
				);
			}
			self::$_resourceMapped[] = $urlName;
		}
		
		return self::$_resourceMapped;
		
	}
	
}