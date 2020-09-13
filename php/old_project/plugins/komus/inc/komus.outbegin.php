<?php
 // ID записи перезвонов и недозвонов
 $base_id = cot_import('id', 'G', 'INT');
 
 $time_now =  date('H:i:s', $sys['now_offset'] + $cfg['defaulttimezone'] * 3600);
 //Выборка записи для обзвона
    //выбираем свободные записи is_block = 1
    if ($_SESSION['filtr']) {
    	$filtr = $_SESSION['filtr'];
    } else {
    	$filtr = 0;
    }
    //Фильтр
    $sql_filtr_string = "
            SELECT title     
            FROM {$db_x}komus_filtr
            WHERE id = {$filtr}
        ";

    $sql_filtr  = $db->query($sql_filtr_string);
    $filtr_name = $sql_filtr->fetchColumn();
    
    if ($_SESSION['filtr']) {
    	$filtrSql = " AND City = '" . $filtr_name . "'";
    }         
    ///////
    
    if ($base_id == null) {
        $sql_base_string = "
            SELECT *
            FROM {$db_x}komus_contacts            
            WHERE is_block = 1 AND status = 0{$filtrSql}
            LIMIT 1
        ";
        $sql_base        = $db->query($sql_base_string);
        $base_data       = $sql_base->fetch();    
        
        //Все записи прошли
        if ($base_data['id'] == NULL) {
    	//Выбираем недозвоны
    	 $sql_base_string = "
            SELECT *
            FROM {$db_x}komus_contacts            
            WHERE (is_block = 1 AND status = 1 AND count_calls < 3{$filtrSql})  
            ORDER BY count_calls LIMIT 1
         ";
    	 $sql_base        = $db->query($sql_base_string);
         $base_data       = $sql_base->fetch();
       }
    } else {
    	//Перезвоны
    	$sql_base_string = "
            SELECT *
            FROM {$db_x}komus_contacts            
            WHERE id = {$base_id} 
            
        ";
    $sql_base        = $db->query($sql_base_string);
    $base_data       = $sql_base->fetch();
    }
   
    //Запись использована
    if ($base_data['id'] != NULL) {    	
        $update_data['is_block'] = 0;
        $sql_data_update = $db->update($db_x . 'komus_contacts', $update_data, 'id = ' . $base_data['id']);
    }   
    
    $contact_id = $base_data['id'];
?>