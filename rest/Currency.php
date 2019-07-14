<?php
require_once "../params.php";
date_default_timezone_set('Europe/Minsk');
/* 
A domain Class to demonstrate RESTful web services
*/
Class Currency {
	
	private $currencies = array(
		1 => 'Apple iPhone 6S',  
		2 => 'Samsung Galaxy S6',  
		3 => 'Apple iPhone 6S Plus',  			
		4 => 'LG G4',  			
		5 => 'Samsung Galaxy S6 edge',  
		6 => 'OnePlus 2');
		
	/*
		Вывод всех курсов валют из таблицы
	*/
	public function getAllCurrency(){
        GLOBAL $mysqli;
        $currency =[];
		$sql = "SELECT * FROM currency";
        if ($result = $mysqli->query($sql)) {
            /* выборка данных и помещение их в массив */
            while ($row = $result->fetch_row()) {
               $currency[$row[4]] = $row[5];
            }
            /* очищаем результирующий набор */
            $result->close();
        }
        /* закрываем подключение */
        $mysqli->close();

		return $currency;    
	}
	/*
		Вывод курсов валют по параметрам ИД валюты и даты с - по
	*/
	public function getCurrency($ValuteId, $DateFrom, $DateTo){
        GLOBAL $mysqli;
        $currency =[];

		$sql = "SELECT * FROM currency WHERE currency.valuteID = '".$ValuteId."' AND currency.date >= '".date("Y-m-d", strtotime($DateFrom))."' AND currency.date <= '".date("Y-m-d", strtotime($DateTo))."' ";
        if ($result = $mysqli->query($sql)) {
            /* выборка данных и помещение их в массив */
            while ($row = $result->fetch_row()) {
               $currency[] = $row;
            }
            /* очищаем результирующий набор */
            $result->close();
        }
        /* закрываем подключение */
        $mysqli->close();

		return $currency;
	}
    /*
		Очистка таблицы курсов валют
	*/
    public function delCurrency(){
    
        GLOBAL $mysqli;
        $sql = "DELETE FROM currency";
        /* удаляем все даннные, пока без обработки результата */
        if ($mysqli->query($sql) === TRUE) {
            $result = "Record deleted successfully";
        } else {
            $result = "Error deleting record: " . $mysqli->error;
        }
        /* закрываем подключение */
        $mysqli->close();
        
        return $result;
    }

    /*
		Заполнение таблицы курсов
	*/
    public function fillCurrency(){
        GLOBAL $mysqli;
        $listValute = [];
        $xml = simplexml_load_file('http://www.cbr.ru/scripts/XML_valFull.asp?d=0');
        $node = $xml->xpath('//Item');

        $i = 0;
        foreach($node as $value){
        
            $listValute[$i]['Id'] = $value->attributes()->ID;
            $listValute[$i]['Name'] = $value->Name;
            $listValute[$i]['Num_Code'] = $value->ISO_Num_Code;
            $listValute[$i]['Char_Code'] = $value->ISO_Char_Code;
            $listValute[$i]['Nominal']=$value->Nominal;
            $i++;
        }
        // Дополняем валюты курсами 
        $listCurrency = [];
        $i = 0;
        $date = strtotime('-30 days');
        
        foreach( $listValute as $key => $val ){
            $xml = simplexml_load_file('http://www.cbr.ru/scripts/XML_dynamic.asp?date_req1='.date('d/m/Y', $date).'&date_req2='.date("d/m/Y").'&VAL_NM_RQ='.$val['Id']);
            $node = $xml->xpath('//Record');
            foreach($node as $value){
                $listCurrency[$i]['Id'] = $val['Id'];
                $listCurrency[$i]['Name'] = $val['Name'];
                $listCurrency[$i]['Num_Code'] = $val['Num_Code'];
                $listCurrency[$i]['Char_Code'] = $val['Char_Code'];
                $listCurrency[$i]['value'] = $value->Value;
                $listCurrency[$i]['Date'] = $value->attributes()->Date;
                $i++;
            }
        }
        $sql = "INSERT INTO `currency` (`valuteID`, `numCode`, `charCode`, `name`, `value`, `date`) VALUES ";
        
        foreach($listCurrency as $key=>$val){
            $sql .= "('{$val['Id']}','{$val['Num_Code']}','{$val['Char_Code']}','{$val['Name']}','".(preg_replace("/,/",".",$val['value']))."','". date("Y-m-d", strtotime($val['Date']))."'),";
        }
        
        $sql = substr($sql, 0, -1);
        $sql .= ";";
        
        if ($mysqli->query($sql) === TRUE) {
            $result = "Record insert successfully";
        } else {
            $result = "Error insert record: " . $mysqli->error;
        }
        
        return $result;
    }
    /*
		Получаем значение курсов на дату(для селекта)
	*/
    public function getCurrencyByDate( $setData ){
        $currency = [];
        GLOBAL $mysqli;
        $sql = "SELECT * FROM currency WHERE currency.date = '".$setData."'";
        if ($result = $mysqli->query($sql)) {
            /* выборка данных и помещение их в массив */
            while ($row = $result->fetch_row()) {
               $currency[] = $row;
            }
            /* очищаем результирующий набор */
            $result->close();
        }
        /* закрываем подключение */
        $mysqli->close();
        
        return $currency;
    }
}
?>