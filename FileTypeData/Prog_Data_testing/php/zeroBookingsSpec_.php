<?php

namespace me\adamcameron\kahlan\spec\acceptance\job;

use Kahlan\Plugin\Double;
use me\adamcameron\kahlan\app\Container;
use me\adamcameron\kahlan\dao\BookingDAO;
use me\adamcameron\kahlan\dao\CustomerDAO;
use me\adamcameron\kahlan\dao\JobDAO;
use me\adamcameron\kahlan\job\SyncCustomersJob;
use me\adamcameron\kahlan\repository\JobRepository;
use me\adamcameron\kahlan\spec\Helper;

describe('Zero-bookings test', function () {
	given('testData', function () {
		$helper = new Helper(__FILE__);
		$testData = $helper->loadTestData();
		return $testData;
	});

	given('bookingDao', function () {
		$bookingDao = Double::instance([
			'extends' => BookingDAO::class,
			'methods' => ['__construct']
		]);

		allow($bookingDao)
			->toReceive('getBookingsSince')
			->andReturn([]);

		return $bookingDao;
	});

	given('jobDao', function () {
		$jobDao = Double::instance([
			'extends' => JobDAO::class,
			'methods' => ['__construct']
		]);

		allow($jobDao)
			->toReceive('getLastRunForJob')
			->andReturn($this->testData->getLastRunForJob);

		allow($jobDao)
			->toReceive('setLastRunForJob')
			->andReturn(null);

		return $jobDao;
	});

	given ('customerDao', function () {
		$customerDao = Double::instance([
			'extends' => CustomerDAO::class,
			'methods' => ['__construct']
		]);

		allow($customerDao)
			->toReceive('getCustomerByEmailAddress');

		allow($customerDao)
			->toReceive('saveCustomerByEmail');

		return $customerDao;
	});

	given('container', function () {
		$container = new Container();

		$container['dao.booking'] = $this->bookingDao;
		$container['dao.job'] = $this->jobDao;
		$container['dao.customer'] = $this->customerDao;

		return $container;
	});

	it ('does not perform any customer processing', function () {
		expect($this->jobDao)
			->toReceive('getLastRunForJob')
			->with(JobRepository::SYNC_CUSTOMERS_JOB);
		expect($this->jobDao)
			->toReceive('setLastRunForJob')
			->with(JobRepository::SYNC_CUSTOMERS_JOB, $this->testData->getLastRunForJob);

		expect($this->customerDao)
			->not
			->toReceive('getCustomerByEmailAddress');
		expect($this->customerDao)
			->not
			->toReceive('getCustomerByEmailAddress');

		$job = new SyncCustomersJob($this->container, ['recordsToProcess' => 5]);
		$job->run();
	});
});