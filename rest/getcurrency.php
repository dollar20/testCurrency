<?php
// Include params file
require_once "Currency.php";
$setData = $_REQUEST['setData'];

$result = '';
$objCurrency = new Currency();
$result = $objCurrency->getCurrencyByDate($setData);

echo json_encode($result); 
?>