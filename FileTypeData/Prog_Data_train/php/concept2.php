<?php
require_once __DIR__ . '/vendor/autoload.php';

$serviceFactory = new \OAuth\ServiceFactory();
$uriFactory = new \OAuth\Common\Http\Uri\UriFactory();
$currentUri = $uriFactory->createFromSuperGlobalArray($_SERVER);
$currentUri->setQuery('');
use OAuth\OAuth2\Service\Concept2Logbook;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;

$storage = new Session();
$credentials = new Credentials(
    'wSw5bDJMc2zXi1JaWWbjhKFZyp9bU1GYi0iBKsQt',
    'z9PSXE0rP43c7eJDOBDHDM9ATZdT9twMNZqnEKsq',
    'http://danube.bzz.hu/auth.php'
);
// Instantiate the Ustream service using the credentials, http client and storage mechanism for the token

$c2lb = $serviceFactory->createService('Concept2Logbook', $credentials, $storage, array('user:read'));

if (!empty($_GET['code'])) {
    // retrieve the CSRF state parameter
    $state = isset($_GET['state']) ? $_GET['state'] : null;
    // This was a callback request from Ustream, get the token
    $c2lb->requestAccessToken($_GET['code'], $state);
    $result = json_decode($c2lb->request('users/self.json'), true);
    echo 'Your unique Ustream user id is: ' . $result['id'] . ' and your username is ' . $result['username'];
} elseif (!empty($_GET['go']) && $_GET['go'] === 'go') {
    $url = $c2lb->getAuthorizationUri();
    header('Location: ' . $url);
} else {
    $url = $currentUri->getRelativeUri() . '?go=go';
    echo "<a href='$url'>Login with Ustream!</a>";
}
?>