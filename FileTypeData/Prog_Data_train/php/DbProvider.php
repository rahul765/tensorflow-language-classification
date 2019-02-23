<?php

namespace me\adamcameron\general\db\provider;

use Doctrine\DBAL\Connections\MasterSlaveConnection;
use Doctrine\DBAL\DriverManager;
use me\adamcameron\general\db\dao\ColoursDAO;
use me\adamcameron\general\db\model\DbConfig;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class DbProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $pimple['db.connection'] = function ($pimple) {
            return self::getMasterSlaveConnection($pimple['config.db']);
        };
        $pimple['dao.colours'] = function ($pimple) {
            return new ColoursDAO($pimple['db.connection']);
        };
    }

    public static function getMasterSlaveConnection(DbConfig $dbConfig)
    {
        return DriverManager::getConnection([
            'wrapperClass' => MasterSlaveConnection::class,
            'driver' => 'pdo_mysql',
            'master' => [
                'user' => $dbConfig->master->user,
                'password' => $dbConfig->master->password,
                'host' => $dbConfig->master->host,
                'port' => $dbConfig->master->port,
                'dbname' => $dbConfig->master->dbname
            ],
            'slaves' => [
                [
                    'user' => $dbConfig->slave->user,
                    'password' => $dbConfig->slave->password,
                    'host' => $dbConfig->slave->host,
                    'port' => $dbConfig->slave->port,
                    'dbname' => $dbConfig->slave->dbname
                ]
            ]
        ]);
    }
}
