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

require_once cot_langfile('komus_reports', 'plug');

function getYesNo( $value ) {
	$ret = '';
	if (!empty($value)) {
		$ret = ($value == 1) ? 'x' : '';
	}
	return $ret;
}

function get_reference_item($name, $value) {
    global $db_x;
    echo $value;
}

function get_date_data($date_time)
{
    $data = explode(' ', $date_time);
    $date = explode('-', $data[0]);
    $time = explode(':', $data[1]);
    return array_merge($date, $time);
}

function getStatusItem($ref_id) {
    global $db, $db_x;

    if (empty($ref_id)) {
        return '';
    } else {
        $sql_ref_string = "
            SELECT title
            FROM {$db_x}komus_statuses
            WHERE id = $ref_id
        ";
        $sql_ref = $db->query($sql_ref_string);

        return $sql_ref->fetchColumn();
    }
}

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

function format_phone($phone) {
    if (empty($phone)) {
        return '';
    }
    if (strlen($phone) == 7) {
        sscanf($phone, "%3s%4s", $prefix, $exchange);
    } elseif (strlen($phone) == 10) {
        sscanf($phone, "%3s%3s%4s", $area, $prefix, $exchange);
    } elseif (strlen($phone) > 10) {
        if(substr($phone, 0, 1) == '1') {
            sscanf($phone, "%1s%3s%3s%4s", $country, $area, $prefix, $exchange);
        } else{
            sscanf($phone, "%3s%3s%4s%s", $area, $prefix, $exchange, $extension);
        }
    } else {
        return "Неизвестный формат номера: $phone";
    }
    $out = '';
    $out .= isset($country) ? $country.' ' : '';
    $out .= isset($area) ? '(' . $area . ') ' : '';
    $out .= $prefix . '-' . $exchange;
    $out .= isset($extension) ? ' x' . $extension : '';
    return $out;
}

function clear_simbol($text) {
	$poor_string = array('==','=-','=');
	$out_text = str_replace($poor_string,'',$text);
	
	return $out_text;
}

?>
