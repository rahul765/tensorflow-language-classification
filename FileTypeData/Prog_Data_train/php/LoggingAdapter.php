<?php

namespace me\adamcameron\general\testApp\adapter;

use GuzzleHttp\Promise\Promise;

class LoggingAdapter implements Adapter {

    private $adapter;
    private $logger;
    private $thisFile;

    public function __construct(Adapter $adapter, $logger) {
        $this->adapter = $adapter;
        $this->logger = $logger;
        $this->thisFile = explode(".", basename(__FILE__))[0];
    }

    public function get($url, $parameters) : Promise {
        $logDetails = json_encode($parameters);

        return $this->performLoggedRequest(__FUNCTION__, $logDetails, $url, $parameters);
    }

    public function post($url, $body) : Promise {
        $logDetails = json_encode($body);

        return $this->performLoggedRequest(__FUNCTION__, $logDetails, $url, $body);
    }

    public function put($url, $body, $parameters) : Promise {
        $logDetails = json_encode([
            'parameters' => $parameters,
            'body' => $body
        ]);

        return $this->performLoggedRequest(__FUNCTION__, $logDetails, $url, $body, $parameters);
    }

private function performLoggedRequest($method, $logDetails, ...$requestArgs) : Promise {
    $this->logger->logMessage(sprintf("%s: Requesting for %s", $this->thisFile, $logDetails));

    $response = call_user_func_array([$this->adapter, $method], $requestArgs);

    $response->then(function($response) use ($logDetails) {
        $body = $response->getBody();
        $this->logger->logMessage(sprintf("%s: Response for %s: %s", $this->thisFile, $logDetails, $body));
        $body->rewind();
    });

    return $response;
}

}
