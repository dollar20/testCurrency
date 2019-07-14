<?php
require_once "Currency.php";

$result = '';
$objCurrency = new Currency();
$result = $objCurrency->delCurrency();

echo json_encode($result); 
?>