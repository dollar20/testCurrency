<?php
require_once "Currency.php";

$result = '';
$objCurrency = new Currency();
$result = $objCurrency->fillCurrency();

echo json_encode($result);

?>