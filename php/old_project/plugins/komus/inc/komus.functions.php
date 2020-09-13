<?php
/**
 * Contact Plugin API
 *
 * @package contact
 * @version 2.1.0
 * @author Seditio.by & Cotonti Team
 * @copyright (c) 2008-2011 Seditio.by and Cotonti Team
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_langfile('komus', 'plug');

function getReferenceItem($ref_id) {
    global $db, $db_x;

    if (empty($ref_id)) {
        return '';
    } else {
        $sql_ref_string = "
            SELECT title
            FROM {$db_x}komus_references_items
            WHERE id = $ref_id
        ";
        $sql_ref = $db->query($sql_ref_string);

        return $sql_ref->fetchColumn();
    }
}

function send_mail( $param = false) {
    $mail_ok = true;
    $mail_heders = "From: tver-crm@komus.net\n";
    $mail_heders.= "Content-Type: text/html; charset=\"utf-8\"\n";
    $mail_heders.= "Content-Transfer-Encoding: 8bit\n";
       
     if( mail( $param["mail_to"], $param["mail_subject"], $param["message"], $mail_heders ) ) {  
        $mail_ok = true;
     } else {
       $mail_ok = false;
     }
   return $mail_ok; 
  }

function get_reference_select($reference_id, $alias, $call_id = '') {
    global $db, $db_x;
    
    $sql_call_string = "SELECT $alias FROM {$db_x}komus_data WHERE id = $call_id";
    $sql_call = $db->query($sql_call_string);
    $value = $sql_call->fetchColumn();
    
    $sql_reference_string = "SELECT id, title FROM {$db_x}komus_references_items WHERE reference_id = $reference_id ORDER BY sort";
    $sql_reference = $db->query($sql_reference_string);
    $reference_html = <<<HTML
        <select name="{$alias}">\n
HTML;
    foreach ($sql_reference->fetchAll() as $reference) {
        $selected = ($value == $reference['id']) ?
            ' selected="true"' :
            '';
        $reference_html .= <<<HTML
          <option value="{$reference['id']}"{$selected}>{$reference['title']}</option>\n
HTML;
    }
    $reference_html .= <<<HTML
    </select>\n
HTML;
    return $reference_html;
}

function komus_selectbox_date($utime = '', $mode = 'long', $name = '', $max_year = 2015, $min_year = 1935, $usertimezone = true, $check18 = false)
{
    global $L, $R, $usr;
    $name = preg_match('#^(\w+)\[(.*?)\]$#', $name, $mt) ? $mt[1] : $name;

    $utime = ($usertimezone && $utime > 0) ? ($utime + $usr['timezone'] * 3600) : $utime;

    if ($utime == 0)
    {
        list($s_year, $s_month, $s_day, $s_hour, $s_minute) = array(null, null, null, null, null);
    }
    else
    {
        list($s_year, $s_month, $s_day, $s_hour, $s_minute) = explode('-', @date('Y-m-d-H-i', $utime));
    }
    $months = array();
    $months[1] = $L['January'];
    $months[2] = $L['February'];
    $months[3] = $L['March'];
    $months[4] = $L['April'];
    $months[5] = $L['May'];
    $months[6] = $L['June'];
    $months[7] = $L['July'];
    $months[8] = $L['August'];
    $months[9] = $L['September'];
    $months[10] = $L['October'];
    $months[11] = $L['November'];
    $months[12] = $L['December'];

    $year = cot_selectbox($s_year, $name.'[year]', range($max_year, $min_year));
    $month = cot_selectbox($s_month, $name.'[month]', array_keys($months), array_values($months));
    $day = cot_selectbox($s_day, $name.'[day]', range(1, 31));

    $range = array();
    for ($i = 0; $i < 24; $i++)
    {
        $range[] = sprintf('%02d', $i);
    }
    $hour = cot_selectbox($s_hour, $name.'[hour]', $range);

    $range = array();
    for ($i = 0; $i < 60; $i++)
    {
        $range[] = sprintf('%02d', $i);
    }

    $minute = cot_selectbox($s_minute, $name.'[minute]', $range);

    $rc = empty($R["input_date_{$mode}"]) ? 'input_date' : "input_date_{$mode}";
    $rc = empty($R["input_date_{$name}"]) ? $rc : "input_date_{$name}";
    
    $check18_field = ($check18) ?
        cot_inputbox('hidden', $name . '[check18]', '1') :
        '';

    $result = cot_rc($rc, array(
        'day' => $day,
        'month' => $month,
        'year' => $year,
        'hour' => $hour,
        'minute' => $minute,
        'check18' => $check18_field
    ));

    return $result;
}

function get_contact_id($id)
{
    global $db, $db_x;
    
    $sql_contact_string = "SELECT contact_id FROM {$db_x}komus_calls WHERE id = $id";
    $sql_contact = $db->query($sql_contact_string);
    return $sql_contact->fetchColumn();
}

function get_calls_quantity($id)
{
    global $db, $db_x;
    
    $sql_call_string = "SELECT COUNT(*) count_calls FROM {$db_x}komus_calls WHERE contact_id = $id";
    $sql_call = $db->query($sql_call_string);
    return $sql_call->fetchColumn();
}

function unix_to_mysql($time)
{  
    $date_time = explode(' ', $time);
    $date = explode('.', $date_time[0]);
    return $date[2] . '-' . $date[1] . '-' . $date[0] . ' ' . $date_time[1];
} 

function getReferenceSelect($refID, $name, $val = 0, $addEmpty = false) {
    global $db, $db_x;

    $sql_ref_string = "
        SELECT id, title 
        FROM {$db_x}komus_references_items 
        WHERE reference_id = $refID
        ORDER BY sort
    ";
    $sql_ref = $db->query($sql_ref_string);

    $out = <<<HTML
    <select name={$name}>\n
HTML;
    foreach ($sql_ref as $item) {
        $selected = ($item['id'] == $val) ?
            ' selected="selected"' :
            '';
        $out .= <<<HTML
      <option value="{$item['id']}"{$selected}>{$item['title']}</option>\n
HTML;
    }
    $out .= <<<HTML
    </select>\n
HTML;
    return $out;
}



?>
