<?php

namespace doctrineTest\provider\controller;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;

class PdoControllerProvider implements ControllerProviderInterface {

	public function connect(Application $app) {
		$controllers = $app["controllers_factory"];

		$controllers->get("/query", [$app["controller.pdo"], "doGetForQuery"])
			->method("GET")
			->bind("route.pdo.query");

		$controllers->get("/preparedStatement/{maxId}", [$app["controller.pdo"], "doGetForPreparedStatement"])
			->method("GET")
			->bind("route.pdo.preparedStatement");

		$controllers->get("/preparedStatementWithBindParam/{maxId}", [$app["controller.pdo"], "doGetForPreparedStatementWithBindParam"])
			->method("GET")
			->bind("route.pdo.preparedStatementWithBindParam");

		$controllers->get("/preparedStatementWithBindValue/{maxId}", [$app["controller.pdo"], "doGetForPreparedStatementWithBindValue"])
			->method("GET")
			->bind("route.pdo.preparedStatementWithBindValue");

		$controllers->get("/preparedStatementWithParamList/{ids}", [$app["controller.pdo"], "doGetForPreparedStatementWithParamList"])
			->method("GET")
			->bind("route.pdo.preparedStatementWithParamList");

		$controllers->get("/callProc/{id}", [$app["controller.pdo"], "doGetForCallProc"])
			->method("GET")
			->bind("route.pdo.callProc");

		$controllers->get("/transaction/{id}", [$app["controller.pdo"], "doGetForTransaction"])
			->method("GET")
			->bind("route.pdo.transaction");

		return $controllers;
	}
}
