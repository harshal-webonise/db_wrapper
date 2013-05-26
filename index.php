<?php

require_once('db_wrapper/DbWrapper.php');
echo '<pre>';
$conn = DbWrapper::getInstance("localhost", "root", "root", "test");
$r = $conn->select('*')
    ->from(array('organizations'))
    ->result();

print_r($r);

