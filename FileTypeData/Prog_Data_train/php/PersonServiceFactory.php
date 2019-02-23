<?php

namespace me\adamcameron\general\chainOfResponsibility\factory;

use me\adamcameron\general\chainOfResponsibility\handler\CacheablePersonHandler;
use me\adamcameron\general\chainOfResponsibility\handler\CachedPersonHandler;
use me\adamcameron\general\chainOfResponsibility\handler\DatabasePersonHandler;
use me\adamcameron\general\chainOfResponsibility\repository\PersonRepository;
use me\adamcameron\general\chainOfResponsibility\service\CacheService;
use me\adamcameron\general\chainOfResponsibility\service\LoggingService;
use me\adamcameron\general\chainOfResponsibility\service\PersonService;

class PersonServiceFactory {

    public static function getPersonService()
    {
        $loggingService = new LoggingService();
        $cacheService = new CacheService($loggingService);
        $personRepository = new PersonRepository($loggingService);

        $cachedHandler = new CachedPersonHandler($cacheService);
        $databaseHandler = new DatabasePersonHandler($personRepository);
        $cacheableHandler = new CacheablePersonHandler($cacheService);

        $databaseHandler->setNextHandler($cacheableHandler);
        $cachedHandler->setNextHandler($databaseHandler);

        $personService = new PersonService($cachedHandler);

        return $personService;
    }
}
