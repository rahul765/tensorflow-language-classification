<?php
class MatchTest extends PHPUnit_Framework_TestCase
{
	 public function setUp()
	 {
		  $client = new \HonAPI\HonClient(123);
		  $this->match = $client->getMatch();
	 }

	 /**
	  * Match::setMatchId
	  */
	 public function testSetMatchId()
	 {
		  $this->match->setMatchId(456);
		  $props = $this->match->getProperties();
		  $this->assertEquals(456, array_values($props)[0]);
		  $this->assertEquals('matchid', array_keys($props)[0]);
	 }

	 /**
	  * Match::getSettings
	  */
	 public function testGetSettings()
	 {
		  $this->match->getSettings();
		  $this->assertEquals('settings', $this->match->getMethod());
	 } 

	 /**
	  * Match::getInventory
	  */
	 public function testGetInventory()
	 {
		  $this->match->getInventory();
		  $this->assertEquals('inventory', $this->match->getMethod()); 
	 }

	 /**
	  * Match::getStatistics
	  */
	 public function testGetStatistics()
	 {
		  $this->match->getStatistics();
		  $this->assertEquals('statistics', $this->match->getMethod());  
	 }

	 /**
	  * Match::getSummary
	  */
	 public function testGetSummary()
	 {
		  $this->match->getSummary();
		  $this->assertEquals('summ', $this->match->getMethod());  
	 }

	 /**
	  * Match::getAll
	  */
	 public function testGetAll()
	 {
		  $this->match->getAll();
		  $this->assertEquals('all', $this->match->getMethod());   
	 }
}
