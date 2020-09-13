<?php
function Filter() {
	global $db, $db_x, $t;
	
	$sql_filtr_string = "
            SELECT *     
            FROM {$db_x}komus_filtr
            ORDER BY title
        ";
     $sql_filtr        = $db->query($sql_filtr_string);
     $field_html = "<select name=\"filtr\">\n";
     $field_html .= "<option value=\"0\"> </option>\n";
     foreach ($sql_filtr->fetchAll() as $field) {
          $selected = ($_SESSION['filtr'] == $field['id']) ?
                     ' selected="true"' :
                    '';
                                $field_html .= <<<HTML
    <option value="{$field['id']}"{$selected}>{$field['title']}</option>\n
HTML;
                            }
                            $field_html .= "</select>\n"; 

   $t->assign(array(
       'KOMUS_SPLASH_FILTER'           => "Город: ".$field_html,
       'KOMUS_FILTER_SHOW'             => true
   ));                            	
}

?>