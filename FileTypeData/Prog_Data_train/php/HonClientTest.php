<?php
class HonClientTest extends PHPUnit_Framework_TestCase
{
	 public function setUp()
	 {
		$this->client = new \HonApi\HonClient(123);
	 }

	 /**
	  * HonClient is instantiable
	  */
	 public function testIsInstantiable()
	 {
		  $this->assertInstanceOf('HonAPI\HonClient', $this->client);
	 }

	 /**
	  * PlayerStats object returned from HonClient::getPlayerStats
	  */
	 public function testGetPlayerStats()
	 {
		  $this->assertInstanceOf('HonAPI\PlayerStats', $this->client->getPlayerStats());
	 }

	 /**
	  * HeroStats object returned from HonClient::getHeroStats
	  */
	 public function testGetHeroStats()
	 {
		  $this->assertInstanceOf('HonAPI\HeroStats', $this->client->getHeroStats());
	 }

	 /**
	  * MatchHistory object returned from HonClient::getMatchHistory
	  */
	 public function testGetMatchHistory()
	 {
		  $this->assertInstanceOf('HonAPI\MatchHistory', $this->client->getMatchHistory());
	 }

	 /**
	  * Match object returned from HonClient::getMatch
	  */
	 public function testGetMatch()
	 {
		  $this->assertInstanceOf('HonAPI\Match', $this->client->getMatch());
	 }

	 /**
	  * MultiMatch object return from HonClient::getMultiMatch
	  */
	 public function testGetMultiMatch()
	 {
		  $this->assertInstanceOf('HonAPI\MultiMatch', $this->client->getMultiMatch());
	 }
}
