<?php

session_start();

define('ROOT_PATH', __DIR__ . '/../../');

define('APP_PATH', ROOT_PATH . 'app/');
define('CACHE_PATH', ROOT_PATH . 'cache/');
define('VENDOR_PATH', ROOT_PATH . 'vendor/');
define('PUBLIC_PATH', ROOT_PATH . 'public/');
define('RESOURCE_PATH', ROOT_PATH . 'resource/');

define('MODULE_PATH', APP_PATH . 'modules/');

require VENDOR_PATH . 'autoload.php';
require APP_PATH . 'Helpers/functions.php';

/**
 * Load the configuration
 */
$config = array(
    'path.root' => ROOT_PATH,
    'path.cache' => CACHE_PATH,
    'path.public' => PUBLIC_PATH,
    'path.app' => APP_PATH,
    'path.module' => MODULE_PATH,
    'path.resource' => RESOURCE_PATH,
);

/** include Config files */
foreach (glob(APP_PATH . 'config/*.php') as $configFile) {
    $config += require_once $configFile;
}

if ($config['slim']['settings']['debug']) {
    error_reporting(E_ALL ^ E_NOTICE);
}

$container = new \Slim\Container($config['slim']);

$app = new \Slim\App($container);

$t = new App\Modules\CoreModule($app->getContainer());
/*
// Bootstrap Eloquent ORM
$dbcontainer = new Illuminate\Container\Container;
$connFactory = new \Illuminate\Database\Connectors\ConnectionFactory($dbcontainer);
$conn = $connFactory->make($config['db'][$config['slim']['db_driver']]);
$resolver = new \Illuminate\Database\ConnectionResolver();
$resolver->addConnection('default', $conn);
$resolver->setDefaultConnection('default');
\Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);
// End Bootstrap Eloquent ORM
 */
use \Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Configure the database and boot Eloquent
 */
$capsule = new Capsule;

$capsule->addConnection($config['db'][$config['slim']['db_driver']]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

/** Initialize up Slim hooks and middleware */
require APP_PATH . 'bootstrap/middleware.php';

/** Initialize up Slim DependencyInjection */
require APP_PATH . 'bootstrap/di.php';

/** Initialize routes for application */
foreach (glob(APP_PATH . 'routers/*.php') as $routeFile) {
    require_once $routeFile;
}

$app->getContainer()->dispatcher->addListener('acme.action', function ($event) {
    echo "action 1";
});

$app->getContainer()->dispatcher->addListener('acme.action', function ($event) {
    echo "action 2";
});

$app->getContainer()->dispatcher->addListener('acme.action', function ($event) {
    echo "action 3";
});

return $app;
