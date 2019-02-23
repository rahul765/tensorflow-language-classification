<?php

namespace doctrineTest\controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class PdoController {

	public static function doGetForQuery(Request $request, Application $app) {
		$numbers = $app["repository.number"]->getAll();
		return $app["twig"]->render("numbers.html.twig", ["numbers"=>$numbers]);
	}

	public static function doGetForPreparedStatement(Request $request, Application $app) {
		$maxId = $request->get("maxId");

		$numbers = $app["repository.number"]->getSubset($maxId);

		return $app["twig"]->render("numbers.html.twig", ["numbers"=>$numbers]);
	}

	public static function doGetForPreparedStatementWithBindParam(Request $request, Application $app) {
		$maxId = $request->get("maxId");

		$numbers = $app["repository.number"]->getSubsetWithBindParam($maxId);

		return $app["twig"]->render("numbers.html.twig", ["numbers"=>$numbers]);
	}

	public static function doGetForPreparedStatementWithBindValue(Request $request, Application $app) {
		$maxId = $request->get("maxId");

		$numbers = $app["repository.number"]->getSubsetWithBindValue($maxId);

		return $app["twig"]->render("numbers.html.twig", ["numbers"=>$numbers]);
	}

	public static function doGetForPreparedStatementWithParamList(Request $request, Application $app) {
		$ids = $request->get("ids");
		$idsArray = explode(",", $ids);

		$numbers = $app["repository.number"]->getSubsetWithParamList($idsArray);

		return $app["twig"]->render("numbers.html.twig", ["numbers"=>$numbers]);
	}

	public static function doGetForCallProc(Request $request, Application $app) {
		$id = $request->get("id");

		$things = $app["repository.number"]->getThingsWithId($id);

		return $app["twig"]->render(
			"things.html.twig",
			[
				"number" => $things["number"],
				"colour" => $things["colour"],
				"day" => $things["day"]
			]
		);
	}

	public static function doGetForTransaction(Request $request, Application $app) {
		$id = $request->get("id");

		$numbers = $app["repository.number"]->getNumbersUsingTransaction($id);

		return $app["twig"]->render(
			"transaction.html.twig",
			[
				"withinTransaction" => $numbers["withinTransaction"],
				"afterRollback" => $numbers["afterRollback"]
			]
		);
	}
}