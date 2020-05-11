<?php
require 'PHPExcel-1.8/Classes/PHPExcel.php';
require 'db_data.php';

$excel = PHPExcel_IOFactory::load('pricelist.xlsx');
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false
];
$pdo = new PDO($dsn, $user, $password, $opt);

function getLastId()    // получаю последний id в бд
{
    global $pdo;

    $stmt = $pdo->query("SELECT MAX(id) FROM price");
    $id_max_inDB = $stmt->fetch();
    return $id_max_inDB['MAX(id)'] ? $id_max_inDB['MAX(id)'] : 1;
}

$counter = getLastId();             // получаю из базы последний id

function parsData()
{
    global $excel;
    global $hat;
    global $pdo;
    global $counter;

    foreach ($excel->getWorksheetIterator() as $worksheet) {
        $lists[] = $worksheet->toArray();        // массив распарсенных строк из файла
    }

    array_shift($lists[0]);    // удалил первый массив с названиями колонок
    foreach ($lists[0] as $list) {           // пробегаем по всем записям и записываем в базу с корректировками цены
        $sql = "INSERT INTO price(id, name, cost, cost_all, warehouse1, warehouse2, country, notes) VALUES (:id, :name, :cost, :cost_all, :warehouse1, :warehouse2, :country, :notes)";
        $stmt = $pdo->prepare($sql);

        $params = [':id' => $counter, ':name' => $list[0], ':cost' => floatval($list[1]), ':cost_all' => intval($list[2]), ':warehouse1' => $list[3], ':warehouse2' => $list[4], ':country' => $list[5], ':notes'=>''];
        $stmt->execute($params);
        $counter++;
    }
}


function getDataDB(){            // получаю все данные из базы
    global $pdo;
    global $hat;
    $stmt = $pdo->query('SELECT * FROM price');
    $data_for_table=[];

    while($result = $stmt->fetch()) {
        $data_for_table[] = $result;
    }

    foreach ($data_for_table as $kay=>$value){
        $cost_max[] = $value['cost'];
        if($value['cost_all'] !== 0) {
            $cost_all_min[] = $value['cost_all'];
        }
    }

    $cost_max = max($cost_max);
    $cost_all_min = min($cost_all_min);

    echo '<table border="1">';                  // вся таблица
        foreach ($data_for_table as $row) {
            echo '<tr data-attr-tr=' . $row['id'] . '>';
                foreach($row as $item=>$col){
                    if($row['cost'] === $cost_max) {
                        echo '<td style="background-color: red" data-' . $item . ' =' . $col . '>' . $col . '</td>';
                    } else if($row['cost_all'] === $cost_all_min) {
                        echo '<td style="background-color: green" data-' . $item . ' =' . $col . '>' . $col . '</td>';
                    } else {
                        echo '<td data-' . $item . ' =' . $col . '>' . $col . '</td>';
                    }
                }
            echo '</tr>';
        }
    echo '</table>';

    $warehouse1 = [];
    $warehouse2 = [];
    $cost_avg = [];
    $cost_all_avg = [];

    foreach ($data_for_table as $value){
        if($value['warehouse1']) {
            $warehouse1[] = $value['warehouse1'];
        }
    }

    foreach ($data_for_table as $value){
        if($value['warehouse2']) {
            $warehouse2[] = $value['warehouse2'];
        }
    }

    foreach ($data_for_table as $value){
        if($value['cost']) {
            $cost_avg[] = $value['cost'];
        }
    }

    foreach ($data_for_table as $value){
        if($value['cost_all']) {
            $cost_all_avg[] = $value['cost_all'];
        }
    }

    // Вывести под таблицей общее количество товаров на Складе1 и на Складе2
    echo 'На складе 1 всего товаров: ' . array_sum($warehouse1) . '</br>';
    echo 'На складе 2 всего товаров: ' . array_sum($warehouse2) . '</br>';
    echo '</br>';

    // Вывести под таблицей среднюю стоимость розничной цены товара
    echo 'Средняя стоимость розничной цены: ' . array_sum($cost_avg)/sizeof($cost_avg) . '</br>';

    // Вывести под таблицей среднюю стоимость оптовой цены товара
    echo 'Средняя стоимость розничной цены: ' . intval(array_sum($cost_all_avg)/sizeof($cost_all_avg)) . '</br>';
    echo '</br>';

}




