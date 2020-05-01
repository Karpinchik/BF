<?php
require 'db_data.php';

function getLastId()
{
    global $pdo;

    $stmt = $pdo->query("SELECT MAX(id) FROM price");
    $id_max_inDB = $stmt->fetch();
    return $id_max_inDB['MAX(id)'] ? $id_max_inDB['MAX(id)'] : 1;
}


