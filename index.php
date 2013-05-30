<?php

require_once('db_wrapper/DbWrapper.php');
echo '<pre>';
$conn = DbWrapper::getInstance();

// List all organizations
$r = $conn->select('*')
    ->from(array('organizations'))
    ->result();

resultFormatter($r);

// List 10 organization whose id is greater than 10
$r = $conn->select('*')
    ->from(array('organizations'))
    ->where(array('id > ' => '10'))
    ->limit(10)
    ->result();
resultFormatter($r);

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

//$conn->save('users', array('fname' => "sushant","lname"=>"test"));
//echo $conn->getQuery();
//print_r($r);

function resultFormatter($records) {

    echo '<table>';
    foreach($records as $record){
        echo '<tr>';
            foreach($record as $field){
                echo "<td>$field</td>";
            }
        echo '</tr>';
    }
    echo '</table>';

}