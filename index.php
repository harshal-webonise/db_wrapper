<?php

require_once('db_wrapper/DbWrapper.php');

$conn = DbWrapper::getInstance("localhost","root","webonise6186","db_wrapper");

$conn->select(array('first_name','last_name'))
     ->from(array('users'))
     ->where();


