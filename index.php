<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
date_default_timezone_set('Europe/Minsk');
// Include params file
require_once "params.php";

$dateMin = strtotime('-30 days');

$sql = "SELECT * FROM currency WHERE currency.date = '".date('Y-m-d')."'";
if ($result = $mysqli->query($sql)) {

    /* выборка данных и помещение их в массив */
    while ($row = $result->fetch_row()) {
       $currency[] = $row;
    }

    /* очищаем результирующий набор */
    $result->close();
}
$sql = "SELECT * FROM currency ";
if ($resultC = $mysqli->query($sql)) {
    /* выборка данных и помещение их в массив */
    $countCurrency = mysqli_num_rows($resultC);
    /* очищаем результирующий набор */
    $resultC->close();
}

/* закрываем подключение */
$mysqli->close();
?>
 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Test Main Page</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <style type="text/css">
            body{ font: 14px sans-serif; text-align: center; }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="page-header">
                <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h1>
            </div>
            <div class="row">
                <div class="col">
                    <form>
                        <div class="form-group">
                            <label for="inputDate"><h3>Введите дату, на которую отображать курсы валют:</h3></label>
                            <input type="date" onchange="changeDate()" id="setData" value="<?= date('Y-m-d')?>" class="form-control" min="<?= date('Y-m-d', $dateMin)?>" max="<?= date('Y-m-d')?>"><br />
                            <table id="valuteCurrency" class="table table-striped">
                                <thead><tr><th colspan="2" id="nametbl">Курсы валют на <?= date('d.m.Y')?></th></tr></thead>
                                <tbody>
                                <?php if(!empty($currency)){ ?>
                                <?php foreach($currency as $index=>$value){ ?>
                                    <tr id="tr">
                                        <td><?php echo $value['4']?></td>
                                        <td><?php echo $value['5']?></td>
                                    </tr>
                                <?php } ?> 
                                <?php }else { ?>
                                    <tr id="tr"><td colspan="2">Данные о курсах на текущую дату отсутствуют</td></tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="col">
                    <button type="button" onclick="delCurrency()" class="btn btn-danger">Очистить таблицу курсов</button>
                    <div id="result_clear"></div><br />
                    <h5>Перед загрузкой курсов в БД, очистите таблицу</h5>
                    <button type="button" onclick="fillCurrency(<?php echo $countCurrency;?>)" class="btn btn-success">Загрузить курсы валют за 30 дней, включая текущий</button>
                    <div id="result_form"></div><br />
                    <a href="logout.php" class="btn btn-warning">Вернуться на страницу авторизации</a>
                </div>
            </div>
        </div>
    </body>
</html>

<script>
// При выборе даты изменяем вывод в таблице курсов
function changeDate(){
    trHTML = '';
    $.ajax({
        url:      "./rest/getcurrency.php",
        data:     "setData="+$("#setData").val(),  
        type:     "POST",
        dataType: "html",
        success: function(response) {
                
            $('#valuteCurrency tr#tr').remove();
        	result = $.parseJSON(response);
            $.each(result, function (i, item) {
               trHTML += '<tr id="tr"><td>' + item[4] + '</td><td>' + item[5] + '</td></tr>';
            });
            $('#valuteCurrency').append(trHTML);
            if(trHTML == '')
                $('#valuteCurrency').append('<tr id="tr"><td colspan="2">Данные о курсах на текущую дату отсутствуют</td></tr>');
            
            setDate = new Date($("#setData").val());
            dop = '';
            if((setDate.getMonth()+1)<10) dop = '0';
            
            $('#nametbl').html('Курсы валют на ' + setDate.getDate() + '.' + dop + (setDate.getMonth()+1) + '.' + setDate.getFullYear());
        },
    	  error: function(response) {
            $('#result_form').html('Ошибка. Таблица курсов не заполнена.');
    	  }
   });
}

// загрузка курсов в БД, возможна только после очистки таблицы
function fillCurrency(countCurrency) {
    if(countCurrency!=0){
        $('#result_form').html('Необходимо очистить таблицу курсов');
    }else{
        $.ajax({
            url:      "./rest/fillcurrency.php",
            type:     "POST",
            dataType: "html",
            success: function(response) {
                $('#result_form').html('Таблица курсов заполнена успешно');
            },
              error: function(response) {
                $('#result_form').html('Ошибка. Таблица курсов не заполнена.');
              }
       });
       changeDate();
   }
}
// удаляем курсы перед загрузкой
function delCurrency(){
    $.ajax({
        url:      "./rest/delcurrency.php",
        type:     "POST",
        dataType: "html",
        success: function(response) {
            $('#result_clear').html('Данные о курсах валют удалены успешно');
        },
        error: function(response) {
            $('#result_clear').html('Ошибка очистки таблицы курсов.');
        }
   });
   changeDate();
}
</script>