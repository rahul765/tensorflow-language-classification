<?php

namespace doctrineTest;

use doctrineTest\provider;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;

class DoctrineApplication extends Application
{

	public function __construct(array $values=[])
	{
		parent::__construct($values);
		$this->registerProviders();
		$this->mountControllers();
	}

	private function registerProviders()
	{
		$this->register(new provider\service\ConfigProvider());
		$this->register(new provider\service\DoctrineProvider());
		$this->register(new provider\service\ControllerProvider());
		$this->register(new provider\service\ControllerProviderProvider());
		$this->register(new provider\service\FactoryProvider());
		$this->register(new provider\service\HelperProvider());
		$this->register(new provider\service\RepositoryProvider());
		$this->register(new TwigServiceProvider(), [
			"twig.path" => __DIR__ . '\view'
		]);
	}

	function mountControllers(){
		$this->mount("/hello", $this["controllerProvider.hello"]);
		$this->mount("/pdo", $this["controllerProvider.pdo"]);
		$this->mount("/colour", $this["controllerProvider.colour"]);
	}

}