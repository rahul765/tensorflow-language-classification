<?php

require __DIR__ . '/dependencies.php';

class UserRepository {

	private $dataSource;

	public function __construct($dataSource) {
		$this->dataSource = $dataSource;
	}

	public function getById($id) {
		$userAsJson = $this->dataSource->getById($id);

		$rawUser = json_decode($userAsJson);
		$user = new User($rawUser->id, $rawUser->firstName, $rawUser->lastName);

		return $user;
	}

	public function getByFilters($filters) {
		$usersAsJson = $this->dataSource->getByFilters($filters);

		$rawUsers = json_decode($usersAsJson);
		$users = array_map(function($rawUser){
			return new User($rawUser->id, $rawUser->firstName, $rawUser->lastName);
		}, $rawUsers);

		return $users;
	}

	public function create($firstName, $lastName){
		$rawNewUser = json_decode($this->dataSource->create($firstName, $lastName));
		return new User($rawNewUser->id, $rawNewUser->firstName, $rawNewUser->lastName);
	}
}

class LoggedUserRepository {

	private $repository;
	private $loggerService;

	public function __construct($repository, $loggerService) {
		$this->repository = $repository;
		$this->loggerService = $loggerService;
	}

	public function __call($method, $arguments) {
		$serialisedArgs = json_encode($arguments);
		$this->loggerService->logText("$method called with $serialisedArgs");

		return call_user_func_array([$this->repository, $method], $arguments);
	}

}

$dataSource = new DataSource();
$userRepository = new UserRepository($dataSource);

$loggerService = new LoggerService();
$loggedUserRepository = new LoggedUserRepository($userRepository, $loggerService);

$user = $loggedUserRepository->getById(5);
var_dump($user);

$users = $loggedUserRepository->getByFilters(['firstName'=>'Zachary', 'lastName'=>'Cameron Lynch']);
var_dump($users);

$newUser = $loggedUserRepository->create('Zachary', 'Cameron Lynch');
var_dump($newUser);