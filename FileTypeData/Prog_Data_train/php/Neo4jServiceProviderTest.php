<?php

namespace KBrabrandTest\Silex\Provider;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use KBrabrand\Silex\Provider\Neo4jServiceProvider;

class Neo4jServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!class_exists('\Everyman\Neo4j\Client')) {
            $this->markTestSkipped('Everyman\Neo4j was not installed.');
        }
    }

    public function testRegister() {
        $host = 'otherhost';
        $port = 7575;

        $app = new Application();

        $app->register(new Neo4jServiceProvider(), array(
            'neo4j.transport' => $host,
            'neo4j.port'      => $port,
        ));

        $app->get('/', function() use($app) {
            $app['neo4j'];
        });

        $request = Request::create('/');
        $app->handle($request);

        $transport = $app['neo4j']->getTransport();

        $this->assertTrue($app['neo4j'] instanceof \Everyman\Neo4j\Client);
        $this->assertEquals(1, preg_match('/' . $host . ':' . $port . '/', $transport->getEndpoint()));
    }
}
