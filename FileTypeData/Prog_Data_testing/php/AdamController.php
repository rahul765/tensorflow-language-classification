<?php

namespace me\adamcameron\general\coroutines;

use Icicle\Coroutine\Coroutine;
use Icicle\Loop;
use Icicle\Awaitable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdamController extends AbstractController
{

    private static $logFile;

    public function doGET(Request $request)
    {
        self::$logFile = LOG_ROOT . '/adam.log';
        error_log("=================================". PHP_EOL. PHP_EOL, 3, self::$logFile);

        $processStartTime = $this->getNowAsTimeString();

        $this->slowLog('mainline entry at top of controller');

        $routine1 = new Coroutine($this->logAsync('first'));
        $routine2 = new Coroutine($this->logAsync('second'));
        $routine3 = new Coroutine($this->logAsync('third'));
        $routine4 = new Coroutine($this->logAsync('fourth'));
        $routine5 = new Coroutine($this->logAsync('fifth'));

        Loop\Run();

        $this->slowLog('mainline entry at bottom of controller');

        $processEndTime = $this->getNowAsTimeString();

        return new Response("Started: $processStartTime<br>Ended: $processEndTime<br>");
    }

    public function slowLog($message)
    {
        $timedMessage = sprintf('%s: %s%s', $this->getNowAsTimeString(), $message, PHP_EOL ) ;
        error_log($timedMessage, 3, self::$logFile);
    }

    public function logAsync($message){
        $delay = rand(1, 10);

        $promise = Awaitable\resolve();

        yield $promise->delay($delay)->then(function() use ($message, $delay){
            $delayedMessage = "$message ($delay)";
            $this->slowLog($delayedMessage);
        });
    }

    private function getNowAsTimeString()
    {
        return date('G:i:s', time());
    }
}
