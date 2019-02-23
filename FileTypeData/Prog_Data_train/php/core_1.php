<?php

Configure::write('debug', 2);

Configure::write('Error', array(
	'handler' => 'AppError::handleError',
	'level' => E_ALL & ~E_DEPRECATED,
	'trace' => true
));

Configure::write('Exception', array(
	'handler' => 'ErrorHandler::handleException',
	'renderer' => 'AppExceptionRenderer',
	'log' => true
));

Configure::write('App.encoding', 'UTF-8');

Configure::write('Session', array(
	'defaults' => 'php'
));

Configure::write('Security.salt', 'pDblTMZaFam59d@F9c#V1G9UEL17)Odz');

Configure::write('Security.cipherSeed', '45929496511896579869824871389438');

Configure::write('Acl.classname', 'Api.ApiPhpAcl');

date_default_timezone_set('UTC');

$engine = 'File';

$duration = '+999 days';
if (Configure::read('debug') > 0) {
	$duration = '+10 seconds';
}

$prefix = 'demo_api_';

Cache::config('_cake_core_', array(
	'engine' => $engine,
	'prefix' => $prefix . 'cake_core_',
	'path' => CACHE . 'persistent' . DS,
	'serialize' => ($engine === 'File'),
	'duration' => $duration
));

Cache::config('_cake_model_', array(
	'engine' => $engine,
	'prefix' => $prefix . 'cake_model_',
	'path' => CACHE . 'models' . DS,
	'serialize' => ($engine === 'File'),
	'duration' => $duration
));
