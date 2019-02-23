<?php

namespace me\adamcameron\kahlan\job;

use me\adamcameron\kahlan\app\Container;
use me\adamcameron\kahlan\model\Booking;
use me\adamcameron\kahlan\model\Customer;
use me\adamcameron\kahlan\repository\BookingRepository;
use me\adamcameron\kahlan\repository\CustomerRepository;
use me\adamcameron\kahlan\repository\JobRepository;
use Monolog\Logger;

class SyncCustomersJob
{
    /** @var BookingRepository  */
    private $bookingRepository;

    /** @var CustomerRepository  */
    private $customerRepository;

    /** @var JobRepository  */
    private $jobRepository;

    /** @var  Logger */
    private $logger;

    private $recordsToProcess = 0;

    public function __construct(Container $container, array $params)
    {
        $this->bookingRepository = $container['repository.booking'];
        $this->customerRepository = $container['repository.customer'];
        $this->jobRepository = $container['repository.job'];
        $this->logger = $container['service.logger'];

        $this->setRecordsToProcess($params);
    }

    public function run() : void
    {
        $lastRun = $this->jobRepository->getLastRunForJob(JobRepository::SYNC_CUSTOMERS_JOB)->lastRun;
        $nextRun = $lastRun;
        $this->logger->info("Started processing bookings since [$lastRun]");

        $lastProcessedDate = $this->processBookings($lastRun);

        if (!empty($lastProcessedDate)) {
            $nextRun = $lastProcessedDate;
        }
        $this->jobRepository->setLastRunForJob(JobRepository::SYNC_CUSTOMERS_JOB, $nextRun);
        $this->logger->info("Next run will start from [$nextRun]");

        $this->logger->info("Finished processing bookings since [$lastRun]");
    }

    private function processBookings($lastRun) : ?string
    {
        $bookings = $this->bookingRepository->getBookingsSince($lastRun, $this->recordsToProcess);

        $bookingCount = count($bookings);
        $this->logger->info("Processing [$bookingCount] bookings");
        if (!$bookingCount) {
            return null;
        }

        $this->processCustomers($bookings);

        return end($bookings)->created;
    }

    private function processCustomers(array $bookings) : void
    {
        $customers = array_map(function (Booking $booking) {
            $previousId = $this->getPreviousBookingId($booking);

            return new Customer($booking->id, $booking->emailAddress, $previousId);
        }, $bookings);

        $this->logger->info(sprintf('Updating [%d] customers', count($customers)));

        $saved = $this->customerRepository->saveCustomers($customers);
        $this->logger->info("[$saved] customer records updated");

    }

    private function getPreviousBookingId(Booking $booking) : ?int
    {
        $existingCustomer = $this->customerRepository->getCustomerByEmailAddress($booking->emailAddress);
        if (is_null($existingCustomer)) {
            return null;
        }
        if ($existingCustomer->bookingId == $booking->id) {
            return $existingCustomer->previousBookingId;
        }
        return $existingCustomer->bookingId;
    }

    private function setRecordsToProcess($params)
    {
        $this->recordsToProcess = (int) ($params['recordsToProcess'] ?: 0);
    }
}
