<?php
require 'db_data.php';

$cost_min = intval($_POST['cost_min']);
$cost_max = intval($_POST['cost_max']);
$type_cost = $_POST['type_cost'];
$items = intval($_POST['items']);
$more_or_less = $_POST['more_or_less'];

$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false
];
$pdo = new PDO($dsn, $user, $password, $opt);
$data_for_table = [];

if(!empty($_POST['cost_min']) and !empty($_POST['cost_max']) and !empty($_POST['items'])) {
    if ($type_cost == 'Розничная цена' and $more_or_less == 'Более') {
        $stmt = $pdo->prepare('SELECT * FROM price WHERE (cost BETWEEN :cost_min AND :cost_max) AND (warehouse1>:items)');
        $params = [':cost_min' => $cost_min, ':cost_max' => $cost_max, ':items' => $items];
        $stmt->execute($params);
        while ($result = $stmt->fetch()) {
            $data_for_table[] = $result;
        }
    } else if ($type_cost == 'Розничная цена' and $more_or_less == 'Менее') {
        $stmt = $pdo->prepare('SELECT * FROM price WHERE (cost BETWEEN :cost_min AND :cost_max) AND (warehouse1<:items)');
        $params = [':cost_min' => $cost_min, ':cost_max' => $cost_max, ':items' => $items];
        $stmt->execute($params);
        while ($result = $stmt->fetch()) {
            $data_for_table[] = $result;
        }
    } else if ($type_cost == 'Оптовая цена' and $more_or_less == 'Более') {
        $stmt = $pdo->prepare('SELECT * FROM price WHERE (cost BETWEEN :cost_min AND :cost_max) AND (warehouse1>:items)');
        $params = [':cost_min' => $cost_min, ':cost_max' => $cost_max, ':items' => $items];
        $stmt->execute($params);
        while ($result = $stmt->fetch()) {
            $data_for_table[] = $result;
        }
    } else if ($type_cost == 'Оптовая цена' and $more_or_less == 'Менее') {
        $stmt = $pdo->prepare('SELECT * FROM price WHERE (cost BETWEEN :cost_min AND :cost_max) AND (warehouse1<:items)');
        $params = [':cost_min' => $cost_min, ':cost_max' => $cost_max, ':items' => $items];
        $stmt->execute($params);
        while ($result = $stmt->fetch()) {
            $data_for_table[] = $result;
        }
    }
}

if(count($data_for_table) !== 0 ) {
    echo json_encode($data_for_table);
} else {
    $data_for_table[] = 'no content';
    echo json_encode($data_for_table);
}

