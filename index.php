<?php
ini_set('display_errors', 1);

require_once('db_wrapper/DbWrapper.php');
echo '<pre>';
$conn = DbWrapper::getInstance();

echo  'List all organizations<br><br>';
$r = $conn->select('*')
    ->from(array('organizations'))
    ->result();

resultFormatter($r);
//
echo 'List 10 organization whose id is greater than 10<br><br>';
$r = $conn->select('*')
    ->from(array('organizations'))
    ->where(array('id>' => '10'))
    ->limit(10)
    ->result();
resultFormatter($r);

echo 'List Organization whose id is greater than 10 and less than equal to 50<br><br>';
$r = $conn->select('*')
    ->from(array('organizations'))
    ->where(array('id > ' => '10','id <=' => '50'))
    ->result();
resultFormatter($r);

echo 'List all organization who has bee created after 2013-02-10 00:00:00<br><br>';
$r = $conn->select('*')
    ->from(array('organizations'))
    ->where(array('created_on > ' => "'2013-02-10 00:00:00'"))
    ->result();
resultFormatter($r);

echo 'List all organizations who has id between 10 to 50 and its order should be descending by name<br><br>';
$r = $conn->select('*')
    ->from(array('organizations'))
    ->where(array('between' => array('id', '10', '50')))
    ->orderBy('name', 'DESC')
    ->result();
resultFormatter($r);

echo 'display information about organization whose id is 70<br><br>';
$r = $conn->select('*')
    ->from(array('organizations'))
    ->where(array('id=' => '70'))
    ->result();

resultFormatter($r);

echo 'return a count of users per organization with organization name<br><br>';
$r = $conn->select(array('organizations.name','count(users.id)'))
     ->from(array('organizations','users'))
     ->where(array('organizations.id='=>'users.organisation_id'))
     ->groupBy('organizations.name')
     ->result();

resultFormatter($r);

echo 'display information about organization whose name is "Org Name 30"<br><br>';
$r = $conn->select('*')
    ->from(array('organizations'))
    ->where(array('name=' => "'Org Name 30'"))
    ->result();

resultFormatter($r);

echo 'display all the users of organization_id 30<br><br>';
$r = $conn->select('*')
    ->from(array('users'))
    ->where(array('organisation_id=' => "30"))
    ->result();

resultFormatter($r);

//$conn->save('users', array('fname=' => "'abc'", "lname=" => "'xyz'"), array('id=' => '20'));
//echo $conn->getQuery();
//$conn->delete('users', array('city='=>"'City7'"));
//print_r($r);

//$conn->update('users', array('fname='=>"'test'"), array('id='=>'1'));

function resultFormatter($records) {

    echo '<br><br><table>';
    foreach ($records as $record) {
        echo '<tr>';
        foreach ($record as $field) {
            echo "<td>$field</td>";
        }
        echo '</tr>';
    }
    echo '</table>';

}