<?php
//Завершение
        $sql_call_string = "
            SELECT contacts.*, calls.call_status
            FROM {$db_x}komus_contacts contacts
            LEFT JOIN {$db_x}komus_calls calls ON calls.contact_id = contacts.id
            WHERE contacts.id = $contact_id
        ";
        $sql_call        = $db->query($sql_call_string);
        $call_data       = $sql_call->fetch();        
        
        //Недозвоны
        if ($call_data['status'] == 1 && $call_data['count_calls'] < 3) {        	      	
        	$update_data['is_block'] = 1; 
        	$call_data['count_calls']++;
        	$update_data['count_calls'] = $call_data['count_calls'];
        } 
         
        $sql_data_update = $db->update($db_x . 'komus_contacts', $update_data, 'id = ' . $contact_id);
        
        $calls["count_calls"] = $call_data['count_calls'];
        $calls["status"]      = $call_data['status'];
        $calls["status_int"]  = $call_data['status_int'];
        $calls["recall_time"] = $call_data['data_recall'];
        
        $sql_status_update = $db->update($db_x . 'komus_calls', $calls, 'id = ' . $_SESSION['call_id']);
        
        //Счетчик звонков
        if (CF_TYPE_PROJECT) {	               
           include_once cot_incfile("komus", "plug", "count");
           CountCalls($usr['id'], $call_data["status"]);
        }   
      
?>