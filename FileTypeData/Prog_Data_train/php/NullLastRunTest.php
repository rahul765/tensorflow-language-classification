<?php

namespace me\adamcameron\kahlan\test\acceptance\job\syncCustomersJobTests;

use me\adamcameron\kahlan\repository\JobRepository;

/** @coversDefaultClass me\adamcameron\kahlan\job\SyncCustomersJob */
class NullLastRunTest extends SyncDataJobTest
{
	private static $zeroDate = '0000-00-00 00:00:00';

	protected function mockJobDaoBehaviour($dao)
	{
		$dao->expects($this->once())
			->method('getLastRunForJob')
			->willReturn($this->testData->getLastRunForJob);

		$dao->expects($this->once())
			->method('setLastRunForJob')
			->with(
				JobRepository::SYNC_CUSTOMERS_JOB,
				self::$zeroDate
			)
			->willReturn(null);
	}

	protected function mockBookingDaoBehaviour($dao)
	{
		$dao->expects($this->once())
			->method('getBookingsSince')
			->with(self::$zeroDate, $this->recordsToProcess)
			->willReturn($this->testData->getBookingsSince);
	}

	protected function mockCustomerDaoBehaviour($dao)
	{
		$dao->expects($this->never())
			->method('getCustomerByEmailAddress');

		$dao->expects($this->never())
			->method('saveCustomerByEmail');
	}
}
