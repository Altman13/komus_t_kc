<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/datas/config.php';
$dsn = 'mysql:host='.$cfg['mysqlhost'].'; dbname='.$cfg['mysqldb'];
$options = array( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 
$db = new PDO($dsn, $cfg['mysqluser'], $cfg['mysqlpassword'], $options);

$sql_selects_call_date_now="SELECT cot_komus_contacts.company_name as name, 
cot_komus_contacts.fio, cot_komus_contacts.email, cot_komus_contacts.phone, 
cot_komus_calls.begin_time < NOW() AS date_call
FROM cot_komus_contacts, cot_komus_calls
WHERE cot_komus_calls.contact_id=cot_komus_contacts.id
AND cot_komus_calls.begin_time<NOW()";
$call_date_now = $db->query($sql_selects_call_date_now);
$test=$call_date_now->fetchAll();
var_dump( $test);
?>