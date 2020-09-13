<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=tools
[END_COT_EXT]
==================== */

/**
 * Admin interface for Komus plugin
 *
 * @package komus
 * @version 1.0.0
 * @author Larion Lushnikov
 * @copyright (c) Komus
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('komus', 'plug');
require_once cot_langfile('komus', 'plug');

$mode = cot_import('mode', 'G', 'ALP');

$t = new XTemplate(cot_tplfile('komus.tools', 'plug'));

switch ($mode) {
case 'load_report':
    $title = $L['komus_load_report'];

    if (empty($_FILES['file'])) {
        $t->assign(array(
           'KOMUS_ADMIN_LOAD_REPORT_ACTION' => cot_url('admin', 'm=other&p=komus&mode=load_report')
        ));
        $t->parse('MAIN.LOAD_REPORT');
    } else {
        require_once 'Spreadsheet/Excel/reader.php';

        $data = new Spreadsheet_Excel_Reader();
        $data->setOutputEncoding('windows-1251');
        $data->read($_FILES['file']['tmp_name']);

        $quantity_rows = $data->sheets[0]['numRows'];

        for ($i = 2; $i <= $quantity_rows; $i++) {
            $user1 = $data->sheets[0]['cells'][$i][1];
            $phone1 = $data->sheets[0]['cells'][$i][5];
            echo $phone1 . ' - ' . $data->sheets[0]['cells'][$i][2] . ' 00:' . $data->sheets[0]['cells'][$i][3] . '<br />';
            $j = $i + 1;
            while ($j <= $quantity_rows) {
//                $is_call_interval = 
                if ($phone1 == $data->sheets[0]['cells'][$j][5] && $user1 == $data->sheets[0]['cells'][$i][1] && $is_call_interval) {
                    $i = $j + 1;
                }
                $j++;
            }
        }

        header('Location: ' . cot_url('admin', 'm=other&p=komus', '', true));
    }
    break;

default:
    $title = $L['komus_title'];
    $t->assign(array(
        'KOMUS_ADMIN_LOAD_REPORT_URL' => cot_url('admin', 'm=other&p=komus&mode=load_report')
    ));
    $t->parse('MAIN.HOME');
}

$t->assign(array(
    'KOMUS_ADMIN_TITLE' => $title,
));

$t->parse('MAIN');
$plugin_body .= $t->text('MAIN');


?>
