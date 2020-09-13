<?php
function Calls() {
	global $db, $db_x, $t;
	
	if (CF_CALLS_PE) {		
	 //Записи для перезвона
    $sql_base_string = "
            SELECT contacts.*, DATE_FORMAT(creation_time, '%d.%m.%Y %H:%i') data_call, DATE_FORMAT(data_recall, '%d.%m.%Y %H:%i') data_recall1,
                   u.user_lastname, u.user_firstname        
            FROM {$db_x}komus_contacts AS contacts
            LEFT JOIN {$db_x}users AS u ON u.user_id = contacts.user_id                      
            WHERE contacts.status = 4
            ORDER BY data_recall
        ";
    $sql_base        = $db->query($sql_base_string);
   
     foreach ($sql_base->fetchAll() as $item) {     
     	$status  = getReferenceItem($item['status']);
     	$t->assign(array(
     	         'KOMUS_CALLS_FIO'                  =>  $item['Last_Name'].' '.$item['Patronymic'].' '.$item['First_Name'],
     	         'KOMUS_CALLS_CITY'                 =>  $item['City'],       	                      	              	           	         
     	         'KOMUS_CALLS_RECALL'               =>  $item['data_recall1'],
     	         'KOMUS_CALLS_COMMENT'              =>  $item['comment'],
     	         'KOMUS_CALLS_STATUS'               =>  "Перезвонить",
     	         'KOMUS_OPERATOR_NAME'              =>  $item['user_lastname'] . ' ' . $item['user_firstname'],
     	         'KOMUS_CALLS_URL'                  =>  cot_url('plug', 'e=komus&mode=new_call&project=1&id='.$item['id']),
     	         'KOMUS_CALLS_FLAG'                 =>  CF_CALLS_PE      
             ));
              
        $t->parse('MAIN.OPERATOR.CALLS');  
     }
	}
	
	if (CF_CALLS_NE) {
     //Статус недозвоны
    $sql_base_string = "
            SELECT contacts.*, DATE_FORMAT(creation_time, '%d.%m.%Y %H:%i') data_call, DATE_FORMAT(data_recall, '%d.%m.%Y %H:%i') data_recall1,
                   u.user_lastname, u.user_firstname        
            FROM {$db_x}komus_contacts AS contacts
            LEFT JOIN {$db_x}users AS u ON u.user_id = contacts.user_id                                          
            WHERE contacts.status = 1 AND contacts.count_calls < 3
            ORDER BY creation_time
        ";
    $sql_base        = $db->query($sql_base_string);
   
     foreach ($sql_base->fetchAll() as $item) {
     	$status  = getReferenceItem($item['status']);
     	$t->assign(array(
     	         'KOMUS_NOCALLS_FIO'                  =>  $item['Last_Name'].' '.$item['Patronymic'].' '.$item['First_Name'],
     	         'KOMUS_NOCALLS_CITY'                 =>  $item['City'], 
     	         'KOMUS_NOCALLS_COMMENT'              =>  $item['comment'],
     	         'KOMUS_NOCALLS_STATUS'               =>  $status,
     	         'KOMUS_NOCALLS_QANTITY'              =>  $item['count_calls'],
     	         'KOMUS_NOCALLS_RECALL'               =>  $item['data_call'],
     	         'KOMUS_NOOPERATOR_NAME'              =>  $item['user_lastname'] . ' ' . $item['user_firstname'],
     	         'KOMUS_NOCALLS_URL'                  =>  cot_url('plug', 'e=komus&mode=new_call&project=1&id='.$item['id']),
     	         'KOMUS_NOCALLS_FLAG'                 =>  CF_CALLS_NE      
             ));
              
        $t->parse('MAIN.OPERATOR.NOCALLS');  
     }    
	}
}
?>