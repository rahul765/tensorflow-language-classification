<?php

namespace me\adamcameron\general\test\puzzle\tree;

use me\adamcameron\general\puzzle\tree\Tree;

/** @coversDefaultClass \puzzle\Tree */
class TreeTest extends \PHPUnit_Framework_TestCase {

	private $testDir;

	function setup() {
		$this->testDir = realpath(__DIR__ . "/testfiles");
	}

	/**
	 * @covers ::loadFromCsv
	 * @covers ::__construct
	 * @covers ::addNode
	 * @covers ::jsonSerialize
	 * @dataProvider provideCasesForLoadFromCsvTests
	 */
	function testLoadFromCsv($baseFile){
		$files = $this->getFilesFromBase($baseFile);

		$result = Tree::loadFromCsv($files["src"]);
		$resultAsJson = json_encode($result);
		$resultAsArray = json_decode($resultAsJson);

		$expectedJson = file_get_contents($files["expectation"]);
		$expectedAsArray = json_decode($expectedJson);
		$this->assertEquals($expectedAsArray, $resultAsArray);
	}

	private function getFilesFromBase($base){
		return [
			"src" => sprintf("%s/%s/data.csv", $this->testDir, $base),
			"expectation" => sprintf("%s/%s/expected.json", $this->testDir, $base)
		];
	}

	function provideCasesForLoadFromCsvTests(){
		return [
			"puzzle requirements" => ["testSet" => "puzzle"],
			"one element" => ["testSet" => "one"],
			"deep" => ["testSet" => "deep"],
			"flat" => ["testSet" => "flat"],
			"not ordered" => ["testSet" => "notOrdered"]
		];
	}
}
