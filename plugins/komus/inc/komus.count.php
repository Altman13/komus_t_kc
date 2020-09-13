<?php
 function CountCalls($user_id, $status_call) {
 	global $db, $db_x;
    $operator_id = $user_id;
    $countArr = array("nocall" => 0, "recall" => 0, "badcall" => 0, "anketa1" => 0, "anketa2" => 0, "anketa3" => 0, "anketa5" => 0);
    $sql_count_string = "
            SELECT *
            FROM {$db_x}komus_count_calls
            WHERE operator = $operator_id
        ";
    $sql_count        = $db->query($sql_count_string);
    $count_data       = $sql_count->fetch();

    switch ($status_call) {
 	   case 1:
 	      $countArr["nocall"] = 1;
 	   break;
 	
 	   case 4:
 	      $countArr["recall"] = 1;
 	   break;
 	
 	   case 5:
 	      $countArr["badcall"] = 0;
 	   break;
 	
 	   case 6:
 	      $countArr["anketa1"] = 1;
 	   break;
 	
 	   case 7:
 	      $countArr["anketa2"] = 1;
 	   break;
 	
 	   case 49:
 	      $countArr["anketa3"] = 1;
       break;
 	
 	   case 46:
 	      $countArr["anketa4"] = 1;
 	   break;
 	
 	   case 47:
 	      $countArr["anketa5"] = 1;
 	   break;
    }

    if (!$count_data) {
 	    $countArr["operator"] = $operator_id;
 	    $sql_insert = $db->insert($db_x . 'komus_count_calls', $countArr); 
    } else {
 	    foreach ($count_data as $key => $item) {
 	 	    $count_data[$key] += $countArr[$key];
 	    }
 	    $countArr["operator"] = $operator_id;
 	    $sql_update = $db->update($db_x . 'komus_count_calls', $count_data, 'operator = ' . $operator_id); 	
   }      
 } 
 
 function ShowCount($usr_id) {
 	global $db, $db_x, $t;
 	
 	$sql_count_string = "
            SELECT *
            FROM {$db_x}komus_count_calls
            WHERE operator = {$usr_id}
        ";
     $sql_count        = $db->query($sql_count_string);
     $count_data       = $sql_count->fetch();

     $t->assign(array(
                'KOMUS_QUANTITY_NOCALLS' =>  $count_data['nocall'],
                'KOMUS_QUANTITY_RECALLS' =>  $count_data['recall'],
                'KOMUS_QUANTITY_ANKETA1' =>  $count_data['anketa1'],
                'KOMUS_QUANTITY_ANKETA2' =>  $count_data['anketa2'],                
                'KOMUS_QUANTITY_ANKETA3' =>  $count_data['anketa3'],
                'KOMUS_QUANTITY_ANKETA4' =>  $count_data['anketa4'],
                'KOMUS_QUANTITY_ANKETA5' =>  $count_data['anketa5'],
                'KOMUS_COUNT_SHOW'       =>  true    
     ));
 }
?>