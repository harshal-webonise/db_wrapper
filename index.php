<?php

require_once('db_wrapper/DbWrapper.php');
echo '<pre>';
$conn = DbWrapper::getInstance("localhost", "root", "root", "test");

$r = $conn->select()
    ->from(array('users'))
    ->limit(2,10)
    ->result();

print_r($r);

