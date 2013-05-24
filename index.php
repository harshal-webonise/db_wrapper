<?php

require_once('db_wrapper/DbWrapper.php');
echo '<pre>';
$conn = DbWrapper::getInstance("localhost", "root", "root", "test");

$r = $conn->select(array('fname'))
    ->from(array('users'))
    ->where(array('id'=>2))
    ->result();

print_r($r);

