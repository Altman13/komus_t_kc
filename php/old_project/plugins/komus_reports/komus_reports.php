<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=standalone
[END_COT_EXT]
==================== */

/**
 * Komus Reports Plugin for Cotonti CMF
 *
 * @package komus_reports
 * @version 1.0.0
 * @author Larion Lushnikov
 * @copyright (c) Komus
 * @license BSD
 */

error_reporting(0);
defined('COT_CODE') or die('Wrong URL');

/*========================================*/
$max_calls = 4;
/*========================================*/

require_once cot_incfile('forms');
require_once cot_langfile('komus');
require_once cot_langfile('komus_reports');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('users', 'w');

$gr_operator_groups = array(5, 6);
$operator_groups = array();

$gr_operator_access = false;
$operator_access = false;

$sql_user_string = "SELECT gru_groupid FROM {$db_x}groups_users WHERE gru_userid = {$usr['id']}";
$sql_user = $db->query($sql_user_string);

foreach ($sql_user->fetchAll() as $group) {
    if (in_array($group['gru_groupid'], $gr_operator_groups)) {
        $gr_operator_access = true;
    }
    if (in_array($group['gru_groupid'], $operator_groups)) {
        $operator_access = true;
    }
}

$plugin_title = $L['komus_reports_title'];

$mode = cot_import('mode', 'G', 'ALP');

switch ($mode) {
case 'report':
    $rep = cot_import('rep', 'G', 'INT');
    require_once('plugins/komus_reports/inc/komus_reports.xls_tpl.php');
    
    if (empty($_POST)) {
        $t->assign(array(
            'KOMUS_CREATE_ACTION'     => cot_url('plug', 'e=komus_reports&mode=report&rep=' . $rep),
            'KOMUS_CREATE_FROM_DATE'  => cot_selectbox_date($sys['now_offset'], 'short', 'from'),
            'KOMUS_CREATE_TO_DATE'    => cot_selectbox_date($sys['now_offset'], 'short', 'to')
        ));
        $t->parse('MAIN.REPORT');
    } else {
        $from = cot_import('from', 'P', 'ARR');
        $from_sql = $from['year'] . '-' . $from['month'] . '-' . $from['day'] . ' 00:00:00';
        $to = cot_import('to', 'P', 'ARR');
        $to_sql = $to['year'] . '-' . $to['month'] . '-' . $to['day'] . ' 23:59:59';
        $current_user_sql = ($operator_access) ?
            ' AND d.user_id = ' . $usr['id'] : 
            '';

        $sql_references_string = "SELECT id, title, value FROM {$db_x}komus_references_items";
        $sql_references = $db->query($sql_references_string);
        $references = array();
        foreach ($sql_references as $row) {
            $tmp = array();
            $tmp['title'] = $row['title'];
            $tmp['value'] = $row['value'];
            $references[$row['id']] = $tmp;
        }
            
        require_once 'Spreadsheet/Excel/Writer.php';

/*===========================================================*/
/*=============== Отчеты ====================================*/
/*===========================================================*/
        
        switch ($rep) {
/*---------------------------------------------*/
/*---------- Основной отчет -------------------*/
/*---------------------------------------------*/ 
        case 1:
            $rep_name = 'current';
            
            $sql_report_string = "
                SELECT contacts.*, DATE_FORMAT(contacts.creation_time, '%d.%m.%Y %H:%i') creation_time_,
				u.user_lastname, u.user_firstname
                FROM {$db_x}komus_contacts contacts
                LEFT JOIN {$db_x}users u ON u.user_id = contacts.user_id
                WHERE 
                    contacts.creation_time >= '$from_sql' 
                    AND contacts.creation_time <= '$to_sql'{$current_user_sql}
                    and contacts.inn is not null  
                   ORDER BY contacts.id
            ";
            $sql_report = $db->query($sql_report_string);

            $xls_filename = 'report_' . $rep_name . '.xls';

            $xls = new Spreadsheet_Excel_Writer('reports/' . $xls_filename);
            $xls->setVersion(8);
            $sheet =& $xls->addWorksheet(iconv('utf-8', 'windows-1251', 'Report'));
            $sheet->setInputEncoding('windows-1251');
            $xls->setCustomColor(10, 0, 255 ,255);
            //$sheet->setRow(0, 18.75);
            $formatHeader =& $xls->addFormat();
            $formatHeader->setBorder(1);
            $formatHeader->setHAlign('center');
            $formatHeader->setVAlign('vcenter');
            $formatHeader->setBold();
            $formatHeader->setTextWrap();
            $formatHeader->setFgColor(10);

            $formatHeader1 =& $xls->addFormat();
            $formatHeader1->setBorder(1);
            $formatHeader1->setHAlign('center');
            $formatHeader1->setVAlign('vcenter');
            $formatHeader1->setBold();
            $formatHeader1->setTextWrap();
            
            $formatCell =& $xls->addFormat();          
            $formatCell->setHAlign('center');
            $formatCell->setVAlign('top');
            $formatCell->setBorder(1);
            $formatCell->setTextWrap();
                       
            $sheet->setColumn(0, 15, 20);            
                                    
           
            $sheet->write(0, 0, iconv('utf-8', 'windows-1251', 'Дата\время'), $formatHeader);
			
                       
            $sheet->write(0, 1, iconv('utf-8', 'windows-1251', 'Контактный номер телефонан'), $formatHeader);
			$sheet->write(0, 2, iconv('utf-8', 'windows-1251', 'Суть обращения'), $formatHeader);
			$sheet->write(0, 3, iconv('utf-8', 'windows-1251', 'Комментарий'), $formatHeader);
			$sheet->write(0, 4, iconv('utf-8', 'windows-1251', 'Результат звонка'), $formatHeader);
            $sheet->write(0, 5, iconv('utf-8', 'windows-1251', 'Юридическое лицо'), $formatHeader);
            $sheet->write(0, 6, iconv('utf-8', 'windows-1251', 'ИНН партнера'), $formatHeader);
            $sheet->write(0, 7, iconv('utf-8', 'windows-1251', 'Номер лота'), $formatHeader);
            $sheet->write(0, 8, iconv('utf-8', 'windows-1251', 'Оператор'), $formatHeader);        
           
                                              
            $count = 1; 
            foreach ($sql_report as $key => $row) {            	
                $row_num = $key;                
                $sheet->write(1 + $row_num, 0, iconv('utf-8', 'windows-1251', $row['creation_time_']), $formatCell);
						
                $sheet->writeString(1 + $row_num, 1, iconv('utf-8', 'windows-1251', clear_simbol($row['phone'])), $formatCell);
				
				if ($row['is_type'] == 84) $is_type = getReferenceItem($row['is_type']). ' ('.$row['anothertxt'].')';
				else $is_type = getReferenceItem($row['is_type']);
				$sheet->write(1 + $row_num, 2, iconv('utf-8', 'windows-1251', $is_type), $formatCell);
				$sheet->write(1 + $row_num, 3, iconv('utf-8', 'windows-1251', clear_simbol($row['comment'])), $formatCell);
				
                $sheet->write(1 + $row_num, 4, iconv('utf-8', 'windows-1251', getReferenceItem($row['status'])), $formatCell);
				
				// if ($row['is_transfercall'] == 67) $is_transfercall = getReferenceItem($row['is_transfercall']).' на номер: '.$row['transfer_to'];
				// else $is_transfercall = getReferenceItem($row['is_transfercall']);
                //$sheet->write(1 + $row_num, 5, iconv('utf-8', 'windows-1251', $is_transfercall), $formatCell);
                /*88	ООО Стилус		
                89	ООО Канцпласт		
                90	ООО СмартОфис		
                91	ООО Интегра		
                107	ЦТО		
                108	Гедент		
                109	Профимаркет*/

                if($row['organization']=='88'){
                $sheet->write(1 + $row_num, 5, iconv('utf-8', 'windows-1251', clear_simbol('ООО Стилус')), $formatCell);
                }
                elseif($row['organization']=='89'){
                    $sheet->write(1 + $row_num, 5, iconv('utf-8', 'windows-1251', clear_simbol('ООО Канцпласт')), $formatCell);
                    }
                elseif($row['organization']=='90'){
                    $sheet->write(1 + $row_num, 5, iconv('utf-8', 'windows-1251', clear_simbol('ООО СмартОфис')), $formatCell);
                        }
                elseif($row['organization']=='91'){
                    $sheet->write(1 + $row_num, 5, iconv('utf-8', 'windows-1251', clear_simbol('ООО Интегра')), $formatCell);
                        }                                    
                elseif($row['organization']=='107'){
                    $sheet->write(1 + $row_num, 5, iconv('utf-8', 'windows-1251', clear_simbol('ЦТО')), $formatCell);
                        }                                    
                elseif($row['organization']=='108'){
                    $sheet->write(1 + $row_num, 5, iconv('utf-8', 'windows-1251', clear_simbol('Гедент')), $formatCell);
                        }                                    
                elseif($row['organization']=='109'){
                    $sheet->write(1 + $row_num, 5, iconv('utf-8', 'windows-1251', clear_simbol('Профимаркет')), $formatCell);
                        }                                    
                elseif($row['organization']=='125'){
                        $sheet->write(1 + $row_num, 5, iconv('utf-8', 'windows-1251', clear_simbol('МосКанц')), $formatCell);
                        }                                   
                elseif($row['organization']=='126'){
                        $sheet->write(1 + $row_num, 5, iconv('utf-8', 'windows-1251', clear_simbol('ДивоОфис')), $formatCell);
                        }                                   
                $sheet->write(1 + $row_num, 5, iconv('utf-8', 'windows-1251', clear_simbol($row['organization'])), $formatCell);
                $sheet->write(1 + $row_num, 6, iconv('utf-8', 'windows-1251', clear_simbol($row['inn'])), $formatCell);
                $sheet->write(1 + $row_num, 7, iconv('utf-8', 'windows-1251', clear_simbol($row['number_lot'])), $formatCell);
                $sheet->write(1 + $row_num, 8, iconv('utf-8', 'windows-1251', $row['user_lastname'] . ' ' . $row['user_firstname']), $formatCell);                
                $count++;
            }
            $sql_mail_sended ="
            SELECT cot_komus_contacts.is_type, count(cot_komus_contacts.is_type) as mail_count,
            CASE WHEN cot_komus_contacts.is_type = 72 THEN 'budget_help@komus.net'
            WHEN cot_komus_contacts.is_type = 70 THEN 'tender_budget@tvr.komus.net'
            WHEN cot_komus_contacts.is_type = 77 THEN 'budget_help@komus.net'
            WHEN cot_komus_contacts.is_type = 78 THEN 'budget_help@komus.net'
            WHEN cot_komus_contacts.is_type = 80 THEN 'edo.nkp@komus.net'
            WHEN cot_komus_contacts.is_type = 82 THEN 'gcs_portal@komus.net'
            WHEN cot_komus_contacts.is_type = 93 THEN 'tender_budget@tvr.komus.net'
            WHEN cot_komus_contacts.is_type = 102 THEN 'tender_budget@tvr.komus.net'
            WHEN cot_komus_contacts.is_type = 106 THEN 'budget_help@komus.net'
            WHEN cot_komus_contacts.is_type = 113 THEN 'budget_help@komus.net'
            WHEN cot_komus_contacts.is_type = 117 THEN 'budget_help@komus.net'
            WHEN cot_komus_contacts.is_type = 120 THEN 'tender_budget@tvr.komus.net'
            WHEN cot_komus_contacts.is_type = 124 THEN 'tender_reg@tvr.komus.net'
            WHEN cot_komus_contacts.is_type = 129 THEN 'tender_budget@tvr.komus.net'
            WHEN cot_komus_contacts.is_type = 130 THEN 'tender_budget@tvr.komus.net'
            END AS send_mail
            FROM cot_komus_contacts 
            WHERE cot_komus_contacts.is_type IN ('70', '72', '77', '78', '80', '82', '93', '102', '106', '113', '117', '120', '124','129','130')
            AND cot_komus_contacts.creation_time >= '$from_sql' 
            AND cot_komus_contacts.creation_time <= '$to_sql'{$current_user_sql}   
            GROUP BY cot_komus_contacts.is_type";

            $sheet =& $xls->addWorksheet(iconv('utf-8', 'windows-1251', 'mailSend'));
            $sheet->setInputEncoding('windows-1251');
            $xls->setCustomColor(10, 0, 255 ,255);
           //$sheet->setRow(0, 18.75);
            $formatHeader =& $xls->addFormat();
            $formatHeader->setBorder(1);
            $formatHeader->setHAlign('center');
            $formatHeader->setVAlign('vcenter');
            $formatHeader->setBold();
            $formatHeader->setTextWrap();
            $formatHeader->setFgColor(10);
            $formatHeader1 =& $xls->addFormat();
            $formatHeader1->setBorder(1);
            $formatHeader1->setHAlign('center');
            $formatHeader1->setVAlign('vcenter');
            $formatHeader1->setBold();
            $formatHeader1->setTextWrap();
            $formatCell =& $xls->addFormat();          
            $formatCell->setHAlign('center');
            $formatCell->setVAlign('top');
            $formatCell->setBorder(1);
            $formatCell->setTextWrap();
            $sheet->setColumn(0, 15, 20);            
            $sheet->write(0, 0, iconv('utf-8', 'windows-1251', 'Тип обращения'), $formatHeader);
            $sheet->write(0, 1, iconv('utf-8', 'windows-1251', 'Количество отправленных писем'), $formatHeader);
            $sheet->write(0, 2, iconv('utf-8', 'windows-1251', 'Почтовые адреса'), $formatHeader);
            $mail_sended = $db->query($sql_mail_sended);
            $mails=$mail_sended->fetchAll();
            
            foreach ($mails as $key => $row) {   
                $row_num = $key;           	
                $sheet->write(0 + $row_num, 0, iconv('utf-8', 'windows-1251', getReferenceItem($row['is_type'])), $formatCell);
                $sheet->write(0 + $row_num, 1, iconv('utf-8', 'windows-1251', $row['mail_count']), $formatCell);
                $sheet->write(0 + $row_num, 2, iconv('utf-8', 'windows-1251', $row['send_mail']), $formatCell);
            }
            $xls->close(); 
            break;
        }
        if ($gr_operator_access) {
            $t->assign(array(
                'KOMUS_REPORTS_XLS_FILENAME' => $xls_filename
            ));
        }
        $t->assign(array(
            'KOMUS_REPORTS_SELECT_DATE' => $select_date,
            'KOMUS_REPORTS_XLS_OUT'     => $xls_out,
            'KOMUS_REPORTS_TITLE'       => $L['komus_reports_title']
        ));
        $t->parse('MAIN.XLS_OUT');
    }
    break;
        
default:
    if ($gr_operator_access) {
        $t->assign(array(            
            'KOMUS_REPORTS_BASE_URL'                 => cot_url('plug', 'e=komus_reports&mode=report&rep=1'),
			'KOMUS_REPORTS_NEW_URL'                  => cot_url('plug', 'e=komus_reports&mode=report&rep=2')
        ));
        $t->parse('MAIN.HOME.GRAND_OPERATOR');
    }

    if ($operator_access) {
        $t->assign(array(
        ));
        $t->parse('MAIN.HOME.OPERATOR');
    }
    $t->assign(array(
        'KOMUS_REPORTS_TITLE' => $L['komus_reports_title']
    ));
    $t->parse('MAIN.HOME');
}
?>
