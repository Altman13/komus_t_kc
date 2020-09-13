<?php
function Calls2(){
global $db, $db_x, $t;	

//Фильтры
if ($_SERVER["REQUEST_METHOD"] == "POST") {		
	$calls_data = cot_import('calls', 'P', 'ARR');	
    $_SESSION['calls_data'] = $calls_data['year'] . '-' . $calls_data['month'] . '-' . $calls_data['day'];          
    
    $_SESSION['filtr_status'] = cot_import('filtr_status', 'P', 'TXT');        
}

//Фильтр по дате звонка
if (!empty($_SESSION['calls_data']) && $_SESSION['calls_data'] != '--') {
	$calls_data_filtr = " AND creation_time >= '".$_SESSION['calls_data']."  00:00:00' AND creation_time <= '".$_SESSION['calls_data']."  23:59:59'";
	$tmp_date = explode('-', $_SESSION['calls_data']);
	$calls_data_ts = mktime(0,0,0,$tmp_date[1],$tmp_date[2],$tmp_date[0]);
} else {
	$calls_data_filtr = '';
	$calls_data_ts = 0;
}

if ($_SESSION['filtr_status']) {
	    if ($_SESSION['filtr_status'] == "1") {
	    	$filtr_status = " status = 1 AND count_calls = 1";
	    } elseif ($_SESSION['filtr_status'] == "2"){
	    	$filtr_status = " status = 1 AND count_calls = 2";
	    } else {
	    	$filtr_status = " status = " . $_SESSION['filtr_status'];
	    }
    	
} 

//Фильтр по статусу звонка
$statusArr = array();
$statusArr[1] = "Недозвон1";
$statusArr[2] = "Недозвон2";
$statusArr[4] = "Перезвон";

$field_status = "<select name=\"filtr_status\">\n";
$field_status .= "<option value=\"0\"> </option>\n";
foreach ($statusArr as $key => $st){
    $selected = ($_SESSION['filtr_status'] == $key) ?
                     ' selected="true"' :
                    '';
                                $field_status .= <<<HTML
    <option value="{$key}"{$selected}>{$st}</option>\n
HTML;
                        
}
$field_status .= "</select>\n"; 
   
/////////////////////////////
	
    $t->assign(array(      
        'KOMUS_CALLS_FILTR'             => cot_selectbox_date($calls_data_ts, 'short', 'calls'),
        'KOMUS_SPLASH_STATUS'           => $field_status       
    ));
   
   //Записи для перезвона
    if (!empty($calls_data_filtr) && !empty($filtr_status) ) {    	
    $sql_base_string = "
            SELECT contacts.*, DATE_FORMAT(creation_time, '%d.%m.%Y %H:%i') data_call1, DATE_FORMAT(data_recall, '%d.%m.%Y %H:%i') data_recall1,
                   u.user_lastname, u.user_firstname        
            FROM {$db_x}komus_contacts AS contacts
            LEFT JOIN {$db_x}users AS u ON u.user_id = contacts.user_id            
            WHERE {$filtr_status} {$calls_data_filtr}
            ORDER BY creation_time
        ";
    $sql_base        = $db->query($sql_base_string);
   
     foreach ($sql_base->fetchAll() as $item) {
     	$status  = getReferenceItem($item['status']);
     	$t->assign(array(
     	         'KOMUS_CALLS_FIO'                  =>  $item['Last_Name'].' '.$item['Patronymic'].' '.$item['First_Name'],                      	              	           	         
     	         'KOMUS_CALLS_PHONE'                =>  $item['phone'],
     	         'KOMUS_CALLS_CALL'                 =>  $item['data_call1'],
     	         'KOMUS_CALLS_RECALL'               =>  $item['data_recall1'],
     	         'KOMUS_CALLS_COMMENT'              =>  $item['comment'],
     	         'KOMUS_CALLS_STATUS'               =>  "Перезвонить",
     	         'KOMUS_OPERATOR_NAME'              =>  $item['user_lastname'] . ' ' . $item['user_firstname'],
     	         'KOMUS_CALLS_URL'                  =>  cot_url('plug', 'e=komus&mode=new_call&project=1&id='.$item['id'])     
             ));
              
        $t->parse('MAIN.OPERATOR.CALLS2');  
     }
    }

}    
?>