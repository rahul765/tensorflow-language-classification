<?php

namespace me\adamcameron\kahlan\dao;


use Doctrine\DBAL\Connection;

/** @codeCoverageIgnore */
class JobDAO
{
    /** @var  Connection */
    public $dbConnection;

    public function __construct(Connection $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function getLastRunForJob(int $id) : ?string
    {
		throw new \Exception('getLastRunForJob not mocked-out');

        $sql = "SELECT lastRun FROM job WHERE id = :id";
        $values = ['id' => $id];
        $types = ['id' => \PDO::PARAM_INT];

        $statement = $this->dbConnection->executeQuery($sql, $values, $types);

        return $statement->fetchColumn(0);
    }

    public function setLastRunForJob(int $id, string $lastRun)
    {
		throw new \Exception('setLastRunForJob not mocked-out');
        $sql = "UPDATE job SET lastRun = :lastRun WHERE id = :id";
        $values = [
            'lastRun' => $lastRun,
            'id' => $id
        ];
        $types = [
            'lastRun' => \PDO::PARAM_STR,
            'id' => \PDO::PARAM_INT
        ];

        $this->dbConnection->executeUpdate($sql, $values, $types);

        return null;
    }
}
