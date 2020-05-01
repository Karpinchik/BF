
<?php

require 'PHPExcel-1.8/Classes/PHPExcel.php';
require 'db_data.php';
require 'functions.php';

$excel = PHPExcel_IOFactory::load('pricelist.xlsx');
//$excel = PHPExcel_IOFactory::load('tt.xlsx');

$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false
];

$pdo = new PDO($dsn, $user, $password, $opt);
$counter = getLastId();             // получаю из базы последний id

function parsData()
{
    global $excel;
    global $pdo;
    global $counter;

    Foreach ($excel->getWorksheetIterator() as $worksheet) {
        $lists[] = $worksheet->toArray();        // массив распарсенных строк из файла
    }

    array_shift($lists[0]);    // удалил первый массив с названиями колонок
    foreach ($lists[0] as $list) {           // пробегаем по всем записям и записываем в базу с корректировками цены
        $sql = "INSERT INTO price(id, name, cost, cost_all, warehouse1, warehouse2, country) VALUES (:id, :name, :cost, :cost_all, :warehouse1, :warehouse2, :country)";
        $stmt = $pdo->prepare($sql);

        $params = [':id' => $counter, ':name' => $list[0], ':cost' => floatval($list[1]), ':cost_all' => intval($list[2]), ':warehouse1' => $list[3], ':warehouse2' => $list[4], ':country' => $list[5]];
        $stmt->execute($params);
        $counter++;
    }
}
//parsData();




//foreach($lists as $list){
//    echo '<table border="1">';
//    foreach($list as $row){
//        echo '<tr>';
//        // Перебор столбцов
//        foreach($row as $col){
//            echo '<td>'.$col. '</td>';
//        }
//        echo '</tr>';
//    }
//    echo '</table>';
//}



//$results = $stmt->fetchAll();
//echo '<pre>';
//print_r($results);
//echo '</pre>';

//while ($a = $stmt->fetch()) {
//    echo $a['country'];
//    echo '</br>';
//    foreach ($a as $row) {
//        echo $a['name'];
////        echo $row;
//        echo '</br>';
//    }
//}

?>


