<?php

require_once('db_wrapper/DbWrapper.php');
echo '<pre>';
$conn = DbWrapper::getInstance("localhost", "root", "root", "test");

// List all organizations
//$r = $conn->select('*')
//    ->from(array('organizations'))
//    ->result();
//
//print_r($r);

// List 10 organization whose id is greater than 10
//$r = $conn->select('*')
//    ->from(array('organizations'))
//    ->where(array('id > ' => '10'))
//    ->limit(10)
//    ->result();
//

// List Organization whose id is greater than 10 and less than equal to 50
//$r = $conn->select('*')
//    ->from(array('organizations'))
//    ->where(array('id > ' => '10','id <=' => '50'))
//    ->result();

// List all organization who has bee created after 2013-02-10 00:00:00
//$r = $conn->select('*')
//    ->from(array('organizations'))
//    ->where(array('created_on > ' => "'2013-02-10 00:00:00'"))
//    ->result();

//List all orders who has id between 10 to 50 and its orders should be descending by name


//display information about organization whose id is 70
//$r = $conn->select('*')
//    ->from(array('organizations'))
//    ->where(array('id=' => "70"))
//    ->result();

//display information about organization whose name is "Org Name 30"
//$r = $conn->select('*')
//    ->from(array('organizations'))
//    ->where(array('name like' => "'Org Name 30'"))
//    ->result();

//display all the users of organization_id 30
//$r = $conn->select('*')
//    ->from(array('users'))
//    ->where(array('organisation_id=' => "30"))
//    ->result();

//return a count of users per organization with organization name
$r = $conn->select(array('COUNT()'))
    ->from(array('users'))
    ->where(array('organisation_id=' => "30"))
    ->result();


//$conn->delete('users', array('OR' => array('fname=' => "'sushant'", 'id=' => '2')));
//echo $conn->getQuery();
print_r($r);