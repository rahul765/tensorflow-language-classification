<?php

namespace me\adamcameron\kahlan\test\acceptance\job\syncCustomersJobTests;

use me\adamcameron\kahlan\app\Container;
use me\adamcameron\kahlan\dao\BookingDAO;
use me\adamcameron\kahlan\dao\CustomerDAO;
use me\adamcameron\kahlan\dao\JobDAO;
use me\adamcameron\kahlan\job\SyncCustomersJob;
use Monolog\Handler\NullHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

abstract class SyncDataJobTest extends TestCase
{
	protected $testData;
	protected $container;
	protected $recordsToProcess;
	protected $job;

	protected abstract function mockJobDaoBehaviour($dao);

	protected abstract function mockBookingDaoBehaviour($dao);

	protected abstract function mockCustomerDaoBehaviour($dao);

	public function setup()
	{
		$this->loadTestData();
		$this->setTestContainer();
		$this->recordsToProcess = 5;
		$this->job = new SyncCustomersJob(
			$this->container,
			['recordsToProcess' => $this->recordsToProcess]
		);
	}

	/**
	 * @covers ::__construct
	 * @covers ::run
	 * @covers ::processBookings
	 * @covers ::processCustomers
	 * @covers ::getPreviousBookingId
	 * @covers ::setRecordsToProcess
	 * @covers \me\adamcameron\kahlan\app\Container::__construct
	 * @covers \me\adamcameron\kahlan\config\Config::__construct
	 * @covers \me\adamcameron\kahlan\provider\ConfigProvider::register
	 * @covers \me\adamcameron\kahlan\provider\RepositoryProvider::register
	 * @covers \me\adamcameron\kahlan\repository\BookingRepository::__construct
	 * @covers \me\adamcameron\kahlan\repository\BookingRepository::getBookingsSince
	 * @covers \me\adamcameron\kahlan\repository\CustomerRepository::__construct
	 * @covers \me\adamcameron\kahlan\repository\CustomerRepository::getCustomerByEmailAddress
	 * @covers \me\adamcameron\kahlan\repository\CustomerRepository::saveCustomers
	 * @covers \me\adamcameron\kahlan\repository\JobRepository::__construct
	 * @covers \me\adamcameron\kahlan\repository\JobRepository::getLastRunForJob
	 * @covers \me\adamcameron\kahlan\repository\JobRepository::setLastRunForJob
	 */
	public function testSyncCustomersJob()
	{
		$this->performTestSpecifics();
	}

	protected function performTestSpecifics()
	{
		$this->job->run();
	}

	private function setTestContainer()
	{
		$container = new Container();

		$this->setMockedLogger($container);
		$this->setMockedJobDao($container);
		$this->setMockedBookingDao($container);
		$this->setMockedCustomerDao($container);

		$this->container = $container;
	}

	private function setMockedLogger($container)
	{
		$log = new Logger('null');
		$log->pushHandler(new NullHandler());

		$container['service.logger'] = $log;
	}

	private function setMockedJobDao($container)
	{
		$container['dao.job'] = function () {
			$dao = $this->getMockBuilder(JobDAO::class)
				->disableOriginalConstructor()
				->getMock();

			$this->mockJobDaoBehaviour($dao);

			return $dao;
		};
	}

	private function setMockedBookingDao($container)
	{
		$container['dao.booking'] = function () {
			$dao = $this->getMockBuilder(BookingDAO::class)
				->disableOriginalConstructor()
				->getMock();

			$this->mockBookingDaoBehaviour($dao);

			return $dao;
		};
	}

	private function setMockedCustomerDao($container)
	{
		$container['dao.customer'] = function () {
			$dao = $this->getMockBuilder(CustomerDAO::class)
				->disableOriginalConstructor()
				->getMock();

			$this->mockCustomerDaoBehaviour($dao);

			return $dao;
		};
	}

	private function loadTestData()
	{
		$this->testData = (object)[
			'getLastRunForJob' => $this->decodeJsonFile('getLastRunForJob.json'),
			'getBookingsSince' => $this->decodeJsonFile('getBookingsSince.json'),
			'getCustomerByEmailAddress' => $this->decodeJsonFile('getCustomerByEmailAddress.json'),
			'saveCustomerByEmail' => $this->decodeJsonFile('saveCustomerByEmail.json')
		];
	}

	private function decodeJsonFile($file)
	{
		$testDataSubDir = $this->getTestDataSubDir();
		$dataPath = realpath(__DIR__ . "/data/$testDataSubDir");
		$dataFile = $dataPath . '/' . $file;
		if (!file_exists($dataFile)) {
			return null;
		}

		return json_decode(file_get_contents($dataFile), true);
	}

	private function getTestDataSubDir()
	{
		$classPathComponents = explode('\\', get_called_class());
		$className = end($classPathComponents);

		return lcfirst($className);
	}
}
