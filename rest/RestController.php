<?php
require_once("CurrencyRestHandler.php");
		
$view = "";
if(isset($_GET["view"]))
	$view = $_GET["view"];
/*
controls the RESTful services
URL mapping
*/
switch($view){

	case "all":
		// to handle REST Url /currency/list/
		$currencyRestHandler = new CurrencyRestHandler();
		$currencyRestHandler->getAllCurrencies();
		break;
		
	case "single":
		// to handle REST Url /cyrrency/<ValuteID>/<DateFrom>/<DateTo>/
		$currencyRestHandler = new CurrencyRestHandler();
		$currencyRestHandler->getCurrency($_GET["ValuteID"],$_GET["DateFrom"],$_GET["DateTo"]);
		break;

	case "" :
		//404 - not found;
		break;
}
?>
