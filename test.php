<?php


require 'PHPExcel-1.8/Classes/PHPExcel.php';
require 'db_data.php';


$excel = PHPExcel_IOFactory::load('pricelist.xlsx');


    foreach ($excel->getWorksheetIterator() as $worksheet) {
        $lists[] = $worksheet->toArray();        // массив распарсенных строк из файла
    }

    array_shift($lists[0]);    // удалил первый массив с названиями колонок


foreach($lists as $list){
    print_r($lists);
//    echo '<table border="1">';
//    // Перебор строк
//    foreach($list as $row){
//        echo '<tr>';
//        // Перебор столбцов
//        foreach($row as $col){
//            echo '<td>'.$col.'</td>';
//        }
//        echo '</tr>';
//    }
//    echo '</table>';
}


