<?php

namespace me\adamcameron\general\testApp\adapter;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\Promise;
use Psr\Http\Message\ResponseInterface;

class HttpErrorMappingAdapter {

	private $adapter;
	private $exceptionMap;

	public function __construct(Adapter $adapter, $exceptionMap) {
		$this->adapter = $adapter;
		$this->exceptionMap = $exceptionMap;

		$this->replaceClientErrorHandler($exceptionMap);
	}

	public function get($status) : Promise {
		return $this->adapter->get($status);
	}

	private function replaceClientErrorHandler($exceptionMap) {
		$stack = $this->adapter->getHandlerStack();

		$stack->remove('http_errors');

		$stack->unshift($this->handleHttpErrors($exceptionMap));
	}

	private function handleHttpErrors($exceptionMap) {
		return function (callable $handler) use ($exceptionMap) {
			return function ($request, array $options) use ($handler, $exceptionMap) {
				return $handler($request, $options)->then(
					function (ResponseInterface $response) use ($request, $handler, $exceptionMap) {
						$code = $response->getStatusCode();

						if ($code < 400) {
							return $response;
						}

						if (array_key_exists($code, $exceptionMap)){
							throw new $exceptionMap[$code](
								(string)$response->getBody(),
								$code
							);
						}
						throw RequestException::create($request, $response);
					}
				);
			};
		};
	}
}
